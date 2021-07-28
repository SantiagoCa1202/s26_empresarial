<?php
class Withholdings extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(26);
  }
  public function withholdings()
  {
    $this->views->getView($this, 'withholdings');
  }
  public function getWithholdings()
  {
    if ($_SESSION['permitsModule']['r']) {

      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'ruc' => !empty($_GET['ruc']) ? intval($_GET['ruc']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'n_authorization' => !empty($_GET['n_authorization']) ? strClean($_GET['n_authorization']) : '',
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'created_at' => !empty($_GET['created_at']) ? $_GET['created_at'] : '',
      ];
      $arrData = $this->model->selectWithholdings($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getWithholding($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectWithholding($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setWithholding()
  {
    $id = intval($_POST['id']);
    $document = strClean($_POST['document']);
    $business_name = strClean($_POST['business_name']);
    $description = !empty($_POST['description']) ? strClean($_POST['description']) : '';
    $n_document = !empty($_POST['n_document']) ? strClean($_POST['n_document']) : '';
    $n_authorization = strClean($_POST['n_authorization']);
    $ret_iva = floatval($_POST['ret_iva']);
    $ret_imp_rent = floatval($_POST['ret_imp_rent']);
    $total = floatval($_POST['total']);
    $file_id = intval($_POST['file_id']);
    $date_issue = strClean($_POST['date_issue']);

    $request = "";
    if (
      valString($document, 13, 13) &&
      valString($business_name, 5, 100) &&
      val_doc($n_document) &&
      ($total !== '' && is_numeric($total)) &&
      val_date($date_issue)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertWithholding(
            $document,
            $business_name,
            $description,
            $n_document,
            $n_authorization,
            $ret_iva,
            $ret_imp_rent,
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
          $request = $this->model->updateWithholding(
            $id,
            $document,
            $business_name,
            $description,
            $n_document,
            $n_authorization,
            $ret_iva,
            $ret_imp_rent,
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
    $arrRes = s26_res("Retención", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delWithholding($id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteWithholding($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Retención", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportWithholdings()
  {
    if ($_SESSION['permitsModule']['r']) {

      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'ruc' => !empty($_GET['ruc']) ? intval($_GET['ruc']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'n_authorization' => !empty($_GET['n_authorization']) ? strClean($_GET['n_authorization']) : '',
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'created_at' => !empty($_GET['created_at']) ? $_GET['created_at'] : '',
      ];

      $data['data'] = $this->model->selectWithholdings($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("withholdings", $data);
    }
    die();
  }
}
