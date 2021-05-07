<?php
require_once('UsersModel.php');
class LoginModel extends Mysql
{
  private $idUser;
  private $user;
  private $password;
  private $token;
  public function __construct()
  {
    parent::__construct();
  }

  public function loginUser(string $user, string $password)
  {
    $this->user = $user;
    $this->password = $password;

    $sql = "SELECT id, status FROM users WHERE 
      (email = '$this->user' OR phone = '$this->user') AND 
      password = '$this->password' AND
      status != 0";
    $request = $this->select($sql);
    return $request;
  }

  public function sessionLogin(int $idUser)
  {
    $Users = new UsersModel;
    $this->idUser = $idUser;
    $request = $Users->selectUser($this->idUser);
    return $request;
  }

  public function getUserEmail(string $email)
  {
    $this->user = $email;
    $sql = "SELECT id, name, last_name, status FROM users WHERE email = '$this->user' AND status = 1";
    $request = $this->select($sql);
    return $request;
  }

  public function setTokenUser(int $id, string $token)
  {
    $this->idUser = $id;
    $this->token = $token;

    $sql = "UPDATE users SET token = ? WHERE id = $this->idUser ";
    $arrData = array($this->token);
    $request = $this->update($sql, $arrData);
    return $request;
  }

  public function getUser(string $email, string $token)
  {
    $this->user = $email;
    $this->token = $token;

    $sql = "SELECT id FROM users WHERE 
      email = '$this->user' AND 
      token = '$this->token' AND
      status = 1
    ";
    $request = $this->select($sql);
    return $request;
  }

  public function insertPassword(int $id, string $password)
  {
    $this->idUser = $id;
    $this->password = $password;

    $sql = "UPDATE users SET password = ?, token = ? WHERE id = $this->idUser";
    $arrData = array($this->password, "");
    $request = $this->update($sql, $arrData);
    return $request;
  }
}
