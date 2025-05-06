<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Info</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color:rgba(156, 165, 225, 0.89);
            font-family: Arial, sans-serif;
        }
        .box {
            background-color: white;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 20px 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .info p {
            margin: 8px 0;
            font-size: 16px;
        }
        .buttons {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 32px;
}

        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .confirm-btn {
            background-color: mediumseagreen;
            color: white;
            border: 2px solid tomato;
        }
        .back-btn {
            background-color: darkred;
            color: white;
            border: 2px solid tomato;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $birthdate = htmlspecialchars($_POST['birthdate']);
    $country = htmlspecialchars($_POST['country']);
    $gender = htmlspecialchars($_POST['gender']);
    $color = htmlspecialchars($_POST['color']);
    $opinion = htmlspecialchars($_POST['opinion']);

    echo '<div class="box">';
    echo '<h2>Confirm Your Information</h2>';
    echo '<div class="info">';
    echo "<p><strong>Full Name:</strong> $fullname</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Birthdate:</strong> $birthdate</p>";
    echo "<p><strong>Country:</strong> $country</p>";
    echo "<p><strong>Gender:</strong> " . ucfirst($gender) . "</p>";
    echo "<p><strong>Favorite Color:</strong> <span style='color: $color;'>$color</span></p>";
    echo "<p><strong>Opinion:</strong> $opinion</p>";
    echo '</div>';
    echo '<div class="buttons">';
    echo '<form action="index.html" method="get" style="margin:0;">';
    echo '<button type="submit" class="back-btn">Back</button>';
    echo '</form>';
    echo '<button class="confirm-btn" onclick="confirmAction()">Confirm</button>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<p>Invalid request method.</p>';
}
?>
<!--<script>
function confirmAction() {
    // Just showing a success message inside the box, no alert/popups
    const box = document.querySelector('.box');
    box.innerHTML = '<h2 style="color: green;">Thank you! Your information has been confirmed.</h2>';
}
</script>-->

</body>
</html>
