<?php

class CheckBooks extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(33);
  }

  public function checkBooks()
  {
    $this->views->getView($this, "checkBooks");
  }

  public function getCheckBooks()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'bank_account_id' => !empty($_GET['bank_account_id']) ? intval($_GET['bank_account_id']) : '',
        'n_check' => !empty($_GET['n_check']) ? intval($_GET['n_check']) : '',
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'date_payment' => !empty($_GET['date_payment']) ? $_GET['date_payment'] : '',
        'beneficiary' => !empty($_GET['beneficiary']) ? strClean($_GET['beneficiary']) : '',
        'reason' => !empty($_GET['reason']) ? strClean($_GET['reason']) : '',
        'type' => !empty($_GET['type']) ? strClean($_GET['type']) : '',
        'payment_status' => !empty($_GET['payment_status']) ? strClean($_GET['payment_status']) : '',
      ];
      $arrData = $this->model->selectCheckBooks($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getCheckBook($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectCheckBook($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;
       
        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setCheckBook()
  {
    $id = intval($_POST['id']);
    $bank_account_id = intval($_POST['bank_account_id']);
    $n_check = intval($_POST['n_check']);
    $date_issue = !empty($_POST['date_issue']) ? $_POST['date_issue'][0] : '';
    $date_payment = !empty($_POST['date_payment']) ? $_POST['date_payment'][0] : '';
    $beneficiary = strClean($_POST['beneficiary']);
    $reason = strClean($_POST['reason']);
    $amount = floatval($_POST['amount']);
    $balance = floatval($_POST['balance']);
    $type = strClean($_POST['type']);
    $payment_status = strClean($_POST['payment_status']);
    $request = "";
    if (
      $bank_account_id > 0 &&
      $n_check > 0 &&
      val_date($date_issue) &&
      val_date($date_payment) &&
      valString($beneficiary, 5, 100) &&
      valString($reason, 5, 1000) &&
      $amount > 0 &&
      ($type == 'emitido' || $type == 'recibido') &&
      ($payment_status == 'pagado' || $payment_status == 'por pagar' || $payment_status == 'anulado')
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertCheckBook(
            $bank_account_id,
            $n_check,
            $date_issue,
            $date_payment,
            $beneficiary,
            $reason,
            $amount,
            $balance,
            $type,
            $payment_status,
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateCheckBook(
            $id,
            $bank_account_id,
            $n_check,
            $date_issue,
            $date_payment,
            $beneficiary,
            $reason,
            $amount,
            $balance,
            $type,
            $payment_status,
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
    $arrRes = s26_res("Cheque", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportCheckBooks()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'bank_account_id' => !empty($_GET['bank_account_id']) ? intval($_GET['bank_account_id']) : '',
        'n_check' => !empty($_GET['n_check']) ? intval($_GET['n_check']) : '',
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'date_payment' => !empty($_GET['date_payment']) ? $_GET['date_payment'] : '',
        'beneficiary' => !empty($_GET['beneficiary']) ? strClean($_GET['beneficiary']) : '',
        'reason' => !empty($_GET['reason']) ? strClean($_GET['reason']) : '',
        'type' => !empty($_GET['type']) ? strClean($_GET['type']) : '',
        'payment_status' => !empty($_GET['payment_status']) ? intval($_GET['payment_status']) : '',
      ];

      $data['data'] = $this->model->selectCheckBooks($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("checkboos", $data);
    }
    die();
  }
}
