<?php
    // ---------------------------------
    // 함수명   : my_db_conn
    // 기능     : DB 연동 관련 함수
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_param
    // 리턴     : Boolean
    // ---------------------------------
    function my_db_conn( &$conn ) {
        $db_host	= "localhost"; // host
        $db_user	= "root"; // user
        $db_pw		= "php504"; // password
        $db_name	= "todolist"; // DB name
        $db_charset	= "utf8mb4"; // charset
        $db_dns		= "mysql:host=".$db_host.";dbname=".$db_name.";charset=".$db_charset;
        
        try {
            $db_options = [
                // DB의 Prepared Statement 기능을 사용하도록 설정
                PDO::ATTR_EMULATE_PREPARES      => false
                // PDO Exception을 Throws 하도록 설정
                ,PDO::ATTR_ERRMODE              => PDO::ERRMODE_EXCEPTION
                // 연상배열로 Fetch를 하도록 설정
                ,PDO::ATTR_DEFAULT_FETCH_MODE   => PDO::FETCH_ASSOC
            ];
    
            // PDO Class로 DB 연동
            $conn = new PDO($db_dns, $db_user, $db_pw, $db_options);
            return true;
        } catch (Exception $e){
            $conn = null;
            error_log("Database error: " . $e->getMessage());
            echo "An error occurred while fetching data."; 
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_destroy_conn
    // 기능     : DB 파기
    // 파라미터 : PDO		&$conn
    // 리턴     : 없음
    // ---------------------------------
    function db_destroy_conn(&$conn) {
        $conn = null;
    }







    // IN-PROGRESS 페이지

    // ---------------------------------
    // 함수명   : db_select_complete
    // 기능     : create_information, 
    //            chal_info 레코드 조회
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : Array / False
    // ---------------------------------
    function db_select_complete( &$conn, &$arr_post ) {
        try {
            $sql =
            " SELECT "
            ." cr.create_id, cr.c_id, ch.l_id, ch.l_name
            , cr.l_com_at1, cr.l_com_at2, cr.l_com_at3, cr.l_com_at4, cr.c_com_at "
            ." FROM create_information cr "
            ." JOIN "
            ." chal_info ch "
            ." ON "
            ." cr.c_id = ch.c_id "
            ." AND "
            ." cr.create_id = :create_id "
            ;

            $arr_ps = [
                ":create_id" => $arr_post["create_id"]
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

    // ---------------------------------
    // 함수명   : db_update_cancel
    // 기능     : create_information, 
    //            chal_info 레코드 수정
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : Boolean
    // ---------------------------------
    function db_update_cancel( &$conn, &$arr_post ) {
        $sql = 
        " UPDATE "
        ." create_information cr "
        ." JOIN "
        ." chal_info ch "
        ." ON cr.c_id = ch.c_id "
        ." SET cr.l_com_at".$arr_post["l_id"]." = NULL "
        ." WHERE "
        ." cr.create_id = :create_id "
        ." AND "
        ." ch.l_id = :l_id ";

        $arr_ps = [
            ":create_id" => $arr_post["create_id"]
            ,":l_id" => $arr_post["l_id"]
        ];

        try {
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute($arr_ps);
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_select_complete_count
    // 기능     : create_information, 
    //            chal_info 레코드 조회
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : Array / False
    // ---------------------------------
    function db_select_complete_count( &$conn, &$arr_post ) {
        $sql = 
        " SELECT "
        . "( (case "
        ."         when ci.l_com_at1 IS NOT NULL then 1 "
        ."        ELSE 0 "
        ."    END) "
        ."    + "
        ."    (case "
        ."        when ci.l_com_at2 IS NOT NULL then 1 "
        ."        ELSE 0 "
        ."    END) "
        ."    + "
        ."    (case "
        ."        when ci.l_com_at3 IS NOT NULL then 1 "
        ."        ELSE 0 "
        ."    END) "
        ."    + "
        ."    (case "
        ."        when ci.l_com_at4 IS NOT NULL then 1 "
        ."        ELSE 0 "
        ."    END)) AS cnt "
        ." FROM create_information ci "
        ." WHERE create_id = :create_id "
        ;

        $arr_ps = [
            ":create_id" => $arr_post["create_id"]
        ];

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_ps);
            $result = $stmt->fetchAll();
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_update_null
    // 기능     : create_information 레코드 수정
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : Boolean
    // ---------------------------------
    function db_update_null( &$conn, &$arr_post ) {
        try {
            $sql = 
            " UPDATE "
            ." create_information "
            ." SET "
            ." c_com_at = NULL "
            ." WHERE "
            ." create_id = :create_id "
            ;

            $arr_ps = [
                ":create_id" => $arr_post["create_id"]
            ];

            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_ps);
            $result = $stmt;
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_update_list
    // 기능     : create_information,
    //            chal_info 레코드 수정
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : Boolean
    // ---------------------------------
    function db_update_list( &$conn, &$arr_post ) {
        $sql = 
        " UPDATE "
        ." create_information cr "
        ." JOIN "
        ." chal_info ch "
        ." ON cr.c_id = ch.c_id "
        ." SET cr.l_com_at".$arr_post["l_id"]." = NOW() "
        ." WHERE "
        ." cr.create_id = :create_id "
        ." AND "
        ." ch.l_id = :l_id "
        ;

        $arr_ps = [
            ":create_id" => $arr_post["create_id"]
            ,":l_id" => $arr_post["l_id"]
        ];

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_ps);
            $result = $stmt;
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_update_challenge
    // 기능     : create_information 레코드 수정
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : Boolean
    // ---------------------------------
    function db_update_challenge( &$conn, &$complete_challenge ) {
        try {
            $sql = 
            " UPDATE "
            ." create_information "
            ." SET "
            ." c_com_at = NOW() "
            ." WHERE "
            ." create_id = :create_id "
            ;

            $arr_ps = [
                ":create_id" => $complete_challenge["create_id"]
            ];

            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_ps);
            $result = $stmt;
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_select_first
    // 기능     : create_information 레코드 조회
    // 파라미터 : PDO		&$conn
    // 리턴     : Array / False
    // ---------------------------------
    function db_select_first( &$conn ) {
        try {
            $sql = 
            " SELECT "
            ." cr.create_id, ch.c_name, cr.c_com_at "
            ." FROM "
            ." create_information cr "
            ." JOIN "
            ." chal_info ch "
            ." ON "
            ." cr.c_id = ch.c_id "
            ." AND "
            ." cr.c_deleted_at IS NULL "
            ." AND "
            ." cr.c_com_at IS NULL "
            ." GROUP BY cr.create_id "
            ." ORDER BY cr.c_created_at DESC"
            ." LIMIT 1 "
            ;

            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll();
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_select_challenge
    // 기능     : chal_info 레코드,
    //            create_information 레코드 조회
    // 파라미터 : PDO		&$conn
    //           Array     &$arr_get
    // 리턴     : Array / False
    // ---------------------------------
    function db_select_challenge( &$conn, &$arr_get ) {
        $sql = 
        " SELECT "
        ." cr.create_id, cr.c_id, ch.l_id, ch.l_name, ch.c_name, DATE(cr.c_created_at)
        , cr.l_com_at1, cr.l_com_at2, cr.l_com_at3, cr.l_com_at4, cr.c_com_at "
        ." FROM create_information cr "
        ." JOIN "
        ." chal_info ch "
        ." ON "
        ." cr.c_id = ch.c_id "
        ." AND "
        ." cr.create_id = :create_id "
        ." AND "
        ." cr.c_deleted_at IS NULL "
        ;

        $arr_ps = [
            ":create_id" => $arr_get["create_id"]
        ];

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_ps);
            $result = $stmt->fetchALL();
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_select_percent
    // 기능     : create_information 레코드 조회
    // 파라미터 : PDO		&$conn
    //           Array     &$arr_get
    // 리턴     : Array / False
    // ---------------------------------
    function db_select_percent( &$conn, &$arr_get ) {   
        try {
            $sql = 
            "   SELECT  "
            ." ( (case "
            ."        when ci.l_com_at1 IS NOT NULL then 25 "
            ."        ELSE 0 "
            ."    END) "
            ."    + "
            ."    (case "
            ."        when ci.l_com_at2 IS NOT NULL then 25 "
            ."        ELSE 0 "
            ."    END) "
            ."    + "
            ."    (case "
            ."        when ci.l_com_at3 IS NOT NULL then 25 "
            ."        ELSE 0 "
            ."    END) "
            ."    + "
            ."    (case "
            ."        when ci.l_com_at4 IS NOT NULL then 25 "
            ."        ELSE 0 "
            ."    END)) AS per "
            ." FROM create_information ci "
            ." WHERE "
            ." create_id = :create_id ";

            $arr_ps = [
                ":create_id" => $arr_get["create_id"]
            ];

            $stmt = $conn->prepare($sql);
            $stmt->execute($arr_ps);
            $result = $stmt->fetchAll();
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }




    // STATUS 페이지


    // CHALLENGE_BAR 페이지


    // INSERT 페이지

    // ---------------------------------
    // 함수명   : db_insert_create_at
    // 기능     : create_information 레코드 생성
    // 파라미터 : PDO		&$conn
    //			  Array		&$arr_post
    // 리턴     : boolean
    // ---------------------------------
    function db_insert_create_at( &$conn, &$arr_post ) {
        $sql = 
        " INSERT INTO create_information ("
        ."  c_id "
        ."  ) "
        ."  VALUES ( "
        ."  :c_id "
        ."  ) "
        ;

        $arr_ps = [
            ":c_id" => $arr_post["chk"]
        ];

        try {
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute($arr_ps);
            return $result;
        } catch(Exception $e) {
            return false;
        }
    }

    // ---------------------------------
    // 함수명   : db_select_challenge
    // 기능     : chal_info 레코드 조회
    // 파라미터 : PDO		&$conn
    // 리턴     : Array / False
    // ---------------------------------
    // function db_select_challenge( &$conn ) {
    //     $sql = 
    //     "   SELECT DISTINCT "
    //     ."  c_id "
    //     ."  ,c_name "
    //     ."  ,l_name "
    //     ."  FROM "
    //     ."  chal_info "
    //     ;

    //     try {
    //         $stmt = $conn->prepare($sql);
    //         $stmt->execute();
    //         $result = $stmt->fetchAll();
    //         return $result;
    //     } catch(Exception $e) {
    //         echo $e->getMessage();
    //         return false;
    //     }
    // }





    // DELETE 페이지

?>