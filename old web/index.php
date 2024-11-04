<!DOCTYPE html>
<html>
<head>
    <title>Server Metrics</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .highlight { color: red; }
    </style>
</head>
<body>
    <h2>Server Metrics</h2>
    <table>
        <thead>
            <tr>
                <th>Server</th>
                <th>CPU</th>
                <th>RAM</th>
                <th>Network</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'your_mysql_user', 'your_mysql_password', 'linux_db');

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch data
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
                    FROM metrics";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $cpuClass = $row['cpu'] > 60 ? 'highlight' : '';
                    $ramClass = $row['ram'] > 70 ? 'highlight' : '';
                    echo "<tr>
                            <td>{$row['server_alias']}</td>
                            <td class='{$cpuClass}'>{$row['cpu']}</td>
                            <td class='{$ramClass}'>{$row['ram']}</td>
                            <td>{$row['network']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No data available</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
