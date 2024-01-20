<?php
    session_start();

    // 관리자 비밀번호 설정
    $adminPassword = "team17";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 폼에서 입력한 비밀번호
        $enteredPassword = $_POST["password"];

        // 비밀번호가 일치하면 세션에 저장
        if ($enteredPassword === $adminPassword) {
            $_SESSION["adminLoggedIn"] = true;
            header("Location: 1.php");
            exit();
        } else {
            $error = "비밀번호가 일치하지 않습니다.";
        }
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
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div style="text-align: right; background-color: #0078d4;"><button style=" margin: 10px;"><a href="logout.php" style="text-decoration: none; color: black; font-weight: bold;">관리자 모드 종료</a></button></div>
    <header>
        <h1>서울시 공공자전거 이용정보 보고서</h1>
    </header>
    <div class="container">
    <h3>보고서를 보려면 관리자 비밀번호를 입력하십시오.</h3>
        <?php if (isset($error)) : ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
    <form method="post" action="">
        <label for="password">비밀번호:</label>
        <input type="password" name="password" required>
        <button type="submit">로그인</button>
    </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        

    </script>
</body>
</html>

