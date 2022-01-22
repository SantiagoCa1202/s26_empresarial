<?php

class ProductsOutlet extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(15);
  }

  public function productsOutlet()
  {
    $this->views->getView($this, "productsOutlet");
  }

  public function getProductsOutlet()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = !empty($_GET['perPage']) ? intval($_GET['perPage']) : 1000000;
      $filter = [
        'code' => !empty($_GET['code']) ? strClean($_GET['code']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'sale_id' => isset($_GET['sale_id']) ? $_GET['sale_id'] : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectProductsOutlet($perPage, $filter);

      $arrData['items'] = group_array_by_date('date', $arrData['items']);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getEntry($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectEntry($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_NUMERIC_CHECK);
      }
    }
    die();
  }
  public function setEntry()
  {
    $id = intval($_POST['id']);
    $document_id = strClean($_POST['document_id']);

    if (
      $id > 0 &&
      $document_id >= -1
    ) {

      if ($_SESSION['permitsModule']['u']) {
        // Actualizar
        $request = $this->model->updateEntry(
          $id,
          $document_id,
        );
        $type = 2;
      } else {
        $request = -5;
      }

      $arrRes = s26_res("Entrada de Producto", $request, $type);
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      die();
    }
  }
}
