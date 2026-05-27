<?php
// php/auth.php

function getUsers(): array {
    $file = __DIR__ . '/../data/users.json';
    if (!file_exists($file)) { file_put_contents($file, '[]'); return []; }
    return json_decode(file_get_contents($file), true) ?? [];
}

function saveUsers(array $users): void {
    file_put_contents(__DIR__ . '/../data/users.json',
        json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function registerUser(string $username, string $email, string $password): array {
    $users = getUsers();
    foreach ($users as $u) {
        if (strtolower($u['email']) === strtolower($email))
            return ['success' => false, 'message' => 'Email-ul este deja înregistrat.'];
    }
    $users[] = [
        'id'         => uniqid('u_', true),
        'username'   => htmlspecialchars(trim($username), ENT_QUOTES),
        'email'      => strtolower(trim($email)),
        'password'   => password_hash($password, PASSWORD_DEFAULT),
        'role'       => 'guest',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    saveUsers($users);
    return ['success' => true];
}

function loginUser(string $email, string $password): array {
    foreach (getUsers() as $u) {
        if (strtolower($u['email']) === strtolower(trim($email))) {
            if (password_verify($password, $u['password']))
                return ['success' => true, 'user' => $u];
            return ['success' => false, 'message' => 'Parolă incorectă.'];
        }
    }
    return ['success' => false, 'message' => 'Email-ul nu există.'];
}

function isLoggedIn(): bool { return isset($_SESSION['user_id']); }

function requireLogin(): void {
    if (!isLoggedIn()) { header('Location: login.php?msg=nologin'); exit; }
}

function getCurrentUser(): ?array {
    if (!isLoggedIn()) return null;
    foreach (getUsers() as $u)
        if ($u['id'] === $_SESSION['user_id']) return $u;
    return null;
}