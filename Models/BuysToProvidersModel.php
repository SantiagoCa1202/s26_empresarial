<?php
require_once('SystemModel.php');
require_once('EstablishmentsModel.php');
require_once('FilesModel.php');

class BuysToProvidersModel extends Mysql
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
  }

  public function selectBuys(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->ruc = $filter['ruc'];
    $this->business_name = $filter['business_name'];
    $this->n_document = $filter['n_document'];
    $this->n_authorization = $filter['n_authorization'];
    $this->establishment_id = $filter['establishment'];
    $this->status = $filter['status'];
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
      status LIKE '%$this->status%' AND 
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
      FROM buystoproviders
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM buystoproviders
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['type_doc'] = $this->Document->selectDocument($items[$i]['type_doc_id']);

      $items[$i]['file'] = $items[$i]['file_id'] > 0 ?  $this->File->selectFile($items[$i]['file_id']) : '';

      $items[$i]['establishment'] = $this->Establishment->selectEstablishment($items[$i]['establishment_id']);
    }


    return [
      'items' => $items,
      'date_issue' => $this->select_dates_company('date_issue', 'buystoproviders', $this->db_company),
      'created_at' => $this->select_dates_company('created_at', 'buystoproviders', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectBuy(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM buystoproviders WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

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
    int $status,
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
    $this->status = $status;

    $sql = "SELECT * FROM buystoproviders WHERE n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO buystoproviders (provider_id, document, business_name, description, type_doc_id, payment_method_id, n_document, n_authorization, iva_, rise, bi_0, bi_, file_id, date_issue, establishment_id, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
        $this->status,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }
}
