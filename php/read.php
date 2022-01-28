<?php
include_once("./helper.php");

$path = "/var/www$_SERVER[REQUEST_URI]";
if (!isPathOK($path) || !file_exists($path)) {
  defaultLogs("php/delete", "Bad path: '$path'\nSomeone trying to snick");
  return include_once("./404.php");
}

if (is_file($path)) {
  header("Content-Type: " . mime_content_type($path));
  header("Content-Length: " . filesize($path));
  header("Cache-Control: must-revalidate");
  header("Pragma: public");
  echo file_get_contents($path, true);
  return;
}

return include_once("./autoindex.php");
