<?php
require_once('./Models/NewSaleModel.php');
class ProductsDamageds extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }

    $this->Stock = new NewSaleModel;

    getPermits(17);
  }

  public function productsDamageds()
  {
    $this->views->getView($this, 'productsDamageds');
  }

  public function getProductsDamageds()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = !empty($_GET['perPage']) ? intval($_GET['perPage']) : 1000000;
      $filter = [
        'code' => !empty($_GET['code']) ? strClean($_GET['code']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'document_id' => isset($_GET['document_id']) ? $_GET['document_id'] : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectProductsDamageds($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getProductDamaged($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectProductDamaged($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setProductDamaged()
  {
    $id = intval($_POST['id']);
    $product_variant_id = intval($_POST['product_variant_id']);
    $cost = floatval($_POST['cost']);
    $amount = intval($_POST['amount']);
    $product_status = strClean($_POST['product_status']);
    $document_id = intval($_POST['document_id']);
    $description = !empty($_POST['description']) ? strClean($_POST['description']) : '999999999';

    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;

    $establishment_id = $_SESSION['userData']['establishment_id'];

    $request = "";
    if (
      $product_variant_id > 0 &&
      $amount > 0 &&
      valString($description, 5, 300) &&
      ($product_status == 'por reclamar' || $product_status == 'reclamado' || $product_status == 'sin solución' || $product_status == 'solucionado') &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear AVERIADO
          $request = $this->model->insertProductDamaged(
            bigintval($product_variant_id),
            $cost,
            $amount,
            $product_status,
            $document_id,
            $description,
            $status,
            $establishment_id,
          );

          if ($request > 0) {
            //RESTAR STOCK
            $this->Stock->subtractStock(
              bigintVal($product_variant_id),
              $establishment_id,
              intval($amount),
            );
          }
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateProductDamaged(
            $id,
            $product_variant_id,
            $cost,
            $amount,
            $product_status,
            $document_id,
            $description,
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
    $arrRes = s26_res("Producto Averiado", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delProductDamaged(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteProductDamaged($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Producto Averiado", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
