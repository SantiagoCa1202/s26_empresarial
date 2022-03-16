<?php
require_once('ProvidersModel.php');
require_once('EstablishmentsModel.php');
require_once('CategoriesModel.php');
require_once('SystemModel.php');

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
  public $offer;
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
    $this->System = new SystemModel;
  }

  public function selectProducts(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->variants = $filter['variants'];
    $this->sku = $filter['sku'];
    $this->product = $filter['product'];
    $this->model = $filter['model'];
    $this->trademark = $filter['trademark'];
    $this->provider = $filter['provider'];
    $this->category = $filter['category'];
    $this->pvp = $filter['pvp'];
    $this->perPage = $perPage;

    // BUSCAR EN CATEGORIAS / SUBCATEGORIAS
    $arrCat = explode("-", $this->category);
    if ($arrCat[0] == 'cat') {
      $search_cat = "c.id LIKE '%{$arrCat[1]}%' AND";
    } else if ($arrCat[0] == 'subcat') {
      $search_cat = "sc.id LIKE '%{$arrCat[1]}%' AND";
    } else {
      $search_cat = "";
    }

    $info = "SELECT COUNT(DISTINCT p.id) as count, 
      SUM(pv.stock) as total_stock, 
      SUM(pv.cost) as total_cost, 
      SUM(pv.pvp) as total_pvp, 
      SUM(pv.total_entries) as total_entries
      FROM products p
      LEFT JOIN (SELECT pv.id, pv.product_id, pv.ean_code, pv.sku, 
        SUM(pe.stock) as stock, 
        SUM(pv.cost * pe.stock) as cost, 
        SUM(pv.pvp_1 * pe.stock) as pvp, 
        SUM(pev.amount_entries) as total_entries
        FROM products_variant pv
        LEFT JOIN (SELECT product_variant_id, 
          SUM(stock) as stock
          FROM products_establishments
          GROUP BY product_variant_id
        ) pe
        ON pv.id = pe.product_variant_id
        LEFT JOIN (SELECT product_variant_id, 
          SUM(amount) as amount_entries
          FROM products_entries_variants
          GROUP BY product_variant_id
        ) pev
        ON pv.id = pev.product_variant_id
        WHERE pv.ean_code LIKE '%$this->variants%' AND
        pv.sku LIKE '%$this->sku%' AND
        (
          pv.pvp_1 LIKE '%$this->pvp%' OR
          pv.pvp_2 LIKE '%$this->pvp%' OR
          pv.pvp_3 LIKE '%$this->pvp%' OR
          pv.pvp_distributor LIKE '%$this->pvp%'
        )
        GROUP BY pv.product_id
      ) pv
      ON p.id = pv.product_id
      LEFT JOIN (SELECT product_id, provider_id
        FROM products_providers
        WHERE provider_id LIKE '%$this->provider%'
        GROUP BY product_id
      ) pprov
      ON p.id = pprov.product_id
      JOIN subcategories sc
      ON p.category_id = sc.id
      JOIN categories c
      ON sc.category_id = c.id

      WHERE p.name LIKE '%$this->product%' AND
      p.model LIKE '%$this->model%' AND
      p.trademark LIKE '%$this->trademark%' AND
      $search_cat
      pv.ean_code LIKE '%$this->variants%' AND
      pv.sku LIKE '%$this->sku%' AND
      pprov.provider_id LIKE '%$this->provider%'
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT DISTINCT p.id, p.name, p.model, p.trademark, sc.name as category, pv.stock, pv.min_stock
      FROM products p
      LEFT JOIN (SELECT pv.product_id, pv.ean_code, pv.sku, SUM(pe.stock) as stock, SUM(pv.min_stock) as min_stock
        FROM products_variant pv
        JOIN products_establishments pe
        ON pv.id = pe.product_variant_id
        WHERE pv.ean_code LIKE '%$this->variants%' AND
        pv.sku LIKE '%$this->sku%' AND
        (
          pv.pvp_1 LIKE '%$this->pvp%' OR
          pv.pvp_2 LIKE '%$this->pvp%' OR
          pv.pvp_3 LIKE '%$this->pvp%' OR
          pv.pvp_distributor LIKE '%$this->pvp%'
        )
        GROUP BY pv.product_id
      ) pv
      ON p.id = pv.product_id
      LEFT JOIN (SELECT product_id, provider_id
        FROM products_providers
        WHERE provider_id LIKE '%$this->provider%'
        GROUP BY product_id
      ) pprov
      ON p.id = pprov.product_id
      JOIN subcategories sc
      ON p.category_id = sc.id
      JOIN categories c
      ON sc.category_id = c.id


      WHERE p.name LIKE '%$this->product%' AND
      p.model LIKE '%$this->model%' AND
      p.trademark LIKE '%$this->trademark%' AND
      $search_cat
      pv.ean_code LIKE '%$this->variants%' AND
      pv.sku LIKE '%$this->sku%' AND
      pprov.provider_id LIKE '%$this->provider%'
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

    $request['category'] = $this->Category->selectSubCategory($request['category_id']);
    $request['providers'] = $this->selectProviders($request['id']);
    $request['variants'] = $this->selectVariants($request['id']);
    $request['series'] = $this->selectVariants($request['id']);

    $request['access_cost'] = $_SESSION['userData']['cost_access'];
    $request['access_providers'] = $_SESSION['permits'][36]['r'];

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

  public function searchSaleProduct($code)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $establishment_id = $_SESSION['userData']['establishment_id'];


    $this->code = $code;

    $rows = "SELECT
      pv.id,pv.product_id,pv.ean_code,pv.sku,pv.min_stock,pv.max_stock,pv.cost,pv.pvp_1,pv.pvp_2,pv.pvp_3,pv.pvp_distributor,pv.color_id,pv.size,pv.fragance,pv.net_content,pv.shape,pv.package,pv.additional_info,pv.created_at,
      p.name, p.trademark, p.model, p.iva, p.serial,
      pe.stock,
      if(pv.pvp_3 > 0, pv.pvp_3, if(pv.pvp_2 > 0, pv.pvp_2, pv.pvp_1)) as pvp
      FROM products_variant pv
      JOIN products p
      ON pv.product_id = p.id
      LEFT JOIN(
        SELECT stock, product_variant_id, status
        FROM products_establishments 
        WHERE establishment_id = $establishment_id AND status = 1
        GROUP BY product_variant_id
      )pe
      ON pv.id = pe.product_variant_id
      WHERE (pv.ean_code LIKE '$this->code' OR pv.sku LIKE '$this->code' OR p.model LIKE '$this->code') AND 
        pe.status = 1
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return $items;
  }

  public function selectVariants(int $product_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $establishment_id = $_SESSION['userData']['establishment_id'];
    $this->product_id = $product_id;

    $info = "SELECT COUNT(pv.id) as count, SUM(pe.stock) as total_stock, SUM(pv.min_stock) as total_min_stock
      FROM products_variant pv
      JOIN products_establishments pe
      ON pv.id = pe.product_variant_id
      WHERE pv.product_id = $this->product_id 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT *, pv.id as id, pe.status as status, pe.stock
      FROM products_variant pv
      LEFT JOIN ( SELECT *
        FROM products_variant_dimensions pvd
      ) pvd
      ON pv.id = pvd.product_variant_id 
      LEFT JOIN ( SELECT pe.product_variant_id, pe.status, pe.stock
        FROM products_establishments pe
        WHERE pe.establishment_id = $establishment_id
      ) pe
      ON pv.id = pe.product_variant_id
      WHERE pv.product_id = $this->product_id

      ORDER BY pv.id DESC
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['cost'] = $_SESSION['userData']['cost_access'] == 1 ?  $items[$i]['cost'] : 0;
      $items[$i]['color'] = $this->System->selectColor($items[$i]['color_id']);
      $items[$i]['establishment_stock'] = $this->selectStockEstablishments($items[$i]['id']);
      $items[$i]['photos'] = $this->selectPhotos($items[$i]['id']);
    }

    return [
      'access_cost' => $_SESSION['userData']['cost_access'],
      'items' => $items,
      'info' => $info_table,
    ];
  }

  public function selectVariant(int $id, int $establishment_id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT *, pv.id as id, p.id as product_id, SUM(pe.stock) as stock
    FROM products_variant pv
    JOIN products p
    ON pv.product_id = p.id
    JOIN products_establishments pe
    ON pv.id = pe.product_variant_id
     WHERE pv.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['color'] = $this->System->selectColor($request['color_id']);

    return $request;
  }

  public function selectProviders(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $info = "SELECT COUNT(pp.id) as count
      FROM products_providers pp 
      JOIN providers p
      ON pp.provider_id = p.id
      WHERE pp.product_id = $this->id
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *, p.id as id
      FROM products_providers pp 
      JOIN providers p
      ON pp.provider_id = p.id
      WHERE pp.product_id = $this->id
    ";

    $items = $this->select_all_company($rows, $this->db_company);
    return [
      'items' => $items,
      'info' => $info_table,
    ];
  }

  public function selectPhotos(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $info = "SELECT COUNT(pp.id) as count
      FROM products_photos pp 
      JOIN photos p
      ON pp.photo_id = p.id
      WHERE pp.product_variant_id = $this->id
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *, pp.id as id
      FROM products_photos pp 
      JOIN photos p
      ON pp.photo_id = p.id
      WHERE pp.product_variant_id = $this->id
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['src'] = asset('media/uploads/photos/') . $items[$i]['src'];
    }

    return [
      'items' => $items,
      'info' => $info_table,
    ];
  }

  public function selectStockEstablishments(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $id;

    $info = "SELECT COUNT(pe.id) as count, SUM(pe.stock) as stock
      FROM products_establishments pe
      JOIN s26_empresarial.establishments es
      ON pe.establishment_id = es.id
      JOIN s26_empresarial.cities c
      ON es.city_id = c.id
      WHERE pe.product_variant_id = $this->product_variant_id
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT pe.id, pe.stock, pe.establishment_id, es.tradename, c.name as city, es.address, pe.product_variant_id, es.n_establishment, pe.status
      FROM products_establishments pe
      JOIN s26_empresarial.establishments es
      ON pe.establishment_id = es.id
      JOIN s26_empresarial.cities c
      ON es.city_id = c.id
      WHERE pe.product_variant_id = $this->product_variant_id
    ";

    $items = $this->select_all_company($rows, $this->db_company);
    return [
      'items' => $items,
      'info' => $info_table,
    ];
  }

  public function selectReportProduct(int $id, string $type, $establishment_id, $year)
  {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->type = $type;
    $this->establishment_id = $establishment_id;
    $this->year = $year;

    $query = ($this->type == 'prod') ? 'pv.product_id' : 'pv.id';

    $info = "SELECT IFNULL(SUM(pee.amount),0) as total_entries, 
      SUM(IFNULL(sp.amount, 0)+IFNULL(scp.amount, 0)) as total_sales,
      SUM(IFNULL(spr.amount, 0)+IFNULL(scpr.amount, 0)) as total_returns,
      SUM(IFNULL(pd.amount, 0)) as total_damaged,
      SUM(IFNULL(sa.amount, 0)) as total_settings
      FROM products_variant pv
      LEFT JOIN (
        SELECT SUM(pee.amount) as amount, pee.product_variant_id 
        FROM products_entries_establishments pee
        JOIN products_variant pv
        ON pee.product_variant_id = pv.id
        WHERE pee.to_establishment_id LIKE '%$this->establishment_id%' AND 
        $query = $this->id AND YEAR(pee.created_at) LIKE '%$this->year%'
      )pee
      ON pv.id = pee.product_variant_id
      LEFT JOIN (
        SELECT SUM(sp.amount) as amount, sp.variant_id 
        FROM sales_products sp
        JOIN sales s
        ON sp.sale_id = s.id
        JOIN products_variant pv
        ON sp.variant_id = pv.id
        JOIN boxes b
        ON s.box_id = b.id
        WHERE b.establishment_id LIKE '%$this->establishment_id%' AND
        $query = $this->id AND YEAR(s.created_at) LIKE '%$this->year%'
      )sp
      ON pv.id = sp.variant_id
      LEFT JOIN (
        SELECT SUM(scp.amount) as amount, scp.variant_id 
        FROM sales_credits_products scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        JOIN products_variant pv
        ON scp.variant_id = pv.id
        JOIN boxes b
        ON sc.box_id = b.id
        WHERE b.establishment_id LIKE '%$this->establishment_id%' AND
        $query = $this->id AND YEAR(sc.created_at) LIKE '%$this->year%'
      )scp
      ON pv.id = scp.variant_id
      LEFT JOIN (
        SELECT SUM(sp.amount) as amount, sp.variant_id 
        FROM sales_products sp
        JOIN sales s
        ON sp.sale_id = s.id
        JOIN products_variant pv
        ON sp.variant_id = pv.id
        JOIN boxes b
        ON s.box_id = b.id
        WHERE b.establishment_id LIKE '%$this->establishment_id%' AND s.status != 1 AND $query = $this->id AND YEAR(s.created_at) LIKE '%$this->year%'
      )spr
      ON pv.id = spr.variant_id
      LEFT JOIN (
        SELECT SUM(scp.amount) as amount, scp.variant_id 
        FROM sales_credits_products scp
        JOIN sales_credits sc
        ON scp.sale_id = sc.id
        JOIN products_variant pv
        ON scp.variant_id = pv.id
        JOIN boxes b
        ON sc.box_id = b.id
        WHERE b.establishment_id LIKE '%$this->establishment_id%' AND sc.status != 1 AND $query = $this->id AND YEAR(sc.created_at) LIKE '%$this->year%'
      )scpr
      ON pv.id = scpr.variant_id
      LEFT JOIN (
        SELECT SUM(pd.amount) as amount, pd.product_variant_id 
        FROM products_damageds pd
        JOIN products_variant pv
        ON pd.product_variant_id = pv.id
        WHERE pd.establishment_id LIKE '%$this->establishment_id%' AND pd.status = 1 AND $query = $this->id AND YEAR(pd.created_at) LIKE '%$this->year%'
      )pd
      ON pv.id = pd.product_variant_id
      LEFT JOIN (
        SELECT SUM(sa.amount) as amount, sa.product_variant_id 
        FROM stock_adjustment sa
        JOIN products_variant pv
        ON sa.product_variant_id = pv.id
        WHERE sa.establishment_id LIKE '%$this->establishment_id%' AND $query = $this->id AND YEAR(sa.created_at) LIKE '%$this->year%'
      )sa
      ON pv.id = sa.product_variant_id
      WHERE $query = $this->id
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    /// REPORTE POR AÑO 
    // TABLA FICTICIA DE MESES
    $t_months = "SELECT 1 as id_month UNION
      SELECT 2 as id_month UNION
      SELECT 3 as id_month UNION
      SELECT 4 as id_month UNION
      SELECT 5 as id_month UNION
      SELECT 6 as id_month UNION
      SELECT 7 as id_month UNION
      SELECT 8 as id_month UNION
      SELECT 9 as id_month UNION
      SELECT 10 as id_month UNION
      SELECT 11 as id_month UNION
      SELECT 12 as id_month
    ";

    // ENTRADAS 
    $entries = "SELECT IFNULL(pee.amount, 0) 
      FROM ( $t_months ) t_months
      LEFT JOIN(SELECT month(pee.created_at) as id_month, sum(pee.amount) as amount 
        FROM products_entries_establishments AS pee
          JOIN products_variant pv
          ON pee.product_variant_id = pv.id
          WHERE year(pee.created_at) LIKE '%$this->year%' AND 
            pee.to_establishment_id LIKE '%$this->establishment_id%' AND 
            $query = $this->id
          GROUP BY month(pee.created_at)
      )pee
      ON pee.id_month = t_months.id_month
    ";

    $info_table_per_month['entries'] = $this->select_all_company($entries, $this->db_company, 2);


    // SALIDAS 
    $sales = "SELECT IFNULL(s.amount, 0) 
      FROM ( $t_months ) t_months
      LEFT JOIN (SELECT SUM(amount) as amount, month(created_at) as id_month
        FROM (
          SELECT SUM(sp.amount) as amount, s.created_at, b.establishment_id
            FROM sales_products sp
            JOIN sales s
            ON sp.sale_id = s.id
            JOIN products_variant pv
            ON sp.variant_id = pv.id
            JOIN boxes b
            ON s.box_id = b.id
            WHERE year(s.created_at) LIKE '%$this->year%' AND b.establishment_id LIKE '%$this->establishment_id%' AND $query = $this->id
            GROUP BY month(s.created_at)
          UNION ALL
          SELECT SUM(sp.amount) as amount, s.created_at, b.establishment_id
            FROM sales_credits_products sp
            JOIN sales_credits s
            ON sp.sale_id = s.id
            JOIN products_variant pv
            ON sp.variant_id = pv.id
            JOIN boxes b
            ON s.box_id = b.id
            WHERE year(s.created_at) LIKE '%$this->year%' AND b.establishment_id LIKE '%$this->establishment_id%' AND $query = $this->id
            GROUP BY month(s.created_at)
        )x
        WHERE year(created_at) LIKE '%$this->year%' AND 
          establishment_id LIKE '%$this->establishment_id%'
        GROUP BY month(created_at)
      )s
      ON s.id_month = t_months.id_month
    ";

    $info_table_per_month['sales'] = $this->select_all_company($sales, $this->db_company, 2);

    // DEVOLUCIONES 
    $returns = "SELECT IFNULL(s.amount, 0) 
      FROM ( $t_months ) t_months
      LEFT JOIN (SELECT SUM(amount) as amount, month(created_at) as id_month
        FROM (
          SELECT SUM(sp.amount) as amount, s.created_at, b.establishment_id
            FROM sales_products sp
            JOIN sales s
            ON sp.sale_id = s.id
            JOIN products_variant pv
            ON sp.variant_id = pv.id
            JOIN boxes b
            ON s.box_id = b.id
            WHERE year(s.created_at) LIKE '%$this->year%' AND b.establishment_id LIKE '%$this->establishment_id%' AND $query = $this->id AND s.status != 1
            GROUP BY month(s.created_at)
          UNION ALL
          SELECT SUM(sp.amount) as amount, s.created_at, b.establishment_id
            FROM sales_credits_products sp
            JOIN sales_credits s
            ON sp.sale_id = s.id
            JOIN products_variant pv
            ON sp.variant_id = pv.id
            JOIN boxes b
            ON s.box_id = b.id
            WHERE year(s.created_at) LIKE '%$this->year%' AND b.establishment_id LIKE '%$this->establishment_id%' AND $query = $this->id AND s.status != 1
            GROUP BY month(s.created_at)
        )x
        WHERE year(created_at) LIKE '%$this->year%' AND 
          establishment_id LIKE '%$this->establishment_id%'
        GROUP BY month(created_at)
      )s
      ON s.id_month = t_months.id_month
    ";

    $info_table_per_month['returns'] = $this->select_all_company($returns, $this->db_company, 2);

    // AVERIAS 
    $damageds = "SELECT IFNULL(pd.amount, 0) 
      FROM ( $t_months ) t_months
      LEFT JOIN(SELECT month(pd.created_at) as id_month, sum(pd.amount) as amount 
        FROM products_damageds AS pd
          JOIN products_variant pv
          ON pd.product_variant_id = pv.id
          WHERE year(pd.created_at) LIKE '%$this->year%' AND 
            pd.establishment_id LIKE '%$this->establishment_id%' AND 
            $query = $this->id
          GROUP BY month(pd.created_at)
      )pd
      ON pd.id_month = t_months.id_month
    ";

    $info_table_per_month['damageds'] = $this->select_all_company($damageds, $this->db_company, 2);

    // AJUSTE DE STOCK 
    $settings = "SELECT IFNULL(sa.amount, 0) 
      FROM ( $t_months ) t_months
      LEFT JOIN(SELECT month(sa.created_at) as id_month, sum(sa.amount) as amount 
        FROM stock_adjustment AS sa
          JOIN products_variant pv
          ON sa.product_variant_id = pv.id
          WHERE year(sa.created_at) LIKE '%$this->year%' AND 
            sa.establishment_id LIKE '%$this->establishment_id%' AND 
            $query = $this->id
          GROUP BY month(sa.created_at)
      )sa
      ON sa.id_month = t_months.id_month
    ";

    $info_table_per_month['settings'] = $this->select_all_company($settings, $this->db_company, 2);

    return [
      'info' => $info_table,
      'infoPerMonth' => $info_table_per_month,
    ];
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
    float $iva,
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
    $this->iva = $iva;

    $query_insert = "INSERT INTO products (
        name, description, trademark, model, type_product, type, remanufactured, category_id, discontinued, serial, iva) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
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
      $this->iva,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function updateProduct(
    int $id,
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
    float $iva,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
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
    $this->iva = $iva;

    $sql = "UPDATE products SET
        name = ?, description = ?, trademark = ?, model = ?, type_product = ?, type = ?, remanufactured = ?, category_id = ?, discontinued = ?, serial = ?, iva = ? WHERE id = $this->id";
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
      $this->iva,
    );
    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function updatePrices(
    int $variant_id,
    float $pvp_1,
    float $pvp_2,
    float $pvp_3,
    float $pvp_distributor,
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->variant_id = $variant_id;
    $this->pvp_1 = $pvp_1;
    $this->pvp_2 = $pvp_2;
    $this->pvp_3 = $pvp_3;
    $this->pvp_distributor = $pvp_distributor;



    $sql = "UPDATE products_variant SET pvp_1 = ?, pvp_2 = ?, pvp_3 = ?, pvp_distributor = ? WHERE id = $this->variant_id";
    $arrData = array(
      $this->pvp_1,
      $this->pvp_2,
      $this->pvp_3,
      $this->pvp_distributor,
    );
    $request = $this->update_company($sql, $arrData, $this->db_company);
    return $request;
  }

  public function insertVariantEstablishment(
    int $product_variant_id,
    int $establishment_id,
    int $amount
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->establishment_id = $establishment_id;
    $this->amount = $amount;

    $query_insert = "INSERT INTO products_establishments (product_variant_id, establishment_id, stock) VALUES (?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->establishment_id,
      $this->amount
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
    int $color_id,
    string $size,
    string $fragance,
    string $net_content,
    string $shape,
    string $package,
    string $additional_info,

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
    $this->color_id = $color_id;
    $this->size = $size;
    $this->fragance = $fragance;
    $this->net_content = $net_content;
    $this->shape = $shape;
    $this->package = $package;
    $this->additional_info = $additional_info;

    $query_insert = "INSERT INTO products_variant (
      product_id,
      ean_code,
      sku,
      min_stock,
      max_stock,
      cost,
      pvp_1,
      pvp_2,
      pvp_3,
      pvp_distributor,
      color_id,
      size,
      fragance,
      net_content,
      shape,
      package,
      additional_info
          ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->code,
      $this->sku,
      $this->min_stock,
      $this->max_stock,
      $this->cost,
      $this->pvp_1,
      $this->pvp_2,
      $this->pvp_3,
      $this->pvp_distributor,
      $this->color_id,
      $this->size,
      $this->fragance,
      $this->net_content,
      $this->shape,
      $this->package,
      $this->additional_info,
    );

    $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function insertEntryVariant(
    int $product_variant_id,
    int $amount,
    float $cost,
    int $document_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_variant_id = $product_variant_id;
    $this->amount = $amount;
    $this->cost = $cost;
    $this->document_id = $document_id;

    $query_insert = "INSERT INTO products_entries_variants (product_variant_id,amount,cost, document_id) VALUES (?,?,?,?)";
    $arrData = array(
      $this->product_variant_id,
      $this->amount,
      $this->cost,
      $this->document_id,
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
    int $document_id,
    string $serie,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->product_id = $product_id;
    $this->document_id = $document_id;
    $this->serie = $serie;

    $query_insert = "INSERT INTO products_series (product_id, document_id, serie) VALUES (?,?,?)";
    $arrData = array(
      $this->product_id,
      $this->document_id,
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
    int $variant_id,
    int $amount,
    float $cost
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->variant_id = $variant_id;
    $this->amount = $amount;
    $this->cost = $cost;

    $query_insert = "CALL update_cost_variant(?,?,?)";
    $arrData = array(
      $this->amount,
      $this->cost,
      $this->variant_id,
    );
    $request = $this->update_company($query_insert, $arrData, $this->db_company);
    return $request;
  }

  public function updateStock(
    int $variant_id,
    int $amount,
    int $establishment_id
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->variant_id = $variant_id;
    $this->amount = $amount;
    $this->establishment_id = $establishment_id;

    //OBTENER STOCK ACTUAL EN MATRIZ
    $sql = "SELECT stock FROM products_establishments WHERE product_variant_id = $this->variant_id";
    $request = $this->select_company($sql, $this->db_company);
    $stock = $request['stock'];
    $this->newStock = $this->amount + $stock;

    //ACTUALIZAR STOCK
    $sql = "UPDATE products_establishments SET stock = ? 
    WHERE product_variant_id = $this->variant_id AND establishment_id = $this->establishment_id";
    $arrData = array(
      $this->newStock,
    );
    $request = $this->update_company($sql, $arrData, $this->db_company);
    return $request;
  }

  public function insertStockAdjustment(
    int $variant_id,
    int $amount,
    int $establishment_id
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->variant_id = $variant_id;
    $this->amount = $amount;
    $this->establishment_id = $establishment_id;

    $sql = "INSERT INTO stock_adjustment (product_variant_id, amount, establishment_id) VALUES (?,?,?)";
    $arrData = array(
      $this->variant_id,
      $this->amount,
      $this->establishment_id,
    );
    $request = $this->insert_company($sql, $arrData, $this->db_company);
    return $request;
  }


  public function updateStatus(
    int $id,
    int $status
  ) {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->status = $status;

    $sql = "UPDATE products_establishments SET status = ? WHERE id = $this->id";
    $arrData = array(
      $this->status
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);
    return $request;
  }
}
