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
    <a href="2.php" class="active" >이용 많은 정류소</a>
    <a href="3.php" >회원등록 및 회원삭제</a>
    <a href="4.php" >이동시간 대비 이동거리</a>
    <a href="5.php" >회원별 운동량과 탄소절감량</a><br>
    <a href="6.php" >서울 소재 구별 대여 현황</a>
    <a href="7.php" >회원별 누적 이용금액</a>
    <a href="8.php" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" >고장난 자전거 복구 날짜 변경</a>
</nav>
    <div class="container">
        <h2>입력받은 구의 이용 많은 정류소</h2>
        <p>사용자가 입력한 구의 이용 많은 정류소를 1위부터 3위까지 보여줍니다.</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    지역(구)를 입력하세요: <input type="text" name="user_input">
  <input type="submit">
</form>
    <?php
        $host="localhost";
        $user="team17";
        $pw="team17";
        $dbname="team17";
        $conn = new mysqli($host, $user, $pw, $dbname);
        $user_input = isset($_POST['user_input']) ? $_POST['user_input'] : '';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

            $sql = "SELECT station_name
            FROM
                (SELECT station_name, COUNT(*)/num_stand, rank() OVER (ORDER BY COUNT(*) DESC) as 'percentage'
                FROM station s INNER JOIN rent_history r ON s.station_id = r.rent_station_id JOIN bike_info_of_station si ON s.station_id = si.station_id 
                WHERE s.gu = ?
                GROUP BY s.station_id) AS inner_query
            LIMIT 3";

            $stmt = $conn -> prepare($sql);
            $stmt -> bind_param("s",$user_input);
            $stmt -> execute();
            $result = $stmt -> get_result();
            if ($result -> num_rows >0) {
                echo '<br> 6월 <label>'. $user_input . '</label>';
                echo "의 이용많은 정류소는 다음과 같습니다.<br>";
                echo "<br><table border='1'><tr><th>station name</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" .
                    $row["station_name"]. "</td></tr>";
                }
                echo "</table>";
            } else {
                    echo "<br> 결과가 없습니다.";
            }
            $stmt->close();
            $conn->close();
    ?>
  </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

