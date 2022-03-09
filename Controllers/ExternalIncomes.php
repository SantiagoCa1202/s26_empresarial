<?php

class ExternalIncomes extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(10);
  }

  public function externalIncomes()
  {
    $this->views->getView($this, "externalIncomes");
  }

  public function getExternalIncomes()
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
        'tradename' => !empty($_GET['tradename']) ? strClean($_GET['tradename']) : '',
        'description' => !empty($_GET['description']) ? strClean($_GET['description']) : '',
        'establishment_id' => $establishment_id,
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectExternalIncomes($perPage, $filter);
      $arrData['items'] = group_array_by_date('created_at', $arrData['items']);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getExternalIncome($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectExternalIncome($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setExternalIncome()
  {
    $id = intval($_POST['id']);
    $tradename = strClean($_POST['tradename']);
    $description = strClean($_POST['description']);
    $establishment_id = $_SESSION['permits'][41]['r'] ? bigintval($_POST['establishment_id']) : $_SESSION['userData']['establishment_id'];
    $external_incomes_amount = $_POST['external_incomes_amount'];
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;

    $request = "";
    if (
      valString($tradename) &&
      valString($description) &&
      COUNT($external_incomes_amount) > 0 &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertExternalIncome(
            $tradename,
            $description,
            $establishment_id,
            $status
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateExternalIncome(
            $id,
            $tradename,
            $description,
            $establishment_id,
            $status
          );
          $type = 2;
        } else {
          $request = -5;
        }
      }

      if ($request > 0) {
        for ($i = 0; $i < count($external_incomes_amount); $i++) {
          $item = $external_incomes_amount[$i];
          // INSERTAR
          $box_id = $item['add'] == 1 ? bigintval($item['box_id']) : null;
          $bank_account_id = $item['add'] == 2 ? bigintval($item['bank_account_id']) : null;

          if ($item['id'] > 0) {
            // EDITAR
            $this->model->updateExternalIncomeAmount(
              $item['id'],
              floatval($item['amount']),
              intval($item['account']),
              $box_id,
              $bank_account_id,
              intval($item['status']),
            );
          } else {


            $this->model->insertExternalIncomeAmount(
              $id > 0 ? $id : $request,
              floatval($item['amount']),
              intval($item['account']),
              $box_id,
              $bank_account_id,
              intval($item['status']),
            );
          }
        }
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Ingreso Externo", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delExternalIncome(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteExternalIncome($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Ingreso Externo", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
