<?php
// POST Method
//  request할 때의 데이터를 외부에서 볼 수 없다

print_r($_POST);
// 유저가 입력한 데이터를 연상배열로 받아옴.

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POST</title>
</head>
<body>
    <form action="/post_method.php" method="post">
        <fieldset>
            <label for="id">ID : </label>
            <input type="text" id="id" name="id"> 
            <!-- name을 지정해줘야 페이지에 전달할 수 있는 파라미터로 사용한다 -->
            <br>
            <label for="pw">PW : </label>
            <input type="text" id="pw" name="pw">
            <br>
            <button type="submit">전송</button>
            <!-- type을 submit으로 해줘야 버튼을 눌렀을 때 form태그 안에 작성된 모든 데이터은 서버로 전송됨. -->
        </fieldset>
    </form>
</body>
</html>

