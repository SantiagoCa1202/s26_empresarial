<?php
require_once('./Models/SalesReturnsModel.php');
require_once('./Models/NewSaleModel.php');

class SalesCredits extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
    $this->Returns = new SalesReturnsModel;
    $this->NewSale = new NewSaleModel;
  }

  public function salesCredits()
  {
    $this->views->getView($this, "salesCredits");
  }

  public function getSalesCredits()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r']) {
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
      } else {
        $establishment_id = $_SESSION['userData']['establishment_id'];
      }

      $perPage = !empty($_GET['perPage']) ? intval($_GET['perPage']) : 1000000;
      $filter = [
        'sale_id' => !empty($_GET['sale_id']) ? strClean($_GET['sale_id']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'customer' => !empty($_GET['customer']) ? strClean($_GET['customer']) : '',
        'establishment_id' => $establishment_id,
        'type_doc_id' => !empty($_GET['type_doc_id']) ? intval($_GET['type_doc_id']) : '',
        'status' => isset($_GET['status']) ? $_GET['status'] : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectSalesCredits($perPage, $filter);

      $arrData['items'] = group_array_by_date('date', $arrData['items']);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getSaleCredit($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectSaleCredit($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setSale()
  {
    $id = intval($_POST['id']);
    $note = strClean($_POST['note']);
    $n_document = strClean($_POST['n_document']);
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;
    $request = "";
    if (
      $id > 0 &&
      ($status == 1 || $status == 2)
    ) {

      if ($_SESSION['permitsModule']['u']) {
        // Actualizar

        $request = $this->model->updateSale(
          bigintVal($id),
          $note,
          $n_document
        );

        $type = 2;
      } else {
        $request = -5;
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Crédito", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function payCredit()
  {

    $sale_id = intval($_POST['sale_id']);
    $payment_method_id = intval($_POST['payment_method_id']);
    $amount = floatval($_POST['amount']);
    $bank_account_id = intval($_POST['bank_account_id']);
    $transaction = strClean($_POST['transaction']);
    $share = strClean($_POST['share']);
    $n_check = intval($_POST['n_check']);
    $bank_entity_id = intval($_POST['bank_entity_id']);
    $date_check = empty($_POST['date_check']) ? null : $_POST['date_check'];
    $status = $payment_method_id >= 4 && $payment_method_id <= 7 ? 2 : 1;
    $box_id = isset($_SESSION['userData']['device']['box_id']) ? $_SESSION['userData']['device']['box_id'] : null;

    $request = "";

    if ($box_id > 0) {

      if (
        $sale_id > 0 &&
        $payment_method_id > 0 && $payment_method_id <= 7 &&
        $amount > 0
      ) {
        if ($_SESSION['permitsModule']['u']) {
          $request = $this->model->insertSaleCreditPayment(
            $sale_id,
            date("Y-m-d H:i:s"),
            $payment_method_id,
            $amount,
            $bank_account_id,
            $transaction,
            $share,
            $bank_entity_id,
            $n_check,
            $date_check,
            $status,
            $box_id,
          );
        } else {
          $request = -5;
        }
        $type = 2;
      } else {
        $type = 0;
        $request = -1;
      }

      $arrRes = s26_res("Crédito", $request, $type);
    } else {
      $arrRes = array('type' => 0, 'msg' => 'El dispositivo no tiene permiso para realizar esta acción, comuniquese con servicio ténico.');
    }

    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function setPayment()
  {

    $id = intval($_POST['id']);
    $payment_method_id = intval($_POST['payment_method_id']);
    $amount = floatval($_POST['amount']);
    $bank_account_id = intval($_POST['bank_account_id']);
    $transaction = strClean($_POST['transaction']);
    $share = strClean($_POST['share']);
    $n_check = intval($_POST['n_check']);
    $bank_entity_id = intval($_POST['bank_entity_id']);
    $date_check = empty($_POST['date_check']) ? null : $_POST['date_check'];
    $status = intval($_POST['status']);
    $current_box_id = isset($_SESSION['userData']['device']['box_id']) ? $_SESSION['userData']['device']['box_id'] : null;
    $box_id = intval($_POST['box_id']);
    $box_name = strClean($_POST['box_name']);

    $val_status = 1;

    if ($amount == 0) {
      $val_status = 0;
    } else if ($amount > 0 && $payment_method_id > 0 && $payment_method_id < 4) {
      $val_status = 1;
    } else {
      $val_status = $status;
    }

    $request = "";

    if ($box_id == $current_box_id) {

      if (
        $id > 0 &&
        $payment_method_id > 0 && $payment_method_id <= 7 &&
        $amount > 0
      ) {
        if ($_SESSION['permitsModule']['u']) {
          $request = $this->model->updateSaleCreditPayment(
            $id,
            $payment_method_id,
            $amount,
            $bank_account_id,
            $transaction,
            $share,
            $bank_entity_id,
            $n_check,
            $date_check,
            $val_status,
          );
        } else {
          $request = -5;
        }
        $type = 2;
      } else {
        $type = 0;
        $request = -1;
      }

      $arrRes = s26_res("Pago", $request, $type);
    } else {
      $arrRes = array('type' => 0, 'msg' => 'El Registro solo puede ser editado desde ' . $box_name);
    }

    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function processCredit()
  {

    $sale = $_POST;

    $id = $_POST['id'];
    $document_id = $sale['emission_point'] == 0 ? 1 : $sale['emission_point']['document_id'];
    $n_establishment = str_pad($_SESSION['userData']['establishment']['n_establishment'], 3, "0", STR_PAD_LEFT);
    $note = strClean($_POST['note']);

    if ($sale['emission_point'] == 0) {
      $n_document = "";
    } else {
      $n_document = $n_establishment . '-' . str_pad($sale['emission_point']['n_point'], 3, "0", STR_PAD_LEFT) . '-' . str_pad($sale['emission_point']['sequential_numbering'], 9, "0", STR_PAD_LEFT);
    }

    $res = "";

    // ACTUALIZAR CREDITO 
    $request = $this->model->updateSale(
      bigintVal($id),
      $note,
      $n_document,
      date("Y-m-d H:i:s"),
      bigintval($document_id),
      bigintval($sale['customer_billing']['id']),
    );

    if ($request > 0) {

      //INCREMENTAR SECUENCIA 
      if ($sale['emission_point'] != 0) {

        $emission_point_id = $sale['emission_point']['id'];
        $sequential_numbering = $sale['emission_point']['sequential_numbering'];
        $new_sequential_numbering = $sequential_numbering + 1;

        $this->NewSale->increaseSequence(
          $emission_point_id,
          $new_sequential_numbering
        );
      }

      $res =  array('type' => 1, 'msg' => 'Crédito Procesado Correctamente.');
    } else {
      $res =  array('type' => 0, 'msg' => 'Error al Procesar Crédito.');
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function cancelSaleCredit(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->cancelSaleCredit($id);

      if ($request > 0) {
        //INSERTAR VENTA EN DEVOLUCIONES 
        $request = $this->Returns->insertReturn($id, 'crédito');
      }
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Crédito", $request, 3, 'Anular');
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
