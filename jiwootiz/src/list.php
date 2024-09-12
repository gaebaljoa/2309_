<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    require_once(ROOT."lib/lib_db.php");
    
    // var_dump($_SERVER["REQUEST_METHOD"]);

    $conn = null;
    // $list_cnt = 5; // 한 페이지에 글 5개
    // $page_num = 1; // 페이지 num 초기화

    try {
        if(!jw_conn($conn)) {
            throw new Exception("DB Error : PDO Instance");
        }

        // $boards_cnt = db_select_boards_cnt($conn);

        $result = db_select_boards_paging($conn);

        if(!$result) {
            throw new Exception("DB Error : SELECT boards"); 
        }
    } catch(Exception $e) {
        echo $e->getMessage(); 
        error_log($e->getMessage());  // 에러 로그에 기록
        echo "문제가 발생했습니다. 나중에 다시 시도해주세요.";  // 사용자에게는 간단한 메시지 제공
        exit;
    } finally {
        db_destroy_conn($conn);
    }
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
            <?php 
                foreach($result as $item) {
            ?>
                <tr>
                    <td><?php echo $item["id"]; ?></td>
                    <td><?php echo $item["title"]; ?></td>
                    <td><?php echo $item["writer"]; ?></td>
                    <td><?php echo $item["create_at"]; ?></td>
                </tr>
            <?php
                }
            ?>
        </table>
        <!-- <div>
            <a href=""></a>
        </div> -->
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>