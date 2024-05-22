<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Intrusion Logs</title>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="styles1.css">
</head>
<body>

<header>
    <h1>All Intrusion Logs</h1>
</header>

<div class="container">
    <section class="section" id="ip-header-logs">
        <h2 class="section-title">누적 침입 로그</h2>
        <div id="ip-header-logs-content">
            <?php
           $servername = "192.168.111.132";
           $username = "newuser";
           $password = "1234";
           $dbname = "snort"; 
           
            $conn = new mysqli($servername, $username, $password, $dbname);

            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $perPage = 10; 
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $perPage;

            $countSql = "SELECT COUNT(*) AS cnt FROM iphdr";
            $countResult = $conn->query($countSql);
            $countRow = $countResult->fetch_assoc();
            $totalPages = ceil($countRow['cnt'] / $perPage);

            $sql = "SELECT e.signature, i.ip_src, i.ip_dst, i.ip_ver, i.ip_len, i.ip_ttl, e.timestamp 
                    FROM event e
                    JOIN iphdr i ON e.sid = i.sid AND e.cid = i.cid 
                    ORDER BY e.timestamp DESC 
                    LIMIT $offset, $perPage";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><tr><th>Signature</th><th>IP Source</th><th>IP Destination</th><th>IP Version</th><th>Length</th><th>TTL</th><th>Timestamp</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $alertMessage = ""; 
                    
                    switch ($row["signature"]) {
                        case "535": $alertMessage = "ICMP Flooding Attack 탐지"; break;
                        case "533": $alertMessage = "TCP SYN Flooding Attack 탐지"; break;
                        case "537": $alertMessage = "TCP ACK Flooding Attack 탐지"; break;
                        case "534": $alertMessage = "UDP Flooding Attack 탐지"; break;
                        case "532": $alertMessage = "SSH brute force 탐지"; break;
                        case "538": $alertMessage = "FTP brute force 탐지"; break;
                        case "539": $alertMessage = "Telnet brute force 탐지"; break;
                        case "536": $alertMessage = "telnet 탐지(로그인 실패)"; break;
                        case "531": $alertMessage = "SSH 탐지"; break;
                        case "522": $alertMessage = "XMAS 포트 스캔 탐지"; break;
                        case "530": $alertMessage = "TCP Open Scan"; break;
                        case "525": $alertMessage = "Null Scan"; break;
                        case "528": $alertMessage = "Land Attack(종단간 동일지점 동일한 IP)"; break;
                        case "527": $alertMessage = "SYN + FIN 플래그 조합 비정상적 TCP 플래그"; break;
                        case "526": $alertMessage = "FIN 플래그가 설정되지 않은 비정상적 TCP 플래그"; break;
                        case "524": $alertMessage = "플래그가 설정되지 않은 비정상적 TCP 플래그"; break;
                        default: $alertMessage = "Unknown Attack"; 
                    }
                    echo "<tr>";
                    echo "<td>" . $alertMessage . "</td>";
                    echo "<td>" . htmlspecialchars(long2ip($row["ip_src"])) . "</td>";
                    echo "<td>" . htmlspecialchars(long2ip($row["ip_dst"])) . "</td>";
                    echo "<td>" . $row["ip_ver"] . "</td>";
                    echo "<td>" . $row["ip_len"] . "</td>";
                    echo "<td>" . $row["ip_ttl"] . "</td>";
                    echo "<td>" . $row["timestamp"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='log-entry'>No IP header logs found.</div>";
            }

            
            echo "<div class='pagination'>";
            $startRange = max(1, $page - 2);
            $endRange = min($totalPages, $page + 2);

            if ($startRange > 1) {
                echo "<a href='?page=1'>First</a>";
                echo "<span>...</span>";
            }

            for ($i = $startRange; $i <= $endRange; $i++) {
                echo "<a href='?page=$i'" . ($i == $page ? " class='active'" : "") . ">" . $i . "</a>";
            }

            if ($endRange < $totalPages) {
                echo "<span>...</span>";
                echo "<a href='?page=$totalPages'>Last</a>";
            }

            echo "</div>";

            
            $conn->close();
            ?>
        </div>
    </section>
</div>

</body>
</html>
