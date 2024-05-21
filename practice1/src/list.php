<?php
define("ROOT",$_SERVER["DOCUMENT_ROOT"]."/practice1/src/");
// ROOT = C:Apache24/htdocs/practice1/src/
require_once(ROOT."lib/lib_db.php");

// 페이징 처리
$list_cnt = 5;
$page_num = 1;
if(isset($_GET["page"])){
    $page_num = $_GET["page"];
};
$offset = ($page_num - 1) * $list_cnt;

// DB 조회 시 사용할 데이터배열
$arr_param = [
    "list_cnt" => $list_cnt
    ,"offset" => $offset
];

$conn = null;

// DB 접속
if(!my_db_conn($conn)) {
    echo "DB Error : PDO Instance";
    exit; // 여기 밑으로는 실행 안함
}

// 게시글 리스트 조회
$result = db_select_boards_paging($conn, $arr_param);
if(!$result) {
    echo "DB Error : SELECT boards";
    exit;
}

// DB 파기
db_destroy_conn($conn); 

// var_dump($result);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/practice1/src/css/common.css">
    <title>리스트 페이지</title>
</head>
<body>
    <header>
        <h1>Mini Board</h1>
    </header>
    <main>
        <table>
            <colgroup>
                <col width="20%">
                <col width="50%">
                <col width="30%">
            </colgroup>
            <tr class="table-title">
                <th >번호</th>
                <th>제목</th>
                <th>작성일자</th>
            </tr>
            <?php
                // 게시글 목록 생성
                foreach($result as $item) {
            ?>
                    <tr>
                        <td><?php echo $item["id"]; ?></td>
                        <td><?php echo $item["title"]; ?></td>
                        <td><?php echo $item["create_at"]; ?></td>
                    </tr>
            <?php
                } 
            ?>
        </table>
        <section>
            <a href="#">이전</a>
            <a href="/practice1/src/list.php/?page=1">1</a>
            <a href="/practice1/src/list.php/?page=2">2</a>
            <a href="/practice1/src/list.php/?page=3">3</a>
            <a href="/practice1/src/list.php/?page=4">4</a>
            <a href="/practice1/src/list.php/?page=5">5</a>
            <a href="#">다음</a>
        </section>
    </main>
</body>
</html>