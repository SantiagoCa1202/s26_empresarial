<?php

require_once('SystemModel.php');
class ProductsSeriesModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Document = new SystemModel;
  }

  public function selectSeries(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->serie = $filter['serie'];
    $this->name = $filter['name'];
    $this->document_id = $filter['document_id'];
    $this->product_id = $filter['product_id'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $document_id = $this->document_id >= -1 ? "ps.document_id = '$this->document_id' AND" : "";

    $product_id = $this->product_id > 0 ? "ps.product_id = '$this->product_id' AND" : "";

    $where = "
      $document_id
      $product_id
      ps.serie LIKE '%$this->serie%' AND
      (
        p.name LIKE '%$this->name%' OR
        p.model LIKE '%$this->name%' OR
        p.trademark LIKE '%$this->name%'
      ) AND
      ps.status LIKE '%$this->status%'
    ";

    $info = "SELECT COUNT(*) as count
      FROM products_series ps
      JOIN products p
      ON ps.product_id = p.id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT DISTINCT ps.id, ps.serie, p.name, b.type_doc_id, b.n_document, ps.document_id, p.model, p.trademark, ps.status
      FROM products_series ps
      JOIN products p
      ON ps.product_id = p.id
      LEFT JOIN ( 
        SELECT id, type_doc_id, n_document
        FROM buys
        GROUP BY id
      ) b
      ON ps.document_id = b.id
      WHERE $where  
      ORDER BY ps.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['document'] = $items[$i]['document_id'] > 0 ? $this->Document->selectDocument($items[$i]['type_doc_id']) : '';
    }

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectSerie(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT ps.id, ps.serie, p.name, ps.document_id, p.model, p.trademark, ps.status
      FROM products_series ps
      JOIN products p
      ON ps.product_id = p.id
      WHERE ps.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function updateSerie(
    int $id,
    int $document_id,
    string $serie,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->document_id = $document_id;
    $this->serie = $serie;
    $this->status = $status;

    $sql = "UPDATE products_series SET document_id = ?, serie = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->document_id,
      $this->serie,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }
}
