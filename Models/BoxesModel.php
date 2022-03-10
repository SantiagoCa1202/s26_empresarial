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

    $establishment_id = $this->establishment_id > 0 ? "d.establishment_id = '$this->establishment_id' AND" : "";

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
      LEFT JOIN ( SELECT box_id, establishment_id
        FROM s26_empresarial.devices
        GROUP BY box_id
      )d
      ON b.id = d.box_id 
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT b.*,
      SUM(b.cash + IFNULL(s.total_sales,0) + IFNULL(sc.total_sales_credits,0) - IFNULL(e.total_expenses,0) + IFNULL(ei.total_external_incomes,0) - IFNULL(de.total_deposits,0)) as amount, 
      SUM(IFNULL(sp.total_sales_pending,0) + IFNULL(scp.total_sales_credits_pending,0)) as amount_pending, 
      CONCAT(d.tradename, ' - ', LPAD(d.n_establishment,3,'0') ) as establishment
      FROM boxes b
      LEFT JOIN ( SELECT d.box_id, d.establishment_id, es.tradename, es.n_establishment
        FROM s26_empresarial.devices d
        JOIN s26_empresarial.establishments es
        ON d.establishment_id = es.id
        GROUP BY d.box_id 
      )d
      ON b.id = d.box_id 
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
      SUM(b.cash + IFNULL(s.total_sales,0) + IFNULL(sc.total_sales_credits,0) - IFNULL(e.total_expenses,0) + IFNULL(ei.total_external_incomes,0) - IFNULL(de.total_deposits,0)) as amount, 
      SUM(IFNULL(sp.total_sales_pending,0) + IFNULL(scp.total_sales_credits_pending,0)) as amount_pending, 
      CONCAT(d.tradename, ' - ', LPAD(d.n_establishment,3,'0') ) as establishment
      FROM boxes b
      LEFT JOIN ( SELECT d.box_id, d.establishment_id, es.tradename, es.n_establishment
        FROM s26_empresarial.devices d
        JOIN s26_empresarial.establishments es
        ON d.establishment_id = es.id
        GROUP BY d.box_id 
      )d
      ON b.id = d.box_id 
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
}
