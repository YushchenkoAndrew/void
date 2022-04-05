<?php

if (!isset($_GET["t"])) {
  return reqHandler(400, "'t' parm not specified");
}

$ch = curl_init(getenv("DOCKER_URL") . "/images/{$_GET["t"]}/push");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "X-Registry-Auth: " . $_SERVER["HTTP_X_REGISTRY_AUTH"] ?? "",
]);

// Set unlimited execution time
set_time_limit(0); 

$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

include_once("./helper.php");
if ($res === false) return reqHandler(500, "Error: $err");
reqHandler(200, "Success", json_decode($res, true));
