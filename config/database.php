<?php
/**
 * Kredensial koneksi ke database MySQL
 */

$db_config = [
    'host'     => 'localhost',
    'port'     => 3306,
    'database' => 'bkkk_nexus',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['database']};charset={$db_config['charset']}",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
