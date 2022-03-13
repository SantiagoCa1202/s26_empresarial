<?php

class PaymentMethods extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(48);
  }

  public function paymentMethods()
  {
    $this->views->getView($this, "paymentMethods");
  }

  public function getPaymentMethods()
  {
    if ($_SESSION['permitsModule']['r']) {
      $filter = [
        'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
        'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
        'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
      ];
      $arrData = $this->model->selectPaymentMethods($filter);

      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getPaymentMethod($id)
  {
    if ($_SESSION['permitsModule']['r']) {
      $id = intval(strClean($id));
      if ($id > 0) {
        $arrData = $this->model->selectPaymentMethod($id);
        $arrRes = (empty($arrData)) ? 0 : $arrData;

        echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
      }
    }
    die();
  }

  public function setPaymentMethod()
  {
    
    $id = intval($_POST['id']);
    $status = !empty($_POST['status']) ? intval($_POST['status']) : 1;
    $request = "";
    if (
      $id > 0 &&
      ($status == 1 || $status == 2)
    ) {

      if ($_SESSION['permitsModule']['u']) {
        // Actualizar
        $request = $this->model->updatePaymentMethod(
          $id,
          $status
        );
        $type = 2;
      } else {
        $request = -5;
      }
    } else {
      $type = 0;
      $request = -1;
    }
    $arrRes = s26_res("Froma de Pago", $request, $type);
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
