<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER",ROOT."/header.php");
    define("FILE_FOOTER",ROOT."/footer.php");
    define("ERROR_MSG_PARAM","Parameter Error : %s");
    require_once(ROOT."/lib/lib_db.php");

    $conn = null;
    $arr_err_msg = [];

    try {
        if(!jw_conn($conn)) {
            throw new Exception("DB ERROR : PDO Instance");
        }
        
        $http_method = $_SERVER["REQUEST_METHOD"];

        if($http_method === "GET") {
            $id = isset($_GET["id"]) ? $_GET["id"] : "";
            $page = isset($_GET["page"]) ? $_GET["page"] : "";

            if($id === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "id");
            }
            if($page === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "page");
            }
            if(count($arr_err_msg) >= 1) {
                throw new Exception(implode("<br>", $arr_err_msg));
            }

            $arr_param = [
                "id" => $id
            ];

            $result = db_select_boards_id($conn, $arr_param);

            if($result === false) {
                throw new Exception("DB ERROR : Select id");
            } else if(!(count($result) === 1)) {
                throw new Exception("DB ERROR : Select id Count");
            }

            $item = $result[0];
        } else {
            // POST 일 때
            $id = isset($_POST["id"]) ? $_POST["id"] : "";

            $arr_err_msg = [];

            if($id === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "id");
            }

            if(count($arr_err_msg) >= 1) {
                throw new Exception(implode("<br>", $arr_err_msg));
            }

            $conn->beginTransaction();

            $arr_param = [
                "id" => $id
            ];

            if(!db_delete_boards_id($conn, $arr_param)) {
                throw new Exception("DB ERROR : Delete Boards id");
            }

            $conn->commit();

            header("Location: list.php");
            exit;
        }
    } catch(Exception $e) {
        if($http_method === "POST") {
            $conn->rollback();
        }
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
    <link rel="stylesheet" href="./css/jiwootiz.css">
    <title>지우티즈</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <div class="main">
        <div class="d_warning">
            <b>정말 삭제하시겠습니까?</b>
        </div>
        <div class="d_container">
            <div class="d_header">
                <b class="d_title"><?php echo $item["title"] ?></b>
            </div>
            <div class="d_create_at">
                <span><?php echo $item["create_at"] ?></span>
            </div>
            <div class="d_main">
                <div class="d_content"><?php echo $item["content"] ?></div>
            </div>
        </div>
        <div class="d_btn">
            <form action="/jiwootiz/src/delete.php" method="post"> 
                <input type="hidden" name="id" value="<?php echo $id; ?>">               
                <a class="cancelbtn" href="/jiwootiz/src/detail.php?id=<?php echo $id ?>&page=<?php echo $page ?>">취소</a>
                <button class="deletebtn" type="submit">삭제</button>
            </form>
        </div>
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>