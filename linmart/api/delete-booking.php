<?php
header("Content-Type: application/json; charset=UTF-8");

$DATA_FILE = __DIR__ . "/data/bookings.json";

$raw  = file_get_contents("php://input");
$body = json_decode($raw, true);

$id = $body["id"] ?? "";
if (!$id) {
  http_response_code(400);
  echo json_encode(["message" => "id is required"]);
  exit;
}

$list = json_decode(@file_get_contents($DATA_FILE), true);
if (!is_array($list)) $list = [];

$before = count($list);
$list = array_values(array_filter($list, fn($b) => ($b["id"] ?? "") !== $id));

if (count($list) === $before) {
  http_response_code(404);
  echo json_encode(["message" => "Booking not found"]);
  exit;
}

file_put_contents($DATA_FILE, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(["status" => "ok"]);
