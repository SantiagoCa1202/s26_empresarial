<?php
require_once('ProvidersModel.php');
require_once('CategoriesModel.php');

class ProductsModel extends Mysql
{
  public $id;
  public $auxiliary_code;
  public $ean_code;
  public $name;
  public $description;
  public $trademark;
  public $serie;
  public $stock;
  public $min_stock;
  public $provider;
  public $category;
  public $iva;
  public $cost;
  public $pvp_1;
  public $pvp_2;
  public $pvp_3;
  public $status;
  public $perPage;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Provider = new ProvidersModel;
    $this->Category = new CategoriesModel;
  }

  public function selectProducts(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->auxiliary_code = $filter['auxiliary_code'];
    $this->ean_code = $filter['ean_code'];
    $this->name = $filter['name'];
    $this->serie = $filter['serie'];
    $this->trademark = $filter['trademark'];
    $this->provider = $filter['provider'];
    $this->category = $filter['category'];
    $this->cost = $filter['cost'];
    $this->pvp = $filter['pvp'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $where = '
      id LIKE "%' . $this->id . '%" AND
      auxiliary_code LIKE "%' . $this->auxiliary_code . '%" AND
      ean_code LIKE "%' . $this->ean_code . '%" AND
      name LIKE "%' . $this->name . '%" AND
      serie LIKE "%' . $this->serie . '%" AND
      trademark LIKE "%' . $this->trademark . '%" AND
      provider_id LIKE "%' . $this->provider . '%" AND
      category_id LIKE "%' . $this->category . '%" AND
      cost LIKE "%' . $this->cost . '%" AND
      (
        pvp_1 LIKE "%' . $this->pvp . '%" OR
        pvp_2 LIKE "%' . $this->pvp . '%" OR
        pvp_3 LIKE "%' . $this->pvp . '%"
      ) AND
      status LIKE "%' . $this->status . '%" AND 
      status > 0 
    ';

    $info = "SELECT COUNT(*) as count, 
      SUM(stock) as total_stock, 
      SUM(cost*stock) as total_cost,
      SUM(pvp_1*stock) as total_pvp
      FROM products
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);
      
    //PENDIENTE 
    $info_table['total_entries'] = "0";
    $info_table['total_outputs'] = "0";
    $rows = "
      SELECT *
      FROM products
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['provider'] = $this->Provider->selectProvider($items[$i]['provider_id']);
      $items[$i]['category'] = $this->Category->selectCategory($items[$i]['category_id']);
    }


    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectProduct(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM products WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }
}
