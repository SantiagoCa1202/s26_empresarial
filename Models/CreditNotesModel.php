<?php
require_once('BuysModel.php');
require_once('FilesModel.php');

class CreditNotesModel extends Mysql
{
  public $id;
  public $buy_id;
  public $ruc;
  public $business_name;
  public $n_document;
  public $n_authorization;
  public $status;
  public $date_issue;
  public $created_at;
  public $perPage;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Buys = new BuysModel;
    $this->File = new FilesModel;
  }

  public function selectCreditNotes(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->ruc = $filter['ruc'];
    $this->business_name = $filter['business_name'];
    $this->n_document = $filter['n_document'];
    $this->n_authorization = $filter['n_authorization'];
    $this->establishment_id = $filter['establishment_id'];
    $this->date_issue = $filter['date_issue'];
    $this->created_at = $filter['created_at'];
    $this->perPage = $perPage;

    $date_issue = ($this->date_issue != '' && count($this->date_issue) == 2) ?
      " AND c.date_issue BETWEEN '{$this->date_issue[0]} 00:00:00' AND '{$this->date_issue[1]}  23:59:59'" : "";

    $created_at = ($this->created_at != '' && count($this->created_at) == 2) ?
      " AND c.created_at BETWEEN '{$this->created_at[0]} 00:00:00' AND '{$this->created_at[1]}  23:59:59'" : "";

    $where = "
      c.id LIKE '%$this->id%' AND
      b.document LIKE '%$this->ruc%' AND
      b.business_name LIKE '%$this->business_name%' AND
      c.n_document LIKE '%$this->n_document%' AND
      c.n_authorization LIKE '%$this->n_authorization%' AND
      b.establishment_id LIKE '%$this->establishment_id%' AND
      c.status > 0 
      $date_issue
      $created_at
    ";

    $info = "SELECT COUNT(c.id) as count, 
      SUM(c.bi_0) as total_bi_0,
      SUM(c.bi_) as total_bi_,
      SUM(c.iva) as total_iva,
      SUM(c.total) as total
      FROM credit_notes c
      JOIN buys b
      ON c.buy_id = b.id
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT 
      c.id, c.buy_id, c.description, c.type_doc_id, c.n_document, c.n_authorization, c.iva_, c.bi_0, c.bi_, c.iva, c.total, c.file_id, c.date_issue, c.created_at, c.status, b.document, b.business_name
      FROM credit_notes c
      JOIN buys b
      ON c.buy_id = b.id
      WHERE $where  
      ORDER BY c.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['buy'] = $this->Buys->selectBuy($items[$i]['buy_id']);
      $items[$i]['file'] = $items[$i]['file_id'] > 0 ?  $this->File->selectFile($items[$i]['file_id']) : '';
    }


    return [
      'items' => $items,
      'date_issue' => $this->select_dates_company('date_issue', 'credit_notes', $this->db_company),
      'created_at' => $this->select_dates_company('created_at', 'credit_notes', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectCreditNote(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM credit_notes WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['buy'] = $this->Buys->selectBuy($request['buy_id']);
    $request['file'] = $request['file_id'] > 0 ?  $this->File->selectFile($request['file_id']) : '';

    return $request;
  }

  public function insertBuy(
    int $buy_id,
    string $description,
    string $n_document,
    string $n_authorization,
    float $bi_0,
    float $bi_,
    int $file_id,
    string $date_issue,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->buy_id = $buy_id;
    $this->description = $description;
    $this->n_document = $n_document;
    $this->n_authorization = $n_authorization;
    $this->bi_0 = $bi_0;
    $this->bi_ = $bi_;
    $this->file_id = $file_id;
    $this->date_issue = $date_issue;

    $sql = "SELECT * FROM credit_notes WHERE n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO credit_notes (buy_id, description, n_document, n_authorization, iva_, bi_0, bi_, file_id, date_issue) VALUES (?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->buy_id,
        $this->description,
        $this->n_document,
        $this->n_authorization,
        _iva,
        $this->bi_0,
        $this->bi_,
        $this->file_id,
        $this->date_issue,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateBuy(
    int $id,
    int $buy_id,
    string $description,
    string $n_document,
    string $n_authorization,
    float $bi_0,
    float $bi_,
    int $file_id,
    string $date_issue,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->buy_id = $buy_id;
    $this->description = $description;
    $this->n_document = $n_document;
    $this->n_authorization = $n_authorization;
    $this->bi_0 = $bi_0;
    $this->bi_ = $bi_;
    $this->file_id = $file_id;
    $this->date_issue = $date_issue;

    $sql = "SELECT * FROM credit_notes WHERE id != $this->id AND n_document = '$this->n_document'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $sql = "UPDATE credit_notes SET buy_id = ?, description = ?, n_document = ?, n_authorization = ?, bi_0 = ?, bi_ = ?, file_id = ?, date_issue = ? WHERE id = $this->id";
      $arrData = array(
        $this->buy_id,
        $this->description,
        $this->n_document,
        $this->n_authorization,
        $this->bi_0,
        $this->bi_,
        $this->file_id,
        $this->date_issue,
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

    if ($this->id !== 1) {
      $sql = "UPDATE credit_notes SET status = 0 WHERE id = $this->id";
      $request = $this->delete_company($sql, $this->db_company);
    } else {
      $request = -4;
    }
    return $request;
  }
}
