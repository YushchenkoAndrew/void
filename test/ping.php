<?php

printf("RUN: %s \t\t", PrettyPrint("[ping.php]", "YELLOW", true));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, VOID_URL);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_NOBODY, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$res = curl_exec($ch);

$header = [];
foreach (explode("\r\n", substr($res, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE))) as &$line) {
  if (strlen($line) == 0 || !strpos($line, ":")) continue;
  $header[substr($line, 0, ($pos = strpos($line, ":")))] = substr($line, $pos + 2);
}

curl_close($ch);

if ($header["X-Custom-Header"] === "pong") return PrettyPrint("PASS", "GREEN_BACKGROUND");
PrettyPrint("FAILED", "RED_BACKGROUND");
