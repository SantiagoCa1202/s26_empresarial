<?php
require_once('UsersModel.php');
require_once('PhotosModel.php');

class CategoriesModel extends Mysql
{
  public $id;
  public $name;
  public $description;
  public $photo;
  public $icon;
  public $color;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Icon = new UsersModel;
    $this->Photo = new PhotosModel;
  }

  public function selectCategories(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->description = $filter['description'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;


    $date_range = ($this->date != '' && count($this->date) == 2) ?
      ' AND created_at BETWEEN "' . $this->date[0] . ' 00:00:00" AND "
      ' . $this->date[1] . ' 23:59:59" OR created_at BETWEEN "
      ' . $this->date[1] . ' 00:00:00" AND "' . $this->date[0] . ' 23:59:59"' : '';


    $where = '
      id LIKE "%' . $this->id . '%" AND
      name LIKE "%' . $this->name . '%" AND
      description LIKE "%' . $this->description . '%" AND
      status LIKE "%' . $this->status . '%" AND 
      status > 0 
      ' . $date_range;

    $info = "SELECT COUNT(*) as count 
      FROM categories
      WHERE $where 
    ";
    $info_table = $this->info_table_company($info, $this->db_company);

    $rows = "
      SELECT *
      FROM categories
      WHERE $where  
      ORDER BY id DESC LIMIT 0, $this->perPage
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['icon'] = $this->Icon->selectIcon($items[$i]['icon_id']);
      $items[$i]['photo'] = $this->Photo->selectPhoto($items[$i]['photo_id']);
    }
    return [
      'items' => $items,
      'info' => $info_table
    ];
  }

  public function selectCategory(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM categories WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['icon'] = $this->Icon->selectIcon($request['icon_id']);
    $request['photo'] = $this->Photo->selectPhoto($request['photo_id']);
    return $request;
  }

  public function insertCategory(
    string $name,
    string $description,
    string $photo,
    int $icon,
    string $color,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $return = "";
    $this->name = $name;
    $this->description = $description;
    $this->photo = $photo;
    $this->icon = $icon;
    $this->color = $color;
    $this->status = $status;

    $sql = "SELECT * FROM categories WHERE name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO categories (name, description, photo_id, icon_id, color, status) VALUES (?,?,?,?,?,?)";
      $arrData = array(
        $this->name,
        $this->description,
        $this->photo,
        $this->icon,
        $this->color,
        $this->status
      );
      $request_insert = $this->insert_company($query_insert, $arrData, $this->db_company);
      $return = $request_insert;
    } else {
      $return = 0;
    }
    return $return;
  }

  public function updateCategory(
    int $id,
    string $name,
    string $description,
    string $photo,
    int $icon,
    string $color,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->photo = $photo;
    $this->icon = $icon;
    $this->color = $color;
    $this->status = $status;

    $sql = "SELECT * FROM categories WHERE id != '$this->id' AND name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE categories SET name = ?, description = ?, photo_id = ?, icon_id = ?, color = ?, status = ? WHERE id = $this->id";
      $arrData = array(
        $this->name,
        $this->description,
        $this->photo,
        $this->icon,
        $this->color,
        $this->status
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = 0;
    }
    return $request;
  }

  public function deleteCategory(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE categories SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    if ($request) {
      $request = 1;
    } else {
      $request = 0;
    }
    return $request;
  }
}
