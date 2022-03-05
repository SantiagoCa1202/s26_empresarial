<?php
require_once('SystemModel.php');
class ProductsDamagedsModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
    $this->Document = new SystemModel;
  }

  public function selectProductsDamageds(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->code = $filter['code'];
    $this->name = $filter['name'];
    $this->document_id = $filter['document_id'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND pev.created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $document_id = $this->document_id >= -1 ? "pev.document_id = '$this->document_id' AND" : "";


    $where = "
      $document_id
      pv.ean_code LIKE '%$this->code%' AND
      (
        p.name LIKE '%$this->name%' OR
        p.model LIKE '%$this->name%' OR
        p.trademark LIKE '%$this->name%'
      )
      $date_range AND pd.status > 0

    ";

    $info = "SELECT COUNT(*) as count, SUM(pd.cost*pd.amount) as total_cost,  SUM(pd.amount) as total_damageds
      FROM products_damageds pd
      JOIN products_variant pv
      ON pd.product_variant_id = pv.id
      JOIN products p
      ON pv.product_id = p.id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT DISTINCT pd.id, pd.cost, pv.ean_code, p.name, pd.amount, b.type_doc_id , b.n_document, pd.document_id, p.model, p.trademark, pd.created_at
      FROM products_damageds pd
      JOIN products_variant pv
      ON pd.product_variant_id = pv.id
      JOIN products p
      ON pv.product_id = p.id
      LEFT JOIN ( 
        SELECT id, type_doc_id, n_document
        FROM buys
        GROUP BY id
      ) b
      ON pd.document_id = b.id
      WHERE $where  
      ORDER BY pd.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['document'] = $items[$i]['document_id'] > 0 ? $this->Document->selectDocument($items[$i]['type_doc_id']) : '';
    }

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'products_damageds', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectProductDamaged(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT pd.*, CONCAT(pv.ean_code, ' / ', p.name, ' / ', p.model, ' / ', p.trademark, ' / ', pv.sku) as product, pe.stock
      FROM products_damageds pd
      JOIN products_variant pv
      ON pd.product_variant_id = pv.id
      JOIN products p
      ON pv.product_id = p.id
      JOIN products_establishments pe
      ON pv.id = pe.product_variant_id AND pd.establishment_id = pe.establishment_id
      WHERE pd.id = $this->id
    ";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertProductDamaged(
    int $product_variant_id,
    float $cost,
    int $amount,
    string $product_status,
    int $document_id,
    string $description,
    int $status,
    int $establishment_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->cost = $cost;
    $this->amount = $amount;
    $this->product_status = $product_status;
    $this->document_id = $document_id;
    $this->description = $description;
    $this->status = $status;
    $this->establishment_id = $establishment_id;

    $query_insert = "INSERT INTO products_damageds (product_variant_id, cost, amount, product_status, document_id, description, status, establishment_id) VALUES (?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->cost,
      $this->amount,
      $this->product_status,
      $this->document_id,
      $this->description,
      $this->status,
      $this->establishment_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateProductDamaged(
    int $id,
    int $product_variant_id,
    float $cost,
    int $amount,
    string $product_status,
    int $document_id,
    string $description,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->product_variant_id = $product_variant_id;
    $this->cost = $cost;
    $this->amount = $amount;
    $this->product_status = $product_status;
    $this->document_id = $document_id;
    $this->description = $description;
    $this->status = $status;
    $sql = "UPDATE products_damageds SET product_variant_id = ?, cost = ?, amount = ?, product_status = ?, document_id = ?, description = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->product_variant_id,
      $this->cost,
      $this->amount,
      $this->product_status,
      $this->document_id,
      $this->description,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function deleteProductDamaged(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "SELECT * FROM products_damageds WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $this->variant_id = $request['product_variant_id'];
    $this->establishment_id = $request['establishment_id'];
    $this->amount = $request['amount'];

    $sql = "SELECT * FROM products_establishments WHERE product_variant_id = $this->variant_id AND establishment_id = $this->establishment_id";
    $request = $this->select_company($sql, $this->db_company);
    $currentStock = $request['stock'];
    $newStock = $currentStock + $this->amount;

    $sql = "UPDATE products_establishments SET stock = ? WHERE product_variant_id = $this->variant_id AND establishment_id = $this->establishment_id";
    $arrData = array(
      $newStock
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    $sql = "UPDATE products_damageds SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
