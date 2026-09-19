<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

define('BASE_PATH', dirname(__DIR__, 2));

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'];

// CORREÇÃO: Removemos as barras iniciais e finais soltas para evitar a duplicação
if ($domain == 'localhost' || str_contains($domain, '192.168.') || $domain == '127.0.0.1') {
    $projectFolder = 'alldyneltdapp/'; // Apenas o nome da pasta com barra no final
} else {
    $projectFolder = ''; // Em produção na raiz do domínio, fica totalmente vazio
}

define('BASE_URL', $protocol . $domain . '/' . $projectFolder);

$host = "localhost";
$username = "root";
$password = "";
$database = "alldyne_db";

$conn = mysqli_connect($host, $username, $password, $database);

//FUNÇÃO AUXILIAR DE EXIBIÇÃO SEGURA DE DADOS
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>