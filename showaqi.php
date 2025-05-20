<?php
if (!isset($_POST['cities']) || count($_POST['cities']) < 1) {
    header("Location: request.php?error=Please select at least 1 city.");
    exit();
}
if (count($_POST['cities']) > 10) {
    header("Location: request.php?error=You can select up to 10 cities only.");
    exit();
}

$conn = new mysqli("localhost", "root", "", "AQI");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$city_ids = array_map('intval', $_POST['cities']);
$id_list = implode(",", $city_ids);

$sql = "SELECT City, Country, AQI FROM INFO WHERE ID IN ($id_list)";
$result = $conn->query($sql);

// Get cookie value if set
$bgColor = isset($_COOKIE['fav_color']) ? htmlspecialchars($_COOKIE['fav_color']) : 'hsla(170, 63.00%, 18.00%, 0.80)';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Selected Cities AQI</title>
    <link rel="stylesheet" href="AQI.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: <?= $bgColor ?>;
            margin: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0077aa;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #0077aa;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f6f9fb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selected City AQI Data</h2>
        <table>
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>AQI</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['City']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Country']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['AQI']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No data found.</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
