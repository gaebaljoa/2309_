<?php
    define("ROOT",$_SERVER["DOCUMENT_ROOT"]."/bamboo/src/");
    define("FILE_HEADER", ROOT."/header.php");
    define("ERROR_MSG_PARAM", "%s : 필수 입력 사항입니다.");
    require_once(ROOT."/lib/lib_db.php");

    $conn = null;
    $http_method = $_SERVER["REQUEST_METHOD"]; // 요청받은 메소드 구별 용도
    var_dump($http_method);
    $arr_err_msg = [];
    $title = ""; // title 초기화
    $content = ""; // content 초기화

    // 요청받은 메소드가 POST인 경우에만 동작 실행. (GET으로 실행할게 없음)
    if($http_method === "POST") {
        try {
            // POST로 잘 요청됐는지 확인
            $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
            $content = isset($_POST["content"]) ? trim($_POST["content"]) : "";
            if($title === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "제목");
            }  
            if($content === "") {
                $arr_err_msg[] = sprintf(ERROR_MSG_PARAM, "내용");
            }
            var_dump($arr_err_msg);

            // DB 연동
            if(count($arr_err_msg) === 0) {
                // POST 요청을 제대로 받은 경우만 DB 연동
                if(!my_db_conn($conn)) {
                    throw new Exception("DB ERROR : PDO Instance");
                }
        
                // 트랜잭션 시작
                $conn->beginTransaction();
    
                // POST로 요청받은 데이터 INSERT
                $arr_param = [
                    "title" => $_POST["title"]
                    ,"content" => $_POST["content"]
                ];
                
                if(!db_insert_boards($conn, $arr_param)) {
                    throw new Exception("DB ERROR : Insert Boards");
                }
    
                $conn->commit();
    
                header("Location: list.php");
                exit;
            }

        } catch(Exception $e) {
            // db 연동이 안됨 = 트랜잭션 시작 이전, db 연동 후 false = 트랜잭션 시작 이후 에러 발생
            // db_insert_boards 함수 실패 시
            if($conn !== null) {
                $conn->rollback();
            }
            header("Location: /bamboo/src/error.php?err_msg={$e->getMessage()}");
            exit;
        } finally {
            db_destroy_conn($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/bamboo/src/css/common.css">
    <title>작성 페이지</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <main>
        <!-- 제목,내용 부재시 에러 메세지 출력 -->
         <div class="i_err_msg">
             <?php foreach($arr_err_msg as $val) { ?>
                 <b><?php echo $val; ?></b>
             <?php } ?>
         </div>
        <form action="/bamboo/src/insert.php" method="post">
            <div class="i_container">
                <div class="i_title">
                    <label for="title">제목</label>
                    <input type="text" name="title" id="title">
                </div>
                <div class="i_content">
                    <label for="content">내용</label>
                    <textarea name="content" id="content"></textarea>
                </div>
                <div class="i_btn">
                    <a href="/bamboo/src/list.php">취소</a>
                    <button type="submit">작성</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>