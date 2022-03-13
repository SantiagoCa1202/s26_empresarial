<?php
require_once('SystemModel.php');

class BankAccountsModel extends Mysql
{
  public $id;
  public $bank_entity_id;
  public $n_account;
  public $account_type;
  public $checkbook;
  public $predetermined;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
    $this->BankEntities = new SystemModel;
  }

  public function selectBankAccounts(int $perPage, string $status, $checkbook)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->status = $status;
    $this->checkbook = $checkbook;
    $this->perPage = $perPage;

    $status = $this->status > 0 ? "ba.status = $this->status AND " : '';
    $where = "
      ba.checkbook LIKE '%$this->checkbook%' AND 
      $status
      ba.status > 0 
    ";

    $info = "SELECT COUNT(*) as count 
      FROM bank_accounts ba
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT ba.*,
      SUM( IFNULL(s.total_sales, 0) + 
        IFNULL(sc.total_sales_credits, 0) +
        IFNULL(td.total_destination_account, 0) -
        IFNULL(ts.total_source_account, 0) + 
        IFNULL(d.total_deposits, 0) -
        IFNULL(e.total_expenses, 0) + 
        IFNULL(ei.total_external_incomes, 0)
      ) as amount,

      SUM(IFNULL(sp.total_sales_pending,0) + 
        IFNULL(scp.total_sales_credits_pending,0)
      ) as amount_pending
      FROM bank_accounts ba

      /** SUMA DE VENTAS / CREDITOS EN CUENTAS BANCARIAS */
      LEFT JOIN ( SELECT SUM(sp.amount) as total_sales, sp.bank_account_id
        FROM sales_payments sp
        JOIN sales s
        ON sp.sale_id = s.id
        WHERE sp.status = 1 AND s.status = 1
        GROUP BY sp.bank_account_id 
      )s
      ON ba.id = s.bank_account_id
      LEFT JOIN ( SELECT SUM(sp.amount) as total_sales_credits, sp.bank_account_id
        FROM sales_credits_payments sp
        JOIN sales_credits s
        ON sp.sale_id = s.id
        WHERE sp.status = 1 AND s.status = 1
        GROUP BY sp.bank_account_id 
      )sc
      ON ba.id = sc.bank_account_id

      /** SUMA DE TRASFERENCIAS  */
      /** ORIGEN */
      LEFT JOIN (SELECT SUM(amount) as total_source_account, source_account_id
        FROM transfers 
        WHERE status = 1
        GROUP BY source_account_id
      )ts
      ON ba.id = ts.source_account_id
      /** Destino */
      LEFT JOIN (SELECT SUM(amount) as total_destination_account, destination_account_id
        FROM transfers 
        WHERE status = 1
        GROUP BY destination_account_id
      )td
      ON ba.id = td.destination_account_id
      /** SUMA DE DEPOSITOS */
      LEFT JOIN (SELECT SUM(dc.amount) as total_deposits, d.bank_account_id
        FROM deposits_cash dc
        JOIN deposits d
        ON dc.deposit_id = d.id
        WHERE dc.status = 1 AND d.status = 1
        GROUP BY d.bank_account_id
      )d
      ON ba.id = d.bank_account_id

      /** SUMA DE EGRESOS */
      LEFT JOIN (SELECT SUM(amount) as total_expenses, bank_account_id
        FROM expenses 
        WHERE status = 1
        GROUP BY bank_account_id
      )e
      ON ba.id = e.bank_account_id
      /** SUMA DE INGRESOS EXTERNOS */
      LEFT JOIN ( SELECT SUM(eia.amount) as total_external_incomes, eia.bank_account_id
        FROM external_incomes_amounts eia
        JOIN external_incomes ei
        ON eia.external_income_id = ei.id
        WHERE ei.status = 1 AND eia.status = 1
        GROUP BY eia.bank_account_id
      )ei
      ON ba.id = ei.bank_account_id

      /** VALORES POR EFECTIVIZAR */
      LEFT JOIN(SELECT SUM(sp.amount) as total_sales_pending, sp.bank_account_id
        FROM sales_payments sp
        JOIN sales s
        ON sp.sale_id = s.id
        WHERE s.status = 1 AND sp.status = 2
        GROUP BY sp.bank_account_id
      )sp
      ON ba.id = sp.bank_account_id
      LEFT JOIN(SELECT SUM(sp.amount) as total_sales_credits_pending, sp.bank_account_id
        FROM sales_credits_payments sp
        JOIN sales_credits s
        ON sp.sale_id = s.id
        WHERE s.status = 1 AND sp.status = 2
        GROUP BY sp.bank_account_id 
      )scp
      ON ba.id = scp.bank_account_id
      WHERE $where  
      GROUP BY ba.id
      ORDER BY ba.id ASC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      // $items[$i]['amount'] = 10500.75;
      $items[$i]['bank_entity'] = $this->BankEntities->selectBankEntity($items[$i]['bank_entity_id']);
    }

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectBankAccount(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM bank_accounts WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    $request['bank_entity'] = $this->BankEntities->selectBankEntity($request['bank_entity_id']);

    return $request;
  }

  public function insertBankAccount(
    int $bank_entity_id,
    string $n_account,
    string $account_type,
    int $checkbook,
    int $predetermined,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->bank_entity_id = $bank_entity_id;
    $this->n_account = $n_account;
    $this->account_type = $account_type;
    $this->checkbook = $checkbook;
    $this->predetermined = $predetermined;
    $this->status = $status;

    $sql = "SELECT * FROM bank_accounts WHERE n_account = '$this->n_account' AND bank_entity_id = '$this->bank_entity_id'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      if ($this->predetermined == 1) {
        $sql = "UPDATE bank_accounts SET predetermined = ?";
        $arrData = array(2);
        $this->update_company($sql, $arrData, $this->db_company);
      }

      $query_insert = "INSERT INTO bank_accounts (
          bank_entity_id,
          n_account,
          account_type,
          checkbook,
          predetermined,
          status
        ) VALUES (?,?,?,?,?,?)";
      $arrData = array(
        $this->bank_entity_id,
        $this->n_account,
        $this->account_type,
        $this->checkbook,
        $this->predetermined,
        $this->status,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateBankAccount(
    int $id,
    int $bank_entity_id,
    string $n_account,
    string $account_type,
    int $checkbook,
    int $predetermined,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->bank_entity_id = $bank_entity_id;
    $this->n_account = $n_account;
    $this->account_type = $account_type;
    $this->checkbook = $checkbook;
    $this->predetermined = $predetermined;
    $this->status = $status;

    $sql = "SELECT * FROM bank_accounts WHERE id != '$this->id' AND bank_entity_id = '$this->bank_entity_id' AND n_account = '$this->n_account'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      if ($this->predetermined == 1) {
        $sql = "UPDATE bank_accounts SET predetermined = ?";
        $arrData = array(2);
        $this->update_company($sql, $arrData, $this->db_company);
      }

      $sql = "UPDATE bank_accounts SET 
        bank_entity_id = ?,
        n_account = ?,
        account_type = ?,
        checkbook = ?,
        predetermined = ?,
        status = ? WHERE id = $this->id";
      $arrData = array(
        $this->bank_entity_id,
        $this->n_account,
        $this->account_type,
        $this->checkbook,
        $this->predetermined,
        $this->status,
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function deleteBankAccount(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    // VALIDAR QUE LA CUENT BANCARIA NO ESTE VINCULADA A OTRAS TABLAS 
    // $sql = "SELECT * FROM bank_accounts WHERE role_id = $this->id";
    $request = "";

    if (empty($request)) {
      $sql = "UPDATE bank_accounts SET status = 0 WHERE id = $this->id";
      $request = $this->delete_company($sql, $this->db_company);
    } else {
      $request = -3;
    }
    return $request;
  }
}
