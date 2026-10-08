<?php
require_once __DIR__ . '/../config/database.php';

$utilizador = 'admin';
$palavraPasse = 'admin123';

$hash = password_hash($palavraPasse, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    'INSERT INTO administradores (utilizador, palavra_passe) VALUES (:utilizador, :palavra_passe)'
);
$stmt->execute([
    'utilizador' => $utilizador,
    'palavra_passe' => $hash,
]);

echo 'Administrador criado. APAGA este ficheiro agora!';