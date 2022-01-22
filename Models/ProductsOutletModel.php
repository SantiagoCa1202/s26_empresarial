<?php

class ProductsOutletModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectProductsOutlet(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->code = $filter['code'];
    $this->name = $filter['name'];
    $this->sale_id = $filter['sale_id'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND s.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      pv.ean_code LIKE '%$this->code%' AND
      s.id LIKE '%$this->sale_id%' AND
      (
        p.name LIKE '%$this->name%' OR
        p.model LIKE '%$this->name%' OR
        p.trademark LIKE '%$this->name%' OR 
        pv.sku LIKE '%$this->name%'
      )
      $date_range
    ";

    $info = "SELECT COUNT(*) as count, SUM(cost*amount) as total_cost, 
      SUM(amount) as total_sales, SUM(pvp*amount) as total_pvp, SUM(discount*amount) as total_discount, SUM(pvp*amount-discount) as total
      FROM (
        SELECT sp.cost, sp.amount, sp.pvp, sp.discount
          FROM sales_products sp
          JOIN products_variant pv
          ON sp.variant_id = pv.id
          JOIN products p
          ON pv.product_id = p.id
          JOIN sales s
          ON sp.sale_id = s.id
          WHERE $where AND s.status = 1
        UNION ALL
        SELECT sp.cost, sp.amount, sp.pvp, sp.discount
          FROM sales_credits_products sp
          JOIN products_variant pv
          ON sp.variant_id = pv.id
          JOIN products p
          ON pv.product_id = p.id
          JOIN sales_credits s
          ON sp.sale_id = s.id
          WHERE $where AND s.status = 1
      )x
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT date, id, sale_id, ean_code, name, model, trademark, sku, cost, pvp, discount, amount, status, product_id, type
      FROM (
        SELECT DISTINCT s.date, sp.id, sp.sale_id, pv.ean_code, p.name, p.model, p.trademark, pv.sku, sp.cost, sp.pvp, sp.discount, sp.amount, s.status, pv.product_id, CONCAT('ven.') as type
          FROM sales_products sp
          JOIN products_variant pv
          ON sp.variant_id = pv.id
          JOIN products p
          ON pv.product_id = p.id
          JOIN sales s
          ON sp.sale_id = s.id
          WHERE $where  
        UNION ALL
        SELECT DISTINCT s.date, sp.id, sp.sale_id, pv.ean_code, p.name, p.model, p.trademark, pv.sku, sp.cost, sp.pvp, sp.discount, sp.amount, s.status, pv.product_id, CONCAT('cre.') as type
          FROM sales_credits_products sp
          JOIN products_variant pv
          ON sp.variant_id = pv.id
          JOIN products p
          ON pv.product_id = p.id
          JOIN sales_credits s
          ON sp.sale_id = s.id
          WHERE $where 
      )x
      ORDER BY date DESC, id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);


    return [
      'items' => $items,
      'dates' => $this->select_dates_company('date', 'sales', $this->db_company),
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
