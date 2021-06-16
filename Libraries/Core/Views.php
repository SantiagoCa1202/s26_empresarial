<?php
class Views
{
  function getView($controller, $view, $data = "")
  {
    $controller = get_class($controller);

    if ($controller == "Dashboard") {
      $view = "Views/" . $view . ".php";
    } else {
      if (file_exists("Views/" . $view . ".php")) {
        $view = "Views/" . $view . ".php";
      } else {
        $view = "Views/" . $controller . "/" . $view . ".php";
      }
    }

    require_once($view);
  }

  function exportData($file, $data = "")
  {

    if (file_exists("Export/" . $file . ".php")) {
      $export = "Export/" . $file . ".php";
      require_once($export);
    } else {
      return false;
    }
  }
}
