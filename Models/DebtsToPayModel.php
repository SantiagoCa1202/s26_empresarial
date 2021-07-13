<?php
class DebtsToPayModel extends Mysql
{
  public $id;
  public $creditor_type;
  public $ruc;
  public $business_name;
  public $n_document;
  public $description;
  public $amount;
  public $credit_note;
  public $date;
  public $expiration_date;
  public $establishment_id;
  public $status;
  public $status_payment;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectDebts(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->creditor_type = $filter['creditor_type'];
    $this->ruc = $filter['ruc'];
    $this->business_name = $filter['business_name'];
    $this->n_document = $filter['n_document'];
    $this->date = $filter['date'];
    $this->status_payment = $filter['status_payment'];
    $this->establishment_id = $filter['establishment_id'];
    $this->status = $filter['status'];

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      ' AND date BETWEEN "' . $this->date[0] . ' 00:00:00" AND "
    ' . $this->date[1] . ' 23:59:59" OR date BETWEEN "
    ' . $this->date[1] . ' 00:00:00" AND "' . $this->date[0] . ' 23:59:59"' : '';


    $where = '
    id LIKE "%' . $this->id . '%" AND
    creditor_type LIKE "%' . $this->creditor_type . '%" AND
    ruc LIKE "%' . $this->ruc . '%" AND
    business_name LIKE "%' . $this->business_name . '%" AND
    n_document LIKE "%' . $this->n_document . '%" AND
    status_payment LIKE "%' . $this->status_payment . '%" AND
    establishment_id LIKE "%' . $this->establishment_id . '%" AND
    status LIKE "%' . $this->status . '%" AND 
    status > 0 
    ' . $date_range;


    $info = "SELECT COUNT(*) as count 
      FROM debts_to_pay
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
        FROM debts_to_pay
        WHERE $where  
        ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectDebt(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM debts_to_pay WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertDebt(
    string $creditor_type,
    string $ruc,
    string $business_name,
    string $n_document,
    string $description,
    float $amount,
    float $credit_note,
    string $date,
    int $establishment_id,
    string $expiration_date,
    int $status
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->creditor_type = $creditor_type;
    $this->ruc = $ruc;
    $this->business_name = $business_name;
    $this->n_document = $n_document;
    $this->description = $description;
    $this->amount = $amount;
    $this->credit_note = $credit_note;
    $this->date = $date;
    $this->expiration_date = $expiration_date;
    $this->establishment_id = $establishment_id;
    $this->status = $status;

    $query_insert = "INSERT INTO debts_to_pay (
      creditor_type,
      ruc,
      business_name,
      n_document,
      description,
      amount,
      credit_note,
      date,
      expiration_date,
      establishment_id,
      status
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->creditor_type,
      $this->ruc,
      $this->business_name,
      $this->n_document,
      $this->description,
      $this->amount,
      $this->credit_note,
      $this->date,
      $this->expiration_date,
      $this->establishment_id,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function updateDebt(
    int $id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    # code...
  }

  public function deleteDebt(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    # code...
  }

  public function insertRecordDebt(
    $debt_id,
    $expiration_date,
    $payment_date,
    $description,
    $amount,
    $payment_method_id,
    $bank_entity_id,
    $n_transaction,
    $check_id,
    $payment_status
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->debt_id = $debt_id;
    $this->expiration_date = $expiration_date;
    $this->payment_date = $payment_date;
    $this->description = $description;
    $this->amount = $amount;
    $this->payment_method_id = $payment_method_id;
    $this->bank_entity_id = $bank_entity_id;
    $this->n_transaction = $n_transaction;
    $this->check_id = $check_id;
    $this->payment_status = $payment_status;

    $query_insert = "INSERT INTO record_of_debts_payable (
      debt_id,
      expiration_date,
      payment_date,
      description,
      amount,
      payment_method_id,
      bank_entity_id,
      n_transaction,
      check_id,
      payment_status
    ) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->debt_id,
      $this->expiration_date,
      $this->payment_date,
      $this->description,
      $this->amount,
      $this->payment_method_id,
      $this->bank_entity_id,
      $this->n_transaction,
      $this->check_id,
      $this->payment_status
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }
}
