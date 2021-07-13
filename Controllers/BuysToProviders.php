<?php
class BuysToProviders extends Controllers
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

  public function buysToProviders()
  {
    $this->views->getView($this, "buysToProviders");
  }

  public function getBuys()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = isset($_GET['perPage']) ? intval($_GET['perPage']) : '10000';
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'ruc' => !empty($_GET['ruc']) ? intval($_GET['ruc']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'n_authorization' => !empty($_GET['n_authorization']) ? strClean($_GET['n_authorization']) : '',
        'establishment' => !empty($_GET['establishment']) ? intval($_GET['establishment']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
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

  public function setBuy()
  {
    $id = intval($_POST['id']);
    $provider_id = intval($_POST['provider_id']) == 0 ? '' : intval($_POST['provider_id']);
    $document = strClean($_POST['document']);
    $business_name = strClean($_POST['business_name']);
    $description = !empty($_POST['description']) ? strClean($_POST['description']) : '';
    $n_document = !empty($_POST['n_document']) ? strClean($_POST['n_document']) : '';
    $type_doc_id = intval($_POST['type_doc_id']);
    $payment_method = intval($_POST['payment_method']);
    $date_issue = strClean($_POST['date_issue'][0]);
    $n_authorization = strClean($_POST['n_authorization']);
    $file_id = intval($_POST['file_id']);
    $rise = floatval($_POST['rise']);
    $subtotal_0 = floatval($_POST['subtotal_0']);
    $subtotal_12 = floatval($_POST['subtotal_12']);
    $total = floatval($_POST['total']);
    $total_import = floatval($_POST['total_import']);
    $credit_note = floatval($_POST['credit_note']);
    $payment_type = intval($_POST['payment_type']);
    $payment_method_counted = intval($_POST['payment_method_counted']);
    $bank_entity_id = !empty($_POST['bank_entity_id']) ? intval($_POST['bank_entity_id']) : null;
    $check_id = !empty($_POST['check_id']) ? intval($_POST['check_id']) : null;
    $n_transaction = strClean($_POST['n_transaction']);
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
      $total !== '' && is_numeric($total) &&
      val_date($date_issue) &&
      ($payment_type == 1 || $payment_type == 2) &&
      ($status == 1 || $status == 2)
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
            $payment_method,
            $n_document,
            $n_authorization,
            $rise,
            $subtotal_0,
            $subtotal_12,
            $file_id,
            $date_issue,
            $establishment_id,
            $status,
          );
          $type = 1;

          if ($request > 0) {
            $d = count($credit_date);
            $expiration_date = $credit_date[$d - 1];

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
              $date_issue,
              $establishment_id,
              $expiration_date,
              1
            );
            if ($request_debt > 0) {
              $amount = ($total_import - $credit_note) / count($credit_date);
              for ($i = 0; $i < count($credit_date); $i++) {
                # code...
                $request_record_debt = $DebtsToPayModel->insertRecordDebt(
                  $request_debt,
                  $credit_date[$i],
                  '',
                  'pago de documento',
                  $amount,
                  $payment_type == 1 ? $payment_method_counted : '',
                  $payment_type == 1 ? $bank_entity_id : '',
                  $n_transaction,
                  $payment_type == 1 ? $check_id : '',
                  $payment_type == 1 ? 'pagado' : 'por pagar'
                );
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
            $payment_method,
            $n_document,
            $n_authorization,
            _iva,
            $rise,
            $subtotal_0,
            $subtotal_12,
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
}
