<?php
    define("ROOT",$_SERVER["DOCUMENT_ROOT"]."/bamboo/src");
    define("FILE_HEADER", ROOT."/header.php");
    require_once(ROOT."/lib/lib_db.php");

    $conn = null;
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/css/common.css">
    <title>디테일 페이지</title>
</head>
<body>
    <header>
        <div class="header">
            <a href="list.php">대나무숲</a>
        </div>
    </header>
    <main>
        <div class="d_container">
            <div class="d_title">
                <span>제목 들어가는 영역 입니다</span>
            </div>
            <div class="d_content">
                <span>내용 들어가는 영역 입니단</span>
            </div>
            <div class="d_btn">
                <a href="list.php">목록</a>
                <a href="update.php">수정</a>
                <a href="delete.php">삭제</a>
            </div>
        </div>
    </main>
</body>
</html>