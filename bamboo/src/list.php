<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/bamboo/src/");
    define("FILE_HEADER", ROOT."header.php");
    require_once(ROOT."lib/lib_db.php");

    $conn = null;
    $list_cnt = 5; // 한 페이지에 출력할 게시글 수 
    $page_num = 1; // 페이지 초기화

    try {
        // DB 연동
        if(!my_db_conn($conn)) {
            throw new Exception("DB ERROR : PDO Instance");
        }
        
        // 페이지를 위한 boards count select 
        $boards_cnt = db_select_boards_cnt($conn);
        if($boards_cnt === false) {
            throw new Exception("DB ERROR : Select count");
        }

        // 끝 페이지 구하기
        $max_page_num = ceil($boards_cnt / $list_cnt);

        // 현재 페이지 구하기
        $page_num = isset($_GET["page"]) ? $_GET["page"] : 1;

        // 오프셋 구하기(몇번째부터 가져올건지)
        $offset = ($page_num - 1) * $list_cnt;

        // 이전, 다음 페이지 구하기
        $prev_page_num = $page_num - 1;
        if($prev_page_num === 0) {
            $prev_page_num = 1;
        }

        $next_page_num = $page_num + 1;
        if($next_page_num > $max_page_num) {
            $next_page_num = $max_page_num;
        }

        // 게시글 조회
        $arr_param = [
            "list_cnt" => $list_cnt
            ,"offset" => $offset
        ];

        $result = db_select_boards_paging($conn, $arr_param);
        if(!$result) {
            throw new Exception("DB ERROR : Select boards");
        }
    } catch(Exception $e) {
        echo $e->getMessage();
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
    <title>리스트 페이지</title>
</head>
<body>
    <?php
        require_once(FILE_HEADER);
    ?>
    <main>
        <div class="insertbtn">
            <a href="/bamboo/src/insert.php">글 작성</a>
        </div>
        <div class="list">
            <table>
                <colgroup>
                    <col width="30%">
                    <col width="40%">
                    <col width="30%">
                </colgroup>
                <tr>
                    <th>글 번호</th>
                    <th>제목</th>
                    <th>작성일자</th>
                </tr>
                <?php
                    foreach($result as $item) {
                ?>
                    <tr>
                        <td>
                            <a href="/bamboo/src/detail.php?id=<?php echo $item["id"] ?>&page=<?php echo $page_num;?>">
                                <?php echo $item["id"] ?>
                            </a>
                        </td>
                        <td>
                            <a href="/bamboo/src/detail.php?id=<?php echo $item["id"] ?>&page=<?php echo $page_num;?>">
                                <?php echo $item["title"] ?>
                            </a>
                        </td>
                        <td><?php echo $item["create_at"] ?></td>
                    </tr>
                <?php
                    }
                ?>
            </table>
        </div>
        <div class="pagebtn">
            <a href="/bamboo/src/list.php?page=<?php echo $prev_page_num ?>">이전</a>
            <?php 
                for($i = 1; $i <= $max_page_num; $i++) {
            ?>
                <a href="/bamboo/src/list.php?page=<?php echo $i ?>"><?php echo $i ?></a>
            <?php 
                } 
            ?>
            <a href="/bamboo/src/list.php?page=<?php echo $next_page_num ?>">다음</a>
        </div>
    </main>
</body>
</html>