<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    define("ERROR_MSG_PARAM", "Parameter Error : %s");
    require_once(ROOT."lib/lib_db.php");
    
    // var_dump($_SERVER["REQUEST_METHOD"]);

    $conn = null;
    $list_cnt = 5;
    $page_num = 1;
    $arr_err_msg = [];

    try {
        if(!jw_conn($conn)) {
            throw new Exception("DB Error : PDO Instance");
        }

        $boards_cnt = db_select_boards_cnt($conn);
        if($boards_cnt === false) {
            throw new Exception("DB Error : SELECT Count");
        }

        $max_page_num = ceil($boards_cnt / $list_cnt);

        $page_num = isset($_GET["page"]) ? $_GET["page"] : "1";
        if($page_num === "") {
            $arr_err_msg[] = sprintf(ERROR_MSG_PARAM,"page");
        }
        if(count($arr_err_msg) >= 1) {
            throw new Exception(implode("<br>", $arr_err_msg));
        }

        $offset = ($page_num - 1) * $list_cnt;

        $prev_page_num = $page_num - 1;
        if($prev_page_num === 0) {
            $prev_page_num = 1;
        }

        $next_page_num = $page_num + 1;
        if($next_page_num > $max_page_num) {
            $next_page_num = $max_page_num;
        }

        $arr_param = [
            "list_cnt" => $list_cnt
            ,"offset" => $offset
        ];
        $result = db_select_boards_paging($conn, $arr_param);

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
    <link rel="stylesheet" href="/jiwootiz/src/css/jiwootiz.css">
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
        <div>
            <a href="/jiwootiz/src/list.php/?page=<?php echo $prev_page_num ?>">이전</a>
            <?php
                for($i = 1; $i <= $max_page_num; $i++){
            ?>
                    <a href="/jiwootiz/src/list.php/?page=<?php echo $i ?>"><?php echo $i ?></a>
            <?php
                }
            ?>
            <a href="/jiwootiz/src/list.php/?page=<?php echo $next_page_num ?>">다음</a>
        </div>
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>