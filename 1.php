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
    <a href="1.php" class="active" >고장수리 평균 소요시간</a>
    <a href="2.php" >이용 많은 정류소</a>
    <a href="3.php" >회원등록 및 회원삭제</a>
    <a href="4.php" >이동시간 대비 이동거리</a>
    <a href="5.php" >회원별 운동량과 탄소절감량</a><br>
    <a href="6.php" >서울 소재 구별 대여 현황</a>
    <a href="7.php" >회원별 누적 이용금액</a>
    <a href="8.php" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" >고장난 자전거 복구 날짜 변경</a>
</nav>
    <div class="container">
        <h2>고장사유별 수리 평균 소요시간</h2>
        <p>서울시 공공자전거를 이용하는 회원들이 접수한 자전거 고장내역 중  "사유별 수리 평균 소요시간" 에 대한 정보입니다.<br> </p>
     

        <?php
            $host="localhost";
            $user="team17";
            $pw="team17";
            $dbName="team17";

            // MySQL 연결
            $conn = new mysqli($host, $user, $pw, $dbName);

// Connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Get the submitted reason or default to an empty string
$selected_reason = isset($_GET['reason']) ? $_GET['reason'] : '';

// HTML form
echo "<form action='' method='GET'>";
echo '고장 사유 선택: ';
$reasons = ['안장 ', '페달 ', '타이어 ', '단말기 ', '체인 ', '기타      '];
foreach ($reasons as $reason) {
    echo "<label><input type='radio' name='reason' value='$reason'";
    if ($reason === $selected_reason) {
        echo ' checked';
    }
    echo ">$reason</label>";
}
echo "&nbsp;";
echo '<input type="submit" name="submit" value="조회">';
echo '</form>';

// When the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['reason'])) {
    $selected_reason = $_GET['reason'];

    // SQL query creation
    $sql = "CREATE VIEW avg_time AS (
        SELECT
            t1.complaint_id,
            t1.bike_id,
            t1.datetime,
            t1.reason_to_fix,
            t2.return_datetime,
            TIMEDIFF(t2.return_datetime, t1.datetime) AS interval_duration
        FROM
            bike_breakdown t1
        JOIN
            bike_fix t2 ON t1.complaint_id = t2.complaint_id AND t1.bike_id = t2.bike_id
    );";

    $sql1 = "SELECT ROUND(AVG(TIME_TO_SEC(interval_duration) / 3600), 2) AS avg_duration FROM avg_time WHERE reason_to_fix='$selected_reason'";

	$sql3 = "SELECT COUNT(*) AS count_num FROM avg_time WHERE reason_to_fix = '$selected_reason'";
    
    $sql2 = "DROP VIEW avg_time";

    // Execute SQL queries
    $result1 = $conn->query($sql);
    $result2 = $conn->query($sql1);
	$result3 = $conn->query($sql3);

    // Display results
	if ($result3->num_rows > 0) {
        $row = $result3->fetch_assoc();
		echo '<br><label>' . $selected_reason . '</label>';
		echo '<label> 수리 총 요청 건수: ' . $row['count_num'] . '건</label>';
		}
        

    if ($result2->num_rows > 0) {
        $row = $result2->fetch_assoc();

		echo '<br><label>' . $selected_reason . '</label>';
        echo '<label> 수리 평균 소요시간: ' . $row['avg_duration'] . '시간</label>';
    }   

	else {
        echo '<label>데이터를 찾을 수 없습니다.</label>';
    }

    // Drop the view
    $result3 = $conn->query($sql2);
}

// Close database connection
$conn->close();
?>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        

    </script>
</body>
</html>