<?php
class TokenModel extends Mysql
{
  public $intIdToken;
  public $strToken;
  public function __construct()
  {
    parent::__construct();
  }

  public function selectToken(string $token)
  {
    $this->strToken = $token;
    $sql = "SELECT * FROM token_security WHERE token = $this->strToken AND status = 1 AND user_id = 1";
    $request = $this->select($sql);
    return $request;
  }

  public function insertToken()
  {
    $this->strToken = mt_rand(100000, 999999);
    $query_insert = "INSERT INTO token_security (user_id, token) VALUES (?,?)";
    $arrData = array(1, $this->strToken);
    $return = $this->insert($query_insert, $arrData);
    return $return;
  }
  public function updateToken()
  {
    $sql = "UPDATE token_security SET status = ? WHERE user_id = 1";
    $arrData = array(0);
    $return = $this->update($sql, $arrData);
    return $return;
  }
}
