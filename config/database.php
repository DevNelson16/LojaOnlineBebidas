<?php
$host = 'localhost';
$baseDados = 'loja_bebidas';
$utilizador = 'root';
$palavraPasse = '';

$dsn = "mysql:host=$host;dbname=$baseDados;charset=utf8mb4";

$opcoes = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $utilizador, $palavraPasse, $opcoes);
} catch (PDOException $erro) {
    die('Não foi possível ligar à base de dados.');
}