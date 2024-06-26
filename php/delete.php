<?php
include_once("./helper.php");
include_once("./bot.php");

// TODO: 
// * Check if path is containing /tmp dir
// * If so then check the difference between curr time and file's last updated
// * If the time is expired then just delete the file

$path = "/var/www$_SERVER[REQUEST_URI]";
if (!isPathOK($path) || !file_exists($path)) {
  defaultLogs("php/delete", "Bad path: '$path'\nSomeone trying to snick");
  return reqHandler(404, "File not found");
}

if (delFiles($path)) return reqHandler(200, "Success");
defaultLogs("php/delete", "Error: " . error_get_last(), "For some reason can't delete this directory '$_SERVER[REQUEST_URI]'");
return reqHandler(500, "For some reason can't delete this directory '$_SERVER[REQUEST_URI]'");
