<?php
class CreditNotes extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(25);
  }
  public function creditNotes()
  {
    $this->views->getView($this, 'creditNotes');
  }
  public function getCreditNotes()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r']) {
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
      } else {
        $establishment_id = $_SESSION['userData']['establishment_id'];
      }

      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'ruc' => !empty($_GET['ruc']) ? intval($_GET['ruc']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'n_authorization' => !empty($_GET['n_authorization']) ? strClean($_GET['n_authorization']) : '',
        'establishment_id' => $establishment_id,
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'created_at' => !empty($_GET['created_at']) ? $_GET['created_at'] : '',
      ];
      $arrData = $this->model->selectCreditNotes($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getCreditNote($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectCreditNote($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setCreditNote()
  {
    $id = intval($_POST['id']);
    $buy_id = intval($_POST['buy_id']);
    $description = !empty($_POST['description']) ? strClean($_POST['description']) : '';
    $n_document = !empty($_POST['n_document']) ? strClean($_POST['n_document']) : '';
    $n_authorization = strClean($_POST['n_authorization']);
    $bi_0 = floatval($_POST['bi_0']);
    $bi_ = floatval($_POST['bi_']);
    $total = floatval($_POST['total']);
    $file_id = intval($_POST['file_id']);
    $date_issue = strClean($_POST['date_issue']);

    $request = "";
    if (
      $buy_id > 0 &&
      val_doc($n_document) &&
      ($total !== '' && is_numeric($total)) &&
      val_date($date_issue)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertBuy(
            $buy_id,
            $description,
            $n_document,
            $n_authorization,
            $bi_0,
            $bi_,
            $file_id,
            $date_issue,
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateBuy(
            $id,
            $buy_id,
            $description,
            $n_document,
            $n_authorization,
            $bi_0,
            $bi_,
            $file_id,
            $date_issue,
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
    $arrRes = s26_res("Nota de Crédito", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delCreditNote($id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteCreditNote($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Compra", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportCreditNotes()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r']) {
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
      } else {
        $establishment_id = $_SESSION['userData']['establishment_id'];
      }

      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'ruc' => !empty($_GET['ruc']) ? intval($_GET['ruc']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'n_authorization' => !empty($_GET['n_authorization']) ? strClean($_GET['n_authorization']) : '',
        'establishment_id' => $establishment_id,
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'created_at' => !empty($_GET['created_at']) ? $_GET['created_at'] : '',
      ];

      $data['data'] = $this->model->selectCreditNotes($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("creditNotes", $data);
    }
    die();
  }
}
