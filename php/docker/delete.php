<?php

$ch = curl_init(getenv("DOCKER_URL") . "/images/prune?" . http_build_query([ "filters" => ["dangling" => "1"]]));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$res = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

include_once("./helper.php");

if ($res === false) return reqHandler(500, "Error: $err");
reqHandler(200, "Success", json_decode($res, true));
