<?php
$conn = new mysqli("localhost", "root", "", "AQI");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM INFO";
$result = $conn->query($sql);

$error = "";
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Cities</title>
    <link rel="stylesheet" href="AQI.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgba(32, 22, 91, 0.67);
            margin: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0077aa;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin: 8px 0;
            padding: 5px 10px;
        }

    .submit-btn {
                display: block;
                width: 23%;
                padding: 10px;
                background-color: #0077aa;
                color: white;
                border: none;
                font-size: 17px;
                border-radius: 9px;
                cursor: pointer;
                margin: 15px auto 0 auto;  /* shorthand: top, right, bottom, left */
}


        .submit-btn:hover {
            background-color: #005f88;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Exactly 10 Cities</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="showaqi.php">
            <?php
            if ($result->num_rows > 0) {
                $count = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<label>";
                    echo $count . ". ";
                    echo "<input type='checkbox' name='cities[]' value='" . $row['ID'] . "'> ";
                    echo htmlspecialchars($row['City']);
                    echo "</label>";
                    $count++;
                }
            } else {
                echo "No cities found.";
            }
            $conn->close();
            ?>

            <input type="submit" class="submit-btn" value="Submit">
        </form>
    </div>
</body>
</html>
