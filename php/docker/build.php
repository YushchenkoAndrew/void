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

$ch = curl_init(getenv("DOCKER_URL") . "/build?" . http_build_query([
  // A name and optional tag to apply to the image in the name:tag format.
  // If you omit the tag the default latest value is assumed.
  // You can provide several t parameters.
  "t" => $_GET["t"],

  // Do not use the cache when building the image.
  "nocache" => true,

  // Remove intermediate containers after a successful build.
  "rm" => true,
]));

curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/tar"]);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_INFILE, fopen(TAR_GZ_FILE, "r"));
curl_setopt($ch, CURLOPT_INFILESIZE, filesize(TAR_GZ_FILE));
curl_setopt($ch, CURLOPT_READFUNCTION, fn($ch, $fp, $len) => fgets($fp, $len));
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
  print(($res = json_decode($data ?? "")) ? $res->stream : "");
  return strlen($data);
});


// This header will disable fastcgi_buffering and gzip
header("X-Accel-Buffering: no");
header("Cache-Control: no-cache, must-revalidate");

// Set unlimited execution time
set_time_limit(0); 

// Send buffer ctx immediately and delete topmost buffer
ob_implicit_flush();
ob_end_flush();

$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

unlink(TAR_FILE);
unlink(TAR_GZ_FILE);

// if ($res === false) return reqHandler(500, "Error: $err");
// reqHandler(200, "Success", json_decode($res, true));
