<?php
$servername = "192.168.111.132";
$username = "newuser";
$password = "1234";
$dbname = "snort"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT signature, timestamp FROM event ORDER BY timestamp DESC LIMIT 5";
$result = $conn->query($sql);

$alerts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alertMessage = "";
        switch ($row["signature"]) {
            case "535":
                $alertMessage = "ICMP Flooding Attack 탐지";
                break;
            case "533":
                $alertMessage = "TCP SYN Flooding Attack 탐지";
                break;
            case "537":
                $alertMessage = "TCP ACK Flooding Attack 탐지";
                break;
            case "534":
                $alertMessage = "UDP Flooding Attack 탐지";
                break;
            case "532":
                $alertMessage = "SSH brute force 탐지";
                break;
            case "538":
                $alertMessage = "FTP brute force 탐지";
                break;
            case "539":
                $alertMessage = "Telnet brute force 탐지";
                break;
            case "536":
                $alertMessage = "telnet 탐지(로그인 실패)";
                break;
            case "531":
                $alertMessage = "SSH 탐지";
                break;
            case "522":
                $alertMessage = "XMAS 포트 스캔 탐지";
                break;
            case "530":
                $alertMessage = "TCP Open Scan";
                break;
            case "525":
                $alertMessage = "Null Scan";
                break;
            case "528":
                $alertMessage = "Land Attack(종단간 동일지점 동일한 IP)";
                break;
            case "527":
                $alertMessage = "SYN + FIN 플래그 조합 비정상적 TCP 플래그";
                break;
            case "526":
                $alertMessage = "FIN 플래그가 설정되지 않은 비정상적 TCP 플래그";
                break;
            case "524":
                $alertMessage = "플래그가 설정되지 않은 비정상적 TCP 플래그";
                break;
            default:
                break;
        }
        if ($alertMessage) {
            $alerts[] = [
                "message" => $alertMessage,
                "timestamp" => $row["timestamp"]
            ];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($alerts);

$conn->close();
?>
