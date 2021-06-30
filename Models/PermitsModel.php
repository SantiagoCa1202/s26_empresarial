<?php
class PermitsModel extends Mysql
{
  public $intPermit_id;
  public $Role_id;
  public $Module_id;
  public $r;
  public $w;
  public $u;
  public $d;

  public function __construct()
  {
    parent::__construct();
  }

  public function selectModules()
  {
    $sql = "SELECT * FROM modules WHERE status != 0";
    $request = $this->select_all($sql);
    return $request;
  }

  public function selectPermitsRol(int $role_id)
  {
    $this->Role_id = $role_id;
    $sql = "SELECT * FROM roles_permits WHERE role_id = $this->Role_id";
    $request = $this->select_all($sql);
    return $request;
  }

  public function deletePermits(int $id)
  {
    $this->Role_id = $id;
    $sql = "DELETE FROM roles_permits WHERE role_id = $this->Role_id";
    $request = $this->delete($sql);
    return $request;
  }

  public function insertPermits(int $role_id, int $module_id, bool $r, bool $w, bool $u, bool $d)
  {
    $this->Role_id = $role_id;
    $this->Module_id = $module_id;
    $this->r = $r;
    $this->w = $w;
    $this->u = $u;
    $this->d = $d;
    $insert = "INSERT INTO roles_permits (role_id, module_id, r, w, u, d) VALUES (?,?,?,?,?,?)";
    $arrData = array(
      $this->Role_id,
      $this->Module_id,
      $this->r,
      $this->w,
      $this->u,
      $this->d
    );
    $request = $this->insert($insert, $arrData);
    return $request;
  }

  public function permitsModule(int $role_id)
  {
    $this->Role_id = $role_id;
    $sql = "
      SELECT 
        p.role_id,
        p.module_id,
        m.name as module,
        p.r,
        p.w,
        p.u,
        p.d
      FROM roles_permits p
      INNER JOIN modules m
      ON p.module_id = m.id
      WHERE p.role_id = $this->Role_id
    ";
    $request = $this->select_all($sql);
    $arrPermits = array();
    for ($i = 0; $i < count($request); $i++) {
      $arrPermits[$request[$i]['module_id']] = $request[$i];
    }
    return $arrPermits;
  }
}
