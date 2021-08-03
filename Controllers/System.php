<?php

class System extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
  }

  public function getBankEntities()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
      'bank_entity' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
    ];
    $arrData = $this->model->selectBankEntities($perPage, $filter);

    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getBankEntity($id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectBankEntity($id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getDocuments()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
      'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
    ];
    $arrData = $this->model->selectDocuments($perPage, $filter);

    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getDocument($id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectDocument($id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getPaymentMethods()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
      'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
    ];
    $arrData = $this->model->selectPaymentMethods($perPage, $filter);

    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getPaymentMethod($id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectPaymentMethod($id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getIcons()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
    ];
    $arrData = $this->model->selectIcons($perPage, $filter);
    $arrRes = (empty($arrData)) ? 0 : $arrData;

    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getIcon($id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectIcon($id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getCities()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
    ];
    $arrData = $this->model->selectCities($perPage, $filter);
    $arrRes = (empty($arrData)) ? 0 : $arrData;

    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getCity($id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectCity($id);
      $arrRes = (empty($arrData)) ? 0 : $arrData;

      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
}
