<?php
class DebtsToPay extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(30);
  }
  public function debtsToPay()
  {
    $this->views->getView($this, 'debtsToPay');
  }
  public function getDebts()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'creditor_type' => !empty($_GET['creditor_type']) ? intval($_GET['creditor_type']) : '',
        'ruc' => !empty($_GET['ruc']) ? strClean($_GET['ruc']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'status_payment' => !empty($_GET['status_payment']) ? intval($_GET['status_payment']) : '',
        'establishment_id' => !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectDebts($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getDebt($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectDebt($id);
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

  public function setDebt()
  {
    $id = intval($_POST['id']);
    $creditor_type = strClean($_POST['creditor_type']);
    $ruc = strClean($_POST['ruc']);
    $business_name = strClean($_POST['business_name']);
    $n_document = strClean($_POST['n_document']);
    $decription = strClean($_POST['decription']);
    $amount = floatval($_POST['amount']);
    $credit_note = floatval($_POST['credit_note']);
    $date = !empty($_POST['date']) ? strClean($_POST['date'][0]) : '';
    $expiration_date = !empty($_POST['expiration_date']) ? strClean($_POST['expiration_date'][0]) : '';
    $status_payment = intval($_POST['status$status_payment']);
    $status = intval($_POST['status']);
    $request = "";
    if (
      valString($creditor_type, 0, 50) &&
      valString($ruc, 10, 13) &&
      valString($business_name, 0, 100) &&
      valString($n_document, 17, 17) &&
      valString($decription, 0, 1000) &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear 
          $request = $this->model->insertDebt(
            $creditor_type,
            $ruc,
            $business_name,
            $n_document,
            $decription,
            $amount,
            $credit_note,
            $date,
            $expiration_date,
            $status_payment,
            $status,
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateDebt(
            $id,
            $creditor_type,
            $ruc,
            $business_name,
            $n_document,
            $decription,
            $amount,
            $credit_note,
            $date,
            $expiration_date,
            $status_payment,
            $status,
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
    $arrRes = s26_res("Cuenta por Pagar", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delDebt($id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteDebt($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Cuenta por Pagar", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
