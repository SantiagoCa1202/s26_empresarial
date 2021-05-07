<?php

class Dashboard extends Controllers
{
  public function __construct()
  {
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    parent::__construct();
  }

  public function dashboard()
  {
    $this->views->getView($this, "dashboard");
  }
  public function userData()
  {
    if (!empty($_SESSION['login'])) {
      echo json_encode($_SESSION['userData'], JSON_UNESCAPED_UNICODE);
    }
    die;
  }
}
