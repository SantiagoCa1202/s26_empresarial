<?php

class Users extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(2);
  }

  public function users()
  {
    session_regenerate_id(true);
    $this->views->getView($this, "users");
  }

  public function getUsers()
  {
    if ($_SESSION['permitsModule']['r']) {
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
      for ($i = 0; $i < count($arrData['items']); $i++) {
        if (
          $_SESSION['userData']['user_root'] == 1 &&
          $_SESSION['userData']['role_id'] == 1 ||
          $_SESSION['userData']['role_id'] == 1 &&
          $arrData['items'][$i]['role_id'] != 1
        ) {
          $arrData['items'][$i]['btnUp'] = true;
        } else {
          $arrData['items'][$i]['btnUp'] = false;
        }
        if (
          ($_SESSION['userData']['id'] != $arrData['items'][$i]['id']) &&
          ($_SESSION['userData']['user_root'] == 1 &&
            $_SESSION['userData']['role_id'] == 1) ||
          ($_SESSION['userData']['role_id'] == 1 &&
            $arrData['items'][$i]['role_id'] != 1)
        ) {
          $arrData['items'][$i]['btnDel'] = true;
        } else {
          $arrData['items'][$i]['btnDel'] = false;
        }
      }
      echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getUser($id)
  {
    if ($_SESSION['permitsModule']['r']) {
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
    $create_notifications_users = intval($_POST['create_notifications_users']);
    $status = intval($_POST['status']);
    $request = "";
    if (
      valString($name) &&
      valString($last_name) &&
      valString($document, 10, 10) &&
      (filter_var($email, FILTER_VALIDATE_EMAIL)) &&
      valString($phone, 9, 10) &&
      ($role_id > 0) &&
      ($establishment_id > 0) &&
      ($status == 1 || $status == 2) &&
      ($create_notifications_users == 1 || $create_notifications_users == 2)
    ) {
      if ($id == 0) {
        if (
          valString($password, 12, 100) &&
          valString($confirm_password, 12, 100) &&
          ($password === $confirm_password)
        ) {
          $passwordHash = hash("SHA256", $password);
          if ($_SESSION['permitsModule']['w']) {
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
              $create_notifications_users,
              $status
            );
          }
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
        if ($_SESSION['permitsModule']['u']) {
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
            $create_notifications_users,
            $status
          );
        }
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
    if ($_SESSION['permitsModule']['d']) {
      $id = intval($id);
      $requestDelete = $this->model->deleteUser($id);
      if ($requestDelete == 1) {
        $arrRes = array('type' => true, 'msg' => 'Usuario Eliminado.');
      } else {
        $arrRes = array('type' => false, 'msg' => 'Error al eliminar Usuario.');
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function dark_mode()
  {
    if (isset($_COOKIE['dark-mode'])) {
      $val = ($_COOKIE['dark-mode'] == 0) ? 1 : 0;
      $request = $this->model->updateDarkMode($val);
      if ($request == 1) {
        setcookie('dark-mode', $val, time() + (86400 * 30), "/");
      }
    }
    die();
  }

  public function myaccount()
  {
    $this->views->getView($this, "myaccount");
  }

  public function getPayroll(string $idUser)
  {
    $id = (intval($idUser) > 0) ? intval($idUser) : $_SESSION['idUser'];
    if ($id > 0) {
      $arrData = $this->model->selectPayroll($id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getPayRecord(string $idRow)
  {
    $row_id = $idRow;
    if ($row_id > 0) {
      $arrData = $this->model->selectPayRecord($row_id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getPayRecords()
  {

    $perPage = intval($_GET['perPage']);
    $filter = [
      'id' => !empty($_GET['id']) ? intval($_GET['id']) : $_SESSION['idUser'],
      'date' => !empty($_GET['date']) ? $_GET['date'] : '',
    ];

    $arrData = $this->model->selectPayRecords($perPage, $filter);
    if (empty($arrData)) {
      $arrRes = 0;
    } else {
      $arrRes = $arrData;
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getMyNote(string $idRow)
  {
    $row_id = $idRow;
    if ($row_id > 0) {
      $arrData = $this->model->selectMyNote($row_id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getMyNotes()
  {
    $id = $_SESSION['idUser'];

    $arrData = $this->model->selectMyNotes($id);
    if (empty($arrData)) {
      $arrRes = 0;
    } else {
      $arrRes = $arrData;
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function setNote()
  {
    $id = intval($_POST['id']);
    $idUser = intval($_SESSION['idUser']);
    $name = strClean($_POST['name']);
    $note = strClean($_POST['note']);
    $color = strClean($_POST['color']);
    if (
      valString($name) &&
      valString($note, 1, 5000)
    ) {
      if ($id == 0) {

        //Crear Usuario
        $request = $this->model->insertNote(
          $idUser,
          $name,
          $note,
          $color,
        );
        $type = 1;
      } else {

        // Actualizar Nota
        $request = $this->model->updateNote(
          $id,
          $idUser,
          $name,
          $note,
          $color
        );
        $type = 2;
      }

      if ($request > 0) {
        if ($type == 1) {
          $arrRes = array('type' => 1, 'msg' => 'Datos guardados correctamente.');
        } else {
          $arrRes = array('type' => 2, 'msg' => 'Datos actualizados correctamente.');
        }
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos.');
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delNote(int $id)
  {
    $id = intval($id);
    $idUser = intval($_SESSION['idUser']);

    $requestDelete = $this->model->deleteNote($id, $idUser);
    if ($requestDelete == 1) {
      $arrRes = array('type' => true, 'msg' => 'Nota Eliminada.');
    } else {
      $arrRes = array('type' => false, 'msg' => 'Error al eliminar nota.');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getNotification(string $idRow)
  {
    $row_id = $idRow;
    if ($row_id > 0) {
      $arrData = $this->model->selectNotification($row_id);
      if (empty($arrData)) {
        $arrRes = 0;
      } else {
        $arrRes = $arrData;
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getNotifications()
  {
    $id = $_SESSION['idUser'];

    $arrData = $this->model->selectNotifications($id);
    if (empty($arrData)) {
      $arrRes = 0;
    } else {
      $arrRes = $arrData;
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function setNotification()
  {

    $idUser = ($_POST['idUser'] !== 0) ? $_POST['idUser'] : intval($_SESSION['idUser']);
    $issued_by = isset($_POST['issued_by']) ? $_POST['issued_by'] : intval($_SESSION['idUser']);

    $name = strClean($_POST['name']);
    $description = strClean($_POST['description']);
    $url = strClean($_POST['url']);
    $icon = isset($_POST['icon']) ? $_POST['icon'] : 'user';

    $expiration_date = strClean($_POST['expiration_date'][0]);

    if (
      valString($name) &&
      valString($description, 1, 5000)
    ) {
      //Crear Notificación
      $request = $this->model->insertNotification(
        $idUser,
        $issued_by,
        $name,
        $description,
        $url,
        $icon,
        $expiration_date,
      );
      $type = 1;

      if ($request > 0) {
        if ($type == 1) {
          $arrRes = array('type' => 1, 'msg' => 'Datos guardados correctamente.');
        } else {
          $arrRes = array('type' => 2, 'msg' => 'Datos actualizados correctamente.');
        }
      } else {
        $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos.');
      }
    } else {
      $arrRes = array('type' => 0, 'msg' => 'Error al Ingresar datos. Compruebe que los datos ingresados sean correctos');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function delNotification(int $id)
  {
    $id = intval($id);
    $idUser = intval($_SESSION['idUser']);

    $requestDelete = $this->model->deleteNotification($id, $idUser);
    if ($requestDelete == 1) {
      $arrRes = array('type' => true, 'msg' => 'Notificación Eliminada.');
    } else {
      $arrRes = array('type' => false, 'msg' => 'Error al eliminar notificación.');
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
