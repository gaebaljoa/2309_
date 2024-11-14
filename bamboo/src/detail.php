<?php
    define("ROOT",$_SERVER["DOCUMENT_ROOT"]."/bamboo/src");
    define("FILE_HEADER", ROOT."/header.php");
    require_once(ROOT."/lib/lib_db.php");

    $conn = null;
    $id = ""; // id 초기화

    try {
        // db 연동
        if(!my_db_conn($conn)) {
            throw new Exception("DB ERROR : PDO Instance");
        }

        // GET 요청을 받았는지 확인(없거나, 공백이면 안됨)
        if(!isset($_GET["id"]) || $_GET["id"] === "") {
            throw new Exception("Parameter ERROR : No id");
        }
        // GET으로 전달받았음
        $id = $_GET["id"];
        $page = $_GET["page"];

        // 받은 id값으로 게시글 조회
        $arr_param = [
            "id" => $id
        ];

        $result = db_select_boards_id($conn, $arr_param);
        // 쿼리문 결과값 잘 들어왔는지 확인, 예외처리
        if($result === false) {
            throw new Exception("DB ERROR : PDO Select id");
        }
        else if(count($result) !== 1) {
            throw new Exception("DB ERROR : PDO Select id count".count($result));
        }
        
        if(isset($result)) {
            $item = $result[0];
        } else {
            throw new Exception("DB ERROR : Invalid result");
        }
    } catch(Exception $e) {
        header("Location: error.php?err_msg={$e->getMessage()}");
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
    <link rel="stylesheet" href="/bamboo/src/css/common.css">
    <title>디테일 페이지</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <main>
        <div class="d_container">
            <div class="d_title">
                <span><?php echo $item["title"] ?></span>
            </div>
            <div class="d_content">
                <span><?php echo $item["content"] ?></span>
            </div>
            <div class="d_btn">
                <a href="/bamboo/src/list.php?page=<?php echo $page ?>">목록</a>
                <a href="/bamboo/src/update.php?id=<?php echo $id ?>">수정</a>
                <a href="/bamboo/src/delete.php?id=<?php echo $id ?>">삭제</a>
            </div>
        </div>
    </main>
</body>
</html>