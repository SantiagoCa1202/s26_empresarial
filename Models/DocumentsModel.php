<?php
class DocumentsModel extends Mysql
{
  public $id;
  public $type_doc_id;
  public $establishment_id;
  public $n_point;
  public $sequential_numbering;
  public $print;
  public $size;
  public $status;
  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectDocuments(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->type_doc_id = $filter['type_doc_id'];
    $this->n_point = $filter['n_point'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $where = "
      ep.document_id LIKE '%$this->type_doc_id%' AND
      ep.n_point LIKE '%$this->n_point%' AND
      ep.status LIKE '%$this->status%' AND
      ep.status > 0 
    ";

    $info = "SELECT COUNT(*) as count 
      FROM emission_point ep
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT ep.id, ep.document_id, d.name as document, d.alias, ep.n_point, ep.sequential_numbering, ep.print, ep.size, ep.status, ep.legend, ep.location_legend
      FROM emission_point ep
      JOIN s26_empresarial.documents d
      ON ep.document_id = d.id
      WHERE $where  
      ORDER BY ep.id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectDocument(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT ep.id, ep.document_id, d.name as document, d.alias, ep.n_point, ep.sequential_numbering, ep.print, ep.size, ep.legend, ep.location_legend, ep.status, ep.created_at 
    FROM emission_point ep 
    JOIN s26_empresarial.documents d
    ON ep.document_id = d.id
    WHERE ep.id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertDocument(
    int $document_id,
    int $establishment_id,
    int $n_point,
    int $sequential_numbering,
    int $print,
    int $size,
    string $legend,
    string $location_legend,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->document_id = $document_id;
    $this->establishment_id = $establishment_id;
    $this->n_point = $n_point;
    $this->sequential_numbering = $sequential_numbering;
    $this->print = $print;
    $this->legend = $legend;
    $this->location_legend = $location_legend;
    $this->size = $size;
    $this->status = $status;

    $sql = "SELECT * FROM emission_point WHERE document_id = '$this->document_id' AND n_point = '$this->n_point' AND establishment_id = $this->establishment_id ";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO emission_point (document_id, establishment_id, n_point, sequential_numbering, print, size, legend, location_legend, status) VALUES (?,?,?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->document_id,
        $this->establishment_id,
        $this->n_point,
        $this->sequential_numbering,
        $this->print,
        $this->size,
        $this->legend,
        $this->location_legend,
        $this->status,
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }

    return $request;
  }



  public function updateDocument(
    int $id,
    int $document_id,
    int $establishment_id,
    int $n_point,
    int $sequential_numbering,
    int $print,
    int $size,
    string $legend,
    string $location_legend,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->document_id = $document_id;
    $this->establishment_id = $establishment_id;
    $this->n_point = $n_point;
    $this->sequential_numbering = $sequential_numbering;
    $this->print = $print;
    $this->legend = $legend;
    $this->location_legend = $location_legend;
    $this->size = $size;
    $this->status = $status;

    $sql = "SELECT * FROM emission_point WHERE id != '$this->id' AND document_id = '$this->document_id' AND n_point = '$this->n_point' AND establishment_id = '$this->establishment_id' ";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE emission_point SET document_id = ?, n_point = ?, sequential_numbering = ?, print = ?, size = ?, legend = ?, location_legend = ?, status = ? WHERE id = '$this->id'";
      $arrData = array(
        $this->document_id,
        $this->n_point,
        $this->sequential_numbering,
        $this->print,
        $this->size,
        $this->legend,
        $this->location_legend,
        $this->status,
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }

    return $request;
  }

  public function deleteDocument(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    if ($this->id !== 1) {
      $sql = "UPDATE emission_point SET status = 0 WHERE id = $this->id";
      $request = $this->delete_company($sql, $this->db_company);
    } else {
      $request = -4;
    }
    return $request;
  }

  // autorizaciones
  public function selectAuthorizations($perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->emission_point_id = $filter['emission_point_id'];
    $this->n_authorization = $filter['n_authorization'];
    $this->perPage = $perPage;

    $emission_point_id = $this->emission_point_id >= -1 ? "epa.emission_point_id = $this->emission_point_id AND" : "";

    $pagination =  $this->perPage != '' ? "LIMIT 0, $this->perPage" : "";


    $info = "SELECT COUNT(*) as count
      FROM emission_point_authorizations epa
      WHERE $emission_point_id epa.authorization LIKE '%$this->n_authorization%'
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "SELECT 
      epa.id,
      d.name as document,
      ep.n_point,
      epa.emission_point_id,
      epa.authorization,
      epa.from_,
      epa.to_,
      epa.authorization_date,
      epa.expiration_date
      FROM emission_point_authorizations epa
      JOIN emission_point ep
      ON epa.emission_point_id = ep.id
      JOIN s26_empresarial.documents d
      ON ep.document_id = d.id
      WHERE $emission_point_id epa.authorization LIKE '%$this->n_authorization%'
      ORDER BY epa.id DESC $pagination
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectAuthorization(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM emission_point_authorizations
    WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    return $request;
  }

  public function insertAuthorization(
    int $point_id,
    string $authorization,
    int $from,
    int $to,
    string $authorization_date,
    string $expiration_date,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->point_id = $point_id;
    $this->authorization = $authorization;
    $this->from = $from;
    $this->to = $to;
    $this->authorization_date = $authorization_date;
    $this->expiration_date = $expiration_date;

    $query_insert = "INSERT INTO emission_point_authorizations (emission_point_id, authorization, from_, to_, authorization_date, expiration_date) VALUES (?,?,?,?,?,?)";
    $arrData = array(
      $this->point_id,
      $this->authorization,
      $this->from,
      $this->to,
      $this->authorization_date,
      $this->expiration_date,
    );
    $request = $this->insert_company($query_insert, $arrData, $this->db_company);

    return $request;
  }

  public function updateAuthorization(
    int $id,
    string $authorization,
    int $from,
    int $to,
    string $authorization_date,
    string $expiration_date,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->authorization = $authorization;
    $this->from = $from;
    $this->to = $to;
    $this->authorization_date = $authorization_date;
    $this->expiration_date = $expiration_date;

    $query_insert = "UPDATE emission_point_authorizations set authorization = ?, from_ = ?, to_ = ?, authorization_date = ?, expiration_date = ? WHERE id = $this->id";
    $arrData = array(
      $this->authorization,
      $this->from,
      $this->to,
      $this->authorization_date,
      $this->expiration_date,
    );
    $request = $this->update_company($query_insert, $arrData, $this->db_company);

    return $request;
  }
}
