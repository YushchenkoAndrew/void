<?php
include_once("./helper.php");

if (!isset($_GET["path"])) {
  return reqHandler(400, "'path' parm not specified");
}

$path = "/var/www/files$_GET[path]" . (substr($_GET["path"], -1) === "/" ? "" : "/");
if (!isset($_FILES["file"])) {
  return reqHandler(400, "File not uploaded with name 'file' " . print_r(array_keys($_FILES), true));
}

if (!file_exists($path)) mkdir($path, 0760, true);
if (move_uploaded_file($_FILES["file"]["tmp_name"], $path . $_FILES["file"]["name"])) {
  return reqHandler(201, "Success");
}

return reqHandler(500, "Server side error");
