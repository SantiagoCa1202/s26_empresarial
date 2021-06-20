<?php

class Categories extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(46);
  }

  public function categories()
  {
    $this->views->getView($this, "categories");
  }

  public function getCategories()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectCategories($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getCategory($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectCategory($id);
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

  public function setCategory()
  {

    if ($_SESSION['permitsModule']['w']) {
      $id = intval($_POST['id']);
      $name = strClean($_POST['name']);
      $description = strClean($_POST['description']);
      $icon_id = isset($_POST['icon_id']) ? intval($_POST['icon_id']) : 1;
      $photo_id = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 1;
      $color = isset($_POST['color']) ? strClean($_POST['color']) : '#0d6efd';
      $status = intval($_POST['status']);
      $request = "";
      if (
        valString($name) &&
        valString($description, 1, 1000) &&
        ($status == 1 || $status == 2)
      ) {

        if ($id == 0) {

          if ($_SESSION['permitsModule']['w']) {
            //Crear Categoria
            $request = $this->model->insertCategory(
              $name,
              $description,
              $photo_id,
              $icon_id,
              $color,
              $status
            );
          }

          $type = 1;
        } else {

          if ($_SESSION['permitsModule']['u']) {
            // Actualizar
            $request = $this->model->updateCategory(
              $id,
              $name,
              $description,
              $photo_id,
              $icon_id,
              $color,
              $status
            );
          }
          $type = 2;
        }

        if ($request > 0) {
          if ($type == 1) {
            $arrRes = array('type' => 1, 'msg' => 'Datos guardados correctamente.');
          } else {
            $arrRes = array('type' => 2, 'msg' => 'Datos actualizados correctamente.');
          }
        } else if ($request == 0) {
          $arrRes = array('type' => 0, 'msg' => 'La Categoria ya existe.');
        } else {
          $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos.');
        }
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function delCategory(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $requestDelete = $this->model->deleteCategory($id);
      if ($requestDelete == 1) {
        $arrRes = array('type' => true, 'msg' => 'Categoria Eliminada.');
      } else {
        $arrRes = array('type' => false, 'msg' => 'Error al eliminar Categoria.');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function exportCategories()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectCategories($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("categories", $data);
    }
    die();
  }
}
