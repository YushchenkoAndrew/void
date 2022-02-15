<?php

define("COLORS", [
    "RESET" => "\033[0m",  // Text Reset

    "BLACK"=> "\033[0;30m",   // BLACK
    "RED"=> "\033[0;31m",     // RED
    "GREEN"=> "\033[0;32m",   // GREEN
    "YELLOW"=> "\033[0;33m",  // YELLOW
    "BLUE"=> "\033[0;34m",    // BLUE
    "PURPLE"=> "\033[0;35m",  // PURPLE
    "CYAN"=> "\033[0;36m",    // CYAN
    "WHITE"=> "\033[0;37m",   // WHITE

    "BLACK_BOLD"=> "\033[1;30m",  // BLACK
    "RED_BOLD"=> "\033[1;31m",    // RED
    "GREEN_BOLD"=> "\033[1;32m",  // GREEN
    "YELLOW_BOLD"=> "\033[1;33m", // YELLOW
    "BLUE_BOLD"=> "\033[1;34m",   // BLUE
    "PURPLE_BOLD"=> "\033[1;35m", // PURPLE
    "CYAN_BOLD"=> "\033[1;36m",   // CYAN
    "WHITE_BOLD"=> "\033[1;37m",  // WHITE

    "BLACK_UNDERLINED"=> "\033[4;30m",  // BLACK
    "RED_UNDERLINED"=> "\033[4;31m",    // RED
    "GREEN_UNDERLINED"=> "\033[4;32m",  // GREEN
    "YELLOW_UNDERLINED"=> "\033[4;33m", // YELLOW
    "BLUE_UNDERLINED"=> "\033[4;34m",   // BLUE
    "PURPLE_UNDERLINED"=> "\033[4;35m", // PURPLE
    "CYAN_UNDERLINED"=> "\033[4;36m",   // CYAN
    "WHITE_UNDERLINED"=> "\033[4;37m",  // WHITE

    "BLACK_BACKGROUND"=> "\033[40m",  // BLACK
    "RED_BACKGROUND"=> "\033[41m",    // RED
    "GREEN_BACKGROUND"=> "\033[42m",  // GREEN
    "YELLOW_BACKGROUND"=> "\033[43m", // YELLOW
    "BLUE_BACKGROUND"=> "\033[44m",   // BLUE
    "PURPLE_BACKGROUND"=> "\033[45m", // PURPLE
    "CYAN_BACKGROUND"=> "\033[46m",   // CYAN
    "WHITE_BACKGROUND"=> "\033[47m",  // WHITE
]);

/**
 * PrettyPrint will print message with specified color
 * @param string message print message
 * @param string key is a key from colors map
 * @param bool return is true then return prettified string
 * @return null|string if return is true then func will return prettified string
 */
function PrettyPrint($message, $key, $return = false) {
  if ($return) return sprintf("%s %s %s", COLORS[$key], $message, COLORS["RESET"]);
  print(sprintf("%s %s %s\n", COLORS[$key], $message, COLORS["RESET"]));
}