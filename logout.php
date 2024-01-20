<?php
    session_start();

    // 세션 파기
    session_destroy();

    // 로그인 페이지로 이동
    header("Location: login.php");
    exit();
?>