<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    require_once(ROOT."lib/lib_db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/jiwootiz.css">
    <title>지우티즈</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?> 

    <div class="main">
        <table>
            <tr>
                <th>글 번호</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일자</th>
            </tr>
            <tr>
                <td>1</td>
                <td>제목1</td>
                <td>글쓰니1</td>
                <td>24-08-28</td>
            </tr>
            <tr>
                <td>2</td>
                <td>제목2</td>
                <td>글쓰니2</td>
                <td>24-08-28</td>
            </tr>
            <tr>
                <td>3</td>
                <td>제목3</td>
                <td>글쓰니3</td>
                <td>24-08-28</td>
            </tr>
            <tr>
                <td>4</td>
                <td>제목4</td>
                <td>글쓰니4</td>
                <td>24-08-28</td>
            </tr>
            <tr>
                <td>5</td>
                <td>제목5</td>
                <td>글쓰니5</td>
                <td>24-08-28</td>
            </tr>
        </table>
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>