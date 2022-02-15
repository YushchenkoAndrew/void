<?php

printf("RUN: %s \t", PrettyPrint("[docker/push.php]", "YELLOW", true));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, VOID_URL . "/docker/push?t=" . TEST_TAG);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Basic " . base64_encode(getenv("VOID_AUTH")),
  "X-Registry-Auth: " . base64_encode(getenv("DOCKER_AUTH"))
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$body = json_decode(curl_exec($ch), true);
curl_close($ch);

if ($body["status"] === "OK") return PrettyPrint("PASS", "GREEN_BACKGROUND");
PrettyPrint("FAILED", "RED_BACKGROUND");
