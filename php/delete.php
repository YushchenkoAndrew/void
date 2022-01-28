<?php
include_once("./helper.php");
include_once("./bot.php");

/**
 * Delete file by path
 * @param string Path to the file/directory
 * @return bool true on success or false on failure
 */
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
if (!isPathOK($path) || !file_exists($path)) {
  defaultLogs("php/delete", "Bad path: '$path'\nSomeone trying to snick");
  return reqHandler(404, "File not found");
}

if (delFiles($path)) return reqHandler(200, "Success");
defaultLogs("php/delete", "Error: " . error_get_last(), "For some reason can't delete this directory '$_GET[path]'");
return reqHandler(500, "For some reason can't delete this directory '$_GET[path]'");
