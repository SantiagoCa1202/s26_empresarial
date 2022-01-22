<?php

require_once('SystemModel.php');
class ProductsEntriesModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Document = new SystemModel;
  }

  public function selectEntries(int $perPage, array $filter)
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
      $date_range
    ";

    $info = "SELECT COUNT(*) as count, SUM(pev.cost*pev.amount) as total_cost,  SUM(pev.amount) as total_entries
      FROM products_entries_variants pev
      JOIN products_variant pv
      ON pev.product_variant_id = pv.id
      JOIN products p
      ON pv.product_id = p.id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT DISTINCT pev.id, pev.cost, pv.ean_code, p.name, pev.amount, b.type_doc_id , b.n_document, pev.document_id, p.model, p.trademark, pev.created_at
      FROM products_entries_variants pev
      JOIN products_variant pv
      ON pev.product_variant_id = pv.id
      JOIN products p
      ON pv.product_id = p.id
      LEFT JOIN ( 
        SELECT id, type_doc_id, n_document
        FROM buys
        GROUP BY id
      ) b
      ON pev.document_id = b.id
      WHERE $where  
      ORDER BY pev.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['document'] = $items[$i]['document_id'] > 0 ? $this->Document->selectDocument($items[$i]['type_doc_id']) : '';
    }

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'products_entries_variants', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectEntry(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT pev.id, pev.document_id, pev.created_at, pv.ean_code, p.name
      FROM products_entries_variants pev
      JOIN products_variant pv
      ON pev.product_variant_id = pv.id
      JOIN products p
      ON pv.product_id = p.id
      WHERE pev.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function updateEntry(
    int $id,
    int $document_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->document_id = $document_id;

    $sql = "UPDATE products_entries_variants SET document_id = ? WHERE id = $this->id";
    $arrData = array(
      $this->document_id,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }
}
