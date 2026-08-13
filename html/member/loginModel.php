<?php
session_start();
$id = $_POST['id'] ?? '';
$pw = $_POST['pw'] ?? '';

$link = mysqli_connect(getenv("DB_HOST"),getenv("DB_USER"),getenv("DB_PASSWORD"),getenv("DB_NAME")) or die('연결 실패');

$stmt = mysqli_prepare($link, "SELECT * FROM member WHERE id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$ok = false;
if ($row) {
    $stored = $row['pw'];
    if (preg_match('/^\$2[aby]\$/', $stored)) {
        // 이미 해시로 저장된 계정 → password_verify
        $ok = password_verify($pw, $stored);
    } else {
        // 기존 평문 계정 → 우선 평문 비교 (안 깨짐)
        $ok = hash_equals($stored, $pw);
        if ($ok) {
            // 평문 로그인 성공 → 조용히 해시로 업그레이드
            $newHash = password_hash($pw, PASSWORD_DEFAULT);
            $up = mysqli_prepare($link, "UPDATE member SET pw=? WHERE id=?");
            mysqli_stmt_bind_param($up, "ss", $newHash, $row['id']);
            mysqli_stmt_execute($up);
        }
    }
}
mysqli_close($link);

if ($ok) {
    $_SESSION['id']      = $row['id'];
    $_SESSION['name']    = $row['name'];
    $_SESSION['mobile']  = $row['mobile'];
    $_SESSION['address'] = $row['address'];
    $_SESSION['email']   = $row['email'];
    $_SESSION['num']     = $row['num'];
    echo "<script>alert('로그인 성공'); location.href='/index.php';</script>";
} else {
    echo "<script>alert('로그인 실패'); history.go(-1);</script>";
    exit;
}
?>