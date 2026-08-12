<?php
session_start();
$id = $_SESSION['id'];

if($id == ""){
   echo "<script>location.href='/member/login.php';</script>";
   exit;
}

require '/var/www/html/vendor/autoload.php';

// 경로 조작 차단
$filename = basename($_GET['filename'] ?? '');

if (empty($filename)) {
    http_response_code(400);
    exit("잘못된 요청입니다.");
}

try {
    $s3 = new Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => 'ap-northeast-2'
    ]);

    $result = $s3->getObject([
        'Bucket' => getenv('S3_BUCKET'),
        'Key'    => 'uploads/' . $filename
    ]);

    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
    header("Content-Type: " . $result['ContentType']);
    header("Content-Length: " . $result['ContentLength']);
    echo $result['Body'];

} catch (Aws\S3\Exception\S3Exception $e) {
    http_response_code(404);
    exit("파일을 찾을 수 없습니다.");
}