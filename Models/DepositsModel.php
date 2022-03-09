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
      dc.amount LIKE '%$this->amount%' AND
      dc.establishment_id LIKE '%$this->establishment_id%' AND
      d.status LIKE '%$this->status%' AND
      d.status > 0 
      $date_range";

    $info = "SELECT DISTINCT COUNT(d.id) as count, SUM(dc.amount) AS total_deposit
      FROM deposits d
      LEFT JOIN(SELECT SUM(dc.amount) as amount, dc.deposit_id, d.establishment_id
        FROM deposits_cash dc
        LEFT JOIN( SELECT * FROM s26_empresarial.devices GROUP BY box_id)d
        ON dc.box_id = d.box_id
        WHERE dc.status = 1
        GROUP BY dc.deposit_id
      )dc
      ON d.id = dc.deposit_id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT d.*, ba.bank_account, dc.amount
      FROM deposits d
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS bank_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )ba
      ON d.bank_account_id = ba.id
      LEFT JOIN(SELECT SUM(dc.amount) as amount, dc.deposit_id, d.establishment_id
        FROM deposits_cash dc
        LEFT JOIN( SELECT * FROM s26_empresarial.devices GROUP BY box_id)d
        ON dc.box_id = d.box_id
        WHERE dc.status = 1
        GROUP BY dc.deposit_id

      )dc
      ON d.id = dc.deposit_id
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

    $request['arr_boxes'] = $this->selectDepositsCash($request['id']);

    return $request;
  }

  public function insertDeposit(
    $bank_account_id,
    string $description,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->bank_account_id = $bank_account_id;
    $this->description = $description;
    $this->status = $status;

    $query_insert = "INSERT INTO deposits (bank_account_id, description, status) VALUES (?,?,?)";
    $arrData = array(
      $this->bank_account_id,
      $this->description,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateDeposit(
    int $id,
    $bank_account_id,
    string $description,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->bank_account_id = $bank_account_id;
    $this->description = $description;
    $this->status = $status;

    $sql = "UPDATE deposits SET bank_account_id = ?, description = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->bank_account_id,
      $this->description,
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


  // deposito de cajas 

  public function selectDepositsCash($deposit_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->deposit_id = $deposit_id;

    $rows = "SELECT dc.id, dc.deposit_id, dc.box_id, dc.amount as deposit_amount, dc.status, d.establishment, b.name
      FROM deposits_cash dc
      JOIN boxes b
      ON dc.box_id = b.id
      LEFT JOIN ( SELECT d.box_id, CONCAT(es.tradename, ' - ', LPAD(es.n_establishment,3,'0') ) as establishment
        FROM s26_empresarial.devices d
        JOIN s26_empresarial.establishments es
        ON d.establishment_id = es.id
        GROUP BY d.box_id 
      )d
      ON b.id = d.box_id
      WHERE dc.deposit_id = $this->deposit_id AND dc.status > 0
      ORDER BY dc.id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function insertDepositCash(
    $deposit_id,
    $box_id,
    float $amount,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->deposit_id = $deposit_id;
    $this->box_id = $box_id;
    $this->amount = $amount;
    $this->status = $status;

    $query_insert = "INSERT INTO deposits_cash (deposit_id, box_id, amount, status) VALUES (?,?,?,?)";
    $arrData = array(
      $this->deposit_id,
      $this->box_id,
      $this->amount,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateDepositCash(
    int $id,
    float $amount,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->amount = $amount;
    $this->status = $status;

    $sql = "UPDATE deposits_cash SET amount = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->amount,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }
}
