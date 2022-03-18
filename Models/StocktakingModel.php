<?php
class StocktakingModel extends Mysql
{
  public $id;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectProducts(int $perPage, array $filter)
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
      FROM stocktaking s
      LEFT JOIN (SELECT pe.product_variant_id, pe.establishment_id, 
        pe.stock, pv.ean_code, pv.sku, pe.pvp_1, pe.pvp_2, pe.pvp_3,
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

    $rows = "SELECT DISTINCT s.id, s.accountant, pe.ean_code, pe.name, pe.model, pe.trademark, pe.sku, pe.stock, if(pe.pvp_3 > 0, pe.pvp_3, if(pe.pvp_2 > 0, pe.pvp_2, pe.pvp_1)) as pvp, pe.product_id
      FROM stocktaking s
      LEFT JOIN (SELECT pe.product_variant_id, pe.establishment_id, 
        pe.stock, pv.ean_code, pv.sku, pe.pvp_1, pe.pvp_2, pe.pvp_3,
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

  public function selectProduct(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM stocktaking WHERE product_variant_id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertProduct(
    $product_variant_id,
    int $amount,
    $establishment_id
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->amount = $amount;
    $this->establishment_id = $establishment_id;

    $query_insert = "INSERT INTO stocktaking (product_variant_id, accountant, establishment_id) VALUES (?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->amount,
      $this->establishment_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function updateProduct(
    $id,
    int $new_accountant,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->new_accountant = $new_accountant;

    $sql = "UPDATE stocktaking SET accountant = ? WHERE id = $this->id";
    $arrData = array(
      $this->new_accountant,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function prepareInventory(int $establishment_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->establishment_id = $establishment_id;

    $sql = "DELETE FROM stocktaking WHERE establishment_id = $this->establishment_id";

    $request = $this->delete_company($sql, $this->db_company);

    if ($request > 0) {

      $sql = "INSERT INTO stocktaking (product_variant_id, establishment_id) SELECT product_variant_id, establishment_id FROM products_establishments WHERE establishment_id = $this->establishment_id";

      $request = $this->delete_company($sql, $this->db_company);
    }


    return $request;
  }

  public function resetInventory(int $establishment_id)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->establishment_id = $establishment_id;

    $sql = "DELETE FROM stocktaking WHERE establishment_id = $this->establishment_id";

    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function recalcStock(
    $establishment_id
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->establishment_id = $establishment_id;

    $sql = "DELETE FROM stocktaking WHERE establishment_id = $this->establishment_id";

    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
