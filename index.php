<?php
$username_val = ''; // initialize to empty string to avoid warnings
$login_error = ['username' => '', 'password' => ''];
// Connect to DB
$conn = new mysqli("localhost", "root", "", "aqi");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username_val = trim($_POST['username'] ?? '');
    $password_val = $_POST['password'] ?? '';

    // Input validations as before...

    if (empty($login_error['username']) && empty($login_error['password'])) {
        // Prepare statement to get user info by username (email)
        $stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
        $stmt->bind_param("s", $username_val);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            // Verify password
            if (password_verify($password_val, $hashed_password)) {
                // Password correct, redirect to request.php
                header("Location: request.php");
                exit;
            } else {
                $login_error['password'] = "Incorrect username or password";
            }
        } else {
            $login_error['password'] = "Incorrect username or password";
        }
        $stmt->close();
    }
    $conn->close();
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <title>Form Validation</title>

  <link rel="stylesheet" href="AQI.css">
  <link rel="icon" type="image/png" href="assets/favicon.png">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    .error {
      color: red;
      font-size: 14px;
      margin-top: 5px;
    }

    .success {
      color: green;
      font-size: 17px;
      font-weight: bold;
      margin-top: 10px;
      text-align: center;
    }
  </style>
</head>

<body
  style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 0; padding: 20px;">

  <div style="text-align: center;">
    <img src="assets/th.jpeg" alt="image" style="border-radius: 50%; width: 105px; height: 100px;">
    <h2>AQI Index</h2>
  </div>

  <div
    style="width: 100%; max-width: 600px; min-height: 100vh; display: flex; border: 2px solid black; flex-wrap: wrap;">

    <!-- Left Side Box -->
    <div
      style="flex: 1; display: flex; justify-content: center; align-items: center; border-right: 2px solid black; background:burlywood; padding: 20px; flex-direction: column;">
      <h2>Registration Form</h2>
      <form id="registrationForm" action="process.php" method="post" onsubmit="return validateForm()"
        style="width: 100%; max-width: 300px;">

        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" style="width: 100%;">
        <div id="nameError" class="error"></div><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" style="width: 100%;">
        <div id="emailError" class="error"></div><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" style="width: 100%;">
        <div id="passwordError" class="error"></div><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" style="width: 100%;">
        <div id="confirmError" class="error"></div><br>

        <label for="birthdate">Birth Date:</label>
        <input type="date" id="birthdate" name="birthdate" style="width: 100%;">
        <div id="birthError" class="error"></div><br>

        <label for="country">Country:</label>
        <select id="country" name="country" style="width: 100%;">
          <option value="">Select a country</option>
          <option value="USA">USA</option>
          <option value="Canada">Canada</option>
          <option value="UK">UK</option>
          <option value="Australia">Australia</option>
          <option value="Bangladesh">Bangladesh</option>
          <option value="Germany">Germany</option>
        </select><br><br>

        <label>Gender:</label><br>
        <input type="radio" id="male" name="gender" value="male">
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="female">
        <label for="female">Female</label>
        <input type="radio" id="other" name="gender" value="other">
        <label for="other">Other</label>
        <div id="genderError" class="error"></div><br>

        <label for="color">Favorite Color:</label>
        <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
          <input type="color" id="color" name="color" style="width: 50px; height: 30px; border: none; padding: 0;">
          <span id="colorValue" style="font-weight: bold;">#000000</span>
        </div><br><br>

        <label for="opinion">Opinion:</label><br>
        <textarea id="opinion" name="opinion" cols="25" rows="5" style="width: 100%; resize: none;"></textarea><br><br>

        <div style="display: flex; align-items: center;">
          <label for="terms" style="white-space: nowrap;">I agree to the
            <a href="#" onclick="event.preventDefault()">Terms and Conditions</a>
          </label>
          <input type="checkbox" id="terms" name="terms" style="transform: scale(1.1); margin-left: 10px;">
        </div>
        <div id="termsError" class="error"></div><br>

        <div style="text-align: center;">
          <button type="submit"
            style="padding: 7px 20px; background-color: mediumseagreen; color: white; border-radius: 6px; border: 2px solid tomato;">Submit</button>
        </div>

        <div id="successMessage" class="success"></div>
      </form>
    </div>

    <!-- Right Side Box -->
    <div style="flex: 1; display: flex; flex-direction: column; background-color: blueviolet; height: auto;">

      <div
        style="flex: 0.3; justify-content: center; align-items: flex-start; border-bottom: 2px solid black; background-color: goldenrod; padding: 20px;">

        <h2 style="margin-left: 75px;">LOGIN</h2>

        <form method="POST" action="" style="margin-left: 20px; width: 90%; max-width: 300px;">
          <label for="username">Username (Email):</label><br>
          <input type="email" id="username" name="username" required
            value="<?php echo htmlspecialchars($username_val); ?>" style="width: 100%; padding: 5px;">
          <?php if (!empty($login_error['username'])): ?>
            <div class="error"><?php echo $login_error['username']; ?></div>
          <?php endif; ?>

          <br><br>

          <label for="password">Password:</label><br>
          <input type="password" id="password" name="password" required style="width: 100%; padding: 5px;">
          <?php if (!empty($login_error['password'])): ?>
            <div class="error"><?php echo $login_error['password']; ?></div>
          <?php endif; ?>

          <br><br>

          <button type="submit" name="login"
            style="padding: 7px 20px; background-color: darkcyan; color: white; border-radius: 6px; border: 2px solid tomato; margin-left: 75px;">
            Login
          </button>
        </form>

      </div>

      <div
        style="flex: 1; padding: 0; position: relative; height: 100%; display: flex; flex-direction: column; margin: 15px;">

        <!-- Table wrapper -->
        <div style="filter: blur(2px); opacity: 0.5; flex: 1; overflow: auto;">
          <table style="width: 100%; height: 100%; border-collapse: collapse;">
            <thead>
              <tr>
                <th>City</th>
                <th>Aqi</th>
              </tr>
            </thead>
            <tbody>
              <!-- Empty rows to fill space -->
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
        <h2
          style="position: absolute; top: 47%; left: 50%; transform: translate(-50%, -50%); padding: 10px 20px; background-color: antiquewhite; border-radius: 21px; white-space: nowrap; display: inline-block;">Login
          First</h2>
      </div>

    </div>

  </div>

  <!-- JavaScript Validation -->
  <script>
    function validateForm() {
      const name = document.getElementById("fullname").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      const birthDate = new Date(document.getElementById("birthdate").value);
      const currentDate = new Date();
      const genderSelected = document.querySelector('input[name="gender"]:checked');
      const termsChecked = document.getElementById("terms").checked;

      // Calculate age by subtracting birth year from current year
      let age = currentDate.getFullYear() - birthDate.getFullYear();
      const monthDiff = currentDate.getMonth() - birthDate.getMonth();

      // Adjust age if birthday hasn't occurred yet this year
      if (monthDiff < 0 || (monthDiff === 0 && currentDate.getDate() < birthDate.getDate())) {
        age--;
      }

      let isValid = true;

      // Reset all error messages
      document.getElementById("nameError").textContent = "";
      document.getElementById("emailError").textContent = "";
      document.getElementById("passwordError").textContent = "";
      document.getElementById("confirmError").textContent = "";
      document.getElementById("birthError").textContent = "";
      document.getElementById("genderError").textContent = "";
      document.getElementById("termsError").textContent = "";
      document.getElementById("successMessage").textContent = "";

      // Name Validation (no numbers/special characters)
      const nameRegex = /^[A-Za-z\s]+$/;
      if (!nameRegex.test(name)) {
        document.getElementById("nameError").textContent = "Name should not contain numbers or special characters.";
        isValid = false;
      }

      // Email validation (AIUB format only)
      const aiubRegex = /^[0-9]{2}-[0-9]{5}-[0-9]@student\.aiub\.edu$/;
      if (!aiubRegex.test(email)) {
        document.getElementById("emailError").textContent = "Email must be in the format: 22-46444-1@student.aiub.edu";
        isValid = false;
      }

      // Password Validation
      const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      if (!passwordRegex.test(password)) {
        document.getElementById("passwordError").textContent = "Password must be at least 8 characters, with uppercase, lowercase, number, and special character.";
        isValid = false;
      }

      // Confirm Password Validation
      if (password !== confirmPassword) {
        document.getElementById("confirmError").textContent = "Passwords do not match.";
        isValid = false;
      }

      // Age Validation (18 or older)
      if (isNaN(birthDate.getTime()) || age < 18) {
        document.getElementById("birthError").textContent = "You must be 18 years or older.";
        isValid = false;
      }

      // Gender Validation
      if (!genderSelected) {
        document.getElementById("genderError").textContent = "Please select a gender.";
        isValid = false;
      }

      // Terms Validation
      if (!termsChecked) {
        document.getElementById("termsError").textContent = "You must agree to the terms and conditions.";
        isValid = false;
      }


      if (isValid) {
        document.getElementById("registrationForm").submit();
      }


      if (isValid) {
        return true; // Allow form to submit
      } else {
        return false; // Block submission if invalid
      }

    }

    // Update color preview
    document.getElementById("color").addEventListener("input", function () {
      document.getElementById("colorValue").textContent = this.value.toUpperCase();
    });
  </script>
</body>

</html>

