<?php

class VariantsModel extends Mysql
{

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectVariants(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $establishment_id = $_SESSION['userData']['establishment_id'];
    $this->ean_code = $filter['ean_code'];
    $this->sku = $filter['sku'];
    $this->product = $filter['product'];
    $this->model = $filter['model'];
    $this->trademark = $filter['trademark'];
    $this->category = $filter['category'];
    $this->pvp = $filter['pvp'];
    $this->perPage = $perPage;

    // BUSCAR EN CATEGORIAS / SUBCATEGORIAS
    $search_cat = "";
    $arrCat = explode("-", $this->category);
    if ($arrCat[0] == 'cat') {
      $search_cat = "c.id LIKE '%{$arrCat[1]}%' AND";
    } else if ($arrCat[0] == 'subcat') {
      $search_cat = "sc.id LIKE '%{$arrCat[1]}%' AND";
    }

    $info = "SELECT COUNT(pv.id) as count
      FROM products_variant pv
      JOIN products p
      ON pv.product_id = p.id
      JOIN subcategories sc
      ON p.category_id = sc.id
      JOIN categories c
      ON sc.category_id = c.id
      JOIN products_establishments pe
      ON pv.id = pe.product_variant_id AND pe.establishment_id = $establishment_id
      WHERE pv.ean_code LIKE '%$this->ean_code%' AND 
      pv.sku LIKE '%$this->sku%' AND 
      (
        pe.pvp_1 LIKE '%$this->pvp%' OR
        pe.pvp_2 LIKE '%$this->pvp%' OR
        pe.pvp_3 LIKE '%$this->pvp%' OR
        pe.pvp_distributor LIKE '%$this->pvp%'
      ) AND
      p.name LIKE '%$this->product%' AND 
      p.model LIKE '%$this->model%' AND 
      $search_cat
      p.trademark LIKE '%$this->trademark%' 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT pv.id, pv.product_id, pv.ean_code, pv.sku, pv.color_id, pv.size, pv.fragance, pv.net_content, pv.shape, pv.package, pv.additional_info, pv.created_at,
    sc.name as category, 
    p.name, p.model, p.trademark,
    pe.stock, pe.min_stock, pe.max_stock, pe.cost, pe.pvp_1, pe.pvp_2, pe.pvp_3, pe.pvp_distributor,
    if(pe.pvp_3 > 0, pe.pvp_3, if(pe.pvp_2 > 0, pe.pvp_2, pe.pvp_1)) as pvp

      FROM products_variant pv
      JOIN products p
      ON pv.product_id = p.id
      JOIN subcategories sc
      ON p.category_id = sc.id
      JOIN categories c
      ON sc.category_id = c.id
      JOIN products_establishments pe
      ON pv.id = pe.product_variant_id AND pe.establishment_id = $establishment_id
      WHERE pv.ean_code LIKE '%$this->ean_code%' AND 
      pv.sku LIKE '%$this->sku%' AND 
      (
        pe.pvp_1 LIKE '%$this->pvp%' OR
        pe.pvp_2 LIKE '%$this->pvp%' OR
        pe.pvp_3 LIKE '%$this->pvp%' OR
        pe.pvp_distributor LIKE '%$this->pvp%'
      ) AND
      p.name LIKE '%$this->product%' AND 
      p.model LIKE '%$this->model%' AND 
      $search_cat
      p.trademark LIKE '%$this->trademark%' 
      ORDER BY pv.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table,
    ];
  }
}
