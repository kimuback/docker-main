<?php
session_start();
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
    http_response_code(403);
    exit('CSRF 검증 실패');
}
$id = $_SESSION['id'];
if($id == ""){
    echo "<script>location.href='/member/login.php'; </script>";
    exit;
}

require '/var/www/html/vendor/autoload.php';

$date = date('Y-m-d');
$subject = $_POST['subject'];
$content = $_POST['content'];

$upfile = $_FILES['upfile']['name'];
$tmp_file = $_FILES['upfile']['tmp_name'];

// 파일명 정규화: 경로 조작 차단 + 동일 파일명 덮어쓰기 방지
$storedName = "";
if (!empty($upfile)) {
    $safeName = basename($upfile);
    $storedName = date('YmdHis') . '_' . $safeName;
}

$link = mysqli_connect(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASSWORD"),
    getenv("DB_NAME")
) or die('연결 실패');

$stmt = mysqli_prepare($link, "INSERT INTO center(id, subject, content, date, hit, filename) VALUES(?, ?, ?, ?, 0, ?)");
mysqli_stmt_bind_param($stmt, "sssss", $id, $subject, $content, $date, $storedName);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($link);

// 업로드 파일을 S3에 저장 (Pod 로컬이 아닌 공유 저장소)
if (is_uploaded_file($tmp_file)) {
    try {
        $s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => 'ap-northeast-2'
        ]);

        $s3->putObject([
            'Bucket'     => getenv('S3_BUCKET'),
            'Key'        => 'uploads/' . $storedName,
            'SourceFile' => $tmp_file
        ]);
    } catch (Exception $e) {
        error_log("S3 upload failed: " . $e->getMessage());
    }
}
?>
<script>location.href='list.php'; </script>