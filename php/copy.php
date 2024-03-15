<?php
include_once("./helper.php");

$src = "/var/www$_SERVER[REQUEST_URI]";
if (!isPathOK($src) || !is_file($src)) {
  return reqHandler(404, "File not found");
}

parse_str(file_get_contents('php://input'), $_PUT);

if (!isset($_PUT["path"])) {
  return reqHandler(400, "'path' parm not specified");
}

$file = basename($_PUT["path"]);
$dst = @recursiveMkdir(dirname("/var/www/void$_PUT[path]"));

if ($_PUT["replace"] == true ? rename($src, "$dst/$file") : copy($src, "$dst/$file")) {
  return reqHandler(200, "Success");
}

include_once("./bot.php");
defaultLogs("php/copy", "Something went wrong with file copy");
return reqHandler(500, "Server side error");
