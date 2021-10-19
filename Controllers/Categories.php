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
    getPermits(45);
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
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectCategories($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getSubcategories()
  {
    if ($_SESSION['permitsModule']['r']) {
      $arrData = $this->model->selectSubcategories(intval($_GET['category_id']));
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
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function getSubcategory($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectSubcategory($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setCategory()
  {
    $id = intval($_POST['id']);
    $name = strClean($_POST['name']);
    $icon_id = isset($_POST['icon_id']) ? intval($_POST['icon_id']) : 1;
    $photo_id = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 1;
    $color = isset($_POST['color']) ? strClean($_POST['color']) : '#0d6efd';
    $status = intval($_POST['status']);
    $request = "";
    if (
      valString($name) &&
      ($status == 1 || $status == 2)
    ) {

      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Categoria
          $request = $this->model->insertCategory(
            $name,
            $photo_id,
            $icon_id,
            $color,
            $status
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateCategory(
            $id,
            $name,
            $photo_id,
            $icon_id,
            $color,
            $status
          );
          $type = 2;
        } else {
          $request = -5;
        }
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Categoria", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function setSubcategory()
  {
    $id = intval($_POST['id']);
    $category_id = intval($_POST['category_id']);
    $name = strClean($_POST['name']);
    $icon_id = isset($_POST['icon_id']) ? intval($_POST['icon_id']) : 1;
    $photo_id = isset($_POST['photo_id']) ? intval($_POST['photo_id']) : 1;
    $color = isset($_POST['color']) ? strClean($_POST['color']) : '#0d6efd';
    $status = intval($_POST['status']);
    $request = "";
    if (
      valString($name) &&
      ($status == 1 || $status == 2) &&
      $category_id > 0
    ) {

      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Categoria
          $request = $this->model->insertSubcategory(
            $category_id,
            $name,
            $photo_id,
            $icon_id,
            $color,
            $status
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateSubcategory(
            $id,
            $name,
            $photo_id,
            $icon_id,
            $color,
            $status
          );
          $type = 2;
        } else {
          $request = -5;
        }
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Subcategoria", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delCategory(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteCategory($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Categoria", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delSubcategory(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteSubcategory($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Subcategoria", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }


  public function exportCategories()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectCategories($perPage, $filter);
      $data['type'] = $_GET['type_export'];
      $this->views->exportData("categories", $data);
    }
    die();
  }
}
