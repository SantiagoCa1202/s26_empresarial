<?php
class Login extends Controllers
{
  public function __construct()
  {
    session_start();
    if (isset($_SESSION['login'])) {
      header('Location: ' . base_url() . '/dashboard');
    }
    parent::__construct();
  }

  public function login()
  {
    $this->views->getView($this, "login");
  }

  public function loginUser()
  {
    if ($_POST) {
      if (empty($_POST['user']) || empty($_POST['password'])) {
        $arrRes = array('status' => false, 'msg' => 'Erros al iniciar sesíon');
      } else {
        $user = strtolower(strClean($_POST['user']));
        $password = hash("SHA256", $_POST['password']);
        $requestUser = $this->model->loginUser($user, $password);
        if (empty($requestUser)) {
          $arrRes = array('status' => false, 'msg' => 'El usuario o la contraseña son incorrectos');
        } else {
          $arrData = $requestUser;
          if ($arrData['status'] == 1) {
            $_SESSION['idUser'] = $arrData['id'];
            $_SESSION['login'] = true;
            //Get user IP address
            if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
              $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
              $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
              $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            }

            $ip = filter_var($ip, FILTER_VALIDATE_IP);
            $ip = '192.168.0.3';

            $arrData = $this->model->sessionLogin($_SESSION['idUser'], $ip);
            $_SESSION['userData'] = $arrData;
            setcookie('dark-mode', $arrData['dark_mode'], time() + (86400 * 30), "/");
            if (isset($arrData['device']['printer_name'])) {
              setcookie('printer_name', $arrData['device']['printer_name'], time() + (86400 * 30), "/");
            }else{
              setcookie('printer_name', '', time() + (86400 * 30), "/");
            }


            $arrRes = array('status' => true, 'msg' => 'Ok');
          } else {
            $arrRes = array('status' => false, 'msg' => 'Usuario Inactivo');
          }
        }
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function resetPassword()
  {
    if ($_POST) {
      error_reporting(0);
      if (empty($_POST['email'])) {
        $arrRes = array('status' => false, 'msg' => 'Error de datos');
      } else {
        $token = token();
        $email = strtolower(strClean($_POST['email']));
        $arrData = $this->model->getUserEmail($email);

        if (empty($arrData)) {
          $arrRes = array('status' => false, 'msg' => 'El usuario no existe');
        } else {
          $idUser = $arrData['id'];
          $nameUser = $arrData['name'] . ' ' . $arrData['last_name'];

          $url_recovery = base_url() . '/login/confirmUser' . $email . '/' . $token;

          $requesUpdate = $this->model->setTokenUser($idUser, $token);

          $dataUser = array(
            'nameUser' => $nameUser,
            'email' => $email,
            'affair' => 'Recuperar cuenta -' . NAME_SENDER,
            'url_recovery' => $url_recovery
          );
          if ($requesUpdate) {
            $sendEmail = sendEmail($dataUser, 'email_change_password');
            if ($sendEmail) {
              $arrRes = array('status' => true, 'msg' => 'Por favor revise su correo electrónico para obtener más instrucciones.');
            } else {
              $arrRes = array('status' => false, 'msg' => 'Servicio no disponible, intentalo mas tarde.');
            }
          } else {
            $arrRes = array('status' => false, 'msg' => 'Servicio no disponible, intentalo mas tarde.');
          }
        }
      }
      echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function confirmUser(string $params)
  {
    if (empty($params)) {
      header('Location: ' . base_url());
    } else {
      $arrParams = explode(',', $params);
      $email = strClean($arrParams[0]);
      $token = strClean($arrParams[1]);

      $arrRes = $this->model->getUser($email, $token);

      if (empty($arrRes)) {
        header('Location: ' . base_url());
      } else {
        $data['idUser'] = $arrRes['id'];
        $data['email'] = $email;
        $data['token'] = $token;
        $this->views->getView($this, "change-password", $data);
      }
    }
  }

  public function setPassword()
  {
    if (empty($_POST['idUser']) || empty($_POST['email']) || empty($_POST['token']) || empty($_POST['password']) || empty($_POST['password_confirmed'])) {
      $arrRes = array('status' => false, 'msg' => 'Error al restablecer contraseña');
    } else {
      $idUser = intval($_POST['idUser']);
      $password = $_POST['password'];
      $password_confirmed = $_POST['password_confirmed'];
      $email = strClean($_POST['email']);
      $token = strClean($_POST['token']);

      if ($password !== $password_confirmed) {
        $arrRes = array('status' => false, 'msg' => 'Las contraseñas no son iguales');
      } else {
        $arrRes = $this->model->getUser($email, $token);
        if (empty($arrRes)) {
          $arrRes = array('status' => false, 'msg' => 'Error al restablecer contraseña');
        } else {
          $password = hash("SHA256", $password);
          $requestPass = $this->model->insertPassword($idUser, $password);
          if ($requestPass) {
            $arrRes = array('status' => true, 'msg' => 'Contraseña actualizada con éxito.');
          } else {
            $arrRes = array('status' => false, 'msg' => 'Error al restablecer contraseña, intente mas tarde.');
          }
        }
      }
    }
    echo json_encode($arrRes, JSON_UNESCAPED_UNICODE);
    die();
  }
}
