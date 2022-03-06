<?php
class ExternalIncomesModel extends Mysql
{
  public $id;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectExternalIncomes(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->tradename = $filter['tradename'];
    $this->description = $filter['description'];
    $this->establishment_id = $filter['establishment_id'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;


    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND ei.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      ei.tradename LIKE '%$this->tradename%' AND
      ei.description LIKE '%$this->description%' AND
      ei.establishment_id LIKE '%$this->establishment_id%' AND
      ei.status LIKE '%$this->status%' AND
      ei.status > 0 
      $date_range";

    $info = "SELECT COUNT(ei.id) as count, SUM(eia.amount) as total_external_incomes
      FROM external_incomes ei
      LEFT JOIN ( SELECT SUM(amount) as amount, external_income_id, id
        FROM external_incomes_amounts
        WHERE status = 1
        GROUP BY external_income_id
      )eia
      ON ei.id = eia.external_income_id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT ei.*, eia.amount
      FROM external_incomes ei
      LEFT JOIN ( SELECT SUM(amount) as amount, external_income_id, id
        FROM external_incomes_amounts
        WHERE status = 1
        GROUP BY external_income_id
      )eia
      ON ei.id = eia.external_income_id
      WHERE $where
      ORDER BY ei.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'external_incomes', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectExternalIncome(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT *
      FROM external_incomes ei 
      WHERE ei.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    $request['external_incomes_amount'] = $this->selectExternalIncomeAmounts($request['id'])['items'];
    return $request;
  }

  public function insertExternalIncome(
    string $tradename,
    string $description,
    $establishment_id,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->tradename = $tradename;
    $this->description = $description;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $query_insert = "INSERT INTO external_incomes (tradename, description, establishment_id, status) VALUES (?,?,?,?)";
    $arrData = array(
      $this->tradename,
      $this->description,
      $this->establishment_id,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateExternalIncome(
    int $id,
    string $tradename,
    string $description,
    $establishment_id,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->tradename = $tradename;
    $this->description = $description;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $sql = "UPDATE external_incomes SET tradename = ?, description = ?, establishment_id = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->tradename,
      $this->description,
      $this->establishment_id,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function deleteExternalIncome(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE external_incomes SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function selectExternalIncomeAmounts($external_income_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->external_income_id = $external_income_id;

    $info = "SELECT COUNT(*) as count, SUM(amount) as total_external_incomes
      FROM external_incomes_amounts
      WHERE external_income_id = $this->external_income_id AND status > 0
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT eia.*, bank_account
      FROM external_incomes_amounts eia
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS bank_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )ba
      ON eia.bank_account_id = ba.id
      WHERE external_income_id = $this->external_income_id AND status > 0
      ORDER BY id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function insertExternalIncomeAmount(
    $external_income_id,
    $amount,
    $account,
    $bank_account_id,
    $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->external_income_id = $external_income_id;
    $this->amount = $amount;
    $this->account = $account;
    $this->bank_account_id = $bank_account_id;
    $this->status = $status;

    $query_insert = "INSERT INTO external_incomes_amounts (external_income_id, amount, account, bank_account_id, status) VALUES (?,?,?,?,?)";
    $arrData = array(
      $this->external_income_id,
      $this->amount,
      $this->account,
      $this->bank_account_id,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateExternalIncomeAmount(
    $id,
    $amount,
    $account,
    $bank_account_id,
    $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->amount = $amount;
    $this->account = $account;
    $this->bank_account_id = $bank_account_id;
    $this->status = $status;

    $sql = "UPDATE external_incomes_amounts SET amount = ?, account = ?, bank_account_id = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->amount,
      $this->account,
      $this->bank_account_id,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }
}
