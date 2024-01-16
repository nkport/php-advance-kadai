<?php
// データベース接続
$dsn = 'mysql:dbname=php_book_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    // 並び替えボタンを押したとき変数$orderに代入する
    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = NULL;
    }

    // キーワード検索
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }

    // orderパラメータの値によってSQL文を変更する
    if ($order === 'desc') {
        $sql = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at DESC';
    } else {
        $sql = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY updated_at ASC';
    }

    $stmt = $pdo->prepare($sql);

    $partial_match = "%{$keyword}%";
    $stmt->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

    // SQL文を実行
    $stmt->execute();

    // fetchAll()メソッドの戻り値
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    exit($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍管理アプリ</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="products">
            <h1>書籍一覧</h1>
            <?php
            if (isset($_GET['message'])) {
                echo "<p class='success'>{$_GET['message']}</p>";
            }
            ?>
            <div class="products-ui">
                <div>
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>">
                        <img src="images/desc.png" alt="降順に並び替え" class="sort-img">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="images/asc.png" alt="昇順に並び替え" class="sort-img">
                    </a>
                    <form action="read.php" method="get" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" class="search-box" placeholder="書籍名で検索" name="keyword" value="<?= $keyword ?>">
                    </form>
                </div>
                <a href="create.php" class="btn">書籍登録</a>
            </div>
            <table class="products-table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php
                foreach ($results as $result) {
                    $table_row = "
                        <tr>
                        <td>{$result['book_code']}</td>
                        <td>{$result['book_name']}</td>
                        <td>{$result['price']}</td>
                        <td>{$result['stock_quantity']}</td>
                        <td>{$result['genre_code']}</td>
                        <td><a href='update.php?id={$result['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
                        <td><a href='delete.php?id={$result['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></a></td>
                        </tr>
                    ";
                    echo $table_row;
                }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 書籍管理アプリ All rights reserved.</p>
    </footer>
</body>

</html>