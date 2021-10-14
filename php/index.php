<?php
function isPathOK($path) {
  $dirs = explode("/", realpath($path));
  return $dirs[0] === "" && $dirs[1] === "var" && $dirs[2] === "www" && $dirs[3] === "files";
}

function reqHandler($stat, $message) {
  http_response_code($stat);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode(["status" => $message === "Success" ? "OK" : "ERR", "message" => $message]);
}

function delFiles($path) {
  if (!file_exists($path)) return true;

  if (is_file($path)) return unlink($path);
  foreach (scandir($path) as $name) {
    if ($name === "." || $name === "..") continue;
    if (!delFiles("$path/$name")) return false;
  }

  return rmdir($path);
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $path = "/var/www$_SERVER[REQUEST_URI]";
  if (!isPathOK($path) || !file_exists($path)) {
    include_once("./404.php");
    exit;
  }

  if (is_file($path)) {
    header("Content-Type: " . mime_content_type($path));
    header("Content-Length: " . filesize($path));
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    echo file_get_contents($path, true);
    exit;
  }

  include_once("./autoindex.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (!isset($_GET["path"])) {
    reqHandler(400, "'path' parm not specified");
    exit;
  }

  $path = "/var/www/files$_GET[path]" . (substr($_GET["path"], -1) === "/" ? "" : "/");
  if (!isset($_FILES["file"])) {
    reqHandler(400, "File not uploaded with name 'file'");
    exit;
  }

  if (!file_exists($path)) mkdir($path, 0760, true);
  if (move_uploaded_file($_FILES["file"]["tmp_name"], $path . $_FILES["file"]["name"])) {
    reqHandler(201, "Success");
    exit;
  }

  reqHandler(500, "Server side error");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
  if (!isset($_GET["path"])) {
    reqHandler(400, "'path' parm not specified");
    exit;
  }

  $path = "/var/www/files$_GET[path]";
  if (!file_exists($path)) {
    reqHandler(404, "File not found");
    exit;
  }

  delFiles($path);

  reqHandler(200, "Success");
  exit;
}
