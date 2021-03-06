<?php
require_once('SystemModel.php');
require_once('EstablishmentsModel.php');
require_once('FilesModel.php');
require_once('ProvidersModel.php');

class BuysModel extends Mysql
{
  public $id;
  public $document;
  public $business_name;
  public $description;
  public $type_doc_id;
  public $payment_method_id;
  public $n_document;
  public $n_authorization;
  public $iva_;
  public $rise;
  public $bi_0;
  public $bi_;
  public $iva;
  public $total;
  public $pdf;
  public $date_issue;
  public $establishment_id;
  public $status;
  public $perPage;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Document = new SystemModel;
    $this->Establishment = new EstablishmentsModel;
    $this->File = new FilesModel;
    $this->Provider = new ProvidersModel;
  }

  public function selectBuys(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->ruc = $filter['ruc'];
    $this->business_name = $filter['business_name'];
    $this->n_document = $filter['n_document'];
    $this->n_authorization = $filter['n_authorization'];
    $this->establishment_id = $filter['establishment_id'];
    $this->type_doc_id = $filter['type_doc_id'];
    $this->date_issue = $filter['date_issue'];
    $this->created_at = $filter['created_at'];
    $this->perPage = $perPage;

    $date_issue = ($this->date_issue != '' && count($this->date_issue) == 2) ?
      " AND date_issue BETWEEN '{$this->date_issue[0]} 00:00:00' AND '{$this->date_issue[1]}  23:59:59'" : "";

    $created_at = ($this->created_at != '' && count($this->created_at) == 2) ?
      " AND created_at BETWEEN '{$this->created_at[0]} 00:00:00' AND '{$this->created_at[1]}  23:59:59'" : "";

    $where = "
      id LIKE '%$this->id%' AND
      document LIKE '%$this->ruc%' AND
      business_name LIKE '%$this->business_name%' AND
      n_document LIKE '%$this->n_document%' AND
      n_authorization LIKE '%$this->n_authorization%' AND
      establishment_id LIKE '%$this->establishment_id%' AND
      type_doc_id LIKE '%$this->type_doc_id%' AND 
      status > 0 
      $date_issue
      $created_at
    ";

    $info = "SELECT COUNT(*) as count, 
      SUM(rise) as total_rise, 
      SUM(bi_0) as total_bi_0,
      SUM(bi_) as total_bi_,
      SUM(iva) as total_iva,
      SUM(total) as total
      FROM buys
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM buys
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['type_doc'] = $this->Document->selectDocument($items[$i]['type_doc_id']);

      $items[$i]['payment_method'] = $this->Document->selectPaymentMethod($items[$i]['payment_method_id']);

      $items[$i]['file'] = $items[$i]['file_id'] > 0 ?  $this->File->selectFile($items[$i]['file_id']) : '';

      $items[$i]['establishment'] = $this->Establishment->selectEstablishment($items[$i]['establishment_id']);
    }


    return [
      'items' => $items,
      'date_issue' => $this->select_dates_company('date_issue', 'buys', $this->db_company),
      'created_at' => $this->select_dates_company('created_at', 'buys', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectBuy(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM buys WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['provider'] = $this->Provider->selectProvider($request['provider_id']);

    $request['type_doc'] = $this->Document->selectDocument($request['type_doc_id']);

    $request['payment_method'] = $this->Document->selectPaymentMethod($request['payment_method_id']);

    $request['file'] = $request['file_id'] > 0 ?  $this->File->selectFile($request['file_id']) : '';

    $request['establishment'] = $this->Establishment->selectEstablishment($request['establishment_id']);

    return $request;
  }

  public function insertBuy(
    int $provider_id,
    string $document,
    string $business_name,
    string $description,
    int $type_doc_id,
    int $payment_method,
    string $n_document,
    string $n_authorization,
    float $rise,
    float $subtotal_0,
    float $subtotal_12,
    int $file_id,
    string $date_issue,
    int $establishment_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->provider_id = $provider_id;
    $this->document = $document;
    $this->business_name = $business_name;
    $this->description = $description;
    $this->type_doc_id = $type_doc_id;
    $this->payment_method = $payment_method;
    $this->n_document = $n_document;
    $this->n_authorization = $n_authorization;
    $this->rise = $rise;
    $this->subtotal_0 = $subtotal_0;
    $this->subtotal_12 = $subtotal_12;
    $this->file_id = $file_id;
    $this->date_issue = $date_issue;
    $this->establishment_id = $establishment_id;

    $sql = "SELECT * FROM buys WHERE n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO buys (provider_id, document, business_name, description, type_doc_id, payment_method_id, n_document, n_authorization, iva_, rise, bi_0, bi_, file_id, date_issue, establishment_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->provider_id,
        $this->document,
        $this->business_name,
        $this->description,
        $this->type_doc_id,
        $this->payment_method,
        $this->n_document,
        $this->n_authorization,
        _iva,
        $this->rise,
        $this->subtotal_0,
        $this->subtotal_12,
        $this->file_id,
        $this->date_issue,
        $this->establishment_id,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateBuy(
    int $id,
    int $provider_id,
    string $document,
    string $business_name,
    string $description,
    int $type_doc_id,
    int $payment_method,
    string $n_document,
    string $n_authorization,
    float $rise,
    float $subtotal_0,
    float $subtotal_12,
    int $file_id,
    string $date_issue,
    int $establishment_id,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->provider_id = $provider_id;
    $this->document = $document;
    $this->business_name = $business_name;
    $this->description = $description;
    $this->type_doc_id = $type_doc_id;
    $this->payment_method = $payment_method;
    $this->n_document = $n_document;
    $this->n_authorization = $n_authorization;
    $this->rise = $rise;
    $this->subtotal_0 = $subtotal_0;
    $this->subtotal_12 = $subtotal_12;
    $this->file_id = $file_id;
    $this->date_issue = $date_issue;
    $this->establishment_id = $establishment_id;

    $sql = "SELECT * FROM buys WHERE id != $this->id AND n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $sql = "UPDATE buys SET provider_id = ?, document = ?, business_name = ?, description = ?, type_doc_id = ?, payment_method_id = ?, n_document = ?, n_authorization = ?, rise = ?, bi_0 = ?, bi_ = ?, file_id = ?, date_issue = ?, establishment_id = ? WHERE id = $this->id";
      $arrData = array(
        $this->provider_id,
        $this->document,
        $this->business_name,
        $this->description,
        $this->type_doc_id,
        $this->payment_method,
        $this->n_document,
        $this->n_authorization,
        $this->rise,
        $this->subtotal_0,
        $this->subtotal_12,
        $this->file_id,
        $this->date_issue,
        $this->establishment_id,
      );
      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function deleteBuy(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE buys SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
