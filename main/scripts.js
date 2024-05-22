document.addEventListener('DOMContentLoaded', function() {
    initAttackTrendsChart();
    fetchAndUpdateRecentLogs();
    fetchAndUpdateLiveAlerts();
    fetchMoreLogs();
    setInterval(fetchAndUpdateLiveAlerts, 5000); 
    setInterval(fetchAndUpdateRecentLogs, 10000); 
    setInterval(fetchMoreLogs, 5000);
    fetchCounts();
    setInterval(fetchCounts,5000);

  
});

const COLORS = [
    'rgba(255, 99, 132, 0.8)',
    'rgba(255, 206, 86, 0.8)',
    'rgba(75, 192, 192, 0.8)',
    'rgba(153, 102, 255, 0.8)',
    'rgba(54, 162, 235, 0.8)',
    'rgba(255, 159, 64, 0.8)',
    'rgba(255, 99, 132, 0.8)',
    'rgba(75, 192, 235, 0.8)',
    'rgba(153, 102, 235, 0.8)',
    'rgba(255, 206, 64, 0.8)',
    'rgba(54, 162, 64, 0.8)'
];

function fetchAndUpdateLiveAlerts() {
    fetch('fetch_live_alerts.php')
        .then(response => response.json())
        .then(data => {
            updateLiveAlerts(data);
            updateChartWithLiveAlerts(data); 
        })
        .catch(error => {
            console.error('Error fetching live alerts:', error);
        });
}

function updateLiveAlerts(data) {
    const liveAlertsContent = document.getElementById('live-alerts-content');
    liveAlertsContent.innerHTML = ''; 
    
    if (data.length === 0) {
        const noAlertsDiv = document.createElement('div');
        noAlertsDiv.className = 'no-alerts';
        noAlertsDiv.textContent = 'No alerts found';
        liveAlertsContent.appendChild(noAlertsDiv);
    } else {
        data.forEach(alert => {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert';
            const timestampDiv = document.createElement('div');
            timestampDiv.className = 'timestamp';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message';

            timestampDiv.textContent = new Date(alert.timestamp).toLocaleString();
            messageDiv.textContent = alert.message;

            alertDiv.appendChild(timestampDiv);
            alertDiv.appendChild(messageDiv);
            liveAlertsContent.appendChild(alertDiv);
        });
    }
}

function initAttackTrendsChart() {
    const ctx = document.getElementById('myChart').getContext('2d');
    myChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: [], 
            datasets: [{
                label: 'Number of Incidents',
                backgroundColor: COLORS,
                borderColor: COLORS,
                data: [] 
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Attack Type',
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Incidents',
                    },
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.raw;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        },
    });
}

function fetchAndUpdateRecentLogs() {
    fetch('fetch_recent_logs.php')
        .then(response => response.json())
        .then(data => {
            updateRecentLogs(data);
        })
        .catch(error => {
            console.error('Error fetching recent logs:', error);
        });
}

function updateRecentLogs(data) {
    const recentLogsContent = document.getElementById('recent-logs-content');
    recentLogsContent.innerHTML = ''; 

    const table = document.createElement('table');
    table.setAttribute('width', '100%');

    
    const headerRow = document.createElement('tr');
    
    const headers = ['Signature', 'IP Source', 'IP Destination', 'IP Version', 'Length', 'TTL', 'Timestamp'];
    headers.forEach(headerText => {
        const headerCell = document.createElement('th');
        headerCell.textContent = headerText;
        headerRow.appendChild(headerCell);
    });
    table.appendChild(headerRow);

    
    data.forEach(log => {
        const row = document.createElement('tr');
        row.classList.add('main_quick_surf');

        const signatureCell = document.createElement('td');
        signatureCell.textContent = log.signature_message; 

        const ipSrcCell = document.createElement('td');
        ipSrcCell.textContent = log.ip_src;

        const ipDstCell = document.createElement('td');
        ipDstCell.textContent = log.ip_dst;

        const ipVerCell = document.createElement('td');
        ipVerCell.textContent = log.ip_ver;

        const ipLenCell = document.createElement('td');
        ipLenCell.textContent = log.ip_len;

        const ipTTLCell = document.createElement('td');
        ipTTLCell.textContent = log.ip_ttl;

        const timestampCell = document.createElement('td');
        timestampCell.textContent = log.timestamp;

        
        row.appendChild(signatureCell);
        row.appendChild(ipSrcCell);
        row.appendChild(ipDstCell);
        row.appendChild(ipVerCell);
        row.appendChild(ipLenCell);
        row.appendChild(ipTTLCell);
        row.appendChild(timestampCell);

        
        table.appendChild(row);
    });

    
    recentLogsContent.appendChild(table);
}


function updateChartWithLiveAlerts(data) {
    const alertCounts = {}; 
    const currentTime = Date.now();

    
    data.forEach(alert => {
        if (!alertCounts[alert.message]) {
            alertCounts[alert.message] = 0;
        }
        alertCounts[alert.message] += 1;
    });

    
    const labels = [];
    const values = [];
    for (const message in alertCounts) {
        labels.push(message);
        values.push(alertCounts[message]);
    }

    myChart.data.labels = labels;
    myChart.data.datasets[0].data = values;
    myChart.update();
}
function fetchMoreLogs() {
    $.ajax({
        url: 'fetch_recent_logs.php',
        type: 'GET',
        success: function(data) {
            if (data.length > 0) {
                data.forEach(log => {
                    const row = `<tr>
                                    <td>${log.signature_message}</td>
                                    <td>${log.ip_src}</td>
                                    <td>${log.ip_dst}</td>
                                    <td>${log.ip_ver}</td>
                                    <td>${log.ip_len}</td>
                                    <td>${log.ip_ttl}</td>
                                    <td>${log.timestamp}</td>
                                </tr>`;
                    $('#recent-logs-table').append(row);
                });
            } else {
                $('#recent-logs-content').html('<p>No recent logs found.</p>');
            }
        },
        error: function() {
            console.error('Error fetching recent logs.');
        }
    });
}
function fetchCounts() {
    $.ajax({
        url: 'topbar.php', // PHP 파일의 URL
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Success:', data); // 콘솔에 성공 메시지와 데이터 출력
            if (data.error) {
                console.error('Server error:', data.error);
                return;
            }
            $('#access-notables').text(data.event_count);
            $('#endpoint-notables').text(data.dos_count);
            $('#network-notables').text(data.sni_count);
            $('#identity-notables').text(data.tem_count);
            $('#audit-notables').text(data.det_count);
            $('#access-today').text(data.today_event_count);
            $('#endpoint-today').text(data.today_dos_count);
            $('#network-today').text(data.today_sni_count);
            $('#identity-today').text(data.today_tem_count);
            $('#audit-today').text(data.today_det_count);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            console.log(xhr.responseText); // 응답 텍스트 출력
        }
    });
}
