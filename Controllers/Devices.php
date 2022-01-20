<?php

class Devices extends Controllers
{
  public function __construct()
  {
    parent::__construct();

    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
    getPermits(41);
  }

  public function devices()
  {
    $this->views->getView($this, "devices");
  }
}
