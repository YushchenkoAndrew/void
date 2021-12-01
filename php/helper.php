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

function recursiveMkdir($path, $base = "/var/www/files") {
  $dirs = explode("/", trim($path, "/"));
  foreach ($dirs as &$dir) {
    if (empty($dir) || $dir === "..") continue;
    if (!is_dir($base .= "/" . $dir)) mkdir($base, 0777);
  }
  return $base;
}