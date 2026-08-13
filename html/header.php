<?php
session_start();
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}
?>
<html>
<head>
    <title>Mangyul - Mango & Tangerine Dessert Cafe</title>
    <link type="text/css" rel="stylesheet" href="/css/main.css">
    <link type="text/css" rel="stylesheet" href="/css/sub.css">
    
    <!-- 귀여운 웹폰트 (고운돋움) 추가 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gowun+Dodum&display=swap" rel="stylesheet">

    <style>
        /* 전체 폰트 적용 */
        header, header * {
            font-family: 'Gowun Dodum', sans-serif !important;
        }

        header {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 20px 10px 20px;
        }

        /* 로고 - 메뉴 - 로그인/회원가입을 한 라인에 수평(평행) 정렬 */
        .header-content {
            display: flex;
            align-items: center; /* 세로 중앙 정렬 */
            justify-content: space-between; /* 양 끝 정렬 */
            width: 100%;
        }

        /* 로고 + 네비게이션 좌측 묶음 */
        .left-header {
            display: flex;
            align-items: center;
            gap: 45px; /* 로고와 메뉴 사이 적절한 간격 */
        }

        /* 로고 스타일 */
        .logo h1 {
            margin: 0;
            font-size: 2.1rem;
            white-space: nowrap;
        }

        .logo a {
            text-decoration: none;
            color: #ff6f00;
            font-weight: bold;
        }

        /* 메인 메뉴 (로고 오른쪽에 자연스럽게 이어짐) */
        #nav_index ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 25px; /* 메뉴 항목 간 간격 */
        }

        #nav_index ul li a {
            text-decoration: none;
            font-size: 1.2rem; /* 보기 좋은 메뉴 글자 크기 */
            font-weight: bold;
            color: #333;
            padding: 6px 10px;
            border-radius: 8px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        #nav_index ul li a:hover {
            color: #ff6f00;
            background-color: #fff4e6;
        }

        /* 같은 라인 우측 끝: 로그인 / 회원가입 */
        .login {
            font-size: 1.05rem;
            font-weight: bold;
            white-space: nowrap;
        }

        .login a {
            color: #666;
            text-decoration: none;
            padding: 4px 6px;
            transition: color 0.2s;
        }

        .login a:hover {
            color: #ff6f00;
        }
    </style>
</head>
<body>
        <div id="wrap"> 
                <header>
                        <div class="header-content">
                            <!-- [좌측] 로고 & 메인 메뉴 -->
                            <div class="left-header">
                                <div class="logo"> 
                                    <h1><a href="/index.php">🥭Mangyul🍊 </a></h1> 
                                </div>
                                
                                <nav id="nav_index">
                                    <ul>
                                            <li><a href="/index.php"> HOME </a></li>
                                            <li><a href="#"> MENU </a></li>
                                            <li><a href="#"> ABOUT </a></li>
                                            <li><a href="/center/list.php"> COMMUNITY </a></li>
                                    </ul>
                                </nav>
                            </div>

                            <!-- [우측] 로그인 / 회원가입 (한 라인에 평행 배치) -->
                            <div class="login">
                            <?php
                            if(!isset($_SESSION['id']) || $_SESSION['id'] == ""){
                            ?>
                                    <a href="/member/login.php"> Login </a> 
                                    | 
                                    <a href="/member/register.php"> Register </a>
                            <?php }else{?>
                                    <a href="/member/logout.php"> Logout </a> 
                                    | 
                                    <a href="/member/modify.php"> Modify </a>
                            <?php }?>
                            </div>
                        </div>
                </header>
