<?php
class BankAccounts extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(29);
  }
  public function bankAccounts()
  {
    $this->views->getView($this, 'bankAccounts');
  }
  public function getBankAccounts()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $status = !empty($_GET['status']) ? intval($_GET['status']) : '';
      $checkbook = !empty($_GET['checkbook']) ? boolval($_GET['checkbook']) : '';

      $arrData = $this->model->selectBankAccounts($perPage, $status, $checkbook);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getBankAccount($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectBankAccount($id);
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

  public function setBankAccount()
  {
    $id = intval($_POST['id']);
    $bank_entity_id = intval($_POST['bank_entity_id']);
    $n_account = strClean($_POST['n_account']);
    $account_type = strClean($_POST['account_type']);
    $checkbook = intval($_POST['checkbook']);
    $predetermined = intval($_POST['predetermined']);
    $status = intval($_POST['status']);
    $request = "";
    if (
      $bank_entity_id > 0 &&
      valString($n_account, 5, 50) &&
      ($account_type === 'ahorros' || $account_type === 'corriente') &&
      ($checkbook == 2 || $checkbook == 1) &&
      ($predetermined == 2 || $predetermined == 1) &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Usuario
          $request = $this->model->insertBankAccount(
            $bank_entity_id,
            $n_account,
            $account_type,
            $checkbook,
            $predetermined,
            $status,
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateBankAccount(
            $id,
            $bank_entity_id,
            $n_account,
            $account_type,
            $checkbook,
            $predetermined,
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
    $arrRes = s26_res("Cuenta Bancaria", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delBankAccount($id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteBankAccount($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Cuenta Bancaria", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportBankAccounts()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $status = !empty($_GET['status']) ? intval($_GET['status']) : '';

      $data['data'] = $this->model->selectBankAccounts($perPage, $status, '');
      $data['type'] = $_GET['type'];
      $this->views->exportData("bankAccounts", $data);
    }
    die();
  }
}
