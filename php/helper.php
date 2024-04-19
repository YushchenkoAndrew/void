<?php
/**
 * Check if required path is satisfied all requirements
 * @param string $path directory path
 * @return bool is requested path is OK or NOT
 */
function isPathOK($path) {
  $dirs = explode("/", realpath($path));
  return $dirs[0] === "" && $dirs[1] === "var" && $dirs[2] === "www" && $dirs[3] === "void";
}


/**
 * Delete file by path
 * @param string Path to the file/directory
 * @return bool true on success or false on failure
 */
function delFiles($path) {
  clearstatcache();
  if (!file_exists($path)) return true;
  if (is_file($path)) return unlink($path);

  $dir = opendir($path);
  while(($name = readdir($dir)) !== false) {
    if ($name === "." || $name === "..") continue;
    if (!delFiles("$path/$name")) return false;
  }

  closedir($dir);
  clearstatcache();
  return rmdir($path);
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
function recursiveMkdir($path, $base = "") {
  $dirs = explode("/", trim(rtrim($path, "/"), "/"));
  foreach ($dirs as &$dir) {
    if (empty($dir) || $dir === "..") continue;
    if (!is_dir($base .= "/" . $dir)) mkdir($base, 0777);
  }
  return $base;
}

/**
 * Check if path is located in tmp dir
 * @param string $path directory path
 * @return bool return true if root path is /tmp
 */
function is_tmp($path) {
  $dirs = explode("/", trim($path, "/"));
  return $dirs[0] === "tmp";
}

/**
 * Create a temp path
 * @param string $path directory path
 * @param string $name file name
 * @param string $base accumulated variable needed for recursion
 * @return string return hashed temp file name
 */
function tmp_path($path, $name, $base = "/var/www/void/tmp") {
  if (!is_dir($base)) mkdir($base, 0777);

  $dirs = explode("/", trim(rtrim($path, "/"), "/"));
  return sprintf("%s/%s", $base, md5(implode("", array_slice($dirs, 1)) . $name));
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