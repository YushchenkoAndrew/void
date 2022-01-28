<?php
define("TAR_FILE", "/var/www/files/tmp.tar");
define("TAR_GZ_FILE", "/var/www/files/tmp.tar.gz");
include_once("./helper.php");

if (($param = checkParams(["path", "t"]))) {
  return reqHandler(400, "'$path' parm not specified");
}

$path = "/var/www/files$_GET[path]";
if (!isPathOK($path) || !file_exists($path)) {
  defaultLogs("php/delete", "Bad path: '$path'\nSomeone trying to snick");
  return reqHandler(404, "File not found");
}

$p = new PharData(TAR_FILE);
$p->buildFromDirectory($path);
$p->compress(Phar::GZ);

$ch = curl_init(getenv("DOCKER_URL") . "/build?" . http_build_query(["t" => $_GET["t"]]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/tar"]);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_INFILE, fopen(TAR_GZ_FILE, "r"));
curl_setopt($ch, CURLOPT_INFILESIZE, filesize(TAR_GZ_FILE));
curl_setopt($ch, CURLOPT_READFUNCTION, fn($ch, $fp, $len) => fgets($fp, $len));

$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

unlink(TAR_FILE);
unlink(TAR_GZ_FILE);

if ($res === false) return reqHandler(500, "Error: $err");
if ($res === null && isset($_GET["push"])) return include_once("./docker/push.php");
reqHandler(200, "Success", json_decode($res, true));