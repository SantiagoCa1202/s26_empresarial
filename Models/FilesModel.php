<?php
class FilesModel extends Mysql
{
  public $id;
  public $name;
  public $description;
  public $src;
  public $favorites;
  public $type;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectFiles(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->favorites = $filter['favorites'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $where = "
      id LIKE '%$this->id%' AND
      name LIKE '%$this->name%' AND
      favorites LIKE '%$this->favorites%' AND
      status LIKE '%$this->status%' AND 
      status > 0 
      $date_range
    ";


    $info = "SELECT COUNT(*) as count 
      FROM files
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
        FROM files
        WHERE $where  
        ORDER BY id DESC LIMIT 0, $perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['href'] =  asset('media/uploads/files/') . $items[$i]['src'];
    }

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'files', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectFile(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM files WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    $request['href'] =  asset('media/uploads/files/') . $request['src'];

    return $request;
  }

  public function insertFile(
    string $name,
    string $description,
    string $src,
    string $type,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $return = "";
    $this->name = $name;
    $this->description = $description;
    $this->src = $src;
    $this->type = $type;

    $query_insert = "INSERT INTO files (name, description, src, type) VALUES (?,?,?,?)";
    $arrData = array(
      $this->name,
      $this->description,
      $this->src,
      $this->type,
    );
    $request_insert = $this->insert_company($query_insert, $arrData, $this->db_company);
    $return = $request_insert;

    return $return;
  }

  public function updateFile(
    int $id,
    string $name,
    string $description,
    string $status,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->status = $status;

    $sql = "UPDATE files SET name = ?, description = ?, status = ? WHERE id = $this->id";
    $arrData = array(
      $this->name,
      $this->description,
      $this->status,
    );

    $request = $this->update_company($sql, $arrData, $this->db_company);

    return $request;
  }

  public function addToFavorites(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $photo = $this->selectFile($this->id);
    $favorite = $photo['favorites'] ? 0 : 1;

    $sql = "UPDATE files SET favorites = $favorite WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function deleteFile(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE files SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
