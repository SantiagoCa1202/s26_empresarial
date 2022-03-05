<?php
class DepositsModel extends Mysql
{
  public $id;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectDeposits(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->bank_account_id = $filter['bank_account_id'];
    $this->description = $filter['description'];
    $this->amount = $filter['amount'];
    $this->establishment_id = $filter['establishment_id'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;


    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND d.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      d.bank_account_id LIKE '%$this->bank_account_id%' AND
      d.description LIKE '%$this->description%' AND
      d.amount LIKE '%$this->amount%' AND
      d.establishment_id LIKE '%$this->establishment_id%' AND
      d.status LIKE '%$this->status%' AND
      d.status > 0 
      $date_range";

    $info = "SELECT COUNT(*) as count, SUM(d.amount) AS total_deposit
      FROM deposits d
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT d.*, ba.bank_account
      FROM deposits d
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS bank_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )ba
      ON d.bank_account_id = ba.id
      WHERE $where
      ORDER BY d.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'deposits', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectDeposit(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT DISTINCT d.*, ba.bank_account
      FROM deposits d
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS bank_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )ba
      ON d.bank_account_id = ba.id
      WHERE d.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertDeposit(
    $bank_account_id,
    float $amount,
    string $description,
    $establishment_id,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->bank_account_id = $bank_account_id;
    $this->amount = $amount;
    $this->description = $description;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $query_insert = "INSERT INTO deposits (bank_account_id, amount, description, establishment_id, status) VALUES (?,?,?,?,?)";
    $arrData = array(
      $this->bank_account_id,
      $this->amount,
      $this->description,
      $this->establishment_id,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateDeposit(
    int $id,
    $bank_account_id,
    float $amount,
    string $description,
    $establishment_id,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->bank_account_id = $bank_account_id;
    $this->amount = $amount;
    $this->description = $description;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $sql = "UPDATE deposits SET bank_account_id = ?, amount = ?, description = ?, establishment_id = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->bank_account_id,
      $this->amount,
      $this->description,
      $this->establishment_id,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function deleteDeposit(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE deposits SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
