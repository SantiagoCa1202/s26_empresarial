<?php

class Roles extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(44);
  }

  public function roles()
  {
    $this->views->getView($this, "roles");
  }

  public function getRoles()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => isset($_GET['name']) ? strClean($_GET['name']) : '',
        'description' => isset($_GET['description']) ? strClean($_GET['description']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectRoles($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getRol(int $id)
  {
    if ($_SESSION['permitsModule']['r']) {
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
        if ($_SESSION['permitsModule']['w']) {
          //Crear Rol
          $request = $this->model->insertRol(
            $rol,
            $description,
            $status,
          );
        }
        $type = 1;
      } else {
        if ($_SESSION['permitsModule']['u']) {
          // Actualizar 
          $request = $this->model->updateRol(
            $id,
            $rol,
            $description,
            $status,
          );
        }
        $type = 2;
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Rol", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delRol(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteRol($id);
      $arrRes = s26_res("Rol", $request, 3);
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function exportRoles()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => isset($_GET['name']) ? strClean($_GET['name']) : '',
        'description' => isset($_GET['description']) ? strClean($_GET['description']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectRoles($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("roles", $data);
    }
    die();
  }
}
