<?php

require_once('CustomersModel.php');
require_once('SystemModel.php');
class SalesCreditsModel extends Mysql
{

  public function __construct()
  {
    parent::__construct();

    $this->Customer = new CustomersModel;
    $this->Document = new SystemModel;
  }

  public function selectSalesCredits(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $filter['sale_id'];
    $this->n_document = $filter['n_document'];
    $this->customer = $filter['customer'];
    $this->establishment_id = $filter['establishment_id'];
    $this->type_doc_id = $filter['type_doc_id'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND s.date BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      s.id LIKE '%$this->sale_id%' AND
      s.n_document LIKE '%$this->n_document%' AND
      c.document LIKE '%$this->customer%' AND
      s.status LIKE '%$this->status%' AND
      s.establishment_id LIKE '%$this->establishment_id%' AND
      s.document_id LIKE '%$this->type_doc_id%' AND 
      s.status > 0
      $date_range
    ";

    $info_sales = "SELECT COUNT(s.id) as count
      FROM sales_credits s 
      JOIN customers c
      ON s.customer_id = c.id
      WHERE $where

    ";

    $info_outlets = "SELECT SUM(sp.amount) as total_products, SUM(sp.discount) as total_discount, SUM(sp.amount*sp.pvp-sp.discount) as total_sale, SUM(sp.amount*sp.pvp) as total_pvp
      FROM sales_credits_products sp
      JOIN sales_credits s
      ON sp.sale_id = s.id
      JOIN customers c
      ON s.customer_id = c.id
      WHERE $where
    ";
    $info_table = $this->info_table_company($info_sales, $this->db_company);
    $info_table +=  $this->info_table_company($info_outlets, $this->db_company);

    $rows = "SELECT s.id, s.date, d.alias as alias_document, d.name as name_document, s.document_id, s.n_document, c.document as customer, s.customer_id, sp.total_products, sp.total_discount, sp.total_sale, sp.total_pvp, s.status
      FROM sales_credits s 
      LEFT JOIN (
        SELECT sale_id, SUM(amount) as total_products, SUM(discount) as total_discount, SUM(amount*pvp-discount) as total_sale, SUM(amount*pvp) as total_pvp
        FROM sales_credits_products
        GROUP BY sale_id
      )sp 
      ON s.id = sp.sale_id
      JOIN s26_empresarial.documents d
      ON s.document_id = d.id
      JOIN customers c
      ON s.customer_id = c.id
      WHERE $where
      ORDER BY date DESC, id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $item = $items[$i];
      $payments = "SELECT id, status
      FROM sales_credits_payments
      WHERE sale_id = $item[id] AND (status = 2 OR (payment_method_id > 1 AND payment_method_id <= 7 AND amount > 0 AND (bank_account_id IS NULL OR bank_account_id = 0)) )
      ";

      $payments_status = $this->select_all_company($payments, $this->db_company);

      //COMPARAR TOTALES 
      $sql = "SELECT SUM(amount*pvp-discount) as total
        FROM sales_credits_products
        WHERE sale_id = $item[id]
      ";
      $products_totals =  $this->info_table_company($sql, $this->db_company);

      $sql = "SELECT SUM(amount) as total
        FROM sales_credits_payments
        WHERE sale_id = $item[id]
      ";

      $payments_totals =  $this->info_table_company($sql, $this->db_company);

      $items[$i]['balance'] = $products_totals['total'] - $payments_totals['total'];

      

      $items[$i]['status'] = $items[$i]['status'] == 2 ? $items[$i]['status'] : (COUNT($payments_status) > 0 || $products_totals !== $payments_totals ? 3 : 1);
    }

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('date', 'sales', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectSaleCredit(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT *, sp.total_products, sp.total_discount, sp.total_sale, sp.total_pvp
      FROM sales_credits s 
      LEFT JOIN (
        SELECT sale_id, SUM(amount) as total_products, SUM(discount) as total_discount, SUM(amount*pvp-discount) as total_sale, SUM(amount*pvp) as total_pvp
        FROM sales_credits_products
        GROUP BY sale_id
      )sp 
      ON s.id = sp.sale_id
      WHERE s.id = $this->id
    ";
    $request = $this->select_company($sql, $this->db_company);

    $request['customer'] = $this->Customer->selectCustomer($request['customer_id']);

    $request['customer_billing'] = $this->Customer->selectCustomer($request['customer_billing_id']);

    $request['document'] = $this->Document->selectDocument($request['document_id']);

    $products = "SELECT *, sp.id as id, CONCAT(p.name, ' - ', p.model, ' - ', p.trademark, ' - ', pv.sku ) as product
    FROM sales_credits_products sp
    JOIN products_variant pv
    ON sp.variant_id = pv.id
    JOIN products p
    ON pv.product_id = p.id
    WHERE sp.sale_id = $request[id]
    
    ";
    $request['products'] = $this->select_all_company($products, $this->db_company);

    $products_series = "SELECT *, sps.id as id
    FROM sales_credits_products_series sps
    JOIN products_series ps
    ON sps.serie_id = ps.id
    WHERE sps.sale_id = $request[id]
    
    ";
    $request['products_series'] = $this->select_all_company($products_series, $this->db_company);

    $payments = "SELECT *, sp.id as id, pm.name as payment_method, sp.status as status, sp.bank_account_id, CONCAT(ba.bank_entity, ' - ', ba.n_account ) as bank_account, be.bank_entity, b.name as box_name
    FROM sales_credits_payments sp
    JOIN payment_methods pm
    ON sp.payment_method_id = pm.id
    JOIN boxes b
    ON sp.box_id = b.id
    LEFT JOIN ( SELECT ba.id, be.bank_entity, ba.n_account
      FROM bank_accounts ba
      JOIN s26_empresarial.bank_entities be
      ON ba.bank_entity_id = be.id
    ) ba
    ON sp.bank_account_id = ba.id
    LEFT JOIN (SELECT id, bank_entity
      FROM s26_empresarial.bank_entities
    )be
    ON sp.bank_entity_id = be.id
    WHERE sp.sale_id = $request[id] AND sp.status != 0
    ORDER BY payment_method_id ASC
    
    ";
    $request['payments'] = $this->select_all_company($payments, $this->db_company);

    $payments = "SELECT id, status
      FROM sales_credits_payments
      WHERE sale_id = $request[id] AND (status = 2 OR (payment_method_id > 1 AND payment_method_id <= 7 AND amount > 0 AND (bank_account_id IS NULL OR bank_account_id = 0)) )
    ";

    $payments_status = $this->select_all_company($payments, $this->db_company);

    $request['payments_status'] = COUNT($payments_status) > 0 ? 0 : 1;

    return $request;
  }

  //INSERTAR VENTA
  public function insertSaleCredit(
    string $date,
    string $customer_id,
    string $n_document,
    string $note,
    int $establishment_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->customer_id = $customer_id;
    $this->n_document = $n_document;
    $this->note = $note;
    $this->establishment_id = $establishment_id;


    $query_insert = "INSERT INTO sales_credits (date, customer_id, customer_billing_id, note, establishment_id) VALUES (?,?,?,?,?)";
    $arrData = array(
      $this->date,
      $this->customer_id,
      $this->customer_id,
      $this->note,
      $this->establishment_id
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  // INSERTAR PRODUCTO EN SALIDAS (CREDITOS)
  public function insertSaleCreditProduct(
    int $sale_id,
    int $variant_id,
    int $amount,
    float $cost,
    float $pvp,
    float $discount,
    float $iva_,
    float $bi_0,
    float $bi_,
    float $iva,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;
    $this->variant_id = $variant_id;
    $this->amount = $amount;
    $this->cost = $cost;
    $this->pvp = $pvp;
    $this->discount = $discount;
    $this->iva_ = $iva_;
    $this->bi_0 = $bi_0;
    $this->bi_ = $bi_;
    $this->iva = $iva;


    $query_insert = "INSERT INTO sales_credits_products (sale_id, variant_id, amount, cost, pvp, discount, iva_, bi_0, bi_, iva) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->sale_id,
      $this->variant_id,
      $this->amount,
      $this->cost,
      $this->pvp,
      $this->discount,
      $this->iva_,
      $this->bi_0,
      $this->bi_,
      $this->iva,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  //INSERTAR PAGOS 

  public function insertSaleCreditPayment(
    int $sale_id,
    $date,
    int $payment_method_id,
    float $amount,
    $bank_account_id,
    $transaction,
    $share,
    $bank_entity_id,
    $n_check,
    $date_check,
    int $status,
    int $box_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;
    $this->date = $date;
    $this->payment_method_id = $payment_method_id;
    $this->amount = $amount;
    $this->bank_account_id = $bank_account_id;
    $this->transaction = $transaction;
    $this->share = $share;
    $this->bank_entity_id = $bank_entity_id;
    $this->n_check = $n_check;
    $this->date_check = $date_check;
    $this->status = $status;
    $this->box_id = $box_id;


    $query_insert = "INSERT INTO sales_credits_payments (sale_id, date, payment_method_id, amount, bank_account_id, transaction, share, bank_entity_id, n_check, date_check, status, box_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->sale_id,
      $this->date,
      $this->payment_method_id,
      $this->amount,
      $this->bank_account_id,
      $this->transaction,
      $this->share,
      $this->bank_entity_id,
      $this->n_check,
      $this->date_check,
      $this->status,
      $this->box_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  // EDITAR VENTA 
  public function updateSale($id, $note, $n_document, $billing_date = null, $document_id = null, $customer_billing_id = null)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->note = $note;
    $this->n_document = $n_document;
    $this->billing_date = $billing_date;
    $this->document_id = $document_id;
    $this->customer_billing_id = $customer_billing_id;

    $process = "";
    if ($this->document_id > 1) {
      $process = "
        billing_date = ?,
        document_id = ?,
        customer_billing_id = ?,
      ";
      $arrData = array(
        $this->billing_date,
        $this->document_id,
        $this->customer_billing_id,
        $this->note,
        $this->n_document
      );
    } else {
      $arrData = array(
        $this->note,
        $this->n_document
      );
    }

    $sql = "SELECT * FROM sales_credits WHERE id != '$this->id' AND n_document != '' AND n_document = '$this->n_document' AND status = 1";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE sales_credits SET $process note = ?, n_document = ? WHERE id = $this->id";

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  // EDITAR PAGO 
  public function updateSaleCreditPayment(
    int $id,
    int $payment_method_id,
    float $amount,
    $bank_account_id,
    $transaction,
    $share,
    $bank_entity_id,
    $n_check,
    $date_check,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->payment_method_id = $payment_method_id;
    $this->amount = $amount;
    $this->bank_account_id = $bank_account_id;
    $this->transaction = $transaction;
    $this->share = $share;
    $this->bank_entity_id = $bank_entity_id;
    $this->n_check = $n_check;
    $this->date_check = $date_check;
    $this->status = $status;


    $sql = "UPDATE sales_credits_payments SET payment_method_id = ?,
    amount = ?,
    bank_account_id = ?,
    transaction = ?,
    share = ?,
    bank_entity_id = ?,
    n_check = ?,
    date_check = ?,
    status = ? WHERE id = $this->id";
    $arrData = array(
      $this->payment_method_id,
      $this->amount,
      $this->bank_account_id,
      $this->transaction,
      $this->share,
      $this->bank_entity_id,
      $this->n_check,
      $this->date_check,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  // INSERTAR SERIES 
  public function insertSeries(
    int $sale_id,
    int $product_id,
    int $serie_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;
    $this->product_id = $product_id;
    $this->serie_id = $serie_id;


    $query_insert = "INSERT INTO sales_credits_products_series (sale_id, product_id,
    serie_id) VALUES (?,?,?)";
    $arrData = array(
      $this->sale_id,
      $this->product_id,
      $this->serie_id,
    );

    // DESHABILITAR SERIE 
    $sql = "UPDATE products_series SET status = 2 WHERE id = $this->serie_id";
    $this->delete_company($sql, $this->db_company);

    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  //ANULAR VENTA
  public function cancelSaleCredit(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "SELECT * FROM sales_credits WHERE id = $this->id AND status = 2";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $sql = "UPDATE sales_credits SET status = 2 WHERE id = $this->id";
      $request = $this->delete_company($sql, $this->db_company);
    } else {
      $request = -8;
    }

    return $request;
  }
}
