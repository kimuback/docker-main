<?php
// 사용자의 입력 값을 전달 받아 변수에 저장
session_start();
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(403);
    exit('CSRF 검증 실패');
}
if($_SESSION['id'] == ""){
    echo "<script>location.href='login.php';</script>";
    exit;
}
$id = $_SESSION['id'];

$pw = $_POST['pw'];
$pwCheck = $_POST['pwCheck'];
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$address = $_POST['address'];
$email= $_POST['email'];

// 입력 값이 빈 데이터가 있는 지 검증.
if( ! ($pw and $pwCheck and $name)){
    echo "<script>alert('필수 정보를 입력하세요'); history.go(-1); </script>";
    exit;
}
// pw와 pwCheck의 변수의 값이 일치하는지 검증.
if($pw != $pwCheck){
    echo "<script>alert('입력한 패스워드가 동일하지 않습니다.'); history.go(-1); </script>";
    exit;
}

// 데이터 베이스 연결 (데이터베이스 아이피주소, 데이터베이스 계정명, 패스워드, 데이터베이스 이름)
$link = mysqli_connect(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASSWORD"),
    getenv("DB_NAME")
) or die('연결 실패');

$hash = password_hash($pw, PASSWORD_DEFAULT);   // 🔑 해시
$stmt = mysqli_prepare($link, "UPDATE member SET pw=?, name=?, mobile=?, email=?, address=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "ssssss", $hash, $name, $mobile, $email, $address, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// 데이터베이스 연결 닫기
mysqli_close($link);
?>
<!-- 자바 스크립트를 이용해 alert 창 출력 및 logout.php로 이동. -->
<script>alert('수정 완료'); location.href='logout.php'; </script>