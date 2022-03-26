<?php
include_once("./helper.php");

if (!isset($_GET["path"])) {
  return reqHandler(400, "'path' parm not specified");
}

if (!isset($_FILES["file"])) {
  return reqHandler(400, "File not uploaded with name 'file' " . print_r(array_keys($_FILES), true));
}

if (is_tmp($_GET["path"])) {
  move_uploaded_file($_FILES["file"]["tmp_name"], tmp_path($_GET["path"], $_FILES["file"]["name"]));
  return reqHandler(201, "Success");
}

$path = @recursiveMkdir($_GET["path"]);
if (move_uploaded_file($_FILES["file"]["tmp_name"], "$path/{$_FILES["file"]["name"]}")) {
  delFiles(tmp_path($_GET["path"], $_FILES["file"]["name"]));
  return reqHandler(201, "Success");
}

include_once("./bot.php");
defaultLogs("php/upload", "Something went wrong with file upload: {$_FILES["file"]["error"]}");
return reqHandler(500, "Server side error");
