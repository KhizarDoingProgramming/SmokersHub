<?php
// ============================================
// auth.php  –  Handles register & login.
// Called by form.html via fetch (JSON API).
// POST body: { action, name?, email, password }
// ============================================

error_reporting(0);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$db_file = __DIR__ . '/db.php';
if (!file_exists($db_file)) {
    echo json_encode(['success' => false, 'message' => 'Internal Server Error: db.php missing']);
    exit;
}

$pdo = require $db_file;

$input = file_get_contents('php://input');
$data  = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {

    $data = $_POST;
}

$action = $data['action'] ?? '';

// ── Helper ──────────────────────────────────
function respond(bool $success, string $message, array $extra = []): void {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

function sanitize(string $val): string {
    return htmlspecialchars(strip_tags(trim($val)));
}

// ── Route ───────────────────────────────────
match ($action) {
    'register' => handleRegister($pdo, $data),
    'login'    => handleLogin($pdo, $data),
    'logout'   => handleLogout(),
    'me'       => handleMe(),
    default    => respond(false, 'Unknown action.')
};

// ── Register ────────────────────────────────
function handleRegister(PDO $pdo, array $data): void {
    $username = sanitize($data['name']     ?? '');
    $email    = sanitize($data['email']    ?? '');
    $password =          $data['password'] ?? '';

    if (!$username || !$email || !$password) {
        respond(false, 'All fields are required.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(false, 'Invalid email address.');
    }
    if (strlen($password) < 6) {
        respond(false, 'Password must be at least 6 characters.');
    }


    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        respond(false, 'An account with that email already exists.');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

    $stmt = $pdo->prepare(
        'INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$username, $email, $hash, 'user']);

    $userId = (int) $pdo->lastInsertId();

    $_SESSION['user_id']   = $userId;
    $_SESSION['user_name'] = $username;
    $_SESSION['user_role'] = 'user';

    respond(true, 'Account created successfully!', [
        'user' => ['id' => $userId, 'name' => $username, 'role' => 'user']
    ]);
}

// ── Login ───────────────────────────────────
function handleLogin(PDO $pdo, array $data): void {
    $email    = sanitize($data['email']    ?? '');
    $password =          $data['password'] ?? '';

    if (!$email || !$password) {
        respond(false, 'Email and password are required.');
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    $hash = $user['password'] ?? '$2y$12$invalidhashpadding000000000000000000000000000000000000';

    if (!$user || !password_verify($password, $hash)) {
        respond(false, 'Invalid email or password.');
    }

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];

    respond(true, 'Welcome back, ' . $user['username'] . '!', [
        'user' => [
            'id'   => $user['id'],
            'name' => $user['username'],
            'role' => $user['role'],
        ]
    ]);
}

// ── Logout ──────────────────────────────────
function handleLogout(): void {
    session_unset();
    session_destroy();
    respond(true, 'Logged out successfully.');
}

// ── Current user ────────────────────────────
function handleMe(): void {
    if (empty($_SESSION['user_id'])) {
        respond(false, 'Not logged in.');
    }
    respond(true, 'OK', [
        'user' => [
            'id'   => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
        ]
    ]);
}
