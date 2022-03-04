<?php
class StockAdjustmentModel extends Mysql
{

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectAdjustments(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->establishment_id = $filter['establishment_id'];
    $this->ean_code = $filter['ean_code'];
    $this->sku = $filter['sku'];
    $this->product = $filter['product'];
    $this->model = $filter['model'];
    $this->trademark = $filter['trademark'];
    $this->perPage = $perPage;

    $where = "
      s.establishment_id = $this->establishment_id AND
      pe.name LIKE '%$this->product%' AND
      pe.model LIKE '%$this->model%' AND
      pe.trademark LIKE '%$this->trademark%' AND
      pe.ean_code LIKE '%$this->ean_code%' AND
      pe.sku LIKE '%$this->sku%' 
    ";

    $info = "SELECT COUNT(DISTINCT s.id) as count 
      FROM stock_adjustment s
      LEFT JOIN (SELECT pe.product_variant_id, pe.establishment_id, 
        pe.stock, pv.ean_code, pv.sku, pv.pvp_1, pv.pvp_2, pv.pvp_3,
        p.name, p.model, p.trademark
        FROM products_establishments pe
        JOIN products_variant pv 
        ON pe.product_variant_id = pv.id
        JOIN products p
        ON pv.product_id = p.id
        WHERE p.name LIKE '%$this->product%' AND
        p.model LIKE '%$this->model%' AND
        p.trademark LIKE '%$this->trademark%' AND
        pv.ean_code LIKE '%$this->ean_code%' AND
        pv.sku LIKE '%$this->sku%' 
      )pe
      ON s.establishment_id = pe.establishment_id AND s.product_variant_id = pe.product_variant_id
      WHERE $where
     ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT s.id, s.amount, pe.ean_code, pe.name, pe.model, pe.trademark, pe.sku, pe.stock, if(pe.pvp_3 > 0, pe.pvp_3, if(pe.pvp_2 > 0, pe.pvp_2, pe.pvp_1)) as pvp, pe.product_id
      FROM stock_adjustment s
      LEFT JOIN (SELECT pe.product_variant_id, pe.establishment_id, 
        pe.stock, pv.ean_code, pv.sku, pv.pvp_1, pv.pvp_2, pv.pvp_3,
        p.name, p.model, p.trademark, pv.product_id
        FROM products_establishments pe
        JOIN products_variant pv 
        ON pe.product_variant_id = pv.id
        JOIN products p
        ON pv.product_id = p.id
        WHERE p.name LIKE '%$this->product%' AND
        p.model LIKE '%$this->model%' AND
        p.trademark LIKE '%$this->trademark%' AND
        pv.ean_code LIKE '%$this->ean_code%' AND
        pv.sku LIKE '%$this->sku%' 
      )pe
      ON s.establishment_id = pe.establishment_id AND s.product_variant_id = pe.product_variant_id
      WHERE $where  
      ORDER BY s.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }
}
