<?php

class Customers extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(3);
  }

  public function customers()
  {
    $this->views->getView($this, "customers");
  }

  public function getCustomers()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'document' => !empty($_GET['document']) ? strClean($_GET['document']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'address' => !empty($_GET['address']) ? strClean($_GET['address']) : '',
        'phone' => !empty($_GET['phone']) ? strClean($_GET['phone']) : '',
        'mobile' => !empty($_GET['mobile']) ? strClean($_GET['mobile']) : '',
        'email' => !empty($_GET['email']) ? strClean($_GET['email']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];
      $arrData = $this->model->selectCustomers($perPage, $filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getCustomer($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectCustomer($id);
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

  public function setCustomer()
  {
    $id = intval($_POST['id']);
    $document = strClean($_POST['document']);
    $full_name = strClean($_POST['full_name']);
    $address = !empty($_POST['address']) ? strClean($_POST['address']) : $_SESSION['userData']['establishment']['city'];
    $phone = !empty($_POST['phone']) ? strClean($_POST['phone']) : '999999999';
    $mobile = !empty($_POST['mobile']) ? strClean($_POST['mobile']) : '9999999999';
    $email = !empty($_POST['email']) ? strClean($_POST['email']) : '';
    $time_limit = !empty($_POST['time_limit']) ? strClean($_POST['time_limit']) : '0 dias';
    $status = intval($_POST['status']);
    $request = "";
    if (
      valString($document, 10, 13) &&
      valString($full_name) &&
      valString($address, 5, 100) &&
      valString($phone, 9, 9) &&
      valString($mobile, 10, 10) &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {

        if ($_SESSION['permitsModule']['w']) {
          //Crear Usuario
          $request = $this->model->insertCustomer(
            $document,
            $full_name,
            $address,
            $phone,
            $mobile,
            $email,
            $time_limit,
            $status
          );
          $type = 1;
        } else {
          $request = -5;
        }
      } else {

        if ($_SESSION['permitsModule']['u']) {
          // Actualizar
          $request = $this->model->updateCustomer(
            $id,
            $document,
            $full_name,
            $address,
            $phone,
            $mobile,
            $email,
            $time_limit,
            $status
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
    $arrRes = s26_res("Cliente", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delCustomer(int $id)
  {
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $request = $this->model->deleteCustomer($id);
    } else {
      $request = -5;
    }
    $arrRes = s26_res("Cliente", $request, 3);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function exportCustomers()
  {
    if ($_SESSION['permitsModule']['r']) {
      $perPage = intval($_GET['perPage']);
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'document' => !empty($_GET['document']) ? strClean($_GET['document']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'address' => !empty($_GET['address']) ? strClean($_GET['address']) : '',
        'phone' => !empty($_GET['phone']) ? strClean($_GET['phone']) : '',
        'mobile' => !empty($_GET['mobile']) ? strClean($_GET['mobile']) : '',
        'email' => !empty($_GET['email']) ? strClean($_GET['email']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
        'date' => !empty($_GET['date']) ? $_GET['date'] : '',
      ];

      $data['data'] = $this->model->selectCustomers($perPage, $filter);
      $data['type'] = $_GET['type'];
      $this->views->exportData("customers", $data);
    }
    die();
  }
}
