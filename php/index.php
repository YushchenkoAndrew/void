<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $path = "/var/www$_SERVER[REQUEST_URI]";
  $dirs = explode("/", realpath("/var/www$_SERVER[REQUEST_URI]$name"));
  if ($dirs[0] !== "" || $dirs[1] !== "var" || $dirs[2] !== "www" || $dirs[3] !== "files") exit;

  if (file_exists($path) && is_file($path)) {
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
  // TODO:
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
  // TODO:
}