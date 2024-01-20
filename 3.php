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
    <a href="3.php" class="active" >회원등록 및 회원삭제</a>
    <a href="4.php" >이동시간 대비 이동거리</a>
    <a href="5.php" >회원별 운동량과 탄소절감량</a><br>
    <a href="6.php" >서울 소재 구별 대여 현황</a>
    <a href="7.php" >회원별 누적 이용금액</a>
    <a href="8.php" >주차별 따릉이 최다 이용자 순위</a>
    <a href="9.php" >고장난 자전거 복구 날짜 변경</a>
</nav>

    <div class="container">
        <h2>회원등록</h2>
        <p>서울시 공공자전거 회원등록입니다.<br>등록을 원하시면 나이와, 성별, 국적을 선택후 등록을 눌러주세요.</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="age_group">연령 그룹:</label>
        <select name="age_group" required>
            <option value="">선택</option>
            <option value="10대">10대</option>
            <option value="20대">20대</option>
            <option value="30대">30대</option>
            <option value="40대">40대</option>
            <option value="50대">50대</option>
            <option value="60대">60대</option>
            <option value="70대 이상">70대 이상</option>
        </select><br>

        <label for="gender">성별:</label>
        <select name="gender" required>
            <option value="">선택</option>
            <option value="M">남성</option>
            <option value="F">여성</option>
        </select><br>

        <label for="nationality">국적:</label>
        <select name="nationality" required>
            <option value="">선택</option>
            <option value="내국인">내국인</option>
            <option value="외국인">외국인</option>
        </select>

        <input type="submit" value="등록">
    </form>

        <?php
           // 데이터베이스 연결 정보
            $host = "localhost";
            $user = "team17";
            $pw = "team17";
            $dbName = "team17";

            $conn = new mysqli($host, $user, $pw, $dbName);


            // 연결 확인
            if ($conn->connect_error) {
                die("연결 실패: " . $conn->connect_error);
            }

            // 사용자 아이디 생성 (300부터 시작)
            $query = "SELECT MAX(user_id) AS max_user_id FROM user";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $max_user_id = ($row['max_user_id']) ? $row['max_user_id'] : 299; // 최대 값이 없으면 299로 설정

            $user_id = $max_user_id + 1;

            // 사용자에게 입력 받는 부분: 연령 그룹, 성별, 국적
            $age_group = isset($_POST['age_group']) ? $_POST['age_group'] : "";
            $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
            $nationality = isset($_POST['nationality']) ? $_POST['nationality'] : "";

            // 누적 사용량 0으로 입력
            $cumulative_usage_amount = 0;

            // 가입 날짜 현재 날짜로 자동 입력 (대한민국 기준)
            $joined_date = date('y-m-d');

            // user 테이블에 데이터 삽입
            $sql_user = "INSERT INTO user (user_id, age_group, gender, nationality, cumulative_usage_amount) VALUES ('$user_id', '$age_group', '$gender', '$nationality', '$cumulative_usage_amount')";

            if($age_group!= "" && $gender !="" && $nationality != "")
            {
                if ($conn->query($sql_user) === TRUE) {
                   echo "";
                } else {
                   echo "오류: " . $sql_user . "<br>" . $conn->error;
                }
            }
            else{
                echo "";
            }


// 데이터베이스 연결 닫기
$conn->close();
?>
    </div>

    <div class="container">
        <h2>회원삭제</h2>
        <p>서울시 공공자전거 회원삭제입니다.<br>삭제을 원하시는 user_id를 입력후 삭제를 눌러주세요.</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="user_id_to_delete">삭제할 사용자의 user_id:</label>
        <input type="text" name="user_id_to_delete" required>
        <input type="submit" value="삭제">
    </form>

    <?php
        // 데이터베이스 연결 정보
        $host = "localhost";
        $user = "team17";
        $pw = "team17";
        $dbName = "team17";

        $conn = new mysqli($host, $user, $pw, $dbName);



        // 사용자에게 입력 받은 user_id 값
        $user_id_to_delete = isset($_POST['user_id_to_delete']) ? $_POST['user_id_to_delete'] : null;

        // 연결 확인
        if ($conn->connect_error) {
            die("연결 실패: " . $conn->connect_error);
        }

        // user 테이블에서 사용자 삭제
        $sql_delete_user = "DELETE FROM user WHERE user_id = '$user_id_to_delete'";

        if ($conn->query($sql_delete_user) === TRUE) {
            echo "";
        } else {
           echo "오류: " . $sql_delete_user . "<br>" . $conn->error;
        }

        // 데이터베이스 연결 닫기
        $conn->close();
    ?>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        

    </script>
</body>
</html>
