<?php
header('Content-Type: application/json');

// MySQL 데이터베이스 연결
$servername = "192.168.111.132";
$username = "newuser";
$password = "1234";
$dbname = "snort"; 


// 현재 날짜를 가져옴
$current_date = date("Y-m-d");

// MySQL 데이터베이스에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// 이벤트 테이블에서 각각의 튜플 개수 가져오기
$sql_event = "SELECT COUNT(*) AS event_count FROM event";
$sql_dos = "SELECT COUNT(*) AS dos_count FROM event WHERE signature IN (533, 534, 535, 537)";
$sql_sni = "SELECT COUNT(*) AS sni_count FROM event WHERE signature IN(531, 532, 536, 538, 539)";
$sql_tem = "SELECT COUNT(*) AS tem_count FROM event WHERE signature IN(522, 525, 530)";
$sql_det = "SELECT COUNT(*) AS det_count FROM event WHERE signature IN(524, 526,527, 528)";

// 쿼리 실행 및 결과 가져오기
$result_event = $conn->query($sql_event);
$result_dos = $conn->query($sql_dos);
$result_sni = $conn->query($sql_sni);
$result_tem = $conn->query($sql_tem);
$result_det = $conn->query($sql_det);

// 튜플 개수 가져오기
$event_count = ($result_event !== false) ? $result_event->fetch_assoc()["event_count"] : 0;
$dos_count = ($result_dos !== false) ? $result_dos->fetch_assoc()["dos_count"] : 0;
$sni_count = ($result_sni !== false) ? $result_sni->fetch_assoc()["sni_count"] : 0;
$tem_count = ($result_tem !== false) ? $result_tem->fetch_assoc()["tem_count"] : 0;
$det_count = ($result_det !== false) ? $result_det->fetch_assoc()["det_count"] : 0;

// 오늘의 timestamp 데이터만 가져오는 쿼리
$sql_today_event = "SELECT COUNT(*) AS today_event_count FROM event WHERE DATE(timestamp) = '$current_date'";
$sql_today_dos = "SELECT COUNT(*) AS today_dos_count FROM event WHERE signature IN (533, 534, 535, 537) AND DATE(timestamp) = '$current_date'";
$sql_today_sni = "SELECT COUNT(*) AS today_sni_count FROM event WHERE signature IN(531, 532, 536, 538, 539) AND DATE(timestamp) = '$current_date'";
$sql_today_tem = "SELECT COUNT(*) AS today_tem_count FROM event WHERE signature IN(522, 525, 530) AND DATE(timestamp) = '$current_date'";
$sql_today_det = "SELECT COUNT(*) AS today_det_count FROM event WHERE signature IN(524, 526,527, 528) AND DATE(timestamp) = '$current_date'";

// 오늘의 쿼리 실행 및 결과 가져오기
$result_today_event = $conn->query($sql_today_event);
$result_today_dos = $conn->query($sql_today_dos);
$result_today_sni = $conn->query($sql_today_sni);
$result_today_tem = $conn->query($sql_today_tem);
$result_today_det = $conn->query($sql_today_det);

// 오늘의 튜플 개수 가져오기
$today_event_count = ($result_today_event !== false) ? $result_today_event->fetch_assoc()["today_event_count"] : 0;
$today_dos_count = ($result_today_dos !== false) ? $result_today_dos->fetch_assoc()["today_dos_count"] : 0;
$today_sni_count = ($result_today_sni !== false) ? $result_today_sni->fetch_assoc()["today_sni_count"] : 0;
$today_tem_count = ($result_today_tem !== false) ? $result_today_tem->fetch_assoc()["today_tem_count"] : 0;
$today_det_count = ($result_today_det !== false) ? $result_today_det->fetch_assoc()["today_det_count"] : 0;

$data = [
    "event_count" => $event_count,
    "dos_count" => $dos_count,
    "sni_count" => $sni_count,
    "tem_count" => $tem_count,
    "det_count" => $det_count,
    "today_event_count" => $today_event_count,
    "today_dos_count" => $today_dos_count,
    "today_sni_count" => $today_sni_count,
    "today_tem_count" => $today_tem_count,
    "today_det_count" => $today_det_count
];

echo json_encode($data);

// 데이터베이스 연결 종료
$conn->close();
?>
