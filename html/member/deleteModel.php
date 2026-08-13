<?php
session_start();
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(403);
    exit('CSRF 검증 실패');
}
$id = $_SESSION['id'];
$pw = $_POST['pw'];
$pwCheck = $_POST['pwCheck'];

if (! ($pw and $pwCheck)) {
    echo "<script>alert('두 비밀번호는 필수 정보입니다.'); history.go(-1);</script>";
    exit;
}
if ($pw != $pwCheck) {
    echo "<script>alert('두 비밀번호를 일치하여 입력하세요'); history.go(-1);</script>";
    exit;
}

$link = mysqli_connect(getenv("DB_HOST"),getenv("DB_USER"),getenv("DB_PASSWORD"),getenv("DB_NAME")) or die('연결 실패');

// 저장된 해시 조회 (Prepared)
$stmt = mysqli_prepare($link, "SELECT pw FROM member WHERE id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($row && password_verify($pw, $row['pw'])) {          // 🔑 해시 검증으로 변경
    $del = mysqli_prepare($link, "DELETE FROM member WHERE id = ?");
    mysqli_stmt_bind_param($del, "s", $id);
    mysqli_stmt_execute($del);
    mysqli_close($link);
} else {
    mysqli_close($link);
    echo "<script>alert('저장된 비밀번호와 일치하여 입력하세요'); history.go(-1);</script>";
    exit;
}
?>
<script>alert('회원 삭제'); location.href='logout.php';</script>