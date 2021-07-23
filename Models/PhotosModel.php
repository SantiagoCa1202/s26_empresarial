<?php

class PhotosModel extends Mysql
{
  public $id;
  public $name;
  public $description;
  public $src;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectPhotos(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->favorites = $filter['favorites'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;


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
      FROM photos
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM photos
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['href'] =  asset('media/uploads/photos/') . $items[$i]['src'];
    }
    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'photos', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectPhoto(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM photos WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);
    if ($request) {
      $request['href'] =  asset('media/uploads/photos/') . $request['src'];
    }
    return $request;
  }

  public function insertPhoto(
    string $name,
    string $description,
    string $src,
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $return = "";
    $this->name = $name;
    $this->description = $description;
    $this->src = $src;

    $query_insert = "INSERT INTO photos (name, description, src) VALUES (?,?,?)";
    $arrData = array(
      $this->name,
      $this->description,
      $this->src
    );
    $request_insert = $this->insert_company($query_insert, $arrData, $this->db_company);
    $return = $request_insert;

    return $return;
  }

  public function updatePhoto(
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

    $sql = "UPDATE photos SET name = ?, description = ?, status = ? WHERE id = $this->id";
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

    $photo = $this->selectPhoto($this->id);
    $favorite = $photo['favorites'] ? 0 : 1;

    $sql = "UPDATE photos SET favorites = $favorite WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
  public function deletePhoto(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE photos SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
