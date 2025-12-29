<?php
header("Content-Type: application/json; charset=UTF-8");

$DATA_DIR  = __DIR__ . "/data";
$DATA_FILE = $DATA_DIR . "/bookings.json";

if (!is_dir($DATA_DIR)) {
  mkdir($DATA_DIR, 0777, true);
}

if (!file_exists($DATA_FILE)) {
  file_put_contents($DATA_FILE, json_encode([]));
}

$raw = file_get_contents($DATA_FILE);
$list = json_decode($raw, true);
if (!is_array($list)) $list = [];

echo json_encode(["status" => "ok", "bookings" => $list]);
