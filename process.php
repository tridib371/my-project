<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // First check if this is coming from confirmation page
    $confirmed = isset($_POST['confirmed']) && $_POST['confirmed'] == 'yes';

    // Get form data
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $birthdate = htmlspecialchars($_POST['birthdate'] ?? '');
    $country = htmlspecialchars($_POST['country'] ?? '');
    $gender = htmlspecialchars($_POST['gender'] ?? '');
    $color = htmlspecialchars($_POST['color'] ?? '');
    $opinion = htmlspecialchars($_POST['opinion'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$confirmed) {
        // Show confirmation page
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Confirm Your Information</title>
            <style>
                body {
                    display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;
                    background-color:rgba(156, 165, 225, 0.89); font-family: Arial, sans-serif;
                }
                .box {
                    background-color: white; border: 2px solid #333; border-radius: 10px; padding: 20px 30px;
                    max-width: 500px; width: 100%; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                }
                .box h2 {text-align: center; margin-bottom: 20px;}
                .info p {margin: 8px 0; font-size: 16px;}
                .buttons {margin-top: 20px; display: flex; justify-content: center; gap: 32px;}
                .buttons button, .buttons input[type=submit] {
                    padding: 10px 20px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer;
                }
                .confirm-btn {
                    background-color: mediumseagreen; color: white; border: 2px solid tomato;
                }
                .back-btn {
                    background-color: darkred; color: white; border: 2px solid tomato;
                }
            </style>
        </head>
        <body>
            <div class="box">
                <h2>Confirm Your Information</h2>
                <div class="info">
                    <p><strong>Full Name:</strong> <?= $fullname ?></p>
                    <p><strong>Email:</strong> <?= $email ?></p>
                    <p><strong>Birthdate:</strong> <?= $birthdate ?></p>
                    <p><strong>Country:</strong> <?= $country ?></p>
                    <p><strong>Gender:</strong> <?= ucfirst($gender) ?></p>
                    <p><strong>Favorite Color:</strong> <span style="color: <?= $color ?>"><?= $color ?></span></p>
                    <p><strong>Opinion:</strong> <?= $opinion ?></p>
                </div>

                <form method="POST" action="process.php">
                    <input type="hidden" name="fullname" value="<?= $fullname ?>">
                    <input type="hidden" name="email" value="<?= $email ?>">
                    <input type="hidden" name="birthdate" value="<?= $birthdate ?>">
                    <input type="hidden" name="country" value="<?= $country ?>">
                    <input type="hidden" name="gender" value="<?= $gender ?>">
                    <input type="hidden" name="color" value="<?= $color ?>">
                    <input type="hidden" name="opinion" value="<?= $opinion ?>">
                    <input type="hidden" name="password" value="<?= htmlspecialchars($password) ?>">
                    <input type="hidden" name="confirmed" value="yes">
                    
                    <div class="buttons">
                        <button type="button" onclick="window.history.back();" class="back-btn">Back</button>
                        <input type="submit" class="confirm-btn" value="Confirm">
                    </div>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Save to database
        $conn = new mysqli("localhost", "root", "", "aqi");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO user (FullName, Email, Password, BirthDate, Country, Gender, Opinion) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $fullname, $email, $hashedPassword, $birthdate, $country, $gender, $opinion);

        if ($stmt->execute()) {
            setcookie('fav_color', $color ? $color : '#000000', time() + 30*24*60*60, "/");
            
            // Show success message
            echo '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Submission Successful</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: rgba(156, 165, 225, 0.89);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                    }
                    .message-box {
                        background: white;
                        padding: 30px 40px;
                        border-radius: 12px;
                        text-align: center;
                        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                        max-width: 450px;
                    }
                    .message-box h2 {
                        color: mediumseagreen;
                    }
                </style>
                <meta http-equiv="refresh" content="5;url=index.php">
            </head>
            <body>
                <div class="message-box">
                    <h2>Your information has been submitted!</h2>
                    <p>You will be redirected to the homepage shortly.</p>
                    <p>If not, <a href="index.php">click here</a>.</p>
                </div>
            </body>
            </html>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
} else {
    header("Location: index.php");
    exit;
}
?>