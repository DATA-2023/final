<?php
    session_start();

    // 세션이 없으면 로그인 페이지로 이동
    if (!isset($_SESSION["adminLoggedIn"]) || !$_SESSION["adminLoggedIn"]) {
        header("Location: login.php");
        exit();
    }
?>
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
    <a href="5.php" class="active" >회원별 운동량과 탄소절감량</a><br>
    <a href="6.php" >서울 소재 구별 대여 현황</a>
    <a href="7.php" >회원별 누적 이용금액</a>
    <a href="8.php" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" >고장난 자전거 복구 날짜 변경</a>
</nav>
    <div class="container">
        <h2>회원별 운동량과 탄소절감량</h2>
        <p>서울시 공공자전거를 이용하는 회원들의 운동량, 탄소절감량에 대한 정보입니다.<br>성별, 나이대별로 확인하실 수 있습니다.</p>
        
        

        <?php
           // 데이터베이스 연결 설정
            $host = "localhost";
            $user = "team17";
            $pw = "team17";
            $dbName = "team17";

            $conn = new mysqli($host, $user, $pw, $dbName);

   
            // 오류 확인
            if ($conn->connect_error) {
               die("Connection failed: " . $conn->connect_error);
            }

            // 사용자가 클릭한 성별 및 연령대
            $selected_gender = isset($_GET['gender']) ? $_GET['gender'] : '';
            $selected_age_group = isset($_GET['age_group']) ? $_GET['age_group'] : '';

            // SQL 쿼리 작성
            $query = "SELECT
            u.gender,
            u.age_group,
            SUM(wu.dur_time) AS total_duration,
            ROUND(SUM(wu.exercise), 2) AS total_exercise
          FROM
            user u
          JOIN
            usage_per_user upu ON upu.user_id = u.user_id
          JOIN
            workout_usage wu ON upu.usage_id = wu.usage_id
          WHERE
            (u.gender = '$selected_gender' OR '$selected_gender' = '')
            AND (u.age_group = '$selected_age_group' OR '$selected_age_group' = '')
          GROUP BY
            u.gender, u.age_group";

            // 쿼리 실행
            $result = $conn->query($query);

            // 결과 출력
            echo "<form action='' method='GET'>";
            echo "<label>성별 :  </label>";
            echo "<select name='gender'>";
            echo "<option value=''>전체</option>";
            echo "<option value='F' " . ($selected_gender == 'F' ? 'selected' : '') . ">여성</option>";
            echo "<option value='M' " . ($selected_gender == 'M' ? 'selected' : '') . ">남성</option>";
            echo "</select><br>";

            echo "<label>나이대 :  </label>";
            echo "<select name='age_group'>";
            echo "<option value=''>전체</option>";
            echo "<option value='10대' " . ($selected_age_group == '10대' ? 'selected' : '') . ">10대</option>";
            echo "<option value='20대' " . ($selected_age_group == '20대' ? 'selected' : '') . ">20대</option>";
            echo "<option value='30대' " . ($selected_age_group == '30대' ? 'selected' : '') . ">30대</option>";
            echo "<option value='40대' " . ($selected_age_group == '40대' ? 'selected' : '') . ">40대</option>";
            echo "<option value='50대' " . ($selected_age_group == '50대' ? 'selected' : '') . ">50대</option>";
            echo "<option value='60대' " . ($selected_age_group == '60대' ? 'selected' : '') . ">60대</option>";
            echo "<option value='70대이상' " . ($selected_age_group == '70대이상' ? 'selected' : '') . ">70대이상</option>";
            echo "</select>";
            echo "&nbsp;";
            echo "<input type='submit' value='조회'>";
            echo "</form>";

            echo "<table border='1'>";
            echo "<tr><th>성별</th><th>나이대</th><th>총 탄소절감량</th><th>총 운동량</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . $row['age_group'] . "</td>";
                echo "<td>" . $row['total_duration'] . "</td>";
                echo "<td>" . $row['total_exercise'] . "</td>";
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