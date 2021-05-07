<?php

class Users extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
  }

  public function users()
  {
    $this->views->getView($this, "users");
  }

  public function getUsers()
  {
    $perPage = intval($_GET['perPage']);
    $filter = [
      'id' => !empty($_GET['id']) ? intval($_GET['id']) : '',
      'document' => !empty($_GET['document']) ? intval($_GET['document']) : '',
      'name' => !empty($_GET['name']) ? strClean($_GET['name']) : '',
      'role_id' => !empty($_GET['role_id']) ? intval($_GET['role_id']) : '',
      'establishment_id' => !empty($_GET['establishment_id']) ? intval($_GET['establishment_id']) : '',
      'status' => !empty($_GET['status']) ? intval($_GET['status']) : '',
      'date' => !empty($_GET['date']) ? $_GET['date'] : '',
    ];
    $arrData = $this->model->selectUsers($perPage, $filter);

    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getUser(int $id)
  {
    $id = intval(strClean($id));
    if ($id > 0) {
      $arrData = $this->model->selectUser($id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function setUser()
  {
    $id = intval($_POST['id']);
    $name = strClean($_POST['name']);
    $last_name = strClean($_POST['last_name']);
    $document = strClean($_POST['document']);
    $email = strClean($_POST['email']);
    $password = strClean($_POST['new_password']);
    $confirm_password = strClean($_POST['confirm_password']);
    $phone = strClean($_POST['phone']);
    $role_id = intval($_POST['role_id']);
    $establishment_id = intval($_POST['establishment_id']);
    $status = intval($_POST['status']);
    if (
      valString($name) &&
      valString($last_name) &&
      valString($document, 10, 10) &&
      (filter_var($email, FILTER_VALIDATE_EMAIL)) &&
      valString($phone, 9, 10) &&
      ($role_id > 0) &&
      ($establishment_id > 0) &&
      ($status == 1 || $status == 2)
    ) {
      if ($id == 0) {
        if (
          valString($password, 12, 100) &&
          valString($confirm_password, 12, 100) &&
          ($password === $confirm_password)
        ) {
          $passwordHash = hash("SHA256", $password);
          //Crear Usuario 
          $request = $this->model->insertUser(
            $name,
            $last_name,
            $document,
            $email,
            $passwordHash,
            $phone,
            $role_id,
            $establishment_id,
            $status
          );
        } else {
          $request = -1;
        }

        $type = 1;
      } else {
        if (
          valString($password, 12, 100) &&
          valString($confirm_password, 12, 100) &&
          ($password === $confirm_password)
        ) {
          $passwordHash = hash("SHA256", $password);
        } else {
          $passwordHash = "";
        }
        // Actualizar 
        $request = $this->model->updateUser(
          $id,
          $name,
          $last_name,
          $document,
          $email,
          $passwordHash,
          $phone,
          $role_id,
          $establishment_id,
          $status
        );
        $type = 2;
      }

      if ($request > 0) {
        if ($type == 1) {
          $arrRes = array('type' => 1, 'msg' => 'Datos guardados correctamente.');
        } else {
          $arrRes = array('type' => 2, 'msg' => 'Datos actualizados correctamente.');
        }
      } else if ($request == 0) {
        $arrRes = array('type' => 0, 'msg' => 'El Usuario ya existe.');
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos.');
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delUser(int $id)
  {
    $id = intval($id);
    $requestDelete = $this->model->deleteUser($id);
    if ($requestDelete == 1) {
      $arrRes = array('type' => true, 'msg' => 'Usuario Eliminado.');
    } else {
      $arrRes = array('type' => false, 'msg' => 'Error al eliminar Usuario.');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
