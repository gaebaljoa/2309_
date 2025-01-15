<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/todolist/src/");
    define("FILE_HEADER", ROOT."html/header.html");
    require_once(ROOT."lib/db_lib.php");

    $conn = null; // 기존 데이터 없도록 초기화
    $arr_post = []; // 챌린지 데이터 배열 초기화
    $http_method = $_SERVER["REQUEST_METHOD"]; 



    // DB 연동
    if(!my_db_conn($conn)) {
        throw new Exception("DB ERROR : PDO Instance");
    }
    var_dump($conn);

    try {
        // POST 일 때 처리 (챌린지 생성)
        if($http_method === "POST") {
            // 파라미터 획득
            // chk = c_id  ,  1 = $result[0]["c_name"] (무조건 첫번째 챌린지);
            $arr_post["chk"] = isset($_POST["chk"]) ? trim($_POST["chk"]) : "1";

            // 트랜잭션 시작
            $conn->beginTransaction();

            // 챌린지 생성 함수 호출
            $result = db_insert_create_at($conn, $arr_post);
            if($result === false) {
                throw new Exception("DB ERROR : Insert create_information");
            }

            // 모든 처리 완료 후 커밋
            $conn->commit();

            // 커밋 후 진행중 챌린지 페이지로 이동
            header("Location: /todolist/src/in-progress.php");
            // 페이지 이동 후 종료
            exit;
        }
    } catch(Exception $e) {
        // insert 작업 롤백
        $conn->rollback();
        // 에러메세지 출력
        echo $e->getMessage();
        exit;
    } finally {
        db_destroy_conn($conn);
    }

    // GET 일 때 처리 (챌린지 조회)
    // 챌린지 SELECT 함수 호출
    $result = db_select_challenge($conn);
    if($result === false) {
        throw new Exception("DB ERROR : Challenge info SELECT");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/insert.css">
    <title>insert</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <!-- insert 전체 영역 -->
    <div class="insert_container">
        <!-- 생성 할 수 있는 챌린지 목록 전체 form -->
        <form class="challenge_box_container" action="todolist/src/in-progress.php" method="post">
            <!-- 생성일자로 기입 될 현재 날짜 -->
            <p class="insert_create_at"><?php echo date("Y-m-d"); ?></p>
            <!-- 첫번째 챌린지 -->
            <input type="radio" name="c_id" id="c_id1" checked="checked" value="<?php echo $result[0]["c_id"]; ?>">
            <label for="c_id1" class="challenge_box">
                <h3><?php echo $result[0]["c_name"]; ?></h3>
                <br>
                <p><?php echo $result[0]["l_name"]; ?></p>
                <p><?php echo $result[1]["l_name"]; ?></p>
                <p><?php echo $result[2]["l_name"]; ?></p>
                <p><?php echo $result[3]["l_name"]; ?></p>
            </label>
            <!-- 두번째 챌린지 -->
            <input type="radio" name="c_id" id="c_id2" value="<?php echo $result[4]["c_id"] ?>">
            <label for="c_id2" class="challenge_box">
                <h3><?php echo $result[4]["c_name"]; ?></h3>
                <br>
                <p><?php echo $result[4]["l_name"]; ?></p>
                <p><?php echo $result[5]["l_name"]; ?></p>
                <p><?php echo $result[6]["l_name"]; ?></p>
                <p><?php echo $result[7]["l_name"]; ?></p>
            </label>
            <br>
            <!-- 세번째 챌린지 -->
            <input type="radio" name="c_id" id="c_id3" value="<?php echo $result[8]["c_id"] ?>">
            <label for="c_id3" class="challenge_box">
                <h3><?php echo $result[8]["c_name"]; ?></h3>
                <br>
                <p><?php echo $result[8]["l_name"]; ?></p>
                <p><?php echo $result[9]["l_name"]; ?></p>
                <p><?php echo $result[10]["l_name"]; ?></p>
                <p><?php echo $result[11]["l_name"]; ?></p>
            </label>
            <!-- 네번째 챌린지 -->
            <input type="radio" name="c_id" id="c_id4" value="<?php echo $result[12]["c_id"] ?>">
            <label for="c_id4" class="challenge_box">
                <h3><?php echo $result[12]["c_name"]; ?></h3>
                <br>
                <p><?php echo $result[12]["l_name"]; ?></p>
                <p><?php echo $result[13]["l_name"]; ?></p>
                <p><?php echo $result[14]["l_name"]; ?></p>
                <p><?php echo $result[15]["l_name"]; ?></p>
            </label>
            <br>
            <!-- 다섯번째 챌린지 -->
            <input type="radio" name="c_id" id="c_id5" value="<?php echo $result[16]["c_id"] ?>">
            <label for="c_id5" class="challenge_box">
                <h3><?php echo $result[16]["c_name"]; ?></h3>
                <br>
                <p><?php echo $result[16]["l_name"]; ?></p>
                <p><?php echo $result[17]["l_name"]; ?></p>
                <p><?php echo $result[18]["l_name"]; ?></p>
                <p><?php echo $result[19]["l_name"]; ?></p>
            </label>
            <!-- 챌린지 생성 확인 / 취소 버튼 -->
            <footer>
                <button class="insert_yesbtn" type="submit">확인</button>
                <a class="insert_nobtn" href="todolist/src/in-progress.php">취소</a>
            </footer>
        </form>
    </div>
</body>
</html>