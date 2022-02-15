<?php

printf("RUN: %s \t", PrettyPrint("[docker/build.php]", "YELLOW", true));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, VOID_URL . "/docker?path=" . DIR_PATH . "&t=" . TEST_TAG);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . base64_encode(getenv("VOID_AUTH"))]);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ["file" => curl_file_create("Dockerfile", "text/plain")]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$res = curl_exec($ch);
curl_close($ch);

$expected = [
  "Step 1/1 : FROM nginx",
  " ---> c316d5a335a5",
  "Successfully built c316d5a335a5",
  "Successfully tagged grimreapermortis/demo2:demo",
  "",
];

$passed = true;
foreach (explode("\n", $res) as $i => &$item) {
  $passed &= $expected[$i] === $item;
}

if ($passed) return PrettyPrint("PASS", "GREEN_BACKGROUND");
PrettyPrint("FAILED", "RED_BACKGROUND");
