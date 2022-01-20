<?php
class SalesModel extends Mysql
{

  public function __construct()
  {
    parent::__construct();
  }

  //INSERTAR VENTA
  public function insertSale(
    string $date,
    string $document_id,
    string $customer_id,
    string $n_document,
    string $note,
    int $establishment_id,
    int $box_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->date = $date;
    $this->document_id = $document_id;
    $this->customer_id = $customer_id;
    $this->n_document = $n_document;
    $this->note = $note;
    $this->establishment_id = $establishment_id;
    $this->box_id = $box_id;


    $query_insert = "INSERT INTO sales (date, document_id, customer_id, n_document, note, establishment_id, box_id) VALUES (?,?,?,?,?,?,?)";
    $arrData = array(
      $this->date,
      $this->document_id,
      $this->customer_id,
      $this->n_document,
      $this->note,
      $this->establishment_id,
      $this->box_id,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  // INSERTAR PRODUCTO EN SALIDAS (VENTAS)
  public function insertSaleProduct(
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
    string $table = "sales_products"
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
    $this->table = $table;


    $query_insert = "INSERT INTO $this->table (sale_id, variant_id, amount, cost, pvp, discount, iva_, bi_0, bi_, iva) VALUES (?,?,?,?,?,?,?,?,?,?)";
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
  public function insertSalePayment(
    int $sale_id,
    int $payment_method_id,
    float $amount,
    $bank_account_id,
    $transaction,
    $share,
    $bank_entity_id,
    $n_check,
    $date,
    int $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;
    $this->payment_method_id = $payment_method_id;
    $this->amount = $amount;
    $this->bank_account_id = $bank_account_id;
    $this->transaction = $transaction;
    $this->share = $share;
    $this->bank_entity_id = $bank_entity_id;
    $this->n_check = $n_check;
    $this->date = $date;
    $this->status = $status;


    $query_insert = "INSERT INTO sales_payments (sale_id, payment_method_id, amount, bank_account_id, transaction, share, bank_entity_id, n_check, date, status) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $this->sale_id,
      $this->payment_method_id,
      $this->amount,
      $this->bank_account_id,
      $this->transaction,
      $this->share,
      $this->bank_entity_id,
      $this->n_check,
      $this->date,
      $this->status,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  // INSERTAR SERIES 
  public function insertSeries(
    $sale_id,
    $product_id,
    $serie_id
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->sale_id = $sale_id;
    $this->product_id = $product_id;
    $this->serie_id = $serie_id;


    $query_insert = "INSERT INTO sales_products_series (sale_id, product_id,
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
}
