<?php
/**
 * Check if required path is satisfied all requirements
 * @param string $path directory path
 * @return bool is requested path is OK or NOT
 */
function isPathOK($path) {
  $dirs = explode("/", realpath($path));
  return $dirs[0] === "" && $dirs[1] === "var" && $dirs[2] === "www" && $dirs[3] === "files";
}

/**
 * Create quick request with default body
 * @param int $stat response code
 * @param string $message message which will be included in json
 * @param object $result any additional data
 */
function reqHandler($stat, $message, $result = []) {
  http_response_code($stat);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode(["status" => $message === "Success" ? "OK" : "ERR", "message" => $message, "result" => $result]);
}

/**
 * Create recursive directories
 * @param string $path directory path
 * @param string $base accumulated variable needed for recursion
 * @return string return final directory path
 */
function recursiveMkdir($path, $base = "/var/www/files") {
  $dirs = explode("/", trim($path, "/"));
  foreach ($dirs as &$dir) {
    if (empty($dir) || $dir === "..") continue;
    if (!is_dir($base .= "/" . $dir)) mkdir($base, 0777);
  }
  return $base;
}

/**
 * Convert URI into Array
 * @param string $uri optional variable
 * @param int $index get only value based on index
 * @return string[]|string|null return uri array or if parm index is specified then return the value
 */
function uriToArr($uri = null, $index = -1) {
 $arr = array_filter(explode("/", strtok($uri ?? $_SERVER["REQUEST_URI"], "?")), fn(&$item) => $item);
 return $index === -1 ? $arr : ($arr[$index] ?? null);
}

/**
 * Check if required params is specified
 * @param string[] $params required params
 * @return string|null return null if everything is ok or name of undefined param
 */
function checkParams($params = []) {
  foreach ($params as &$key) {
    if (!isset($_GET[$key])) return $key;
  }
  return null;
}