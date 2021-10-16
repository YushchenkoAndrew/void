<?php

// Simple routing option
switch ($_SERVER["REQUEST_METHOD"]) {
  case "HEAD": return include_once("./ping.php");
  case "GET": return include_once("./read.php");
  case "POST": return include_once("./upload.php");
  case "DELETE": return include_once("./delete.php");

  default:
    include_once("./helper.php");
    reqHandler(405, "Unknown method");
}
