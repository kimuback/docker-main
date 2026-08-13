<?php
// register.php에서 전달한 데이터를 변수에 저장
$id = $_POST['id'];
$pw = $_POST['pw'];
$pwCheck = $_POST['pwCheck'];
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$address = $_POST['address'];
$email= $_POST['email'];
$date = date('Y-m-d');

if( ! ($id and $pw and $pwCheck and $name)){
    echo "<script>alert('필수 정보를 입력하세요'); history.go(-1); </script>";
    exit;
}

if($pw != $pwCheck){
    echo "<script>alert('입력한 패스워드가 동일하지 않습니다.'); history.go(-1); </script>";
    exit;
}

//데이터베이스 연결(데이터베이스 위치(ip), 데이터베이스 id, 데이터베이스 암호, 데이터베이스 이름);
$link = mysqli_connect(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASSWORD"),
    getenv("DB_NAME")
) or die('연결 실패');

/*
 * # 아이디 중복 체크 #
 * 데이터베이스에 아이디를 갖고 조회 후
 * 행의 개수가 0이면 중복 없음.
 * 행의 개수가 1이면 이미 가입한 회원이 있음.
 */

// 중복 체크 (Prepared)
$stmt = mysqli_prepare($link, "SELECT num FROM member WHERE id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$dup = mysqli_stmt_num_rows($stmt);
mysqli_stmt_close($stmt);

if ($dup == 0) {
    $hash = password_hash($pw, PASSWORD_DEFAULT);   // 🔑 해시 저장
    $stmt = mysqli_prepare($link, "INSERT INTO member(id,pw,name,mobile,address,email,date) VALUES(?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "sssssss", $id, $hash, $name, $mobile, $address, $email, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo "<script>alert('가입 완료'); location.href='/index.php';</script>";
} else {
    echo "<script>alert('이미 가입되어 있는 계정입니다.'); history.go(-1);</script>";
}

mysqli_close($link);
?>