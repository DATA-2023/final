<?php
    session_start();

    // 세션이 없으면 로그인 페이지로 이동
    if (!isset($_SESSION["adminLoggedIn"]) || !$_SESSION["adminLoggedIn"]) {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>서울시 공공자전거 이용정보 보고서</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        header {
            background-color: #0078d4;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            font-size: 20px;
            margin-top: 20px;
        }
        p {
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0078d4;
            color: #fff;
        }
        /* 네비게이션바 스타일 */
        nav {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            text-decoration: none; 
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 10px; 
        }
        nav a.active {
            text-decoration: underline; 
        }
    </style>
     <meta charset="UTF-8">
</head>
<body>
    <div style="text-align: right; background-color: #0078d4;"><button style=" margin: 10px;"><a href="logout.php" style="text-decoration: none; color: black; font-weight: bold;">관리자 모드 종료</a></button></div>
    <header>
        <h1>서울시 공공자전거 이용정보 보고서</h1>
    </header>   
    <nav>
    <a href="1.php" >고장수리 평균 소요시간</a>
    <a href="2.php" >이용 많은 정류소</a>
    <a href="3.php" >회원등록 및 회원삭제</a>
    <a href="4.php" >이동시간 대비 이동거리</a>
    <a href="5.php" >회원별 운동량과 탄소절감량</a><br>
    <a href="6.php" >서울 소재 구별 대여 현황</a>
    <a href="7.php" >회원별 누적 이용금액</a>
    <a href="8.php" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" class="active" >고장난 자전거 복구 날짜 변경</a>
</nav>
    <div class="container">
        <h2>고장난 자전거 복구 날짜 변경</h2>
        <p>고장난 자전거의 접수 날짜를 입력하면 해당 날짜에 접수된 자전거의 복구 날짜를 일주일 미룹니다.</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
   날짜를 선택하세요: <input type="date" name="user_input">
  <input type="submit">
</form>
    <?php
        $host="localhost";
        $user="team17";
        $pw="team17";
        $dbname="team17";
        $conn = new mysqli($host, $user, $pw, $dbname);
        //$currentPage = isset($_GET['page']) ? $_GET['page'] : 2;
        $user_input = isset($_POST['user_input']) ? $_POST['user_input'] : '';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->begin_transaction();
        try{
            $sql = "UPDATE bike_fix bf
            SET return_datetime=ADDDATE(return_datetime, INTERVAL 7 DAY)
            WHERE bf.complaint_id = (SELECT complaint_id
                                FROM bike_breakdown as bb
                                        WHERE bb.datetime=?)";

            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param("s",$user_input);
            $stmt -> execute();
            $result = $stmt -> get_result();
            if ($result) {
                
            }
            $stmt->close();
            $conn->commit();
            echo 'update가 정상적으로 처리되었습니다.';
        }
        catch (Exception $e) {
            // 에러 발생하면 rollback
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
        finally{
            $conn->close();
        }
    ?>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

