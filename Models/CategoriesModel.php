<?php
require_once('UsersModel.php');
require_once('PhotosModel.php');

class CategoriesModel extends Mysql
{
  public $id;
  public $name;
  public $photo;
  public $icon;
  public $color;
  public $date;
  public $status;

  public $db_company;

  public function __construct()
  {
    parent::__construct();

    $this->Icon = new SystemModel;
    $this->Photo = new PhotosModel;
  }

  public function selectCategories(int $perPage, array $filter)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $filter['id'];
    $this->name = $filter['name'];
    $this->date = $filter['date'];
    $this->status = $filter['status'];
    $this->perPage = $perPage;

    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $status = $this->status > 0 ? "status = $this->status AND " : '';

    $where = "
      id LIKE '%$this->id%' AND
      name LIKE '%$this->name%' AND
      $status
      status > 0 
      $date_range
    ";

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
      $items[$i]['subcategories'] = $this->selectSubcategories($items[$i]['id'], $this->status);
    }

    return [
      'items' => $items,
      'dates' => $this->select_dates_company('created_at', 'categories', $this->db_company),
      'info' => $info_table
    ];
  }

  public function selectSubcategories(int $id, $status)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->status = $status;

    $status = $this->status > 0 ? "AND status = $this->status " : '';

    $info = "SELECT COUNT(*) as count 
      FROM subcategories
      WHERE category_id = $this->id  $status AND status > 0
    ";

    $info_table = $this->info_table_company($info, $this->db_company);


    $rows = "SELECT *
      FROM subcategories
      WHERE category_id = $this->id $status AND status > 0
    ";

    $items = $this->select_all_company($rows, $this->db_company);

    for ($i = 0; $i < count($items); $i++) {
      $items[$i]['icon'] = $this->Icon->selectIcon($items[$i]['icon_id']);
      $items[$i]['photo'] = $this->Photo->selectPhoto($items[$i]['photo_id']);
    }
    return [
      'info' => $info_table,
      'items' => $items
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

  public function selectSubcategory(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $sql = "SELECT * FROM subcategories WHERE id = $this->id";
    $request = $this->select_company($sql, $this->db_company);

    $request['icon'] = $this->Icon->selectIcon($request['icon_id']);
    $request['photo'] = $this->Photo->selectPhoto($request['photo_id']);
    return $request;
  }

  public function insertCategory(
    string $name,
    string $photo,
    int $icon,
    string $color,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->name = $name;
    $this->photo = $photo;
    $this->icon = $icon;
    $this->color = $color;
    $this->status = $status;

    $sql = "SELECT * FROM categories WHERE name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO categories (name, photo_id, icon_id, color, status) VALUES (?,?,?,?,?)";
      $arrData = array(
        $this->name,
        $this->photo,
        $this->icon,
        $this->color,
        $this->status
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateCategory(
    int $id,
    string $name,
    string $photo,
    int $icon,
    string $color,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->name = $name;
    $this->photo = $photo;
    $this->icon = $icon;
    $this->color = $color;
    $this->status = $status;

    $sql = "SELECT * FROM categories WHERE id != '$this->id' AND name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE categories SET name = ?, photo_id = ?, icon_id = ?, color = ?, status = ? WHERE id = $this->id";
      $arrData = array(
        $this->name,
        $this->photo,
        $this->icon,
        $this->color,
        $this->status
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function insertSubcategory(
    string $category_id,
    string $name,
    string $photo,
    int $icon,
    string $color,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->category_id = $category_id;
    $this->name = $name;
    $this->photo = $photo;
    $this->icon = $icon;
    $this->color = $color;
    $this->status = $status;

    $sql = "SELECT * FROM subcategories WHERE name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {
      $query_insert = "INSERT INTO subcategories (category_id, name, photo_id, icon_id, color, status) VALUES (?,?,?,?,?,?)";
      $arrData = array(
        $this->category_id,
        $this->name,
        $this->photo,
        $this->icon,
        $this->color,
        $this->status
      );
      $request = $this->insert_company($query_insert, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function updateSubcategory(
    int $id,
    string $name,
    string $photo,
    int $icon,
    string $color,
    int $status
  ) {

    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;
    $this->name = $name;
    $this->photo = $photo;
    $this->icon = $icon;
    $this->color = $color;
    $this->status = $status;

    $sql = "SELECT * FROM subcategories WHERE id != '$this->id' AND name = '$this->name'";
    $request = $this->select_all_company($sql, $this->db_company);

    if (empty($request)) {

      $sql = "UPDATE subcategories SET name = ?, photo_id = ?, icon_id = ?, color = ?, status = ? WHERE id = $this->id";
      $arrData = array(
        $this->name,
        $this->photo,
        $this->icon,
        $this->color,
        $this->status
      );

      $request = $this->update_company($sql, $arrData, $this->db_company);
    } else {
      $request = -2;
    }
    return $request;
  }

  public function deleteCategory(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE categories SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }

  public function deleteSubcategory(int $id)
  {
    $this->db_company = $_SESSION['userData']['establishment']['company']['data_base']['data_base'];

    $this->id = $id;

    $sql = "UPDATE subcategories SET status = 0 WHERE id = $this->id";
    $request = $this->delete_company($sql, $this->db_company);

    return $request;
  }
}
