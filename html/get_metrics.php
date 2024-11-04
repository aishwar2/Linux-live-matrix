<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '2539', 'linux_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT 
            CASE server
                WHEN '172.17.0.2' THEN 'server1'
                WHEN '172.17.0.3' THEN 'server2'
                WHEN '172.17.0.4' THEN 'server3'
                WHEN '172.17.0.5' THEN 'server4'
                WHEN '172.17.0.6' THEN 'server5'
                WHEN '172.17.0.7' THEN 'server6'
                WHEN '172.17.0.8' THEN 'server7'
            END as server_alias,
            cpu, 
            ram, 
            network, 
            date, 
            time 
        FROM metrics
        ORDER BY id DESC";
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
$conn->close();
?>

