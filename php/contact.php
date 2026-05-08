<?php
// ============================================
// contact.php  –  Saves contact messages to DB
// POST body (JSON): { name, email, message }
// ============================================

header('Content-Type: application/json');

$pdo  = require __DIR__ . '/db.php';
$data = json_decode(file_get_contents('php://input'), true);

$name    = htmlspecialchars(strip_tags(trim($data['name']    ?? '')));
$email   = htmlspecialchars(strip_tags(trim($data['email']   ?? '')));
$message = htmlspecialchars(strip_tags(trim($data['message'] ?? '')));

// Validate
if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}
if (strlen($message) < 10) {
    echo json_encode(['success' => false, 'message' => 'Message is too short.']);
    exit;
}

// Save to DB
$stmt = $pdo->prepare(
    'INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)'
);
$stmt->execute([$name, $email, $message]);

echo json_encode(['success' => true, 'message' => 'Message sent! We\'ll be in touch soon.']);
