<?php

// Default test variables
define("VOID_URL", "http://127.0.0.1:8003/void");
define("DIR_PATH", "/path_test");
define("TEST_TAG", "grimreapermortis/demo2:demo");
include_once("./helper.php");

printf("%s\n\n", PrettyPrint("VOID SERVICE TEST BENCH", "BLUE_BACKGROUND", true));

if (!getenv("VOID_AUTH")) {
  return printf("%s FOR TEST PURPOSE PLEASE SET NEXT ENV VAR => 'VOID_AUTH=USER:PASS'\n", PrettyPrint("NOTE:", "GREEN_BACKGROUND", true));
}

// Run Test files
include_once("./ping.php");
include_once("./upload.php");

include_once("./docker/read.php");
include_once("./docker/build.php");

// TODO: Hmmm think about how to send Docker AUTH into env variables ....
// include_once("./docker/push.php");
include_once("./docker/delete.php");

include_once("./delete.php");