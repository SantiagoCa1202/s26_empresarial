<?php
class TokenModel extends Mysql
{
  public $idUser;
  public $token;
  public function __construct()
  {
    parent::__construct();
  }

  public function selectToken(string $token, int $idUser)
  {
    $this->token = $token;
    $this->idUser = $idUser;
    $sql = "SELECT * FROM token_security WHERE token = $this->token AND status = 1 AND user_id = $this->idUser";
    $request = $this->select($sql);
    return $request;
  }

  public function insertToken(int $idUser)
  {
    $this->idUser = $idUser;
    $this->token = mt_rand(100000, 999999);
    $query_insert = "INSERT INTO token_security (user_id, token) VALUES (?,?)";
    $arrData = array($this->idUser, $this->token);
    $return = $this->insert($query_insert, $arrData);
    return $return;
  }
  public function updateToken(int $idUser)
  {
    $this->idUser = $idUser;
    $sql = "UPDATE token_security SET status = ? WHERE user_id = $this->idUser";
    $arrData = array(0);
    $return = $this->update($sql, $arrData);
    return $return;
  }
}
