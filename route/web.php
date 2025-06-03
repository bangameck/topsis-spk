<?php
include_once __DIR__ . '/../config/controlWeb.php';
#RoutesWeb
function routes($m, $r)
{
  global $db;
  global $base_url;
  if (empty($m) && empty($r)) {
    return include "app/views/home/home.php";
    exit();
  } else {
    return include "app/views/$m/$r.php";
    exit();
  }
}
