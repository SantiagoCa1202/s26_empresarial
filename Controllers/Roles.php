<?php

class Roles extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
  }

  public function roles()
  {
    $this->views->getView($this, "roles");
  }

  public function getRoles()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
      'name' => strClean($_GET['name']),
      'description' => strClean($_GET['description']),
      'status' => !empty($_GET['status']) ? intval($_GET['status']) : ''
    ];
    $arrData = $this->model->selectRoles($perPage, $filter);
    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);

    die();
  }

  public function getRol(int $id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectRol($id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function setRol()
  {
    $id = intval($_POST['id']);
    $rol = strClean($_POST['name']);
    $description = strClean($_POST['description']);
    $status = intval($_POST['status']);

    if (
      valString($rol, 1, 100) && valString($description, 1, 100) &&
      ($status == 2 || $status == 1)
    ) {
      if ($id == 0) {
        //Crear Rol
        $request_rol = $this->model->insertRol(
          $rol,
          $description,
          $status,
        );
        $type = 1;
      } else {
        // Actualizar 
        $request_rol = $this->model->updateRol(
          $id,
          $rol,
          $description,
          $status,
        );
        $type = 2;
      }

      if ($request_rol > 0) {
        if ($type == 1) {
          $arrRes = array('type' => 1, 'msg' => 'Datos guardados correctamente.');
        } else {
          $arrRes = array('type' => 2, 'msg' => 'Datos actualizados correctamente.');
        }
      } else if ($request_rol == 0) {
        $arrRes = array('type' => 0, 'msg' => 'El rol ya existe.');
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos.');
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
    }

    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delRol(int $id)
  {
    $id = intval($id);
    $requestDelete = $this->model->deleteRol($id);
    if ($requestDelete == 1) {
      $arrRes = array('type' => true, 'msg' => 'Rol Eliminado.');
    } else if ($requestDelete == 2) {
      $arrRes = array('type' => false, 'msg' => 'No es posible eliminar un rol asociado a usuarios.');
    } else {
      $arrRes = array('type' => false, 'msg' => 'Error al eliminar Rol.');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
