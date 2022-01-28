<?php

// Simple routing option
switch (uriToArr(null, 3)) {
  case "push": return include_once("./docker/push.php");
}

switch ($_SERVER["REQUEST_METHOD"]) {
  case "GET": return include_once("./docker/read.php");
  case "POST": return include_once("./docker/build.php");
  case "DELETE": return include_once("./docker/delete.php");
  
  default:
    reqHandler(405, "Unknown method");
    defaultLogs("php/docker", "I see, someone tries to sneak around");
}