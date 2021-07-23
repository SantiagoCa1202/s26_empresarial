<?php
class RolesModel extends Mysql
{
  public $id;
  public $rol;
  public $description;
  public $status;
  public $date;
  public $perPage;
  public $company_id;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectRoles(int $perPage, array $filter)
  {
    $this->id = $filter['id'];
    $this->rol = $filter['name'];
    $this->description = $filter['description'];
    $this->status = $filter['status'];
    $this->date = $filter['date'];
    $this->perPage = $perPage;
    $this->company_id = $_SESSION['userData']['establishment']['company_id'];



    $date_range = ($this->date != '' && count($this->date) == 2) ?
      " AND created_at BETWEEN '{$this->date[0]} 00:00:00' AND '{$this->date[1]}  23:59:59'" : "";

    $whereAdmin = (!$_SESSION['userData']['user_root']) ? "AND id != 1" : "";

    $where = "
      id LIKE '%$this->id%' AND
      name LIKE '%$this->rol%' AND
      description LIKE '%$this->description%' AND
      status LIKE '%$this->status%' AND 
      status > 0 AND 
      (company_id = $this->company_id OR
      company_id IS NULL)
      $date_range $whereAdmin
    ";

    $info = "SELECT COUNT(*) as count FROM roles WHERE $where ";
    $info_table = $this->info_table($info);

    $rows = "SELECT * FROM roles WHERE $where  ORDER BY id DESC LIMIT 0, $this->perPage";

    return [
      'items' => $this->select_all($rows),
      'dates' => $this->select_dates('created_at', 'roles'),
      'info' => $info_table,
    ];
  }

  public function selectRol(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM roles WHERE id = $this->id";
    $request = $this->select($sql);
    return $request;
  }

  public function insertRol(string $rol, string $description, int $status)
  {
    $this->rol = $rol;
    $this->description = $description;
    $this->status = $status;
    $this->company_id = $_SESSION['userData']['establishment']['company_id'];

    if ($this->rol !== 'administrador') {

      $sql = "
        SELECT * FROM roles 
        WHERE name = $this->rol AND 
        company_id = $this->company_id
      ";
      $request = $this->select_all($sql);

      if (empty($request)) {
        $query_insert = "INSERT INTO roles (name, description, status, company_id) VALUES (?,?,?,?)";
        $arrData = array(
          $this->rol,
          $this->description,
          $this->status,
          $_SESSION['userData']['establishment']['company_id']
        );
        $request = $this->insert($query_insert, $arrData);
      } else {
        $request = -2;
      }
    } else {
      $request = -4;
    }

    return $request;
  }
  public function updateRol(int $id, string $rol, string $description, int $status)
  {
    $this->id = $id;
    $this->rol = $rol;
    $this->description = $description;
    $this->status = $status;
    $this->company_id = $_SESSION['userData']['establishment']['company_id'];
    if ($this->id > 1) {

      $sql = "SELECT * FROM roles 
        WHERE id != $this->id AND 
        name = '$this->rol' AND 
        company_id = $this->company_id 
      ";
      $request = $this->select_all($sql);

      if (empty($request)) {
        $sql = "UPDATE roles SET name = ?, description = ?, status = ? WHERE id = $this->id AND id != 1";
        $arrData = array($this->rol, $this->description, $this->status);
        $request = $this->update($sql, $arrData);
      } else {
        $request = -2;
      }
    } else {
      $request = -4;
    }

    return $request;
  }

  public function deleteRol(int $id)
  {
    $this->id = $id;
    if ($this->id !== 1) {
      $sql = "SELECT * FROM users WHERE role_id = $this->id";
      $request = $this->select_all($sql);

      if (empty($request)) {
        $sql = '
          UPDATE roles SET status = 0 
          WHERE id = ' . $this->id . ' AND 
          company_id = "' . $_SESSION['userData']['establishment']['company_id']  . '"
        ';
        $request = $this->delete($sql);
      } else {
        $request = -3;
      }
    } else {
      $request = -4;
    }
    return $request;
  }
}
