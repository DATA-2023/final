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
    <a href="7.php" class="active" >회원별 누적 이용금액</a>
    <a href="8.php" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" >고장난 자전거 복구 날짜 변경</a>
</nav>
    <div class="container">
        <h2>회원별 누적 이용금액</h2>
        <p>서울시 공공자전거를 이용하는 회원들의 누적 이용금액에 대한 정보입니다.<br>나이대별, 성별, 국적별로 확인하실 수 있습니다.</p>
        <form method="post">
            <label for="category">구분:</label>
            <select name="category" id="category">
                <option value="all">전체</option>
                <option value="gender">성별</option>
                <option value="age_group">나이대별</option>
                <option value="nationality">국적별</option>
            </select>

            <input type="submit" value="조회">
        </form>

        <?php
            $host="localhost";
            $user="team17";
            $pw="team17";
            $dbName="team17";

            // MySQL 연결
            $conn = new mysqli($host, $user, $pw, $dbName);

            // 연결 확인
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }


            // 사용자의 입력 받기
            $category = isset($_POST['category']) ? $_POST['category'] : 'all';
            
            $query = "SELECT ROUND(AVG(cumulative_usage_amount),0) FROM user";

            // 쿼리 작성
            if ($category == 'gender'){
                $query = "SELECT gender, ROUND(AVG(cumulative_usage_amount),0) AS average_usage FROM user GROUP BY gender;";
            }
            if ($category == 'age_group'){
                $query = "SELECT age_group, ROUND(AVG(cumulative_usage_amount),0) AS average_usage FROM user GROUP BY age_group;";
            }
            if ($category == 'nationality'){
                $query = "SELECT nationality, ROUND(AVG(cumulative_usage_amount),0) AS average_usage FROM user GROUP BY nationality;";
            }

            // 쿼리 실행
            $result = $conn->query($query);


            // 결과 출력
            echo "<h2>회원 구분별 누적 이용금액 평균</h2>";
            echo "<table border='1'>";
            if($category!='all'){
                echo "<tr><th>구분</th><th>이용금액 평균 (원)</th></tr>";
            }
            else{
                echo "<tr><th>이용금액 평균 (원)</tr>";
            }
            while ($row = $result->fetch_assoc()) {
                
                echo "<tr>";
        
                // 각 행의 열을 동적으로 처리
                foreach ($row as $column) {
                    echo "<td>$column</td>";
                
                }

                echo "</tr>";
            }
            echo "</table>";
        
            // 연결 종료
            $conn->close();
        ?>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        

    </script>
</body>
</html>