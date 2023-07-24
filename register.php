<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreation Page</title>
</head>

<body>
    <center>
        <h2 class="head">Sign-Up</h2>
    </center>

    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullname = $_POST["FullName"];
            $email = $_POST["Email"];
            $address = $_POST["address"];
            $Gender = $_POST["gender"];
            $password = $_POST["password"];
            $c_paasword = $_POST["ConfirmPassword"];
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();
            if (empty($fullname) || empty($email) || empty($password) || empty($c_paasword)) {
                array_push($errors, "All Fields Are Required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Invalid email format");
            }
            if (empty($address)) {
                array_push($errors, "Address Is Necessary");
            }
            if (empty($Gender)) {
                array_push($errors, "Please Select A Gender");
            }
            if (strlen($password) <= 8) {
                array_push($errors, "Password Must Be 8 Words Long");
            }
            if ($password !== $c_paasword) {
                array_push($errors, "Password does Not Match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM register WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email Already Exist!!");
            }
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {


                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO register (FullName, Email,address,gender, `password`) VALUES (?, ?, ?, ? ,?)");
                $stmt->bind_param("sssss", $fullname, $email, $address, $Gender, $passwordhash);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>You've Registered Successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Failed to insert record. Please try again later.</div>";
                }

                $stmt->close();
                $conn->close();
            }
        }
        ?>


        <form action="register.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="FullName" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="Email" placeholder="Email:">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="address" placeholder="Address:"></textarea>
            </div>

            <div class="form-group">
                <input type="radio" name="gender" value="Male">
                <label for="male">Male</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="gender" value="Female">
                <label for="female">Female</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="gender" value="Others">
                <label for="others">Others</label><br>
            </div>

            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="ConfirmPassword" placeholder="Confirm Password:">
            </div>
            <center>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </center>
        </form>
        <div>
            <div>
                <p>Already Registered <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>

</html>