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
      FROM products_variant
      WHERE ean_code = $this->code
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function insertProduct(
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
    int $pvp_manual,
    float $iva,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

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
    $this->pvp_manual = $pvp_manual;
    $this->iva = $iva;
    $this->status = $status;

    $query_insert = "INSERT INTO products (
        name, description, trademark, model, type_product, type, remanufactured, category_id, discontinued, serial, discount, pvp_manual, iva, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
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
      $this->pvp_manual,
      $this->iva,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

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
    int $document_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->amount = $amount;
    $this->document_id = $document_id;

    $query_insert = "INSERT INTO products_entries (product_id, amount, document_id) VALUES (?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->amount,
      $this->document_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertVariantEstablishment(
    int $product_variant_id,
    int $establishment_id
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->establishment_id = $establishment_id;

    $query_insert = "INSERT INTO products_establishments (product_variant_id, establishment_id) VALUES (?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->establishment_id
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertEntryEstablishment(
    int $product_variant_id,
    int $amount,
    int $from,
    int $to,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->amount = $amount;
    $this->from = $from;
    $this->to = $to;

    $query_insert = "INSERT INTO products_entries_establishments (product_variant_id, amount, from_establishment_id, to_establishment_id) VALUES (?,?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->amount,
      $this->from,
      $this->to,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertVariant(
    int $product_id,
    string $code,
    string $sku,
    int $amount,
    int $min_stock,
    int $max_stock,
    float $cost,
    float $pvp_1,
    float $pvp_2,
    float $pvp_3,
    float $pvp_distributor,
    string $additional_info,
    int $status,

  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->code = $code;
    $this->sku = $sku;
    $this->amount = $amount;
    $this->min_stock = $min_stock;
    $this->max_stock = $max_stock;
    $this->cost = $cost;
    $this->pvp_1 = $pvp_1;
    $this->pvp_2 = $pvp_2;
    $this->pvp_3 = $pvp_3;
    $this->pvp_distributor = $pvp_distributor;
    $this->additional_info = $additional_info;
    $this->status = $status;

    $query_insert = "INSERT INTO products_variant (
      product_id,
      ean_code,
      sku,
      stock,
      min_stock,
      max_stock,
      cost,
      pvp_1,
      pvp_2,
      pvp_3,
      pvp_distributor,
      additional_info,
      status
          ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->code,
      $this->sku,
      $this->amount,
      $this->min_stock,
      $this->max_stock,
      $this->cost,
      $this->pvp_1,
      $this->pvp_2,
      $this->pvp_3,
      $this->pvp_distributor,
      $this->additional_info,
      $this->status,
    );

    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertEntryVariant(
    int $product_variant_id,
    int $amount,
    int $cost
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->amount = $amount;
    $this->cost = $cost;

    $query_insert = "INSERT INTO products_entries_variants (product_variant_id,amount,cost) VALUES (?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->amount,
      $this->cost
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertVariants(
    int $product_variant_id,
    string $type_variant,
    string $description,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->type_variant = $type_variant;
    $this->description = $description;

    $query_insert = "INSERT INTO products_variants (product_variant_id, type_variant, description) VALUES (?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->type_variant,
      $this->description
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;

  }

  public function insertVariantDimensions(
    int $product_variant_id,
    float $product_length,
    float $product_height,
    float $product_width,
    float $product_weight,
    float $box_length,
    float $box_height,
    float $box_width,
    float $box_weight,
    float $box_stacking,

  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->product_length = $product_length;
    $this->product_height = $product_height;
    $this->product_width = $product_width;
    $this->product_weight = $product_weight;
    $this->box_length = $box_length;
    $this->box_height = $box_height;
    $this->box_width = $box_width;
    $this->box_weight = $box_weight;
    $this->box_stacking = $box_stacking;

    $query_insert = "INSERT INTO products_variant_dimensions (product_variant_id,product_length, product_height, product_width, product_weight, box_length, box_height, box_width, box_weight, box_stacking) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->product_length,
      $this->product_height,
      $this->product_width,
      $this->product_weight,
      $this->box_length,
      $this->box_height,
      $this->box_width,
      $this->box_weight,
      $this->box_stacking,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;

  }

  public function insertPhotos(
    int $product_variant_id,
    int $photo_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->photo_id = $photo_id;

    $query_insert = "INSERT INTO products_photos (product_variant_id, photo_id) VALUES (?,?)";
    $arrData = array(
      $this->product_variant_id,
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
