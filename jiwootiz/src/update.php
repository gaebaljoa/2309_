<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    define("ERROR_MSG_PARAM", "%s : 필수 입력 사항입니다."); // 사용자에게 보여줄 에러메세지
    require_once(ROOT."lib/lib_db.php");

    $conn = null;
    $http_method = $_SERVER["REQUEST_METHOD"];
    $arr_err_msg = [];

    try {
        if(!jw_conn($conn)) {
            throw new Exception("DB Error : PDO Instance");
        }
        var_dump($conn);

        if($http_method === "GET") {
            // GET METHOD 일때 
            // var_dump($_GET);
            $id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];
            $page = isset($_GET["page"]) ? $_GET["page"] : $_POST["page"];

            if($id === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "id");
            }
            if($page === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "page");
            }
            if(count($arr_err_msg) >= 1) {
                throw new Exception(implode("<br>", $arr_err_msg));
            }
        } else {
            // POST METHOD 일 때
            $id = trim(isset($_POST["id"]) ? $_POST["id"] : "");
            $page = trim(isset($_POST["page"]) ? $_POST["page"] : "");
            $title = trim(isset($_POST["title"]) ? $_POST["title"] : "");
            $content = trim(isset($_POST["content"]) ? $_POST["content"] : "");

            if($id === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "id");
            }
            if($page === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "page");
            }
            if(count($arr_err_msg) >= 1) {
                throw new Exception(implode("<br>", $arr_err_msg));
            }

            $arr_err_msg = []; // 다시 초기화

            if(count($arr_err_msg) === 0) {
                $arr_param = [
                    "id" => $id
                    ,"title" => $title
                    ,"content" => $content
                ];

                $conn->beginTransaction();

                $result = db_update_boards_id($conn, $arr_param);
                
                if(!$result) {
                    throw new Exception("DB Error : UPDATE boards id");
                }

                $conn->commit();
                header("Location: detail.php?id={$id}&page={$page}");
                exit;
            }
        }

        $arr_param = [
            "id" => $id
        ];

        $result = db_select_boards_id($conn, $arr_param);

        if($result === false) {
            throw new Exception("DB Error : PDO Select id");
        } else if(!(count($result) === 1)) {
            throw new Exception("DB Error : PDO Select id count,".count($result));
        }

        $item = $result[0];

    } catch(Exception $e) {
        if($http_method === "POST") {
            $conn->rollback();
        }
        header("Location: /jiwootiz/src/error.php?err_msg={$e->getMessage()}");
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
    <link rel="stylesheet" href="jiwootiz/src/css/jiwootiz.css">
    <title>지우티즈</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <div class="main">
        <div class="i_err_msg">
        <?php foreach($arr_err_msg as $val) { ?> 
            <p class="err_msg"><?php echo $val; ?></p>
        <?php } ?>
        </div>
        <form action="/jiwootiz/src/update.php" method="post">
            <div class="d_container">
                <div class="d_header">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="page" value="<?php echo $page; ?>">
                    <label for="title">제목</label>
                    <input type="text" name="title" id="i_title" value="<?php echo $item["title"]; ?>">
                </div>
                <label for="content">내용</label>
                <div class="i_main">
                    <textarea name="content" id="d_content"><?php echo $item["content"]; ?></textarea>
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