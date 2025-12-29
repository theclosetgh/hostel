<?php
header("Content-Type: application/json; charset=UTF-8");

$DATA_DIR  = __DIR__ . "/data";
$DATA_FILE = $DATA_DIR . "/bookings.json";

if (!is_dir($DATA_DIR)) {
  mkdir($DATA_DIR, 0777, true);
}

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data)) {
  http_response_code(400);
  echo json_encode(["message" => "Invalid JSON body"]);
  exit;
}

$required = ["id","fullName","phone","school","roomType","moveInDate","duration","depositRef"];
foreach ($required as $k) {
  if (!isset($data[$k]) || trim((string)$data[$k]) === "") {
    http_response_code(400);
    echo json_encode(["message" => "Missing field: $k"]);
    exit;
  }
}

$list = [];
if (file_exists($DATA_FILE)) {
  $list = json_decode(file_get_contents($DATA_FILE), true);
  if (!is_array($list)) $list = [];
}

array_unshift($list, $data);

file_put_contents($DATA_FILE, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(["status" => "ok", "message" => "Saved", "id" => $data["id"]]);
