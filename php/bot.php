<?php

// TODO:  Maybe one day. Once again, I'll use this POWER ...
function sendLogs($body) {
  $salt = rand(500, 100000);

  $ch = curl_init("http://localhost:3000/bot/logs/alert?key=" . md5($salt . getenv("BOT_KEY")));
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "X-Custom-Header: " . $salt,
  ]);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

  curl_exec($ch);
  curl_close($ch);
}
