<?php

class Transfers extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(22);
  }

  public function transfers()
  {
    $this->views->getView($this, "transfers");
  }

  public function getTransfers()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);

      if ($_SESSION['permits'][41]['r']) {
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
      } else {
        $establishment_id = $_SESSION['userData']['establishment_id'];
      }

      $filter = [
        'source_account_id' => !empty($_GET['source_account_id']) ? intval($_GET['source_account_id']) : '',
        'destination_account_id' => !empty($_GET['destination_account_id']) ? intval($_GET['destination_account_id']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'amount' => !empty($_GET['amount']) ? floatval($_GET['amount']) : '',
        'establishment_id' => $establishment_id,
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectTransfers($perPage, $filter);
      $arrData['items'] = group_array_by_date('created_at', $arrData['items']);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getTransfer($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectTransfer($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setTransfer()
  {
    $id = intval($_POST['id']);
    $source_account_id = intval($_POST['source_account_id']);
    $destination_account_id = intval($_POST['destination_account_id']);
    $amount = floatval($_POST['amount']);
    $description = strClean($_POST['description']);
    $establishment_id = $_SESSION['permits'][41]['r'] ? bigintval($_POST['establishment_id']) : $_SESSION['userData']['establishment_id'];
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;

    $request = "";
    if (
      $source_account_id > 0 &&
      $destination_account_id > 0 &&
      $amount > 0 &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertTransfer(
            $source_account_id,
            $destination_account_id,
            $amount,
            $description,
            $establishment_id,
            $status,
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateTransfer(
            $id,
            $source_account_id,
            $destination_account_id,
            $amount,
            $description,
            $establishment_id,
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
    $arrRes = s26_res("Transferencia", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delTransfer(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteTransfer($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Transferencia", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
