<?php
require_once('./Models/SalesReturnsModel.php');

class Sales extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
    $this->Returns = new SalesReturnsModel;
  }

  public function sales()
  {
    $this->views->getView($this, "sales");
  }

  public function getSales()
  {
    if ($_SESSION['permitsModule']['r']) {

      if ($_SESSION['permits'][41]['r']) {
        $establishment_id = !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : "";
      } else {
        $establishment_id = $_SESSION['userData']['establishment_id'];
      }

      $perPage = !empty($_GET['perPage']) ? intval($_GET['perPage']) : 1000000;
      $filter = [
        'sale_id' => !empty($_GET['sale_id']) ? strClean($_GET['sale_id']) : '',
        'n_document' => !empty($_GET['n_document']) ? strClean($_GET['n_document']) : '',
        'customer' => !empty($_GET['customer']) ? strClean($_GET['customer']) : '',
        'establishment_id' => $establishment_id,
        'type_doc_id' => !empty($_GET['type_doc_id']) ? intval($_GET['type_doc_id']) : '',
        'status' => isset($_GET['status']) ? $_GET['status'] : '',
        'payment_method_id' => isset($_GET['payment_method_id']) ? $_GET['payment_method_id'] : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectSales($perPage, $filter);

      $arrData['items'] = group_array_by_date('date', $arrData['items']);
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getSale($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectSale($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setSale()
  {
    $id = intval($_POST['id']);
    $note = strClean($_POST['note']);
    $n_document = strClean($_POST['n_document']);
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;
    $products_totals = $_POST['products_totals'];
    $payments_totals = $_POST['payments_totals'];
    $request = "";
    if (
      $id > 0 &&
      $products_totals == $payments_totals &&
      ($status == 1 || $status == 2)
    ) {

      if ($_SESSION['permitsModule']['u']) {
        // Actualizar

        $request = $this->model->updateSale(
          bigintVal($id),
          $note,
          $n_document
        );

        // ACTUALIZAR IMPORTES 

        //ELIMINAR IMPORTES ACTUALES 
        $this->model->cancelPayments($id);
        //INSERTAR FORMAS DE PAGO
        foreach ($_POST['payments'] as $key => $value) {
          $payment_method_id = intval($value['payment_method_id']);
          $amount = isset($value['amount']) ? floatval($value['amount']) : 0;
          $bank_account_id = isset($value['bank_account_id']) ? bigintVal($value['bank_account_id']) : null;
          $transaction = isset($value['transaction']) ? strClean($value['transaction']) : null;
          $share = isset($value['share']) ? strClean($value['share']) : null;
          $bank_entity_id = isset($value['bank_entity_id']) ? bigintval($value['bank_entity_id']) : null;
          $n_check = isset($value['n_check']) ? strClean($value['n_check']) : null;
          $date = isset($value['date']) ? $value['date'] : null;
          $status = isset($value['status']) ? $value['status'] : 0;

          $exist = $this->model->selectPayment($id, $payment_method_id);

          $val_status = 1;

          if ($amount == 0) {
            $val_status = 0;
          } else if ($amount > 0 && $payment_method_id > 0 && $payment_method_id < 4) {
            $val_status = 1;
          } else {
            $val_status = $status;
          }

          if (!empty($exist)) {


            $this->model->updateSalePayment(
              bigintVal($id),
              bigintVal($payment_method_id),
              $amount,
              $bank_account_id,
              $transaction,
              $share,
              $bank_entity_id,
              $n_check,
              $date,
              $val_status
            );
          } else {
            if ($amount > 0 && $payment_method_id > 0) {
              $this->model->insertSalePayment(
                bigintVal($id),
                bigintVal($payment_method_id),
                $amount,
                $bank_account_id,
                $transaction,
                $share,
                $bank_entity_id,
                $n_check,
                $date,
                $val_status
              );
            }
          }
        }

        $type = 2;
      } else {
        $request = -5;
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Venta", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function cancelSale(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->cancelSale($id);

      if ($request > 0) {
        //INSERTAR VENTA EN DEVOLUCIONES 
        $request = $this->Returns->insertReturn($id, 'contado');
      }
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Venta", $request, 3, 'Anular');
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
