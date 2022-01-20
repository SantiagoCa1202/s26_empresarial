<?php

class ProductsSeries extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(14);
  }

  public function productsSeries()
  {
    $this->views->getView($this, "productsSeries");
  }

  public function getSeries()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = !empty($_GET['perPage']) ? intval($_GET['perPage']) : '';
      $filter = [
        'serie' => !empty($_GET['serie']) ? strClean($_GET['serie']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'document_id' => isset($_GET['document_id']) ? $_GET['document_id'] : '',
        'product_id' => isset($_GET['product_id']) ? $_GET['product_id'] : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
      ];
      $arrData = $this->model->selectSeries($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getSerie($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectSerie($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_NUMERIC_CHECK);
      }
    }
    die();
  }
  public function setSerie()
  {
    $id = intval($_POST['id']);
    $document_id = strClean($_POST['document_id']);
    $serie = strClean($_POST['serie']);
    $status = intval($_POST['status']);

    if (
      $id > 0 &&
      $document_id >= -1 &&
      ($status == 1 || $status == 2)
    ) {

      if ($_SESSION['permitsModule']['u']) {
        // Actualizar
        $request = $this->model->updateSerie(
          $id,
          $document_id,
          $serie,
          $status,
        );
        $type = 2;
      } else {
        $request = -5;
      }

      $arrRes = s26_res("Serie de Producto", $request, $type);
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      die();
    }
  }
}
