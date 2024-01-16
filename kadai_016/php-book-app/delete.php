<?php
// データベース接続
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    $sql_delete = 'DELETE FROM books WHERE id = :id';
    $stmt_delete = $pdo->prepare($sql_delete);

    // 実際の値をプレースホルダに割り当てる
    $stmt_delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    // SQL文を実行
    $stmt_delete->execute();

    // 削除件数をメッセージ表示
    $count = $stmt_delete->rowCount();
    $message = "商品を{$count}件削除しました。";

    header("Location: read.php?message={$message}");
} catch (PDOException $e) {
    exit($e->getMessage());
}