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

    $where = '
      checkbook LIKE "%' . $this->checkbook . '%" AND 
      status LIKE "%' . $this->status . '%" AND 
      status > 0 
    ';

    $info = "SELECT COUNT(*) as count 
      FROM bank_accounts
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM bank_accounts
      WHERE $where  
      ORDER BY id ASC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['amount'] = 10500.75;
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
