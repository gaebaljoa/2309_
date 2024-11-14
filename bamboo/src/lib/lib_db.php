<?php
// ---------------------------------
// 함수명   : my_db_conn
// 기능     : DB Connect
// 파라미터 : PDO   &$conn
// 리턴     : boolen
// ---------------------------------
function my_db_conn( &$conn ) {
    $db_host	= "localhost"; // host
	$db_user	= "root"; // user
	$db_pw		= "php504"; // password
	$db_name	= "bamboo"; // DB name
	$db_charset	= "utf8mb4"; // charset
	$db_dsn		= "mysql:host=".$db_host.";dbname=".$db_name.";charset=".$db_charset;

    try {
        $db_options = [
            PDO::ATTR_EMULATE_PREPARES		=> false // DB의 Prepared Statement 기능을 사용하도록 설정
			,PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION // 자동으로 PDO Exception을 Throws하도록 설정
			,PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC // 연상배열로 Fetch를 하도록 설정
        ];

        $conn = new PDO($db_dsn, $db_user, $db_pw, $db_options);
        return true;
    } catch(Exception $e) {
        echo $e->getMessage();
        $conn = null;
        return false;
    }
}


// ---------------------------------
// 함수명   : db_destroy_conn
// 기능     : DB Destroy
// 파라미터 : PDO   &$conn
// 리턴     : 없음
// ---------------------------------
function db_destroy_conn(&$conn) {
	$conn = null;
}


// ---------------------------------
// 함수명   : db_select_boards_cnt
// 기능     : boards count 조회
// 파라미터 : PDO		&$conn
// 리턴     : Int / false
// ---------------------------------
function db_select_boards_cnt( &$conn ) {
    try {
        $sql = 
            " SELECT "
            ."      COUNT(id) as cnt "
            ." FROM "
            ."      boards "
            ." WHERE "
            ."      delete_flg = 0 "
            ;
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll();
        return (int)$result[0]["cnt"];
    } catch(Exception $e) {
        return false;
    }
}


// ---------------------------------
// 함수명   : db_select_boards_paging
// 기능     : boards paging 조회
// 파라미터 : PDO		&$conn
//			  Array		&$arr_param 쿼리 작성용 배열
// 리턴     : Array / false
// ---------------------------------
function db_select_boards_paging( &$conn, &$arr_param ) {
    try {
        $sql = 
        " SELECT "
        ."      id "
        ."      ,title"
        ."      ,content"
        ."      ,DATE(create_at) AS create_at"
        ."  FROM "
        ."      boards "
        ."  WHERE "
        ."      delete_flg = 0 "
        ."  ORDER BY "
        ."     id DESC "
        ."  LIMIT :list_cnt OFFSET :offset "
        ;

        $arr_ps = [
            ":list_cnt" => $arr_param["list_cnt"]
            ,":offset" => $arr_param["offset"]
        ];

        $stmt = $conn->prepare($sql); // sql 쿼리 준비
        $stmt->execute($arr_ps); // 플레이스홀드에 값 할당
        $result = $stmt->fetchAll();
        return $result;
    } catch(Exception $e) {
        echo $e->getMessage();
        return false;
    };
}


// ---------------------------------
// 함수명   : db_select_boards_id
// 기능     : boards id 조회
// 파라미터 : PDO		&$conn
//			 Array		&$arr_param 쿼리 작성용 배열
// 리턴     : Array / false
// ---------------------------------
function db_select_boards_id( &$conn, &$arr_param ) {
    try {
        $sql =
        "   SELECT "
        ."      id "
        ."      ,title "
        ."      ,content "
        ."      ,DATE(create_at) AS create_at "
        ."  FROM "
        ."      boards "
        ."  WHERE "
        ."      id = :id "
        ."  AND "
        ."      delete_flg = '0' "
        ;

        $arr_ps = [
            ":id" => $arr_param["id"]
        ];

        $stmt = $conn->prepare($sql);
        $stmt->execute($arr_ps);
        $result = $stmt->fetchAll();
        return $result;
    } catch(Exception $e) {
        echo $e->getMessage();
        return false;
    }
}
?>