<?php
class Buys extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(24);
  }

  public function buys()
  {
    $this->views->getView($this, "buys");
  }

  public function getBuys()
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
        'type_doc_id' => !empty($_GET['type_doc_id']) ? intval($_GET['type_doc_id']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'created_at' => !empty($_GET['created_at']) ? $_GET['created_at'] : '',
      ];
      $arrData = $this->model->selectBuys($perPage, $filter);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getBuy($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectBuy($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setBuy()
  {
    $id = intval($_POST['id']);
    $provider_id = intval($_POST['provider_id']) == 0 ? '' : intval($_POST['provider_id']);
    $document = strClean($_POST['document']);
    $business_name = strClean($_POST['business_name']);
    $description = !empty($_POST['description']) ? strClean($_POST['description']) : '';
    $n_document = !empty($_POST['n_document']) ? strClean($_POST['n_document']) : '';
    $type_doc_id = intval($_POST['type_doc_id']);
    $payment_method_id = intval($_POST['payment_method_id']);
    $date_issue = strClean($_POST['date_issue']);
    $n_authorization = strClean($_POST['n_authorization']);
    $file_id = intval($_POST['file_id']);
    $rise = floatval($_POST['rise']);
    $bi_0 = floatval($_POST['bi_0']);
    $bi_ = floatval($_POST['bi_']);
    $total = floatval($_POST['total']);
    $total_import = !empty($_POST['total_import']) ? floatval($_POST['total_import']) : 0;
    $credit_note = !empty($_POST['credit_note']) ? floatval($_POST['credit_note']) : 0;
    $payment_type = !empty($_POST['payment_type']) ? intval($_POST['payment_type']) : 1;
    $payment_method_counted = !empty($_POST['payment_method_counted']) ? intval($_POST['payment_method_counted']) : "";
    $bank_entity_id = !empty($_POST['bank_entity_id']) ? intval($_POST['bank_entity_id']) : null;
    $check_id = !empty($_POST['check_id']) ? intval($_POST['check_id']) : null;
    $n_transaction = !empty($_POST['n_transaction']) ? strClean($_POST['n_transaction']) : "";
    $counted_date = !empty($_POST['counted_date']) ? $_POST['counted_date'] : '';
    $credit_date = !empty($_POST['credit_date']) ? $_POST['credit_date'] : '';

    if ($_SESSION['permits'][41]['r']) {
      $establishment_id = !empty($_POST['establishment_id']) ? intval($_POST['establishment_id']) : $_SESSION['userData']['establishment_id'];
    } else {
      $establishment_id = $_SESSION['userData']['establishment_id'];
    }
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;
    $request = "";
    $response = [];
    if (
      valString($document, 13, 13) &&
      valString($business_name, 5, 100) &&
      val_doc($n_document) &&
      ($total !== '' && is_numeric($total)) &&
      val_date($date_issue) &&
      ($payment_type == 1 || $payment_type == 2) &&
      ($status == 1 || $status == 2) &&
      ($type_doc_id > 0 || $type_doc_id < 6) &&
      ($payment_method_id > 0 || $payment_method_id < 8)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear
          $request = $this->model->insertBuy(
            $provider_id,
            $document,
            $business_name,
            $description,
            $type_doc_id,
            $payment_method_id,
            $n_document,
            $n_authorization,
            $rise,
            $bi_0,
            $bi_,
            $file_id,
            $date_issue,
            $establishment_id,
            $status,
          );
          $type = 1;

          if ($request > 0) {
            $d = $payment_type == 1 ? '' : count($credit_date);
            $expiration_date = $payment_type == 1 ? $counted_date : $credit_date[$d - 1];

            $description = $payment_type == 1 ? 'pago de contado' : 'pago diferido';
            //INGRESO DE CUENTA POR PAGAR
            require_once('Models/DebtsToPayModel.php');
            $DebtsToPayModel = new DebtsToPayModel;
            $request_debt = $DebtsToPayModel->insertDebt(
              'proveedor',
              $document,
              $business_name,
              $n_document,
              $description,
              $total_import,
              $credit_note,
              $payment_type == 1 ? $counted_date : $date_issue,
              $expiration_date,
              $establishment_id,
              1
            );
            if ($request_debt > 0) {
              if ($payment_type == 1) {
                $request_record_debt = $DebtsToPayModel->insertRecordDebt(
                  $request_debt,
                  $counted_date,
                  $counted_date,
                  'pago de documento',
                  $total_import,
                  $payment_method_counted,
                  $bank_entity_id,
                  $n_transaction,
                  $check_id,
                  1
                );
              } else {
                $amount = ($total_import - $credit_note) / count($credit_date);
                for ($i = 0; $i < count($credit_date); $i++) {
                  # code...
                  $request_record_debt = $DebtsToPayModel->insertRecordDebt(
                    $request_debt,
                    $credit_date[$i],
                    $credit_date[$i],
                    'pago de documento',
                    $amount,
                    null,
                    null,
                    $n_transaction,
                    '',
                    0
                  );
                }
              }
            }
            $arrRes = s26_res("Cuenta Por Pagar", $request_debt, $type);
            array_push($response, $arrRes);
          }
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateBuy(
            $id,
            $provider_id,
            $document,
            $business_name,
            $description,
            $type_doc_id,
            $payment_method_id,
            $n_document,
            $n_authorization,
            $rise,
            $bi_0,
            $bi_,
            $file_id,
            $date_issue,
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
    $arrRes = s26_res("Compra", $request, $type);
    array_push($response, $arrRes);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delBuy(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteBuy($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Compra", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportBuys()
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
        'type_doc_id' => !empty($_GET['type_doc_id']) ? intval($_GET['type_doc_id']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date_issue' => !empty($_GET['date_issue']) ? $_GET['date_issue'] : '',
        'created_at' => !empty($_GET['created_at']) ? $_GET['created_at'] : '',
      ];

      $data['data'] = $this->model->selectBuys($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("buys", $data);
    }
    die();
  }
}
