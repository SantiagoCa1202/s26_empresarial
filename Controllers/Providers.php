<?php

class Providers extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(36);
  }

  public function providers()
  {
    $this->views->getView($this, "providers");
  }

  public function getProviders()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'document' => !empty($_GET['document']) ? strClean($_GET['document']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'tradename' => !empty($_GET['tradename']) ? strClean($_GET['tradename']) : '',
        'city' => !empty($_GET['city']) ? strClean($_GET['city']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectProviders($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getProvider($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectProvider($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;
        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setProvider()
  {
    $id = intval($_POST['id']);
    $document = strClean($_POST['trade_information']['document']);
    $business_name = !empty($_POST['trade_information']['business_name']) ?
      strClean($_POST['trade_information']['business_name']) : '';
    $tradename = !empty($_POST['trade_information']['tradename']) ? strClean($_POST['trade_information']['tradename']) : '';
    $alias = !empty($_POST['trade_information']['alias']) ? strClean($_POST['trade_information']['alias']) : '';

    $phone = !empty($_POST['contacts']['phone']) ?
      strClean($_POST['contacts']['phone']) : '';
    $phone_2 = !empty($_POST['contacts']['phone_2']) ? strClean($_POST['contacts']['phone_2']) : '';
    $mobile_provider = !empty($_POST['contacts']['mobile_provider']) ? strClean($_POST['contacts']['mobile_provider']) : '';
    $email = !empty($_POST['contacts']['email']) ?
      strClean($_POST['contacts']['email']) : '';
    $seller = !empty($_POST['contacts']['seller']) ? strClean($_POST['contacts']['seller']) : '';
    $mobile_seller = !empty($_POST['contacts']['mobile_seller']) ? strClean($_POST['contacts']['mobile_seller']) : '';

    $city = !empty($_POST['current_address']['city']) ? strClean($_POST['current_address']['city']) : '';
    $address = !empty($_POST['current_address']['address']) ? strClean($_POST['current_address']['address']) : '';

    $account_number = !empty($_POST['bank_accounts']['account_number']) ?
      strClean($_POST['bank_accounts']['account_number']) : '';
    $bank_entity_id = !empty($_POST['bank_accounts']['bank_entity_id']) ?
      intval($_POST['bank_accounts']['bank_entity_id']) : '0';
    $document_beneficiary = !empty($_POST['bank_accounts']['document']) ?
      strClean($_POST['bank_accounts']['document']) : '';
    $beneficiary = !empty($_POST['bank_accounts']['beneficiary']) ?
      strClean($_POST['bank_accounts']['beneficiary']) : '';
    $account_type = !empty($_POST['bank_accounts']['account_type']) ?
      strClean($_POST['bank_accounts']['account_type']) : '';

    $categories = !empty($_POST['categories']) ?
      $_POST['categories'] : [];

    $status = intval($_POST['status']);
    $request = "";
    $response = [];
    if (
      valString($document, 13, 13) &&
      valString($business_name, 5, 100) &&
      valString($tradename, 5, 100) &&
      valString($alias, 3, 10) &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Proveedor
          $request = $this->model->insertProvider(
            $document,
            $business_name,
            $tradename,
            $email,
            $seller,
            $phone,
            $phone_2,
            $mobile_provider,
            $mobile_seller,
            $alias,
            $city,
            $address
          );
        }

        $type = 1;
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateProvider(
            $id,
            $document,
            $business_name,
            $tradename,
            $email,
            $seller,
            $phone,
            $phone_2,
            $mobile_provider,
            $mobile_seller,
            $alias,
            $city,
            $address,
            $status
          );
        }
        $type = 2;
      }

      if ($request > 0) {
        if ($type == 1) {
          $arrRes = array('type' => 1, 'msg' => 'Proveedor guardado correctamente.');
          array_push($response, $arrRes);
          if (
            valString($account_number, 6, 15) &&
            $bank_entity_id > 0 &&
            valString($document_beneficiary, 10, 13) &&
            valString($beneficiary, 13, 100) &&
            ($account_type == 'ahorros' || 'corriente')
          ) {
            if ($_SESSION['permitsModule']['w']) {
              //Ingresar Entidad Bancaria
              $request_bank = $this->model->insertBankEntity(
                $request,
                $account_number,
                $bank_entity_id,
                $document_beneficiary,
                $beneficiary,
                $account_type
              );

              if ($request_bank > 0) {
                $arrRes = array('type' => 1, 'msg' => 'Cuenta bancaria guardada correctamente.');
                array_push($response, $arrRes);
              } else if ($request_bank == 0) {
                $arrRes = array('type' => 0, 'msg' => 'La cuenta bancaria ya existe.');
                array_push($response, $arrRes);
              }
            }
          } else {
            $arrRes = array('type' => 3, 'msg' => 'Cuenta Bancaria no ingresada, puedes actualizar datos más tarde.');
            array_push($response, $arrRes);
          }
        } else {
          $arrRes = array('type' => 2, 'msg' => 'Proveedor actualizado correctamente.');
          array_push($response, $arrRes);
          if ($_SESSION['permitsModule']['u']) {
            //Editar Entidad Bancaria
            $request_bank = $this->model->updateBankEntity(
              $id,
              $account_number,
              $bank_entity_id,
              $document_beneficiary,
              $beneficiary,
              $account_type
            );

            if ($request_bank > 0) {
              $arrRes = array('type' => 1, 'msg' => 'Cuenta bancaria editada correctamente.');
              array_push($response, $arrRes);
            } else if ($request_bank == 0) {
              $arrRes = array('type' => 0, 'msg' => 'La cuenta bancaria ya existe.');
              array_push($response, $arrRes);
            }
          }
        }
        if (count($categories) > 0) {
          if ($_SESSION['permitsModule']['w']) {
            //Ingresar Categorias
            $this->model->deleteCategories($id);
            $arrCategories = [];
            for ($i = 0; $i < count($categories); $i++) {
              $request_category = $this->model->insertCategories($request, $categories[$i]);

              if ($request_category > 0) {
                array_push($arrCategories, $i);
              }
            }

            if (count($arrCategories) > 0) {
              $arrRes = array(
                'type' => 1,
                'msg' => count($arrCategories) . ' Categorias guardadas correctamente.'
              );
              array_push($response, $arrRes);
            } else {
              $arrRes = array('type' => 0, 'msg' => 'Error al ingresar Categorias, puedes ingresarlo más tarde.');
              array_push($response, $arrRes);
            }
          }
        } else {
          $arrRes = array('type' => 3, 'msg' => 'Puedes Ingresar las categorias más tarde.');
          array_push($response, $arrRes);
        }
      } else if ($request == 0) {
        $arrRes = array('type' => 0, 'msg' => 'El Proveedor ya existe.');
        array_push($response, $arrRes);
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar Proveedor.');
        array_push($response, $arrRes);
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
      array_push($response, $arrRes);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delProvider(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteRol($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Proveedor", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportProviders()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'document' => !empty($_GET['document']) ? strClean($_GET['document']) : '',
        'business_name' => !empty($_GET['business_name']) ? strClean($_GET['business_name']) : '',
        'tradename' => !empty($_GET['tradename']) ? strClean($_GET['tradename']) : '',
        'city' => !empty($_GET['city']) ? strClean($_GET['city']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectProviders($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("providers", $data);
    }
    die();
  }
}
