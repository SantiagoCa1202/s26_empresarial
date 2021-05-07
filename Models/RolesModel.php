<?php
class RolesModel extends Mysql
{
  public $id;
  public $rol;
  public $description;
  public $status;

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
    $this->perPage = $perPage;

    $where = '
      id LIKE "%' . $this->id . '%" AND
      name LIKE "%' . $this->rol . '%" AND
      description LIKE "%' . $this->description . '%" AND
      status LIKE "%' . $this->status . '%" AND 
      status > 0
    ';

    $info = "SELECT COUNT(*) as count FROM roles WHERE $where ";
    $info_table = $this->info_table($info);

    $rows = "SELECT * FROM roles WHERE $where  ORDER BY id DESC LIMIT 0, $this->perPage";
    return [
      'items' => $this->select_all($rows),
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
    $return = "";
    $this->rol = $rol;
    $this->description = $description;
    $this->status = $status;

    $sql = "SELECT * FROM roles WHERE name = '{$this->rol}'";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $query_insert = "INSERT INTO roles (name, description, status) VALUES (?,?,?)";
      $arrData = array($this->rol, $this->description, $this->status);
      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = 0;
    }
    return $return;
  }
  public function updateRol(int $id, string $rol, string $description, int $status)
  {
    $this->id = $id;
    $this->rol = $rol;
    $this->description = $description;
    $this->status = $status;

    $sql = "SELECT * FROM roles WHERE id != '$this->id' AND name = '$this->rol' ";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $sql = "UPDATE roles SET name = ?, description = ?, status = ? WHERE id = $this->id";
      $arrData = array($this->rol, $this->description, $this->status);
      $request = $this->update($sql, $arrData);
    } else {
      $request = 0;
    }

    return $request;
  }

  public function deleteRol(int $id)
  {
    $this->id = $id;
    $sql = "SELECT * FROM users WHERE role_id = $this->id";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $sql = "UPDATE roles SET status = 0 WHERE id = $this->id";
      $request = $this->delete($sql);

      if ($request) {
        $request = 1;
      } else {
        $request = 0;
      }
    } else {
      $request = 2;
    }
    return $request;
  }
}
