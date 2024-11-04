<!DOCTYPE html>
<html>
<head>
    <title>Server 1 Metrics</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .highlight { color: red; }
    </style>
</head>
<body>
    <h2>Server 1 Metrics</h2>
    <table>
        <thead>
            <tr>
                <th>CPU</th>
                <th>RAM</th>
                <th>Network</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = new mysqli('localhost', 'your_mysql_user', 'your_mysql_password', 'linux_db');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT cpu, ram, network, date, time FROM metrics WHERE server = '172.17.0.2'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $cpuClass = $row['cpu'] > 60 ? 'highlight' : '';
                    $ramClass = $row['ram'] > 70 ? 'highlight' : '';
                    echo "<tr>
                            <td class='{$cpuClass}'>{$row['cpu']}</td>
                            <td class='{$ramClass}'>{$row['ram']}</td>
                            <td>{$row['network']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No data available</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
    <a href="index.php">Back to Server List</a>
</body>
</html>
