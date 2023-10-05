<?php

define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/mini_board/src/"); // ROOT상수에 소스파일 경로 정의해놓음.
require_once(ROOT."./lib/lib_db.php"); // 정의한 ROOT상수/lib/lib_db.php 경로의 파일 불러옴 

$conn = null;
if(!my_db_conn($conn)) {
    // my_db_conn 메소드를 실행했는데 에러(false)가 뜬 경우에 if문 실행됨
    echo "DB Error : PDO Instance";     // 에러 메세지 출력함
    exit;      // exit 실행 되면 if문 밑에 있는 코드들은 모두 실행 안됨.
}

// 게시글 리스트 조회
$result = db_select_boards_paging($conn);
if(!$result) {
    // $result(db_select_boards_paging 메소드) 에러(false) 뜨면 if문 실행됨
    echo "DB Error : SELECT Boards";        // 에러 메세지 출력함
    exit;
}

db_destroy_conn($conn); // DB 파기

// var_dump($result);


?>



<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <title>리스트 페이지</title>
</head>
<body>
    <header>
       <h1>Mini Board</h1>
    </header>
    <main>
        <table>
        <colgroup>
            <col width="25%">
            <col width="50%">
            <col width="30%">
        </colgroup>
            <tr class="table_title">
                <th class="">번호</th>
                <th>제목</th>
                <th>작성일자</th>
            </tr>
            <?php
                // 리스트 생성
                foreach ($result as $item) {
                // $result(다차원배열) 하나하나 반복함
                // key는 인덱스(0,1,2...), value는 번호,제목,작성일자가 배열로 옴
            ?>
                <tr>
                <!-- 각각 인덱스의 value에는 id(번호),title(제목),create_at(작성일자)가 배열로 오는데,
                    이 배열을 echo로 출력함. 이 동작을 $result배열의 0번부터 끝번까지 반복해서 실행함-->
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
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
            <a href="#">다음</a>
        </section>
    </main>
</body>
</html>

