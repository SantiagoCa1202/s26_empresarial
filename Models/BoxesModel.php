<?php
class BoxesModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }
  public function selectBoxes(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->status = $filter['status'];
    $this->cash = $filter['cash'];
    $this->establishment_id = $filter['establishment_id'];
    $this->perPage = $perPage;

    $establishment_id = $this->establishment_id > 0 ? "b.establishment_id = '$this->establishment_id' AND" : "";

    $box_id = $this->id > 0 ? "b.id LIKE '%$this->id%' AND" : "";

    $where = "
      $box_id
      b.name LIKE '%$this->name%' AND
      b.status LIKE '%$this->status%' AND 
      $establishment_id
      b.status > 0 
    ";

    $info = "SELECT COUNT(b.id) as count 
      FROM boxes b
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT b.*,
      SUM(b.cash + 
        IFNULL(s.total_sales,0) + 
        IFNULL(sc.total_sales_credits,0) - 
        IFNULL(e.total_expenses,0) + 
        IFNULL(ei.total_external_incomes,0) - 
        IFNULL(de.total_deposits,0) + 
        IFNULL(ba.total_box_adjustment, 0)
      ) as amount, 
      SUM(IFNULL(sp.total_sales_pending,0) + 
        IFNULL(scp.total_sales_credits_pending,0)
      ) as amount_pending, 
      CONCAT(es.tradename, ' - ', LPAD(es.n_establishment,3,'0') ) as establishment
      FROM boxes b
      JOIN s26_empresarial.establishments es
      ON b.establishment_id = es.id
      LEFT JOIN( SELECT SUM(amount) as total_expenses, box_id
        FROM expenses 
        WHERE status = 1 AND bank_account_id IS NULL
        GROUP BY box_id
      )e
      ON b.id = e.box_id
      LEFT JOIN(SELECT SUM(sp.amount) as total_sales, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE payment_method_id = 1 AND status = 1 
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 1
        GROUP BY s.box_id
      )s
      ON b.id = s.box_id
      LEFT JOIN(SELECT SUM(scp.amount) as total_sales_credits, scp.box_id
        FROM sales_credits_payments scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        WHERE scp.payment_method_id = 1 AND sc.status = 1 AND scp.status = 1
        GROUP BY scp.box_id
      )sc
      ON b.id = sc.box_id

      LEFT JOIN(SELECT SUM(sp.amount) as total_sales_pending, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE status = 2
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 1
        GROUP BY s.box_id
      )sp
      ON b.id = sp.box_id
      LEFT JOIN(SELECT SUM(scp.amount) as total_sales_credits_pending, scp.box_id
        FROM sales_credits_payments scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        WHERE sc.status = 1 AND scp.status = 2
        GROUP BY scp.box_id
      )scp
      ON b.id = scp.box_id

      LEFT JOIN( SELECT SUM(eia.amount) as total_external_incomes, eia.box_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE ei.status = 1 AND eia.status = 1
        GROUP BY eia.box_id
      )ei
      ON b.id = ei.box_id

      LEFT JOIN (SELECT SUM(dc.amount) as total_deposits, dc.box_id
        FROM deposits_cash dc
        JOIN deposits d
        ON dc.deposit_id = d.id
        WHERE dc.status = 1 AND d.status = 1
        GROUP BY dc.box_id
      )de
      ON b.id = de.box_id
      /** AJUSTE DE CAJA  */
      LEFT JOIN (SELECT SUM(adjusted_amount) as total_box_adjustment, box_id
        FROM box_adjustment
      )ba
      ON b.id = ba.box_id
      WHERE $where  
      GROUP BY b.id
      ORDER BY b.id ASC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'boxes', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectBox(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "SELECT DISTINCT b.*,
      SUM(b.cash + 
        IFNULL(s.total_sales,0) + 
        IFNULL(sc.total_sales_credits,0) - 
        IFNULL(e.total_expenses,0) + 
        IFNULL(ei.total_external_incomes,0) - 
        IFNULL(de.total_deposits,0) + 
        IFNULL(ba.total_box_adjustment, 0)
      ) as amount, 
      SUM(IFNULL(sp.total_sales_pending,0) + 
        IFNULL(scp.total_sales_credits_pending,0)
      ) as amount_pending,
      CONCAT(es.tradename, ' - ', LPAD(es.n_establishment,3,'0') ) as establishment
      FROM boxes b
      JOIN s26_empresarial.establishments es
      ON b.establishment_id = es.id
      LEFT JOIN( SELECT SUM(amount) as total_expenses, box_id
        FROM expenses 
        WHERE status = 1 AND bank_account_id IS NULL
        GROUP BY box_id
      )e
      ON b.id = e.box_id
      LEFT JOIN(SELECT SUM(sp.amount) as total_sales, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE payment_method_id = 1 AND status = 1 
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 1
        GROUP BY s.box_id
      )s
      ON b.id = s.box_id
      LEFT JOIN(SELECT SUM(scp.amount) as total_sales_credits, scp.box_id
        FROM sales_credits_payments scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        WHERE scp.payment_method_id = 1 AND sc.status = 1 AND scp.status = 1
        GROUP BY scp.box_id
      )sc
      ON b.id = sc.box_id

      LEFT JOIN(SELECT SUM(sp.amount) as total_sales_pending, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE status = 2
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 1
        GROUP BY s.box_id
      )sp
      ON b.id = sp.box_id
      LEFT JOIN(SELECT SUM(scp.amount) as total_sales_credits_pending, scp.box_id
        FROM sales_credits_payments scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        WHERE sc.status = 1 AND scp.status = 2
        GROUP BY scp.box_id
      )scp
      ON b.id = scp.box_id

      LEFT JOIN( SELECT SUM(eia.amount) as total_external_incomes, eia.box_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE ei.status = 1 AND eia.status = 1 AND eia.bank_account_id IS NULL
        GROUP BY eia.box_id
      )ei
      ON b.id = ei.box_id

      LEFT JOIN (SELECT SUM(dc.amount) as total_deposits, dc.box_id
        FROM deposits_cash dc
        JOIN deposits d
        ON dc.deposit_id = d.id
        WHERE dc.status = 1 AND d.status = 1
        GROUP BY dc.box_id
      )de
      ON b.id = de.box_id

      /** AJUSTE DE CAJA  */
      LEFT JOIN (SELECT SUM(adjusted_amount) as total_box_adjustment, box_id
        FROM box_adjustment
      )ba
      ON b.id = ba.box_id
      WHERE b.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function reportBox($id, $arr_date)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->arr_date = $arr_date;

    $date = ($this->arr_date != '' && count($this->arr_date) == 2) ?
      "BETWEEN '{$this->arr_date[0]} 00:00:00' AND '{$this->arr_date[1]}  23:59:59'" : "";

    $sql = "SELECT DISTINCT b.*,
      IFNULL(SUM(sprod.articles), 0) as articles,
      IFNULL(SUM(sprod.total_discount), 0) as total_discount,
      IFNULL(SUM(s.total_sales), 0) as total_sales,
      IFNULL(SUM(sr.total_returns), 0) as total_returns,
      IFNULL(SUM(sc.total_payments_credit), 0) as total_payments_credit,
      IFNULL(SUM(eic.total_external_incomes_cost), 0) as total_external_incomes_cost,
      IFNULL(SUM(eig.total_external_incomes_gain), 0) as total_external_incomes_gain,
      IFNULL(SUM(ec.total_expenses_cost), 0) as total_expenses_cost,
      IFNULL(SUM(eg.total_expenses_gain), 0) as total_expenses_gain,
      IFNULL(SUM(de.total_deposits), 0) as total_deposits,
      IFNULL(SUM(sp.total_sales_pending), 0) as total_sales_pending
      FROM boxes b

      /* SUMA DE VENTA EN PRODUCTO*/
      LEFT JOIN(SELECT 
        SUM(sp.amount) as articles, 
        SUM(sp.discount) as total_discount, s.box_id
        FROM sales s 
          LEFT JOIN (SELECT SUM(amount) as amount, SUM(discount) as discount, sale_id
            FROM sales_products
            GROUP BY sale_id
          )sp
          ON s.id = sp.sale_id
        WHERE s.status = 1 AND s.created_at $date
        GROUP BY s.box_id
      )sprod
      ON b.id = sprod.box_id
      /** SUMA DE VENTA */
      LEFT JOIN(SELECT SUM(sp.amount) as total_sales, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE status = 1 
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 1 AND s.created_at $date
        GROUP BY s.box_id
      )s
      ON b.id = s.box_id
      /** SUMA DE DEVOLUCIONES  */
      LEFT JOIN(SELECT SUM(sp.amount) as total_returns, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE status = 1 
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 2 AND s.created_at $date
        GROUP BY s.box_id
      )sr
      ON b.id = sr.box_id
      /** SUMA DE ABONOS EN CREDITOS  */
      LEFT JOIN(SELECT SUM(scp.amount) as total_payments_credit, scp.box_id
        FROM sales_credits_payments scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        WHERE sc.status = 1 AND scp.status = 1 AND scp.date $date
        GROUP BY scp.box_id
      )sc
      ON b.id = sc.box_id

      /** SUMA DE INGRESOS EXTERNOS */
      LEFT JOIN( SELECT SUM(eia.amount) as total_external_incomes_cost, eia.box_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE ei.status = 1 AND eia.status = 1 AND eia.account = 1 AND eia.created_at $date
        GROUP BY eia.box_id
      )eic
      ON b.id = eic.box_id
      LEFT JOIN( SELECT SUM(eia.amount) as total_external_incomes_gain, eia.box_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE ei.status = 1 AND eia.status = 1 AND eia.account = 2 AND eia.created_at $date
        GROUP BY eia.box_id
      )eig
      ON b.id = eig.box_id

      /** SUMA DE EGRESOS */
      LEFT JOIN( SELECT SUM(amount) as total_expenses_cost, box_id
        FROM expenses 
        WHERE status = 1 AND account = 1 AND created_at $date
        GROUP BY box_id
      )ec
      ON b.id = ec.box_id
      LEFT JOIN( SELECT SUM(amount) as total_expenses_gain, box_id
        FROM expenses 
        WHERE status = 1 AND account = 2 AND created_at $date
        GROUP BY box_id
      )eg
      ON b.id = eg.box_id

      /** SUMA DE DEPOSITOS */
      LEFT JOIN (SELECT SUM(dc.amount) as total_deposits, dc.box_id
        FROM deposits_cash dc
        JOIN deposits d
        ON dc.deposit_id = d.id
        WHERE dc.status = 1 AND d.status = 1 AND d.created_at $date
        GROUP BY dc.box_id
      )de
      ON b.id = de.box_id

      /** SUMA DE PAGOS DE VENTAS PENDIENTE */
      LEFT JOIN(SELECT SUM(sp.amount) as total_sales_pending, s.box_id
        FROM sales s 
        LEFT JOIN (SELECT SUM(amount) as amount, sale_id
          FROM sales_payments
          WHERE status = 2
          GROUP BY sale_id
        )sp
        ON s.id = sp.sale_id
        WHERE s.status = 1 AND s.created_at $date
        GROUP BY s.box_id
      )sp
      ON b.id = sp.box_id
      WHERE b.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    return $request;
  }

  public function insertBox(
    string $name,
    float $cash,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->name = $name;
    $this->cash = $cash;
    $this->status = $status;

    $sql = "SELECT * FROM boxes WHERE name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO boxes (name, cash, status) VALUES (?,?,?)";
      $arrData = array(
        $this->name,
        $this->cash,
        $this->status
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }

    return $request;
  }

  public function updateBox(
    int $id,
    string $name,
    float $cash,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->name = $name;
    $this->cash = $cash;
    $this->status = $status;

    $sql = "SELECT * FROM boxes WHERE id != '$this->id' AND name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE boxes SET name = ?, cash = ?, status = ? WHERE id = $this->id";
      $arrData = array(
        $this->name,
        $this->cash,
        $this->status
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function deleteBox(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    if ($this->id !== 1) {
      $sql = "UPDATE boxes SET status = 0 WHERE id = $this->id";
      $request = $this->delete_company($sql, $this->db_company);
    } else {
      $request = -4;
    }
    return $request;
  }

  // CUADRE DE CAJA 
  public function insertBoxAdjustment(
    $box_id,
    $adjusted_amount,
    $coin_100,
    $coin_50,
    $coin_20,
    $coin_10,
    $coin_5,
    $coin_1,
    $coin_0_50,
    $coin_0_25,
    $coin_0_10,
    $coin_0_05,
    $coin_0_01,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->box_id = $box_id;
    $this->adjusted_amount = $adjusted_amount;
    $this->coin_100 = $coin_100;
    $this->coin_50 = $coin_50;
    $this->coin_20 = $coin_20;
    $this->coin_10 = $coin_10;
    $this->coin_5 = $coin_5;
    $this->coin_1 = $coin_1;
    $this->coin_0_50 = $coin_0_50;
    $this->coin_0_25 = $coin_0_25;
    $this->coin_0_10 = $coin_0_10;
    $this->coin_0_05 = $coin_0_05;
    $this->coin_0_01 = $coin_0_01;

    $query_insert = "INSERT INTO box_adjustment (box_id, `100`, `50`, `20`, `10`, `5`, `1`, `0.50`, `0.25`, `0.10`, `0.05`, `0.01`, adjusted_amount
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->box_id,
      $this->coin_100,
      $this->coin_50,
      $this->coin_20,
      $this->coin_10,
      $this->coin_5,
      $this->coin_1,
      $this->coin_0_50,
      $this->coin_0_25,
      $this->coin_0_10,
      $this->coin_0_05,
      $this->coin_0_01,
      $this->adjusted_amount,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function selectBoxAdjustment(int $perPage, $box_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->box_id = $box_id;
    $this->perPage = $perPage;

    $box_id = $this->box_id > 0 ? "box_id = '$this->box_id'" : "";


    $where = "
      $box_id
    ";

    $rows = "SELECT *
      FROM box_adjustment
      WHERE $where  
      ORDER BY id ASC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }
}
