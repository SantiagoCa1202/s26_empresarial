<?php
class ExpensesModel extends Mysql
{
  public $id;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectExpenses(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->n_document = $filter['n_document'];
    $this->tradename = $filter['tradename'];
    $this->description = $filter['description'];
    $this->amount = $filter['amount'];
    $this->account = $filter['account'];
    $this->bank_account_id = $filter['bank_account_id'];
    $this->payment_method_id = $filter['payment_method_id'];
    $this->establishment_id = $filter['establishment_id'];
    $this->box_id = $filter['box_id'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;


    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND e.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $bank_account_id = $this->bank_account_id > 0 ? "e.bank_account_id = '$this->bank_account_id' AND" : "";

    $n_document = $this->n_document != '' ? "e.n_document = '$this->n_document' AND" : "";

    $where = "
      $bank_account_id
      $n_document
      e.id LIKE '%$this->id%' AND
      e.tradename LIKE '%$this->tradename%' AND
      e.description LIKE '%$this->description%' AND
      e.amount LIKE '%$this->amount%' AND
      e.account LIKE '%$this->account%' AND
      e.payment_method_id LIKE '%$this->payment_method_id%' AND
      d.establishment_id LIKE '%$this->establishment_id%' AND
      e.box_id LIKE '%$this->box_id%' AND
      e.status LIKE '%$this->status%' AND
      e.status > 0 
      $date_range";

    $info = "SELECT COUNT(*) as count, SUM(IF(e.account = 1, e.amount, 0)) AS total_cost, SUM(IF(e.account = 2, e.amount, 0)) AS total_gain
      FROM expenses e
      JOIN s26_empresarial.devices d
      ON e.box_id = d.box_id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT e.*, pm.name as payment_method, ba.bank_entity, CONCAT(b.name, ' / ', es.tradename, ' - ', LPAD(es.n_establishment,3,'0') ) as box
      FROM expenses e
      JOIN s26_empresarial.payment_methods pm
      ON e.payment_method_id = pm.id
      LEFT JOIN(
        SELECT ba.id, be.bank_entity
        FROM bank_accounts ba
        JOIN s26_empresarial.bank_entities be
        ON ba.bank_entity_id = be.id
        GROUP BY ba.id
      )ba
      ON e.bank_account_id = ba.id
      JOIN boxes b
      ON e.box_id = b.id
      JOIN s26_empresarial.devices d
      ON e.box_id = d.box_id
      JOIN s26_empresarial.establishments es
      ON d.establishment_id = es.id
      WHERE $where
      ORDER BY e.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'expenses', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectExpense(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT DISTINCT e.*, pm.name as payment_method, CONCAT(ba.bank_entity, ' - ', ba.n_account) as bank_account, CONCAT(b.name, ' / ', es.tradename, ' - ', LPAD(es.n_establishment,3,'0') ) as box
    FROM expenses e
    JOIN s26_empresarial.payment_methods pm
    ON e.payment_method_id = pm.id
    JOIN boxes b
    ON e.box_id = b.id
    JOIN s26_empresarial.devices d
    ON e.box_id = d.box_id
    JOIN s26_empresarial.establishments es
    ON d.establishment_id = es.id
    LEFT JOIN(
      SELECT ba.id, be.bank_entity, ba.n_account
      FROM bank_accounts ba
      JOIN s26_empresarial.bank_entities be
      ON ba.bank_entity_id = be.id
      GROUP BY ba.id
    )ba
    ON e.bank_account_id = ba.id 
    WHERE e.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertExpense(
    $n_document,
    string $tradename,
    string $description,
    float $amount,
    int $account,
    string $date,
    $bank_account_id,
    $payment_method_id,
    $box_id,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->n_document = $n_document;
    $this->tradename = $tradename;
    $this->description = $description;
    $this->amount = $amount;
    $this->account = $account;
    $this->date = $date;
    $this->bank_account_id = $bank_account_id;
    $this->payment_method_id = $payment_method_id;
    $this->box_id = $box_id;
    $this->status = $status;

    $query_insert = "INSERT INTO expenses (n_document, tradename, description, amount, account, date, bank_account_id, payment_method_id, box_id, status) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->n_document,
      $this->tradename,
      $this->description,
      $this->amount,
      $this->account,
      $this->date,
      $this->bank_account_id,
      $this->payment_method_id,
      $this->box_id,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateExpense(
    int $id,
    string $n_document,
    string $tradename,
    string $description,
    float $amount,
    int $account,
    string $date,
    $bank_account_id,
    $payment_method_id,
    $box_id,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->n_document = $n_document;
    $this->tradename = $tradename;
    $this->description = $description;
    $this->amount = $amount;
    $this->account = $account;
    $this->date = $date;
    $this->bank_account_id = $bank_account_id;
    $this->payment_method_id = $payment_method_id;
    $this->box_id = $box_id;
    $this->status = $status;

    $sql = "UPDATE expenses SET n_document = ?, tradename = ?, description = ?, amount = ?, account = ?, date = ?, bank_account_id = ?, payment_method_id = ?,  box_id = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->n_document,
      $this->tradename,
      $this->description,
      $this->amount,
      $this->account,
      $this->date,
      $this->bank_account_id,
      $this->payment_method_id,
      $this->box_id,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function deleteExpense(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE expenses SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
