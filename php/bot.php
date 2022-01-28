<?php

/**
 * Use bot as logging for 'void'
 * TODO:  Maybe one day. Once again, I'll use this POWER ...
 * @param object $body this body will be sended to bot
 */
function sendLogs($body) {
  $salt = rand(500, 100000);

  $ch = curl_init(getenv("BOT_URL") . "/bot/logs/alert?key=" . md5($salt . getenv("BOT_KEY")));
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

/**
 * Create and send default error log to bot
 * @param string $file File where the err/info is from
 * @param string $err error message
 * @param string|null $message small description of situation
 */
function defaultLogs($file, $err, $message = null) {
  sendLogs([
    "stat" => "ERR",
    "name" => "VOID",
    "file" => $file,
    "message" => $message ?? "Ohhh noo something is broken at File Server",
    "desc" => $err,
  ]);
}