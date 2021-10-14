<?php

// Simple routing option
switch ($_SERVER["REQUEST_METHOD"]) {
  case "GET": return include_once("./read.php");
  case "POST": return include_once("./upload.php");
  case "DELETE": return include_once("./delete.php");

  default:
    include_once("./helper.php");
    reqHandler(405, "Unknown method");
}
