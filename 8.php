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
	<meta charset="utf-8">
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
    <a href="8.php" class="active" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" >고장난 자전거 복구 날짜 변경</a>
</nav>
    <div class="container">
        <h2>주차별 따릉이 최다 이용자 순위</h2>
        <p>주차별 거리를 기준으로 한 따릉이 최다 이용자에 대한 정보입니다.<br> </p>
       
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  Enter week number: <input type="number" name="user_input">
  <input type="submit" name="submit" value="입력">
</form>
        <?php
            $host="localhost";
            $user="team17";
            $pw="team17";
            $dbName="team17";

            // MySQL 연결
            $conn = new mysqli($host, $user, $pw, $dbName);

			$user_input = isset($_POST['user_input']) ? $_POST['user_input'] : '';

// Connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

	// MySQL 쿼리 실행
    $sql = "SELECT ROW_NUMBER() OVER (ORDER BY total_distance DESC) AS ranking, user_id, gender, age_group, total_distance
            FROM (
                SELECT u.user_id, u.gender, u.age_group, 
                    SUM(wu.moving_distance) AS total_distance,
                    TIMESTAMPDIFF(WEEK, '2023-06-01', rh.rent_datetime) + 1 AS week_number,
                    RANK() OVER (ORDER BY SUM(wu.moving_distance) DESC) AS weekly_rank
                FROM user u
                JOIN usage_per_user upu ON u.user_id = upu.user_id
                JOIN workout_usage wu ON upu.usage_id = wu.usage_id
                JOIN rent_history rh ON upu.usage_id = rh.usage_id
                WHERE TIMESTAMPDIFF(WEEK, '2023-06-01', rh.rent_datetime) + 1 = ?
                GROUP BY u.user_id, u.gender, u.age_group, TIMESTAMPDIFF(WEEK, '2023-06-01', rh.rent_datetime)
            ) AS TotalDistancePerUser
            WHERE weekly_rank <= 10
            ORDER BY total_distance DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_input); // 정수형 파라미터를 바인딩
    $stmt->execute();
    $result = $stmt->get_result();

    // 쿼리 결과 출력
    if ($result->num_rows > 0) {
        echo '<br>6월 <label>' . $user_input . '</label>';
        echo "주차 따릉이 최다 이용자는 다음과 같습니다.<br>";
		echo "<br><table border='1'><tr><th>Ranking</th><th> Gender </th><th>Age Group</th><th>Total Distance (m) </th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . 
			$row["ranking"]. "</td><td>" . 	
			$row["gender"]. "</td><td>" . $row["age_group"]. "</td><td>" . $row["total_distance"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<br>결과가 없습니다.";
    }

    // 연결 종료
    $stmt->close();
    $conn->close();
	
?>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        

    </script>
</body>
</html>