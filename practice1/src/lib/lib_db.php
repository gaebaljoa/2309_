<?php
// **********************************
// 함수명     : my_db_conn
// 기능       : DB Connect
// 파라미터   : PDO &$conn
// 리턴       : 없음
// **********************************

function my_db_conn( &$conn ){
	$db_host = "localhost";  // host (ip)
	$db_user = "root";  // user
	$db_pw = "php504";  // password
	$db_name = "mini_board";  // DB name
	$db_charset = "utf8mb4";  // charset
	$db_dns = "mysql:host=".$db_host.";dbname=".$db_name.";chartset=".$db_charset;   // 바뀔 수 있는 부분을 변수로

    try {
        $db_options = [
            // DB의 Prepared Statement 기능을 사용하도록 설정
            PDO::ATTR_EMULATE_PREPARES 		=> false	// static 변수라서 instance(클래스 생성) 안해줘도 클래스 사용가능
            // PDO Exception을 Throws하도록 설정
            ,PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION
            // 연상배열로 Fetch를 하도록 설정
            ,PDO::ATTR_DEFAULT_FETCH_MODE  	=> PDO::FETCH_ASSOC
        ];

        // PDO Class로 DB 연동 
        $conn = new PDO($db_dns, $db_user, $db_pw, $db_options); // (PDO 객체 생성)
        return true;
    } catch (Exception $e){
        $conn = null; // DB 파기
        return false;
    }

}

// ------------------------------
// 함수명	: db_destroy_conn
// 기능		: DB Destroy
// 파라미터	: PDO &$conn
// 리턴		: 없음
// ------------------------------

function db_destroy_conn(&$conn){
	$conn = null;
}

// ------------------------------
// 함수명	: db_select_boards_paging
// 기능		: boards paging 조회
// 파라미터	: PDO   &$conn
//            Array   &$arr_param 쿼리 작성용 데이터배열
// 리턴		: Array / False
// ------------------------------
function db_select_boards_paging(&$conn, &$arr_param){
    try {
        $sql = 
        " SELECT "
        ."      id"
        ."      ,title"
        ."      ,create_at"
        ." FROM "
        ."      boards "
        ." ORDER BY "
        ."      id DESC "
        ." LIMIT :list_cnt OFFSET :offset "
        ;

        $arr_ps = [
            ":list_cnt" => $arr_param["list_cnt"]
            ,":offset" => $arr_param["offset"]
        ];
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_ps);
        $result = $stmt->fetchAll();
        return $result;
    } catch(Exception $e) {
        return false;
    }
}

?>