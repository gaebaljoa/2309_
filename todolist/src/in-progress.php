<?php
        define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/todolist/src/");
        define("FILE_HEADER", ROOT."html/header.html");
        define("FILE_STATUS", ROOT."status.php");
        define("FILE_CHALLENGE", ROOT."challenge_bar.php");
        require_once(ROOT."lib/db_lib.php");

    $conn = null;
    $http_method = $_SERVER["REQUEST_METHOD"]; // 요청받은 메소드 확인용
    $arr_post = []; // post 배열 초기화
    $arr_get = []; // get 배열 초기화 
    $arr_err_msg = []; // 에러메세지 배열 초기화


    try {
        // db 연동
        if(!my_db_conn($conn)) {
            throw new Exception("DB ERROR : PDO Instance");
        }
        
        // POST 메소드 일 때
        if($http_method === "POST") {
            // 파라미터 획득
            $arr_post["create_id"] = isset($_POST["create_id"]) ? trim($_POST["create_id"]) : "";
            $arr_post["l_id"] = isset($_POST["l_id"]) ? trim($_POST["l_id"]) : "";
            var_dump($arr_post);

            // 트랜잭션 시작
            $conn->beginTransaction();

            // 해당 챌린지의 완료 여부 조회
            $complete_list_chk = db_select_complete($conn, $arr_post);
            if($complete_list_chk === false) {
                throw new Exception("DB ERROR : Select challenge complete");
            }

            // 유저가 선택한 리스트의 완료일자 획득
            $complete_list = $complete_list_chk[0]["l_com_at".$arr_post["l_id"]];

            // 해당 리스트의 완료일자가 기존에 존재하는 경우
            if($complete_list != null) {
                // 해당 리스트의 중복된 완료 취소 처리
                if(db_update_cancel($conn, $arr_post) === false) {
                    throw new Exception("DB ERROR : Update cancel");
                }

                // 챌린지 완성도 퍼센트 획득
                $complete_count = db_select_complete_count($conn, $arr_post);
                if($complete_count === false) {
                    throw new Exception("DB ERROR : Select complete count");
                }

                // 해당 챌린지의 완성도가 4 미만인 경우(챌린지 미완료) (4이하이면 완료인데도 챌린지 미완료로 처리됨)
                if($complete_count[0]["cnt"] < 4) {
                    // 챌린지 완료일자 수정 (null 값으로)
                    if(db_update_null($conn, $arr_post) === false) {
                        throw new Exception("DB ERROR : Update null");
                    }
                }
            } 
            // 해당 리스트의 완료일자가 기존에 존재하지 않는 경우
            else {
                // 해당 리스트 완료 처리
                if(db_update_list($conn, $arr_post) === false) {
                    throw new Exception("DB ERROR : Update list complete");
                }

                // 해당 챌린지 완료 여부 조회
                $complete_list = db_select_complete($conn, $arr_post);
                if($complete_list === false) {
                    throw new Exception("DB ERROR : Select challenge complete");
                }

                // 해당 챌린지 완료 처리
                // 4개 리스트 모두 완료일자가 존재하는 경우
                if($complete_list[0]["l_com_at1"] != "" && $complete_list[0]["l_com_at2"] != "" && $complete_list[0]["l_com_at3"] != "" && $complete_list[0]["l_com_at4"] != "") {
                    // 파라미터 배열 생성
                    $complete_challenge = ["create_id" => $complete_list[0]["create_id"]];
                    // 챌린지 완료 처리
                    if(db_update_challenge($conn, $complete_challenge) === false) {
                        throw new Exception("DB ERROR : Update complete challenge");
                    }
                }
            }

            // 커밋
            $conn->commit();

            // post로 받아 온 배열 $arr_get 배열에 대입 (삭제 처리에 create_id 사용할 수 있음)
            $arr_get = $arr_post;
        }
        // GET 메소드 일 때
        else {
            // 가장 최근에 만들어진 진행중 챌린지 1개 조회
            $challenge_first = db_select_first($conn);
            if($challenge_first === false) {
                throw new Exception("DB ERROR : Select first challenge");
            }

            // $arr_get 배열에 create_id 값 대입

            // 챌린지바에서 GET 요청으로 create_id 넘어온 경우
            if(isset($_GET["create_id"])) {
                $arr_get["create_id"] = $_GET["create_id"];
            }
            // POST에서 받아온 값을 GET으로 처리하는 경우 (예:삭제페이지로 이동)
            else if(isset($arr_get["create_id"])) {
                $arr_get["create_id"] = $arr_get["create_id"];
            }
            // $_GET, $arr_get 모두 받아오지 못한 경우 (예:바로 in-progress 페이지로 접근했기 때문에)
            else {
                $arr_get["create_id"] = $challenge_first[0]["create_id"];
            }
        }

        // 챌린지가 존재하지 않는 경우 에러페이지로 이동

        // 해당 생성id의 챌린지 정보 조회
        $challenge_info = db_select_challenge($conn, $arr_get);
        if($challenge_info === false) {
            // 완전 비교하기 때문에 0, null 등의 falsy 한 값은 false로 처리되지 않음.
            throw new Exception("DB ERROR : Select Challenge error");
        }

        // 챌린지가 존재하지 않는 경우
        if(count($challenge_info) === 0) {
            $arr_err_msg[] = "error";
        }
        // 에러메세지가 존재하는 경우
        if(count($arr_err_msg) === 1) {
            header("Location: in-progress-no.php");
            exit;
        }

        // 챌린지 진행률 퍼센트 계산
        $list_per = db_select_percent($conn, $arr_get);
        var_dump($list_per[0]["per"]);
        if($list_per === false) {
            throw new Exception("DB ERROR : Select Challenge percent");
        }

    } catch(Exception $e) {
        // 트랜잭션이 시작된 경우에만 롤백
        if (isset($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }

        // 에러메세지 출력
        echo $e->getMessage();  

        // 종료
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
    <link rel="stylesheet" href="./css/in-progress.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Nanum+Pen+Script&family=Noto+Sans+KR:wght@300;400&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="./css/header.css">
	<link rel="stylesheet" href="./css/status.css">
	<link rel="stylesheet" href="./css/challenge_bar.css">
    <title>in-progress</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <?php
        require_once(FILE_STATUS);
    ?>
    <main>
        <div class="inprogress_container">
            <!-- POST폼 (리스트 버튼) -->
            <form class="form_inprogress" action="/todolist/src/in-progress.php" method="post">
                <!-- 챌린지 생성일자 -->
                <div class="create_id">
                    <p><?php echo $challenge_info[0]["DATE(cr.c_created_at)"] ?></p>
                </div>    

                <!-- 챌린지명 -->
                <div class="challenge_name">
                    <p><?php echo $challenge_info[0]["c_name"] ?></p>
                </div>

                <!-- 챌린지 진행바 -->
                <div class="progress_bar">
                    <progress value="<?php echo $list_per[0]["per"] ?>" max="100"></progress>
                </div>

                <!-- 리스트 버튼 -->
                <div class="inprogress_button">
                    <?php foreach($challenge_info as $item) { ?>
                        <input type="hidden" name="create_id" value="<?php echo $item["create_id"] ?>">
                        <!-- <button class="button-com" name="l_id" value="">
                            <p class="list_menu">스트래칭</p>
                            <p class="list_complete">1/1</p>
                        </button>
                        <button class="button-in" name="l_id" value="">
                            <p class="list_menu">물 마시기</p>
                            <p class="list_complete">0/1</p>
                        </button>
                        <button class="button-in" name="l_id" value="">
                            <p class="list_menu">햇볕 쬐기</p>
                            <p class="list_complete">0/1</p>
                        </button>
                        <button class="button-in" name="l_id" value="">
                            <p class="list_menu">아침식사</p>
                            <p class="list_complete">0/1</p>
                        </button> -->

                        <!-- 리스트 버튼 생성 (완료일자가 존재하는 경우) -->
                        <?php if($item["l_id"] == 1 && $item["l_com_at1"] != "") { ?>
                            <button type="submit" class="button-com" name="l_id" value="<?php echo $item["l_id"] ?>">
                        <?php } else if($item["l_id"] == 2 && $item["l_com_at2"] != "") { ?>
                            <button type="submit" class="button-com" name="l_id" value="<?php echo $item["l_id"] ?>">
                        <?php } else if($item["l_id"] == 3 && $item["l_com_at3"] != "") { ?>
                            <button type="submit" class="button-com" name="l_id" value="<?php echo $item["l_id"] ?>">
                        <?php } else if($item["l_id"] == 4 && $item["l_com_at4"] != "") { ?>
                            <button type="submit" class="button-com" name="l_id" value="<?php echo $item["l_id"] ?>">
                        <?php } else { ?>
                        <!-- 리스트 버튼 생성 (완료일자가 존재하지 않는 경우) -->
                            <button type="submit" class="button-in" name="l_id" value="<?php echo $item["l_id"] ?>">
                        <?php } ?>

                        <!-- 리스트 내 p태그 출력 -->
                                <!-- 리스트명 출력 -->
                                <p class="list_menu"><?php echo $item["l_name"] ?></p>
                                <!-- 리스트 완료도 출력 -->
                                <p class="list_complete">
                                    <?php if($item["l_id"] == 1 && $item["l_com_at1"] != "") {
                                        echo "1/1"; 
                                    } else if($item["l_id"] == 2 && $item["l_com_at2"] != "") {
                                        echo "1/1";
                                    } else if($item["l_id"] == 3 && $item["l_com_at3"] != "") {
                                        echo "1/1";
                                    } else if($item["l_id"] == 4 && $item["l_com_at4"] != "") {
                                        echo "1/1";
                                    } else {
                                        echo "0/1";
                                    } ?>
                                </p>
                            </button>
                    <?php } ?>
                </form>
            </div>

            <!-- POST폼 (삭제 버튼) -->
            <form class="trash_box" action="todolist/src/delete.php" method="post">
                <!-- 서버로 전송될 hidden 타입의 value (해당 챌린지의 create_id) -->
                <input type="hidden" value="">
                <button type="submit" class="trash"></button>
            </form>
        </div>
    </main>
    <?php
        // require_once(FILE_CHALLENGE);
    ?>
</body>
</html>