<?php

require_once('CustomersModel.php');

class NewSaleModel extends Mysql
{

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Customer = new CustomersModel;
  }

  public function selectSavedSales(int $user_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->user_id = $user_id;

    $rows = "
      SELECT *
      FROM sales_saved
      WHERE user_id = $this->user_id  
      ORDER BY id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['customer'] = $items[$i]['customer_id'] > 0 ? $this->Customer->selectCustomer($items[$i]['customer_id']) : 0;
      $items[$i]['products'] = $this->selectSavedSalesProducts($items[$i]['id']);
    }
    return $items;
  }

  public function selectSavedSale(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM sales_saved WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['products'] = $this->selectSavedSalesProducts($request['id']);

    return $request;
  }

  public function selectSavedSalesProducts(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $establishment_id = $_SESSION['userData']['establishment_id'];
    $rows = "SELECT ssp.product_variant_id as id, ssp.amount, ssp.cost, ssp.pvp, ssp.discount, ssp.iva, 
      pv.ean_code as code, pe.pvp_1, pe.pvp_2, pe.pvp_3, pe.pvp_distributor, pv.product_id,
      pe.stock, CONCAT(p.name, ' / ', p.model, ' / ', p.trademark) product, p.serial
      FROM sales_saved_products ssp
      JOIN products_variant pv
      ON ssp.product_variant_id = pv.id
      JOIN products_establishments pe
      ON ssp.product_variant_id = pe.product_variant_id AND pe.establishment_id = $establishment_id
      JOIN products p 
      ON pv.product_id = p.id
      WHERE  ssp.sale_saved_id = $this->id  
      ORDER BY  ssp.id ASC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['series'] = $this->selectSavedSalesProductsSeries($items[$i]['product_id']);
      $items[$i]['net_total'] = $items[$i]['amount'] * $items[$i]['pvp'] - $items[$i]['discount'];
    }
    return $items;
  }

  public function selectSavedSalesProductsSeries(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $rows = "SELECT serie_id as id
      FROM sales_saved_products_series
      WHERE product_id = $this->id  
      ORDER BY id 
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function insertSaleSaved(
    int $customer_id,
    string $note,
    int $user_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->customer_id = $customer_id;
    $this->note = $note;
    $this->user_id = $user_id;


    $query_insert = "INSERT INTO sales_saved (customer_id, note, user_id) VALUES (?,?,?)";
    $arrData = array(
      $this->customer_id,
      $this->note,
      $this->user_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function updateSale(
    int $id,
    int $customer_id,
    string $note,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->customer_id = $customer_id;
    $this->note = $note;

    $query_insert = "UPDATE sales_saved SET customer_id = ?,
    note = ? WHERE id = $this->id";
    $arrData = array(
      $this->customer_id,
      $this->note,
    );
    $request = $this->update_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function insertProduct(
    int $sale_saved_id,
    int $product_variant_id,
    int $amount,
    float $cost,
    float $pvp,
    float $discount,
    float $iva
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_saved_id = $sale_saved_id;
    $this->product_variant_id = $product_variant_id;
    $this->amount = $amount;
    $this->cost = $cost;
    $this->pvp = $pvp;
    $this->discount = $discount;
    $this->iva = $iva;


    $query_insert = "INSERT INTO sales_saved_products (sale_saved_id,
    product_variant_id, amount, cost, pvp, discount, iva) VALUES (?,?,?,?,?,?,?)";
    $arrData = array(
      $this->sale_saved_id,
      $this->product_variant_id,
      $this->amount,
      $this->cost,
      $this->pvp,
      $this->discount,
      $this->iva,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function insertSeries(
    int $sale_saved_id,
    int $product_id,
    int $serie_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_saved_id = $sale_saved_id;
    $this->product_id = $product_id;
    $this->serie_id = $serie_id;


    $query_insert = "INSERT INTO sales_saved_products_series (sale_saved_id, product_id,
    serie_id) VALUES (?,?,?)";
    $arrData = array(
      $this->sale_saved_id,
      $this->product_id,
      $this->serie_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function deleteProducts(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM sales_saved_products WHERE sale_saved_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function deleteSeries(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM sales_saved_products_series WHERE sale_saved_id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function deleteSavedSale(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "DELETE FROM sales_saved WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function subtractStock(
    int $variant_id,
    int $establishment_id,
    int $amount,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->variant_id = $variant_id;
    $this->establishment_id = $establishment_id;
    $this->amount = $amount;

    $sql = "SELECT * FROM products_establishments WHERE product_variant_id = $this->variant_id AND establishment_id = $this->establishment_id";
    $request = $this->select_company($sql, $this->db_company);
    $currentStock = $request['stock'];
    $newStock = $currentStock - $this->amount;

    $sql = "UPDATE products_establishments SET stock = ? WHERE product_variant_id = $this->variant_id AND establishment_id = $this->establishment_id";
    $arrData = array(
      $newStock
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);


    return $request;
  }

  //INCREMENTAR SECUENCIA DE DOCUMENTOS
  public function increaseSequence(
    int $id,
    int $sequential_numbering,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->sequential_numbering = $sequential_numbering;

    $query_insert = "UPDATE emission_point SET sequential_numbering = ? WHERE id = $this->id";
    $arrData = array(
      $this->sequential_numbering,
    );
    $request = $this->update_company($query_insert, $arrData, $this->db_company);

    return $request;
  }
  
}
