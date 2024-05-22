<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Network Monitoring Dashboard</title>
<link rel="stylesheet" href="styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</script>
</head>
<body>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<header>
    <h1>서버 침입 탐지 모니터링</h1>
</header>

<div class="top-bar">
    <div class="indicator">
        <div class="indicator-title">이벤트 발생 수</div>
        <div class="indicator-count" id="access-notables">0</div>
        <div class="indicator-change up">+<span id="access-today">0</span></div>
    </div>
    <div class="indicator">
        <div class="indicator-title">DOS 공격</div>
        <div class="indicator-count" id="endpoint-notables">0</div>
        <div class="indicator-change no-change">+<span id="endpoint-today">0</span></div>
    </div>
    <div class="indicator">
        <div class="indicator-title">가로채기 해킹 공격</div>
        <div class="indicator-count" id="network-notables">0</div>
        <div class="indicator-change up">+<span id="network-today">0</span></div>
    </div>
    <div class="indicator">
        <div class="indicator-title">변조 공격</div>
        <div class="indicator-count" id="identity-notables">0</div>
        <div class="indicator-change no-change">+<span id="identity-today">0</span></div>
    </div>
    <div class="indicator">
        <div class="indicator-title">탐지 공격</div>
        <div class="indicator-count" id="audit-notables">0</div>
        <div class="indicator-change no-change">+<span id="audit-today">0</span></div>
    </div>
</div>

<div class="container">
   
  <section class="section" id="live-alerts">
    <h1 class="section-title">실시간 탐지된 공격</h1>

    <div id="live-alerts-content">Loading live alerts...</div>
  </section>


  <section class="section" id="recent-logs">
    <h2 class="section-title">최근 침입한 로그</h2>

    <div id="recent-logs-content">Loading recent logs...</div>


    <button id="load-more-button" onclick="window.location.href='more_logs.php'">더 보기</button>
  </section>


  <section class="section" id="attack-chart">
    <h1 class="section-title">시간 경과에 따른 공격 경향</h1>

    <canvas id="myChart"></canvas>
  </section>

</div>

<script src="scripts.js"></script>


</body>
</html>