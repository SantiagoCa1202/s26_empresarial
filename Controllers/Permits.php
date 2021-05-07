<?php

class Permits extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
  }

  public function getPermitsRol(int $idRol)
  {
    $id = intval($idRol);
    if ($id > 0) {
      $arrModules = $this->model->selectModules();
      $arrPermitsRol = $this->model->selectPermitsRol($id);
      $arrPermits = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
      $arrPermitRol = array('id' => $id);

      if (empty($arrPermitsRol)) {
        for ($i = 0; $i < count($arrModules); $i++) {
          $arrModules[$i]['permits'] = $arrPermits;
        }
      } else {
        for ($i = 0; $i < count($arrModules); $i++) {
          $arrPermits = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
          if (isset($arrPermitsRol[$i])) {
            $arrPermits = array(
              'r' => intval($arrPermitsRol[$i]['r']),
              'w' => intval($arrPermitsRol[$i]['w']),
              'u' => intval($arrPermitsRol[$i]['u']),
              'd' => intval($arrPermitsRol[$i]['d'])
            );
          }
          $arrModules[$i]['permits'] = $arrPermits;
        }
      }
      $arrPermitRol['modules'] = $arrModules;
      echo json_encode($arrPermitRol, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
  public function setPermits()
  {
    if ($_POST) {
      $intRole_id = intval($_POST['id']);
      $modules = $_POST['modules'];
      $this->model->deletePermits($intRole_id);
      foreach ($modules as $module) {
        $module_id = $module['id'];
        $r = $module['permits']['r'];
        $w = $module['permits']['w'];
        $u = $module['permits']['u'];
        $d = $module['permits']['d'];
        $requestPermit = $this->model->insertPermits($intRole_id, $module_id, $r, $w, $u, $d);
      }
      if ($requestPermit > 0) {
        $arrRes = array('status' => true, 'msg' => 'Permisos asignados correctamente');
      } else {
        $arrRes = array('status' => false, 'msg' => 'Error al Asignar Permisos');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
}
