<?php
require_once('BankAccountsModel.php');

class CheckBooksModel extends Mysql
{
  public $id;
  public $bank_account_id;
  public $n_check;
  public $date_issue;
  public $date_payment;
  public $beneficiary;
  public $reason;
  public $amount;
  public $balance;
  public $type;
  public $payment_status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
    $this->BankAccounts = new BankAccountsModel;
  }

  public function selectCheckBooks(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->bank_account_id = $filter['bank_account_id'];
    $this->n_check = $filter['n_check'];
    $this->date_issue = $filter['date_issue'];
    $this->date_payment = $filter['date_payment'];
    $this->beneficiary = $filter['beneficiary'];
    $this->reason = $filter['reason'];
    $this->type = $filter['type'];
    $this->payment_status = $filter['payment_status'];
    $this->perPage = $perPage;


    $date_issue = ($this->date_issue != '' && count($this->date_issue) == 2) ?
      ' AND date_issue BETWEEN "' . $this->date_issue[0] . ' 00:00:00" AND "
      ' . $this->date[1] . ' 23:59:59" OR date_issue BETWEEN "
      ' . $this->date[1] . ' 00:00:00" AND "' . $this->date[0] . ' 23:59:59"' : '';

    $date_payment = ($this->date_payment != '' && count($this->date_payment) == 2) ?
      ' AND date_payment BETWEEN "' . $this->date_payment[0] . ' 00:00:00" AND "
    ' . $this->date[1] . ' 23:59:59" OR date_payment BETWEEN "
    ' . $this->date[1] . ' 00:00:00" AND "' . $this->date[0] . ' 23:59:59"' : '';



    $where = '
      id LIKE "%' . $this->id . '%" AND
      bank_account_id LIKE "%' . $this->bank_account_id . '%" AND
      n_check LIKE "%' . $this->n_check . '%" AND
      date_issue LIKE "%' . $this->date_issue . '%" AND
      date_payment LIKE "%' . $this->date_payment . '%" AND
      beneficiary LIKE "%' . $this->beneficiary . '%" AND
      reason LIKE "%' . $this->reason . '%" AND
      type LIKE "%' . $this->type . '%" AND
      payment_status LIKE "%' . $this->payment_status . '%"
      ' . $date_issue . $date_payment;

    $info = "SELECT COUNT(*) as count 
      FROM checkbooks
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM checkbooks
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['date_issue'] = date(
        "d/m/Y",
        strtotime($items[$i]['date_issue'])
      );
      $items[$i]['n_check'] = str_pad($items[$i]['n_check'], 6, "0", STR_PAD_LEFT);
      $items[$i]['bank_account'] = $this->BankAccounts->selectBankAccount($items[$i]['bank_account_id']);
    }

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectCheckBook(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM checkbooks WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['bank_account'] = $this->BankAccounts->selectBankAccount($request['bank_account_id']);
    return $request;
  }

  public function insertCheckBook(
    int $bank_account_id,
    int $n_check,
    string $date_issue,
    string $date_payment,
    string $beneficiary,
    string $reason,
    float $amount,
    float $balance,
    string $type,
    string $payment_status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $return = "";
    $this->bank_account_id = $bank_account_id;
    $this->n_check = $n_check;
    $this->date_issue = $date_issue;
    $this->date_payment = $date_payment;
    $this->beneficiary = $beneficiary;
    $this->reason = $reason;
    $this->amount = $amount;
    $this->balance = $balance;
    $this->type = $type;
    $this->payment_status = $payment_status;

    $sql = "SELECT * FROM checkbooks WHERE bank_account_id = '$this->bank_account_id' AND n_check = '$this->n_check'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO checkbooks (
          bank_account_id,
          n_check,
          date_issue,
          date_payment,
          beneficiary,
          reason,
          amount,
          balance,
          type,
          payment_status
        ) VALUES (?,?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->bank_account_id,
        $this->n_check,
        $this->date_issue,
        $this->date_payment,
        $this->beneficiary,
        $this->reason,
        $this->amount,
        $this->balance,
        $this->type,
        $this->payment_status,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateCheckBook(
    int $id,
    int $bank_account_id,
    int $n_check,
    string $date_issue,
    string $date_payment,
    string $beneficiary,
    string $reason,
    float $amount,
    float $balance,
    string $type,
    string $payment_status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->bank_account_id = $bank_account_id;
    $this->n_check = $n_check;
    $this->date_issue = $date_issue;
    $this->date_payment = $date_payment;
    $this->beneficiary = $beneficiary;
    $this->reason = $reason;
    $this->amount = $amount;
    $this->balance = $balance;
    $this->type = $type;
    $this->payment_status = $payment_status;

    $sql = "SELECT * FROM checkbooks WHERE id != '$this->id' AND bank_account_id = '$this->bank_account_id' AND n_check = '$this->n_check'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE checkbooks SET 
      bank_account_id = ?,
      n_check = ?,
      date_issue = ?,
      date_payment = ?,
      beneficiary = ?,
      reason = ?,
      amount = ?,
      balance = ?,
      type = ?,
      payment_status = ?
      WHERE id = $this->id";
      $arrData = array(
        $this->bank_account_id,
        $this->n_check,
        $this->date_issue,
        $this->date_payment,
        $this->beneficiary,
        $this->reason,
        $this->amount,
        $this->balance,
        $this->type,
        $this->payment_status,
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }
}
