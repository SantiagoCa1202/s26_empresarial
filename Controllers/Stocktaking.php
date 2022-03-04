<?php
class Stocktaking extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(18);
  }
  public function stocktaking()
  {
    $this->views->getView($this, 'stocktaking');
  }
  public function getProducts()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'establishment_id' => $_SESSION['userData']['establishment_id'],
        'ean_code' => !empty($_GET['ean_code']) ? strClean($_GET['ean_code']) : '',
        'sku' => !empty($_GET['sku']) ? strClean($_GET['sku']) : '',
        'product' => !empty($_GET['product']) ? strClean($_GET['product']) : '',
        'model' => !empty($_GET['model']) ? strClean($_GET['model']) : '',
        'trademark' => !empty($_GET['trademark']) ? strClean($_GET['trademark']) : '',
      ];
      $arrData = $this->model->selectProducts($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getProduct($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectProduct($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setProduct()
  {
    $id = intval($_POST['id']);
    $product_variant_id = bigintval($_POST['product_variant_id']);
    $amount = intval($_POST['amount']);
    $accountant = intval($_POST['accountant']);
    $establishment_id = bigintval($_SESSION['userData']['establishment_id']);

    $request = "";
    if (
      $product_variant_id > 0 && $amount != 0
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          $request = $this->model->insertProduct(
            $product_variant_id,
            $amount,
            $establishment_id
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $new_accountant = $accountant + $amount;
          $request = $this->model->updateProduct(
            $id,
            $new_accountant,
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
    $arrRes = s26_res("Producto", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function prepareInventory()
  {

    if ($_SESSION['permitsModule']['w']) {
      $establishment_id = $_SESSION['userData']['establishment_id'];

      $this->model->prepareInventory($establishment_id);

      $arrRes = array('type' => 1, 'msg' => 'Inventario Preparado Correctamente.');
    } else {
      $arrRes = array('type' => 0, 'msg' => 'No tiene permiso para realizar esta acción');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function resetInventory()
  {
    if ($_SESSION['permitsModule']['d']) {
      $establishment_id = $_SESSION['userData']['establishment_id'];

      $this->model->resetInventory($establishment_id);

      $arrRes = array('type' => 1, 'msg' => 'Inventario Reiniciado Correctamente.');
    } else {
      $arrRes = array('type' => 0, 'msg' => 'No tiene permiso para realizar esta acción');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function recalcStock()
  {
    if ($_SESSION['permitsModule']['u']) {
      $establishment_id = $_SESSION['userData']['establishment_id'];

      $this->model->recalcStock($establishment_id);

      $arrRes = array('type' => 1, 'msg' => 'Stock Recalculado Correctamente.');
    } else {
      $arrRes = array('type' => 0, 'msg' => 'No tiene permiso para realizar esta acción');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
