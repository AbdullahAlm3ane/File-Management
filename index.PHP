<?php
    // Error Variables
    $firstNameError = $lastNameError = $emailError = $passwordError = $termsError = '';

    // Validation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // First Name
        if (empty($_POST['firstName'])) {
            $firstNameError = "First Name is required";
        } else {
            $firstNameError = "";
        }
        
        // Last Name
        if (empty($_POST['lastName'])) {
            $lastNameError = "Last is required";
        } else {
            $firstNameError = "";
        }
        
        // Email
        if (empty($_POST["email"])) {
            $emailError = "Email is required";
        } else {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format";
            } else {
                if (file_exists("data/users.json")) {
                    $current_data = file_get_contents("data/users.json");
                    $array_data = json_decode($current_data, true);

                    foreach ($array_data as $user) {
                        if ($_POST["email"] == $user["email"]) {
                            $emailError = "Email already exist";
                        } else {
                            $emailError = "";
                        }
                    }
                } else {
                    $emailError = "";
                }
            }
        }
        
        // Password
        if (empty($_POST['password'])) {
            $passwordError = "Password is required";
        } else {
            $passwordError = "";
        }
        
        // Terms
        if (empty($_POST['terms'])) {
            $termsError = "Terms is required";
        } else {
            $termsError = "";
        }
    }

    // Button Submit
    if (isset($_POST["submit"])) {
        if ($firstNameError == "" && $lastNameError == "" && $emailError == "" && $passwordError == "" && $termsError == "") {
            $final_data = '';
            if (file_exists("data/users.json")) {
                $final_data = fileWriteAppend();
                file_put_contents('data/users.json', $final_data);
            } else {
                $final_data = fileCreateWrite();
                file_put_contents('data/users.json', $final_data);
            }

            mkdir("data/" . $_POST["email"]);

            header("Location: content.php?folder=" . $_POST["email"]);
            exit();
        }
    }

    // Add to Exist File
    function fileWriteAppend() {
        $current_data = file_get_contents('data/users.json');
        $array_data = json_decode($current_data, true);

        $extra = array(
            "name" => $_POST["firstName"] . ' ' . $_POST["lastName"],
            "email" => $_POST["email"],
            "password" => $_POST["password"]
        );

        $array_data[] = $extra;
        $final_data = json_encode($array_data);

        return $final_data;
    }

    // Create file then add
    function fileCreateWrite() {
        $file = fopen("data/users.json", "w");
        $array_data = [];

        $extra = [
            "name" => $_POST["firstName"] . ' ' . $_POST["lastName"],
            "email" => $_POST["email"],
            "password" => $_POST["password"]
        ];
        
        $array_data[] = $extra;
        $final_data = json_encode($array_data);
        fclose($file);

        return $final_data;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

</head>

<body>
    <div class="bg-cover">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Discription -->
                        <div class="col-sm-5 col-12">
                            <div class="Discription">
                                <div>
                                    <h3>Sign Up</h3>
                                    <p>
                                        Sign up with your simple details.</br>
                                        It will be cross checked by the adminstrattion.
                                    </p>
                                </div>
                                <div>
                                    <h3>Sign In</h3>
                                    <p>Sign in with your email and password</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <div class="col-sm-7 col-12">
                            <form class="form" action="" method="post">
                                <!-- Title -->
                                
                                <!-- FIRST NAME -->
                                <div class="form-group Login">
                                    <label class="form-label" for="#firstName">FIRST NAME</label>
                                    <input name="firstName" id="firstName" class="form-control" type="text" placeholder="Enter First Name">
                                </div>

                                <?php if ($firstNameError != "") {
                                echo '<p style="color: red;">' .$firstNameError . "</p>";} ?>
                                
                                <!-- Last NAME -->
                                <div class="form-group Login">
                                    <label class="form-label" for="#lastName">Last NAME</label>
                                    <input name="lastName" id="lastName" class="form-control" type="text" placeholder="Enter Last Name">
                                </div>

                                <?php if ($lastNameError != "") {
                                echo '<p style="color: red;">' .$lastNameError . "</p>";} ?>
                                
                                <!-- Email -->
                                <div class="form-group Login">
                                    <label class="form-label" for="#email">Email Adderss</label>
                                    <input name="email" id="email" class="form-control" type="email" placeholder="Enter Your Email (example@gmail.com)">
                                </div>
                                
                                <?php if ($emailError != "") {
                                echo '<p style="color: red;">' .$emailError . "</p>";} ?>
                                
                                <!-- Password -->
                                <div class="form-group Login">
                                    <label class="form-label" for="#password">Password</label>
                                    <input name="password" id="password" class="form-control" type="password" placeholder="Enter Your Password">
                                </div>
                                
                                <?php if ($passwordError != "") {
                                echo '<p style="color: red;">' .$passwordError . "</p>";} ?>
                                <br>
                                <!-- Terms -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms">
                                    <label class="form-check-label" for="terms">
                                        I agree with the terms and conditions
                                    </label>
                                </div>
                                
                                <?php if ($termsError != "") {
                                echo '<p style="color: red;">' .$termsError . "</p>";} ?>
                                <br>
                                <!-- Submit -->
                                <div class="form-group text-left">
                                    <input type="submit" name="submit" class="btn btn-primary submit-button fs-14" value="Sign Up">
                                    <span class="p-2 fs-14 user-select-none">Or</span>
                                    <a class="text-decoration-none C-black fs-14" href="login.php">Log In</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous">
    </script>
</body>

</html>