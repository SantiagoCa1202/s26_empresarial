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
        'city_id' => !empty($_GET['city_id']) ? strClean($_GET['city_id']) : '',
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
    $document = strClean($_POST['document']);
    $business_name = !empty($_POST['business_name']) ?
      strClean($_POST['business_name']) : '';
    $tradename = !empty($_POST['tradename']) ? strClean($_POST['tradename']) : '';
    $alias = !empty($_POST['alias']) ? strClean($_POST['alias']) : '';

    $phone = !empty($_POST['phone']) ?
      strClean($_POST['phone']) : '';
    $phone_2 = !empty($_POST['phone_2']) ? strClean($_POST['phone_2']) : '';
    $mobile_provider = !empty($_POST['mobile_provider']) ? strClean($_POST['mobile_provider']) : '';
    $email = !empty($_POST['email']) ?
      strClean($_POST['email']) : '';
    $seller = !empty($_POST['seller']) ? strClean($_POST['seller']) : '';
    $mobile_seller = !empty($_POST['mobile_seller']) ? strClean($_POST['mobile_seller']) : '';

    $city_id = !empty($_POST['city_id']) ? strClean($_POST['city_id']) : '';
    $address = !empty($_POST['address']) ? strClean($_POST['address']) : '';

    $bank_accounts = !empty($_POST['bank_accounts']) ?
      $_POST['bank_accounts'] : [];

    $categories = !empty($_POST['categories']) ?
      $_POST['categories'] : [];

    $trademarks = !empty($_POST['trademarks']) ?
      $_POST['trademarks'] : [];

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
            $city_id,
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
            $city_id,
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
        } else {
          $arrRes = array('type' => 2, 'msg' => 'Proveedor actualizado correctamente.');
          array_push($response, $arrRes);
        }

        if (count($bank_accounts) > 0) {
          if ($_SESSION['permitsModule']['w']) {
            //Ingresar Cuentas Bancarias
            $this->model->deleteBankAccounts($request);
            $arrBankAccounts = [];
            for ($i = 0; $i < count($bank_accounts); $i++) {
              $account_number = strClean($bank_accounts[$i]['account_number']);
              $bank_entity_id = intval($bank_accounts[$i]['bank_entity_id']);
              $document_beneficiary = strClean($bank_accounts[$i]['document']);
              $beneficiary = strClean($bank_accounts[$i]['beneficiary']);
              $account_type = strClean($bank_accounts[$i]['account_type']);

              $request_bank = $this->model->insertBankEntity(
                $request,
                $account_number,
                $bank_entity_id,
                $document_beneficiary,
                $beneficiary,
                $account_type
              );

              if ($request_bank > 0) {
                array_push($arrBankAccounts, $i);
              }
            }

            if (count($arrBankAccounts) > 0) {
              $arrRes = array(
                'type' => 1,
                'msg' => count($arrBankAccounts) . ' Cuentas Bancarias guardadas correctamente.'
              );
              array_push($response, $arrRes);
            } else {
              $arrRes = array('type' => 0, 'msg' => 'Error al ingresar Cuentas Bancarias, puedes ingresarlo más tarde.');
              array_push($response, $arrRes);
            }
          }
        } else {
          $arrRes = array('type' => 3, 'msg' => 'Puedes Ingresar las cuentas bancarias más tarde.');
          array_push($response, $arrRes);
        }

        if (count($categories) > 0) {
          if ($_SESSION['permitsModule']['w']) {
            //Ingresar Categorias
            $this->model->deleteCategories($request);
            $arrCategories = [];
            for ($i = 0; $i < count($categories); $i++) {
              $category = intval($categories[$i]);
              $request_category = $this->model->insertCategories($request, $category);

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
        if (count($trademarks) > 0) {
          if ($_SESSION['permitsModule']['w']) {
            //Ingresar Marcas
            $this->model->deleteTrademarks($request);
            $arrTrademarks = [];
            for ($i = 0; $i < count($trademarks); $i++) {
              $trademark = strClean($trademarks[$i]);
              $request_trademark = $this->model->insertTrademarks($request, $trademark);

              if ($request_trademark > 0) {
                array_push($arrTrademarks, $i);
              }
            }

            if (count($arrTrademarks) > 0) {
              $arrRes = array(
                'type' => 1,
                'msg' => count($arrTrademarks) . ' Marcas guardadas correctamente.'
              );
              array_push($response, $arrRes);
            } else {
              $arrRes = array('type' => 0, 'msg' => 'Error al ingresar Marcas, puedes ingresarlas más tarde.');
              array_push($response, $arrRes);
            }
          }
        } else {
          $arrRes = array('type' => 3, 'msg' => 'Puedes Ingresar las marcas más tarde.');
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
        'city_id' => !empty($_GET['city_id']) ? strClean($_GET['city_id']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectProviders($perPage, $filter);
      $data['type'] = $_GET['type_export'];
      $this->views->exportData("providers", $data);
    }
    die();
  }
}
