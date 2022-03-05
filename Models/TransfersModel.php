<?php
class TransfersModel extends Mysql
{
  public $id;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectTransfers(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->source_account_id = $filter['source_account_id'];
    $this->destination_account_id = $filter['destination_account_id'];
    $this->description = $filter['description'];
    $this->amount = $filter['amount'];
    $this->establishment_id = $filter['establishment_id'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;


    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND t.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      t.source_account_id LIKE '%$this->source_account_id%' AND
      t.destination_account_id LIKE '%$this->destination_account_id%' AND
      t.description LIKE '%$this->description%' AND
      t.amount LIKE '%$this->amount%' AND
      t.establishment_id LIKE '%$this->establishment_id%' AND
      t.status LIKE '%$this->status%' AND
      t.status > 0 
      $date_range";

    $info = "SELECT COUNT(*) as count, SUM(t.amount) AS total_transfer
      FROM transfers t
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT t.*, sba.source_account, dba.destination_account
      FROM transfers t
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS source_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )sba
      ON t.source_account_id = sba.id
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS destination_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )dba
      ON t.destination_account_id = dba.id
      WHERE $where
      ORDER BY t.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'transfers', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectTransfer(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT t.*, sba.source_account, dba.destination_account, CONCAT(es.tradename, ' - ', es.n_establishment) as establishment
      FROM transfers t
      JOIN s26_empresarial.establishments es
      ON t.establishment_id = es.id
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS source_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )sba
      ON t.source_account_id = sba.id
      LEFT JOIN(
        SELECT ba.id, CONCAT(be.bank_entity, ' - ', ba.n_account) AS destination_account
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )dba
      ON t.destination_account_id = dba.id
      WHERE t.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertTransfer(
    $source_account_id,
    $destination_account_id,
    float $amount,
    string $description,
    $establishment_id,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->source_account_id = $source_account_id;
    $this->destination_account_id = $destination_account_id;
    $this->amount = $amount;
    $this->description = $description;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $query_insert = "INSERT INTO transfers (source_account_id, destination_account_id, amount, description, establishment_id, status) VALUES (?,?,?,?,?,?)";
    $arrData = array(
      $this->source_account_id,
      $this->destination_account_id,
      $this->amount,
      $this->description,
      $this->establishment_id,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateTransfer(
    int $id,
    $source_account_id,
    $destination_account_id,
    float $amount,
    string $description,
    $establishment_id,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->source_account_id = $source_account_id;
    $this->destination_account_id = $destination_account_id;
    $this->amount = $amount;
    $this->description = $description;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $sql = "UPDATE transfers SET source_account_id = ?, destination_account_id = ?, amount = ?, description = ?, establishment_id = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->source_account_id,
      $this->destination_account_id,
      $this->amount,
      $this->description,
      $this->establishment_id,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function deleteTransfer(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE transfers SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
