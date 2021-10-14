<?php
include_once("./helper.php");

function delFiles($path) {
  if (!file_exists($path)) return true;
  if (is_file($path)) return unlink($path);

  foreach (scandir($path) as $name) {
    if ($name === "." || $name === "..") continue;
    if (!delFiles("$path/$name")) return false;
  }

  return rmdir($path);
}

if (!isset($_GET["path"])) {
  return reqHandler(400, "'path' parm not specified");
}

$path = "/var/www/files$_GET[path]";
if (!file_exists($path)) return reqHandler(404, "File not found");

delFiles($path);
return reqHandler(200, "Success");
