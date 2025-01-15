<?php 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/challenge_bar.css">
    <title>Challenge_bar</title>
</head>
<body>
    <div class="challenge_container">
        <header class="challenge_header">
            <p class="challenge_logo">Challenge</p>
            <a href="todolist/src/insert.php" class="challenge_insert">+</a>
        </header>
        <!-- in-progress 페이지로 넘겨 줄 get (사용자 입력(챌린지 선택)) -->
        <form action="todolist/src/in-progress.php" method="get">
            <button class="challenge_list challenge_complete">
                <p>10</p>
                <p>건강한 아침</p>
            </button>
            <button class="challenge_list">
                <p>10</p>
                <p>건강한 아침</p>
            </button>
            <button class="challenge_list">
                <p>10</p>
                <p>건강한 아침</p>
            </button>
        </form>
    </div>
</body>
</html>