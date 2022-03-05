<?php
require_once('SystemModel.php');
require_once('FilesModel.php');

class WithholdingsModel extends Mysql
{
  public $id;
  public $document;
  public $business_name;
  public $description;
  public $n_document;
  public $n_authorization;
  public $ret_iva;
  public $ret_imp_rent;
  public $total;
  public $file_id;
  public $date_issue;
  public $status;
  public $perPage;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Document = new SystemModel;
    $this->File = new FilesModel;
  }

  public function selectWithholdings(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->ruc = $filter['ruc'];
    $this->business_name = $filter['business_name'];
    $this->n_document = $filter['n_document'];
    $this->n_authorization = $filter['n_authorization'];
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
      status > 0 
      $date_issue
      $created_at
    ";

    $info = "SELECT COUNT(*) as count, 
      SUM(ret_iva) as total_ret_iva, 
      SUM(ret_imp_rent) as total_ret_imp_rent,
      SUM(total) as total
      FROM withholdings
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM withholdings
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {

      $items[$i]['type_doc'] = $this->Document->selectDocument($items[$i]['type_doc_id']);

      $items[$i]['file'] = $items[$i]['file_id'] > 0 ?  $this->File->selectFile($items[$i]['file_id']) : '';
    }


    return [
      'items' => $items,
      'date_issue' => $this->select_dates_company('date_issue', 'withholdings', $this->db_company),
      'created_at' => $this->select_dates_company('created_at', 'withholdings', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectWithholding(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM withholdings WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['type_doc'] = $this->Document->selectDocument($request['type_doc_id']);

    $request['file'] = $request['file_id'] > 0 ?  $this->File->selectFile($request['file_id']) : '';

    return $request;
  }

  public function insertWithholding(
    string $document,
    string $business_name,
    string $description,
    string $n_document,
    string $n_authorization,
    float $ret_iva,
    float $ret_imp_rent,
    int $file_id,
    string $date_issue,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->document = $document;
    $this->business_name = $business_name;
    $this->description = $description;
    $this->n_document = $n_document;
    $this->n_authorization = $n_authorization;
    $this->ret_iva = $ret_iva;
    $this->ret_imp_rent = $ret_imp_rent;
    $this->file_id = $file_id;
    $this->date_issue = $date_issue;

    $sql = "SELECT * FROM withholdings WHERE n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO withholdings (document, business_name, description, n_document, n_authorization, ret_iva, ret_imp_rent, file_id, date_issue) VALUES (?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->document,
        $this->business_name,
        $this->description,
        $this->n_document,
        $this->n_authorization,
        $this->ret_iva,
        $this->ret_imp_rent,
        $this->file_id,
        $this->date_issue,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateWithholding(
    int $id,
    string $document,
    string $business_name,
    string $description,
    string $n_document,
    string $n_authorization,
    float $ret_iva,
    float $ret_imp_rent,
    int $file_id,
    string $date_issue,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->document = $document;
    $this->business_name = $business_name;
    $this->description = $description;
    $this->n_document = $n_document;
    $this->n_authorization = $n_authorization;
    $this->ret_iva = $ret_iva;
    $this->ret_imp_rent = $ret_imp_rent;
    $this->file_id = $file_id;
    $this->date_issue = $date_issue;

    $sql = "SELECT * FROM withholdings WHERE id != $this->id AND n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $sql = "UPDATE withholdings SET document = ?, business_name = ?, description = ?, n_document = ?, n_authorization = ?, ret_iva = ?, ret_imp_rent = ?, file_id = ?, date_issue = ? WHERE id = $this->id";
      $arrData = array(
        $this->document,
        $this->business_name,
        $this->description,
        $this->n_document,
        $this->n_authorization,
        $this->ret_iva,
        $this->ret_imp_rent,
        $this->file_id,
        $this->date_issue,
      );
      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function deleteWithholding(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE withholdings SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
