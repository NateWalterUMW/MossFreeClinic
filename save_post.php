<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db   = 'homebasedb';
$user = 'homebasedb';
$pass = 'homebasedb';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Get JSON as a string from the request's body
$json_str = file_get_contents('php://input');
$input = json_decode($json_str, TRUE);
$post_title = $input['post_title'];
$post_content = json_encode($input['post_content']);

$type = "blog";
$status = "published";

$stmt = $pdo->prepare("INSERT INTO em_posts (post_title, post_author, post_date, post_type, post_content, post_status) VALUES (:post_title, :post_author, NOW(), :post_type, :post_content, :post_status)");

$stmt->execute([
    'post_title' => $post_title,
    'post_author' => 1,
    'post_type' => $type,
    'post_content' => $post_content,
    'post_status' => $status,
]); 

echo "Post has been saved with ID: " . $pdo->lastInsertId();
?>