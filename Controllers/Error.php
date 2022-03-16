<?php

class Errors extends Controllers
{
  public function __construct()
  {
    parent::__construct();
    session_start();
    if (empty($_SESSION['login'])) {
      header('Location: ' . base_url() . '/login');
    }
  }

  public function notFound()
  {
    $this->views->getView($this, "error");
  }
}
$notFound = new Errors();
$notFound->notFound();
