<?php
require_once('ProvidersModel.php');
require_once('EstablishmentsModel.php');
require_once('CategoriesModel.php');

class ProductsModel extends Mysql
{
  public $id;
  public $auxiliary_code;
  public $ean_code;
  public $name;
  public $description;
  public $trademark;
  public $model;
  public $type_product;
  public $type;
  public $remanufactured;
  public $category_id;
  public $discontinued;
  public $serial;
  public $discount;
  public $offer;
  public $pvp_manual;
  public $iva;
  public $amount;
  public $cost;
  public $pvp_1;
  public $pvp_2;
  public $pvp_3;
  public $pvp_distributor;
  public $pvp_offer;
  public $product_length;
  public $product_width;
  public $product_height;
  public $product_weight;
  public $box_length;
  public $box_width;
  public $box_height;
  public $box_weight;
  public $box_stacking;
  public $status;
  public $perPage;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Provider = new ProvidersModel;
    $this->Category = new CategoriesModel;
    $this->Establishment = new EstablishmentsModel;
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
    $this->establishment_id = $_SESSION['userData']['establishment_id'];

    $where = '
      p.id LIKE "%' . $this->id . '%" AND
      p.auxiliary_code LIKE "%' . $this->auxiliary_code . '%" AND
      p.ean_code LIKE "%' . $this->ean_code . '%" AND
      p.name LIKE "%' . $this->name . '%" AND
      p.model LIKE "%' . $this->serie . '%" AND
      p.trademark LIKE "%' . $this->trademark . '%" AND
      p.category_id LIKE "%' . $this->category . '%" AND
      p.cost LIKE "%' . $this->cost . '%" AND
      (
        p.pvp_1 LIKE "%' . $this->pvp . '%" OR
        p.pvp_2 LIKE "%' . $this->pvp . '%" OR
        p.pvp_3 LIKE "%' . $this->pvp . '%" OR 
        p.pvp_distributor LIKE "%' . $this->pvp . '%"
      ) AND
      p.status LIKE "%' . $this->status . '%" AND 
      p.status > 0 AND 
      pe.establishment_id = ' . $_SESSION['userData']['establishment_id'];

    $info = "SELECT COUNT(*) as count, 
      SUM(pe.stock) as total_stock, 
      SUM(p.cost*pe.stock) as total_cost,
      SUM(p.pvp_1*pe.stock) as total_pvp
      FROM products AS p
      JOIN products_establishments AS pe
      ON p.id = pe.product_id
      WHERE $where
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT *, p.id as id, p.status as status
      FROM products p
      JOIN products_establishments pe
      ON p.id = pe.product_id
      WHERE $where 
      ORDER BY p.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['establishment'] = ($_SESSION['userData']['user_access'] == 2) ? $this->establishmentStock($items[$i]['id'], $this->establishment_id) : $this->establishmentStocks($items[$i]['id']);

      $items[$i]['category'] = $this->Category->selectCategory($items[$i]['category_id']);
      $items[$i]['providers'] = $this->selectProviders($items[$i]['id']);
    }


    return [
      'items' => $items,
      'info' => $info_table,
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

  public function searchProduct($code)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->code = $code;

    $rows = "
      SELECT *
      FROM products
      WHERE ean_code = $this->code
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function insertProduct(
    string $auxiliary_code,
    string $ean_code,
    string $name,
    string $description,
    string $trademark,
    string $model,
    string $type_product,
    string $type,
    int $remanufactured,
    int $category_id,
    int $discontinued,
    int $serial,
    int $discount,
    int $offer,
    int $pvp_manual,
    float $iva,
    int $amount,
    float $cost,
    float $pvp_1,
    float $pvp_2,
    float $pvp_3,
    float $pvp_distributor,
    float $pvp_offer,
    float $product_length,
    float $product_width,
    float $product_height,
    float $product_weight,
    float $box_length,
    float $box_width,
    float $box_height,
    float $box_weight,
    int $box_stacking,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->auxiliary_code = $auxiliary_code;
    $this->ean_code = $ean_code;
    $this->name = $name;
    $this->description = $description;
    $this->trademark = $trademark;
    $this->model = $model;
    $this->type_product = $type_product;
    $this->type = $type;
    $this->remanufactured = $remanufactured;
    $this->category_id = $category_id;
    $this->discontinued = $discontinued;
    $this->serial = $serial;
    $this->discount = $discount;
    $this->offer = $offer;
    $this->pvp_manual = $pvp_manual;
    $this->iva = $iva;
    $this->amount = $amount;
    $this->cost = $cost;
    $this->pvp_1 = $pvp_1;
    $this->pvp_2 = $pvp_2;
    $this->pvp_3 = $pvp_3;
    $this->pvp_distributor = $pvp_distributor;
    $this->pvp_offer = $pvp_offer;
    $this->product_length = $product_length;
    $this->product_width = $product_width;
    $this->product_height = $product_height;
    $this->product_weight = $product_weight;
    $this->box_length = $box_length;
    $this->box_width = $box_width;
    $this->box_height = $box_height;
    $this->box_weight = $box_weight;
    $this->box_stacking = $box_stacking;
    $this->status = $status;



    $sql = "SELECT * FROM products WHERE ean_code = '$this->ean_code'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO products (
        auxiliary_code, ean_code, name, description, trademark, model, type_product, type, remanufactured, category_id, discontinued, serial, discount, offer, pvp_manual, iva, stock, cost, pvp_1, pvp_2, pvp_3, pvp_distributor, pvp_offer, product_length, product_width, product_height, product_weight, box_length, box_width, box_height, box_weight, box_stacking, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->auxiliary_code,
        $this->ean_code,
        $this->name,
        $this->description,
        $this->trademark,
        $this->model,
        $this->type_product,
        $this->type,
        $this->remanufactured,
        $this->category_id,
        $this->discontinued,
        $this->serial,
        $this->discount,
        $this->offer,
        $this->pvp_manual,
        $this->iva,
        $this->amount,
        $this->cost,
        $this->pvp_1,
        $this->pvp_2,
        $this->pvp_3,
        $this->pvp_distributor,
        $this->pvp_offer,
        $this->product_length,
        $this->product_width,
        $this->product_height,
        $this->product_weight,
        $this->box_length,
        $this->box_width,
        $this->box_height,
        $this->box_weight,
        $this->box_stacking,
        $this->status,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }

    return $request;
  }
  public function updatePrices(
    int $product_id,
    float $pvp_1,
    float $pvp_2,
    float $pvp_3,
    float $pvp_distributor,
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->pvp_1 = $pvp_1;
    $this->pvp_2 = $pvp_2;
    $this->pvp_3 = $pvp_3;
    $this->pvp_distributor = $pvp_distributor;



    $sql = "UPDATE products SET pvp_1 = ?, pvp_2 = ?, pvp_3 = ?, pvp_distributor = ? WHERE id = $this->product_id";
    $arrData = array(
      $this->pvp_1,
      $this->pvp_2,
      $this->pvp_3,
      $this->pvp_distributor,
    );
    $request = $this->update_company($sql, $arrData, $this->db_company);
    return $request;
  }

  public function insertEntry(
    int $product_id,
    int $amount,
    float $cost,
    int $document_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->amount = $amount;
    $this->cost = $cost;
    $this->document_id = $document_id;

    $query_insert = "INSERT INTO products_entries (product_id, amount, cost, document_id) VALUES (?,?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->amount,
      $this->cost,
      $this->document_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertEntryEstablishment(
    int $product_id,
    int $amount,
    int $from,
    int $to,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->amount = $amount;
    $this->from = $from;
    $this->to = $to;

    $query_insert = "INSERT INTO products_entries_establishments (product_id, amount, from_establishment_id, to_establishment_id) VALUES (?,?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->amount,
      $this->from,
      $this->to,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertEstablishment(
    int $product_id,
    int $stock,
    int $min_stock,
    int $max_stock,
    int $establishment_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->stock = $stock;
    $this->min_stock = $min_stock;
    $this->max_stock = $max_stock;
    $this->establishment_id = $establishment_id;

    $query_insert = "INSERT INTO products_establishments (product_id, stock, min_stock, max_stock, establishment_id) VALUES (?,?,?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->stock,
      $this->min_stock,
      $this->max_stock,
      $this->establishment_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }
  public function updateEstablishment(
    int $product_id,
    int $amount,
    int $establishment_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->amount = $amount;
    $this->establishment_id = $establishment_id;



    $sql = "SELECT stock FROM products_establishments WHERE product_id = $this->product_id AND establishment_id = $this->establishment_id";
    $request = $this->select_company($sql, $this->db_company);

    $new_stock = $request['stock'] + $this->amount;

    $sql = "UPDATE products_establishments SET stock = ? WHERE product_id = $this->product_id AND establishment_id = $this->establishment_id";
    $arrData = array(
      $new_stock
    );
    $request = $this->update_company($sql, $arrData, $this->db_company);
    return $request;
  }

  public function insertPhotos(
    int $product_id,
    int $photo_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->photo_id = $photo_id;

    $query_insert = "INSERT INTO products_photos (product_id, photo_id) VALUES (?,?)";
    $arrData = array(
      $this->product_id,
      $this->photo_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function deletePhotos(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM products_photos WHERE product_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);
    return $request;
  }

  public function insertSerie(
    int $product_id,
    int $entry_id,
    string $serie,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->entry_id = $entry_id;
    $this->serie = $serie;

    $query_insert = "INSERT INTO products_series (product_id, entry_id, serie) VALUES (?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->entry_id,
      $this->serie,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function deleteSeries(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM products_series WHERE product_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);
    return $request;
  }

  public function selectProviders(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $rows = "
      SELECT *, p.id as id
      FROM products_providers pp 
      JOIN providers p
      ON pp.provider_id = p.id
      WHERE product_id = $this->id
    ";

    $items = $this->select_all_company($rows, $this->db_company);
    return $items;
  }

  public function insertProvider(
    int $product_id,
    int $provider_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->provider_id = $provider_id;

    $query_insert = "INSERT INTO products_providers (product_id, provider_id) VALUES (?,?)";
    $arrData = array(
      $this->product_id,
      $this->provider_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function deleteProviders(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM products_providers WHERE product_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);
    return $request;
  }

  public function establishmentStock(int $product_id, int $establishment_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $sql = "SELECT * FROM products_establishments WHERE product_id = $product_id AND establishment_id = $establishment_id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function establishmentStocks(int $product_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $rows = "
      SELECT *
      FROM products_establishments
      WHERE product_id = $product_id   
    ";

    $items = $this->select_all_company($rows, $this->db_company);
    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['establishment'] = $this->Establishment->selectEstablishment($items[$i]['establishment_id']);;
    }
    return $items;
  }

  public function updateCost(
    int $product_id,
    int $amount,
    int $cost
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->amount = $amount;
    $this->cost = $cost;

    $query_insert = "CALL update_cost_product(?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->amount,
      $this->cost,
    );
    $request = $this->update_company($query_insert, $arrData, $this->db_company);
    return $request;
  }
}
