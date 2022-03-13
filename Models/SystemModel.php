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

  public function selectBankEntities(int $perPage, array $filter)
  {

    $this->id = $filter['id'];
    $this->bank_entity = $filter['bank_entity'];
    $this->perPage = $perPage;

    $where = "
      id LIKE '%$this->id%' AND
      bank_entity LIKE '%$this->bank_entity%' AND
      status > 0
    ";

    $info = "SELECT COUNT(*) as count 
      FROM bank_entities
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
    $request['url_logo'] = asset('media/logos_bank_entities/') . $request['logo'];
    return $request;
  }

  public function selectDocuments(int $perPage, array $filter)
  {

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->perPage = $perPage;

    $where = "
      id LIKE '%$this->id%' AND
      name LIKE '%$this->name%' AND
      status > 0
    ";

    $info = "SELECT COUNT(*) as count 
      FROM documents
      WHERE $where 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *
      FROM documents
      WHERE $where  
      ORDER BY id ASC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all($rows);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectDocument(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM documents WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function selectIcons(int $perPage, array $filter)
  {
    $this->name = $filter['name'];
    $this->perPage = $perPage;

    $info = "SELECT COUNT(*) as count 
      FROM icons
      WHERE name LIKE '%$this->name%' 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *
      FROM icons
      WHERE name LIKE '%$this->name%' 
      ORDER BY id ASC LIMIT 0, $this->perPage 
    ";

    $items = $this->select_all($rows);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectIcon(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM icons WHERE id = $this->id";
    $request = $this->select($sql);

    return $request;
  }

  public function selectCities(int $perPage, array $filter)
  {
    $this->name = $filter['name'];
    $this->perPage = $perPage;

    $info = "SELECT COUNT(*) as count 
      FROM cities
      WHERE name LIKE '%$this->name%' 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *
      FROM cities
      WHERE name LIKE '%$this->name%' 
      ORDER BY id ASC LIMIT 0, $this->perPage 
    ";

    $items = $this->select_all($rows);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectCity(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM cities WHERE id = $this->id";
    $request = $this->select($sql);

    return $request;
  }

  public function selectColors(int $perPage, array $filter)
  {
    $this->name = $filter['name'];
    $this->perPage = $perPage;

    $info = "SELECT COUNT(*) as count 
      FROM colors
      WHERE name LIKE '%$this->name%' 
    ";
    $info_table = $this->info_table($info);

    $rows = "
      SELECT *
      FROM colors
      WHERE name LIKE '%$this->name%' 
      ORDER BY id ASC LIMIT 0, $this->perPage 
    ";

    $items = $this->select_all($rows);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectColor(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM colors WHERE id = $this->id";
    $request = $this->select($sql);

    return $request;
  }
}
