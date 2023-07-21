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
            if (strlen($password) <= 8) {
                array_push($errors, "Password Must Be 8 Words Long");
            }
            if ($password !== $c_paasword) {
                array_push($errors, "Password does Not Match");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                require_once "database.php";

                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare("INSERT INTO register (FullName, Email, `password`) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $fullname, $email, $passwordhash);

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


        <form action="regi.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="FullName" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="Email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="ConfirmPassword" placeholder="Confirm Password:">
            </div>
            <center>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit" name="submit">
                </div>
            </center>
        </form>
    </div>
</body>

</html>