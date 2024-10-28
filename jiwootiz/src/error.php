<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    require_once(ROOT."lib/lib_db.php");

    $conn = null;

    $err_msg = isset($_GET["err_msg"]) ? $_GET["err_msg"] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/jiwootiz/src/css/jiwootiz.css">
    <title>지우티즈</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <div class="main">
        <div class="e_header">
            <b>ERROR</b>
        </div>
        <div class="e_msg">
            <span>해당 페이지를 찾을 수 없습니다.</span>
        </div>
        <div class="e_errmsg">
            <span><?php echo($err_msg); ?></span>
        </div>
        <div class="d_btn">
            <a class="go_listbtn" href="/jiwootiz/src/list.php">목록</a>
        </div>
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>