<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id=?");
$stmt->execute([$id]); $r = $stmt->fetch();
if(!$r){ http_response_code(404); echo json_encode(['error'=>'not found']); exit; }
header('Content-Type: application/json'); echo json_encode($r);
