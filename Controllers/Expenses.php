<?php

class Expenses extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(20);
  }

  public function expenses()
  {
    $this->views->getView($this, "expenses");
  }

  public function getExpenses()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);

      if ($_SESSION['permits'][41]['r']) {
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
      } else {
        $establishment_id = $_SESSION['userData']['establishment_id'];
      }

      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'tradename' => !empty($_GET['tradename']) ? strClean($_GET['tradename']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'amount' => !empty($_GET['amount']) ? floatval($_GET['amount']) : '',
        'account' => !empty($_GET['account']) ? intval($_GET['account']) : '',
        'bank_account_id' => !empty($_GET['bank_account_id']) ? bigintval($_GET['bank_account_id']) : '',
        'payment_method_id' => !empty($_GET['payment_method_id']) ? bigintval($_GET['payment_method_id']) : '',
        'box_id' => !empty($_GET['box_id']) ? bigintval($_GET['box_id']) : '',
        'establishment_id' => $establishment_id,
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectExpenses($perPage, $filter);
      $arrData['items'] = group_array_by_date('date', $arrData['items']);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getExpense($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectExpense($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setExpense()
  {
    $id = intval($_POST['id']);
    $n_document = strClean($_POST['n_document']);
    $tradename = strClean($_POST['tradename']);
    $description = strClean($_POST['description']);
    $amount = floatval($_POST['amount']);
    $account = intval($_POST['account']);
    $date = !empty($_POST['date']) ? strClean($_POST['date']) : date("Y-m-d");
    $bank_account_id = bigintval($_POST['bank_account_id']);
    $payment_method_id = bigintval($_POST['payment_method_id']);
    $establishment_id = $_SESSION['permits'][41]['r'] ? bigintval($_POST['establishment_id']) : $_SESSION['userData']['establishment_id'];
    $box_id = $_SESSION['userData']['device']['box_id'] ;
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;

    $request = "";
    if (
      valString($tradename) &&
      valString($description) &&
      $amount > 0 && 
      ($account == 1 || $account == 2) &&
      $payment_method_id > 0 &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertExpense(
            $n_document,
            $tradename,
            $description,
            $amount,
            $account,
            $date,
            $bank_account_id,
            $payment_method_id,
            $establishment_id,
            $box_id,
            $status
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateExpense(
            $id,
            $n_document,
            $tradename,
            $description,
            $amount,
            $account,
            $date,
            $bank_account_id,
            $payment_method_id,
            $establishment_id,
            $box_id,
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
    $arrRes = s26_res("Egreso", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delExpense(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteExpense($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Egreso", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
