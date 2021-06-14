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
}
