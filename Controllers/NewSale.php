<?php

require_once('./Models/SalesModel.php');
require_once('./Models/SalesCreditsModel.php');

class NewSale extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }

    $this->Sale = new SalesModel;
    $this->SaleCredit = new SalesCreditsModel;
    getPermits(13);
  }

  public function newSale()
  {
    $this->views->getView($this, "newSale");
  }

  public function getSavedSales()
  {

    if ($_SESSION['permitsModule']['r']) {
      $user_id = intval($_SESSION['idUser']);
      $arrData = $this->model->selectSavedSales($user_id);

      echo json_encode($arrData, JSON_NUMERIC_CHECK);
    }
    die();
  }

  public function getSavedSale($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectSavedSale($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function savedSale()
  {
    // INFORMACION DE VENTA
    $id = intval($_POST['id']);
    $customer_id = intval($_POST['customer']['id']);
    $note = strClean($_POST['note']);
    $user_id = intval($_SESSION['idUser']);

    //INFORMACION DE PRODUCTOS 
    $products = $_POST['products'];

    if (
      $id >= 0
    ) {
      if ($id == 0) {
        if ($_SESSION['permitsModule']['w']) {
          //Guardar Venta
          $request = $this->model->insertSaleSaved(
            $customer_id,
            $note,
            $user_id,
          );
        } else {
          $request = -5;
        }
        $type = 1;
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateSale(
            $id,
            $customer_id,
            $note,
            $user_id,

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
    if ($request > 0) {
      $id = $id > 0 ? $id : $request;
      $this->model->deleteProducts($id);
      $this->model->deleteSeries($id);
      for ($i = 0; $i < count($products); $i++) {
        if ($products[$i]['id'] > 0) {

          $series = isset($products[$i]['series']) ? $products[$i]['series'] : [];
          // INSERTAR PRODUCTOS 
          $request_product = $this->model->insertProduct(
            $id,
            intval($products[$i]['id']),
            floatval($products[$i]['amount']),
            floatval($products[$i]['cost']),
            floatval($products[$i]['pvp']),
            floatval($products[$i]['discount_money']),
            floatval($products[$i]['iva']),
          );
          if ($request_product > 0 && count($series) > 0) {

            for ($s = 0; $s < count($series); $s++) {
              $request_serie = $this->model->insertSeries(
                $id,
                intval($products[$i]['product_id']),
                intval($series[$s]),
              );
            }
          }
        }
      }
    }
    $arrRes = s26_res("Venta", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delSavedSale(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $this->model->deleteSeries($id);
      $this->model->deleteProducts($id);
      $request = $this->model->deleteSavedSale($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Venta", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function processSale()
  {
  
    $sale = $_POST;
    $document_id = $sale['emission_point'] == 0 ? 0 : $sale['emission_point']['document_id'];
    $n_establishment = str_pad($_SESSION['userData']['establishment']['n_establishment'], 3, "0", STR_PAD_LEFT);
    $establishment_id = $_SESSION['userData']['establishment_id'];
    $box_id = $_SESSION['userData']['device']['box_id'];

    if ($sale['emission_point'] == 0) {
      $n_document = "";
    } else {
      $n_document = $n_establishment . '-' . str_pad($sale['emission_point']['n_point'], 3, "0", STR_PAD_LEFT) . '-' . str_pad($sale['emission_point']['sequential_numbering'], 9, "0", STR_PAD_LEFT);
    }



    $res = "";
    // VALIDAR SI HAY PRODUCTOS A FACTURAR 
    if (count($sale['products']) > 1) {
      //VALIDAR SI HAY CLIENTE 
      if ($sale['customer']['id'] > 0) {

        if ($sale['type'] == 'contado') {
          // VENTA DE CONTADO
          // VALIDAR IMPORTE 
          $total = 0;
          foreach ($sale['payments'] as $key => $value) {
            $total += $value['amount'];
          }

          if ($sale['total_sale'] == $total) {

            // INSERTAR VENTA 
            $request = $this->Sale->insertSale(
              date("Y-m-d H:i:s"),
              bigintval($document_id),
              bigintval($sale['customer']['id']),
              $n_document,
              $sale['note'],
              $establishment_id,
              $box_id
            );

            if ($request > 0) {
              //INSERTAR PRODUCTOS
              $arr_res_prod = [];
              for ($i = 0; $i < count($sale['products']); $i++) {
                $product = $sale['products'][$i];
                if ($product['id'] > 0) {

                  $bi_0 = 0;
                  $bi_ = 0;
                  $iva = 0;

                  if ($product['iva'] == 12) {
                    $bi_ = floatval($product['net_total']) / _iva__;
                    $iva = floatval($product['net_total']) - $bi_;
                  } else {
                    $bi_0 = floatval($product['net_total']);
                  }

                  $res_product = $this->Sale->insertSaleProduct(
                    bigintVal($request),
                    bigintVal($product['id']),
                    intval($product['amount']),
                    floatval($product['cost']),
                    floatval($product['pvp']),
                    floatval($product['discount_money']),
                    floatval($product['iva']),
                    $bi_0,
                    $bi_,
                    $iva,
                  );

                  //RESTAR STOCK
                  $this->model->subtractStock(
                    bigintVal($product['id']),
                    $establishment_id,
                    intval($product['amount']),
                  );

                  // INSERTAR SERIES
                  $series = isset($product['series']) ? $product['series'] : [];
                  if (count($series) > 0) {
                    for ($s = 0; $s < count($series); $s++) {
                      $serie = $series[$s];
                      $this->Sale->insertSeries(
                        bigintVal($request),
                        bigintVal($product['id']),
                        bigintVal($serie),
                      );
                    }
                  }

                  if ($res_product > 0) {
                    array_push($arr_res_prod, $res_product);
                  }
                }
              }

              //INSERTAR FORMAS DE PAGO
              foreach ($sale['payments'] as $key => $value) {
                $payment_method_id = intval($value['payment_method_id']);
                $amount = isset($value['amount']) ? floatval($value['amount']) : 0;
                $bank_account_id = isset($value['bank_account_id']) ? bigintVal($value['bank_account_id']) : null;
                $transaction = isset($value['transaction']) ? strClean($value['transaction']) : null;
                $share = isset($value['share']) ? strClean($value['share']) : null;
                $bank_entity_id = isset($value['bank_entity_id']) ? bigintval($value['bank_entity_id']) : null;
                $n_check = isset($value['n_check']) ? strClean($value['n_check']) : null;
                $date = isset($value['date']) ? val_date($value['date']) : null;
                $status = $payment_method_id >= 4 && $payment_method_id <= 7 ? 0 : 1;
                if ($amount > 0 && $payment_method_id > 0) {
                  $this->Sale->insertSalePayment(
                    bigintVal($request),
                    bigintVal($payment_method_id),
                    $amount,
                    $bank_account_id,
                    $transaction,
                    $share,
                    $bank_entity_id,
                    $n_check,
                    $date,
                    $status
                  );
                }
              }

              //INCREMENTAR SECUENCIA 
              if ($sale['emission_point'] != 0) {

                $emission_point_id = $sale['emission_point']['id'];
                $sequential_numbering = $sale['emission_point']['sequential_numbering'];
                $new_sequential_numbering = $sequential_numbering + 1;

                $this->model->increaseSequence(
                  $emission_point_id,
                  $new_sequential_numbering
                );
              }
            }
            if (
              $request > 0 &&
              count($sale['products']) - 1  == count($arr_res_prod)
            ) {
              $res =  array('type' => 1, 'msg' => 'Venta Procesada Correctamente.');
            } else {
              $res =  array('type' => 0, 'msg' => 'Error al Procesar Venta.');
            }
          } else {
            $res = array('type' => 0, 'msg' => 'Verifique que el total de la venta sea igual al importe de pago total.');
          }
        } else {
          // VENTA A CREDITO
          // INSERTAR VENTA 
          $request = $this->SaleCredit->insertSaleCredit(
            date("Y-m-d H:i:s"),
            bigintval($sale['customer']['id']),
            $n_document,
            $sale['note'],
            $establishment_id
          );
          if ($request > 0) {
            //INSERTAR PRODUCTOS
            $arr_res_prod = [];
            for ($i = 0; $i < count($sale['products']); $i++) {
              $product = $sale['products'][$i];
              if ($product['id'] > 0) {

                $bi_0 = 0;
                $bi_ = 0;
                $iva = 0;

                if ($product['iva'] == 12) {
                  $bi_ = floatval($product['net_total']) / _iva__;
                  $iva = floatval($product['net_total']) - $bi_;
                } else {
                  $bi_0 = floatval($product['net_total']);
                }

                $res_product = $this->SaleCredit->insertSaleCreditProduct(
                  bigintVal($request),
                  bigintVal($product['id']),
                  intval($product['amount']),
                  floatval($product['cost']),
                  floatval($product['pvp']),
                  floatval($product['discount_money']),
                  floatval($product['iva']),
                  $bi_0,
                  $bi_,
                  $iva,
                );

                //RESTAR STOCK
                $this->model->subtractStock(
                  bigintVal($product['id']),
                  $establishment_id,
                  intval($product['amount']),
                );

                // INSERTAR SERIES
                $series = isset($product['series']) ? $product['series'] : [];
                if (count($series) > 0) {
                  for ($s = 0; $s < count($series); $s++) {
                    $serie = $series[$s];
                    $this->SaleCredit->insertSeries(
                      bigintVal($request),
                      bigintVal($product['id']),
                      bigintVal($serie),
                    );
                  }
                }

                if ($res_product > 0) {
                  array_push($arr_res_prod, $res_product);
                }
              }
            }

            // INSERTAR ABONO 
            $payment = $sale['credit'][0];

            $payment_method_id = intval($payment['payment_method_id']);
            $amount = isset($payment['amount']) ? floatval($payment['amount']) : 0;
            $bank_account_id = isset($payment['bank_account_id']) ? bigintVal($payment['bank_account_id']) : null;
            $transaction = isset($payment['transaction']) ? strClean($payment['transaction']) : null;
            $share = isset($payment['share']) ? strClean($payment['share']) : null;
            $bank_entity_id = isset($payment['bank_entity_id']) ? bigintval($payment['bank_entity_id']) : null;
            $n_check = isset($payment['n_check']) ? strClean($payment['n_check']) : null;
            $date_check = isset($payment['date_check']) ? val_date($payment['date_check']) : null;
            $status = $payment_method_id >= 4 && $payment_method_id <= 7 ? 0 : 1;
            if ($amount > 0 && $payment_method_id > 0) {
              $this->SaleCredit->insertSaleCreditPayment(
                bigintVal($request),
                date("Y-m-d H:i:s"),
                bigintVal($payment_method_id),
                $amount,
                $bank_account_id,
                $transaction,
                $share,
                $bank_entity_id,
                $n_check,
                $date_check,
                $status,
                $box_id,
              );
            }
          }
          if (
            $request > 0 &&
            count($sale['products']) - 1  == count($arr_res_prod)
          ) {
            $res =  array('type' => 1, 'msg' => 'Crédito Guardado Correctamente.');
          } else {
            $res =  array('type' => 0, 'msg' => 'Error al Guardar Crédito.');
          }
        }
      } else {
        $res = array('type' => 0, 'msg' => 'Error al Validar Cliente, Ingreselo Nuevamente.');
      }
    } else {
      $res = array('type' => 0, 'msg' => 'Sin Productos en La Venta');
    }
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    die();
  }
}
