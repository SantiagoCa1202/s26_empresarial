<?php
require_once('SystemModel.php');

class ProvidersModel extends Mysql
{
  public $id;
  public $document;
  public $business_name;
  public $tradename;
  public $email;
  public $seller;
  public $phone;
  public $phone_2;
  public $mobile_provider;
  public $mobile_sellet;
  public $alias;
  public $city_id;
  public $address;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
    $this->System = new SystemModel;
  }

  public function selectProviders(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->document = $filter['document'];
    $this->business_name = $filter['business_name'];
    $this->tradename = $filter['tradename'];
    $this->city_id = $filter['city_id'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      id LIKE '%$this->id%' AND
      document LIKE '%$this->document%' AND
      business_name LIKE '%$this->business_name%' AND
      tradename LIKE '%$this->tradename%' AND
      city_id LIKE '%$this->city_id%' AND
      status LIKE '%$this->status%' AND 
      status > 0 
      $date_range
    ";

    $info = "SELECT COUNT(*) as count 
      FROM providers
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM providers
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'providers', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectProvider(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM providers WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    $request['city'] = $this->System->selectCity($request['city_id']);
    $request['bank_accounts'] = $this->selectBankAccounts($request['id']);
    $request['categories'] = $this->selectCategories($request['id']);
    $request['trademarks'] = $this->selectTrademarks($request['id']);;
    return $request;
  }

  public function insertProvider(
    string $document,
    string $business_name,
    string $tradename,
    string $email,
    string $seller,
    string $phone,
    string $phone_2,
    string $mobile_provider,
    string $mobile_seller,
    string $alias,
    string $city_id,
    string $address
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->document = $document;
    $this->business_name = $business_name;
    $this->tradename = $tradename;
    $this->email = $email;
    $this->seller = $seller;
    $this->phone = $phone;
    $this->phone_2 = $phone_2;
    $this->mobile_provider = $mobile_provider;
    $this->mobile_seller = $mobile_seller;
    $this->alias = $alias;
    $this->city_id = $city_id;
    $this->address = $address;

    $sql = "SELECT * FROM providers 
      WHERE (business_name = '$this->business_name' OR 
      tradename = '$this->tradename') && document = '$this->document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO providers (
        document,
        business_name,
        tradename,
        email,
        seller,
        phone,
        phone_2,
        mobile_provider,
        mobile_seller,
        alias,
        city_id,
        address
      ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->document,
        $this->business_name,
        $this->tradename,
        $this->email,
        $this->seller,
        $this->phone,
        $this->phone_2,
        $this->mobile_provider,
        $this->mobile_seller,
        $this->alias,
        $this->city_id,
        $this->address
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateProvider(
    int $id,
    string $document,
    string $business_name,
    string $tradename,
    string $email,
    string $seller,
    string $phone,
    string $phone_2,
    string $mobile_provider,
    string $mobile_seller,
    string $alias,
    string $city_id,
    string $address,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->document = $document;
    $this->business_name = $business_name;
    $this->tradename = $tradename;
    $this->email = $email;
    $this->seller = $seller;
    $this->phone = $phone;
    $this->phone_2 = $phone_2;
    $this->mobile_provider = $mobile_provider;
    $this->mobile_seller = $mobile_seller;
    $this->alias = $alias;
    $this->city_id = $city_id;
    $this->address = $address;
    $this->status = $status;

    $sql = "SELECT * FROM providers 
      WHERE (business_name = '$this->business_name' OR 
      tradename = '$this->tradename') && document = '$this->document' && id != '$this->id'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE providers SET 
        document = ?,
        business_name = ?,
        tradename = ?,
        email = ?,
        seller = ?,
        phone = ?,
        phone_2 = ?,
        mobile_provider = ?,
        mobile_seller = ?,
        alias = ?,
        city_id = ?,
        address = ?,
        status = ? WHERE id = $this->id";
      $arrData = array(
        $this->document,
        $this->business_name,
        $this->tradename,
        $this->email,
        $this->seller,
        $this->phone,
        $this->phone_2,
        $this->mobile_provider,
        $this->mobile_seller,
        $this->alias,
        $this->city_id,
        $this->address,
        $this->status
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }

    return $request;
  }

  public function deleteProvider(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE providers SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function selectBankAccounts(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $rows = "
      SELECT *
      FROM providers_bank_accounts
      WHERE provider_id = $this->id
      ORDER BY id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['bank_entity'] = $this->System->selectBankEntity($items[$i]['bank_entity_id']);
    }
    return $items;
  }


  public function insertBankEntity(
    int $provider_id,
    string $account_number,
    int $bank_entity,
    string $document_beneficiary,
    string $beneficiary,
    string $account_type
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->provider_id = $provider_id;
    $this->account_number = $account_number;
    $this->bank_entity = $bank_entity;
    $this->document_beneficiary = $document_beneficiary;
    $this->beneficiary = $beneficiary;
    $this->account_type = $account_type;

    $sql = "SELECT * FROM providers_bank_accounts
      WHERE account_number = '$this->account_number'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO providers_bank_accounts (
        provider_id,
        account_number,
        bank_entity_id,
        document,
        beneficiary,
        account_type
      ) VALUES (?,?,?,?,?,?)";
      $arrData = array(
        $this->provider_id,
        $this->account_number,
        $this->bank_entity,
        $this->document_beneficiary,
        $this->beneficiary,
        $this->account_type
      );

      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function deleteBankAccounts(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM providers_bank_accounts WHERE provider_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);
    return $request;
  }

  public function selectCategories(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $rows = "
      SELECT *
      FROM providers_categories
      WHERE provider_id = $this->id
      ORDER BY id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);
    $category_id = [];
    for ($i = 0; $i < count($items); $i++) {
      array_push($category_id, intval($items[$i]['category_id']));
    }
    return $category_id;
  }

  public function insertCategories(
    int $provider_id,
    int $category_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->provider_id = $provider_id;
    $this->category_id = $category_id;

    $query_insert = "INSERT INTO providers_categories (
        provider_id,
        category_id
      ) VALUES (?,?)";
    $arrData = array(
      $this->provider_id,
      $this->category_id
    );

    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function deleteCategories(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM providers_categories WHERE provider_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);
    return $request;
  }

  public function selectTrademarks(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $rows = "
      SELECT *
      FROM providers_trademarks
      WHERE provider_id = $this->id
      ORDER BY id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);
    $trademark = [];
    for ($i = 0; $i < count($items); $i++) {
      array_push($trademark, $items[$i]['trademark']);
    }
    return $trademark;
  }

  public function insertTrademarks(
    int $provider_id,
    string $trademark
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->provider_id = $provider_id;
    $this->trademark = $trademark;

    $query_insert = "INSERT INTO providers_trademarks (
        provider_id,
        trademark
      ) VALUES (?,?)";
    $arrData = array(
      $this->provider_id,
      $this->trademark
    );

    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function deleteTrademarks(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM providers_trademarks WHERE provider_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);
    return $request;
  }
}
