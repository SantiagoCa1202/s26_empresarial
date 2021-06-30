<?php

class SystemModel extends Mysql
{
  public $id;
  public $data_base;
  public $type_trade;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectDatabase(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM data_bases WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function selectTypetrade(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM type_of_shops WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function selectPaymentMethod(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM payment_methods WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function selectBankEntities(int $perPage, array $filter)
  {

    $this->id = $filter['id'];
    $this->bank_entity = $filter['bank_entity'];
    $this->perPage = $perPage;

    $where = '
      id LIKE "%' . $this->id . '%" AND
      bank_entity LIKE "%' . $this->bank_entity . '%" AND
      status > 0
    ';

    $info = "SELECT COUNT(*) as count 
      FROM bank_entities u
      WHERE $where 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *
      FROM bank_entities
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all($rows);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectBankEntity($id)
  {
    $this->id = intval($id);
    $sql = "SELECT * FROM bank_entities WHERE id = $this->id";

    $arr = array(
      "bank_entity" => "",
      "url" => "",
      "logo" => "",
      "status" => "",
    );
    $request = $this->select($sql) ?
      $this->select($sql) :
      $arr;

    return $request;
  }
}
