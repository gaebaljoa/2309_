<?php

define("ROOT", $_SERVER["DOCUMENT_ROOT"]."/mini_board/src/"); // ROOT상수에 소스파일 경로 정의해놓음.
require_once(ROOT."./lib/lib_db.php"); // 정의한 ROOT상수/lib/lib_db.php 경로의 파일 불러옴 

// 페이징 처리
$list_cnt = 5; // 한 페이지에 게시글 몇개 표시할건지 변수로 설정해놓음
$page_num = 1; // 처음에 페이지는 1로 설정되어있음 (처음에 페이지 열 때 몇페이지인지 설정해준거임)
if(isset($_GET["page"])) {
    // $_GET으로 불러온 연상배열의 Key("page")에 value가 있는지 확인하고
    $page_num = $_GET["page"];  // value가 있으면 그 값을 몇페이지인지에 설정해줌 ex) $_GET["page"]의 value가 2이면 2페이지가 됨.
}
$offset = ($page_num - 1) * $list_cnt;
// offset = 데이터 시작하는 행. 일일이 설정해 줄 수 없으므로 식을 만들어서 대입함
//          (현재페이지 - 1) * 한 페이지에 표시되는 게시글 수
//          ex) (3 - 1) * 5 = 2 * 5 = 10
//              3페이지는 10번째 게시글부터 보여지는 것임.
//          1페이지는 0번부터 4번게시글까지(게시글 5개씩이니까) 2페이지는 5번부터 9번게시글까지 3페이지는 10번부터 14번게시글까지.

// DB 조회 시 사용할 데이터 배열
$arr_param = [
    "list_cnt" => $list_cnt
    ,"offset" => $offset
];

$conn = null;
// DB 접속
if(!my_db_conn($conn)) {
    // my_db_conn 메소드를 실행했는데 에러(false)가 뜬 경우에 if문 실행됨
    echo "DB Error : PDO Instance";     // 에러 메세지 출력함
    exit;      // exit 실행 되면 if문 밑에 있는 코드들은 모두 실행 안됨.
}

// 게시글 리스트 조회
$result = db_select_boards_paging($conn, $arr_param);   // $arr_param은 아규먼트.

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
    <link rel="stylesheet" href="/mini_board/src/css/common.css">
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
            <a href="/mini_board/src/list.php/?page=1">1</a>
            <a href="/mini_board/src/list.php/?page=2">2</a>
            <a href="/mini_board/src/list.php/?page=3">3</a>
            <a href="/mini_board/src/list.php/?page=4">4</a>
            <a href="/mini_board/src/list.php/?page=5">5</a>
            <a href="#">다음</a>
            <!-- a태그는 페이지버튼에 설정되어있음.
                url의 파라미터?에 key("page")의 value로 몇페이지인지 설정됨
                page=1이면 1페이지로 이동하는것임. -->
        </section>
    </main>
</body>
</html>

