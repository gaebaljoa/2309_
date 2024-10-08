<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/jiwootiz/src/");
    define("FILE_HEADER", ROOT."header.php");
    define("FILE_FOOTER", ROOT."footer.php");
    require_once(ROOT."lib/lib_db.php");
    $conn = null;

    $id = "";

    try {
        if(!(jw_conn($conn))) {
            throw new Exception( "DB ERROR : PDO Instance" );
        }
        
        if(!isset($_GET["id"]) || $_GET["id"] === "") {
            throw new Exception( "Parameter ERROR : NO id" );
        }

        $id = $_GET["id"];

        $arr_param = [
            "id" => $id
        ];

        $result = db_select_boards_id($conn, $arr_param);

        if($result === false) {
            throw new Exception("DB ERROR : PDO Select_id");
        }
        else if(!(count($result) === 1)) {
            throw new Exception("DB ERROR : PDO Select_id count,".count($result));
        }

        if(isset($result[0])) {
            $item = $result[0];
        } else {
            throw new Exception("DB ERROR: Invalid result");
        }
    } 
    catch(Exception $e) {
        header("Location: error.php?err_msg={$e->getMessage()}");
        exit;
    }
    finally {
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
        <div class="d_container">
            <div class="d_header">
                <b class="d_title"><?php echo($item["title"]); ?></b>
            </div>
            <div class="d_create_at">
                <span><?php echo($item["create_at"]); ?></span>
            </div>
            <div class="d_main">
                <div class="d_content"><?php echo($item["content"]); ?></div>
            </div>
        </div>
        <div class="d_btn">
            <a class="go_listbtn" href="/jiwootiz/src/list.php">목록</a>
            <a class="updatebtn" href="/jiwootiz/src/update.php">수정</a>
            <a class="deletebtn" href="/jiwootiz/src/delete.php">삭제</a>
        </div>
    </div>
    <?php
        require_once(FILE_FOOTER);
    ?>
</body>
</html>