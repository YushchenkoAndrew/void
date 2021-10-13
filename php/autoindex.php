<?php
function formatSize($bytes) {
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . " GB";
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . " MB";
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . " KB";
    return $bytes;
}

function sortDir($path) {
  $files = [];
  foreach (scandir($path) as $name) {
    if ($name === "." || $name === "..") continue;
    if (is_dir("$path/$name")) array_unshift($files, $name);
    else array_push($files, $name);
  }
  return $files;
}

function showDetails($name) {
  $path = "/var/www$_SERVER[REQUEST_URI]$name";

  $modified = date("d-M-Y H:i ", filemtime($path));
  $size = is_file($path) ? formatSize(filesize($path)) : "";

  $name .= is_file($path) ? "" : "/";
  $href = $_SERVER["REQUEST_URI"] . $name;
  return "<a href='$href'>$name</a>" . str_repeat(" ", 60 - strlen($name)) . $modified . str_repeat(" ", 25 - strlen($modified)) . $size;
}
?>

<html>

<head>
  <title>File Server</title>
</head>

<body>
  <h1>Index of <?= $_SERVER["REQUEST_URI"] ?></h1>
  <hr>

  <pre><?php
      echo "  <a href='$_SERVER[REQUEST_URI]..'>../</a><br>";
      foreach (sortDir("/var/www$_SERVER[REQUEST_URI]") as $name) {
        echo "  " . showDetails($name) . "<br>";
      }
      ?></pre>
  <hr>
</body>

</html>