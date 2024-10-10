<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    define("ERROR_MSG_PARAM", "%s : 필수 입력 사항입니다."); // 사용자에게 보여줄 에러메세지
    require_once(ROOT."lib/lib_db.php");

    $conn = null;

    $http_method = $_SERVER["REQUEST_METHOD"];
    var_dump($http_method);
    $arr_err_msg = [];

    $title = "";
    $content = "";

    if($http_method === "POST") {
        try {
            
        } catch {

        } finally {

        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="jiwootiz/src/css/jiwootiz.css">
    <title>지우티즈</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <div class="main">
        <form action="/jiwootiz/src/insert.php" method="post">
            <div class="d_container">
                <div class="d_header">
                    <label for="title">제목</label>
                    <input type="text" name="title" id="i_title" value="">
                </div>
                <label for="content">내용</label>
                <div class="i_main">
                    <textarea name="content" id="d_content"></textarea>
                </div>
            </div>
            <div class="d_btn">
                <a class="go_listbtn" href="/jiwootiz/src/list.php">취소</a>
                <button class="writerbtn" type="submit">작성</button>
            </div>
        </form>
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>