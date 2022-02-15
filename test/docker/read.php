<?php

printf("RUN: %s \t", PrettyPrint("[docker/read.php]", "YELLOW", true));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, VOID_URL . "/docker");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . base64_encode(getenv("VOID_AUTH"))]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$body = json_decode(curl_exec($ch), true);
curl_close($ch);

$passed = $body["status"] === "OK";
$passed &= count($body["result"]) !== 0;

if ($passed) return PrettyPrint("PASS", "GREEN_BACKGROUND");
PrettyPrint("FAILED", "RED_BACKGROUND");
