<?php
    // Error Variables
    $emailError = $passwordError = '';

    // Validation
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Email
        if (empty($_POST["email"])) {
            $emailError = "Email is required";
        } else {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format";
            } else {
                $emailError = "";
            }
        }
        
        // Password
        if (empty($_POST['password'])) {
            $passwordError = "Password is required";
        } else {
            $passwordError = "";
        }
    }

    // Button Submit
    if (isset($_POST["submit"])) {
        if ($emailError == "" && $passwordError == "") {
            if (file_exists("data/users.json")) {
                if (file_exists("data/users.json")) {
                    $current_data = file_get_contents("data/users.json");
                    $array_data = json_decode($current_data, true);

                    foreach ($array_data as $user) {
                        if ($_POST["email"] == $user["email"] && $_POST["password"] == $user["password"]) {
                            if (is_dir($_POST["email"])) {
                                header("Location: content.php?folder=" . $_POST["email"]);
                                exit();
                            } else {
                                mkdir("data/" . $_POST["email"]);

                                header("Location: content.php?folder=" . $_POST["email"]);
                                exit();
                            }
                            
                        } else {
                            if ($_POST["email"] != $user["email"]) {
                                $emailError = "Email not exist";
                                $passwordError = "";
                            } elseif ($_POST["password"] != $user["password"]) {
                                $passwordError = "Password is wrong";
                                $emailError = "";
                            }
                        }
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>

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
                        <div class="col-4">
                            <div class="Discription">
                                <h3>Sign Up <br><br>
                                Sign up with your simple details.
                                    It will be cross checked by the adminstrattion.</h3>

                                <h3>Sign In <br><br>
                            Sign in with your email and password</h3>
                            </div>
                        </div>

                        <!-- Form -->
                        <div class="col-8">
                            <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <!-- Title -->
                                <div class="card-header">
                                    <h2 class="mt-3 text-center">Sign In</h2>
                                    <p class="text-center">Sign In with your email and password</p>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label class="form-label" for="#email" style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:15px;">Email Adderss</label>
                                    <input name="email" id="email" class="form-control" type="email" style="border: 0;border-radius: 0;background-color: transparent;border-bottom: 1px solid gray; border: 0;border-radius: 0;background-color: transparent;border-bottom: 1px solid gray; padding:0.39rem 0rem;">
                                </div>
                                <?php 
                                            if ($emailError != "") {
                                                echo '<p style="color: red;">' . 
                                                $emailError . "</p>";} ?>
                                <!-- Password -->
                                <div class="form-group">
                                    <label class="form-label" for="#password" style="font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:15px;">Password</label>
                                    <input name="password" id="password" class="form-control" type="password" style="border: 0;border-radius: 0;background-color: transparent;border-bottom: 1px solid gray; border: 0;border-radius: 0;background-color: transparent;border-bottom: 1px solid gray; padding:0.39rem 0rem;">
                                </div>
                                <?php if ($passwordError != "") {
                                                echo '<p style="color: red;">' . 
                                                $passwordError. "</p>";} ?>
                                <br><br>
                                <!-- Submit -->
                                <div class="form-group text-center">
                                <button type="submit" name="submit" class="btn btn-success btn-sm  rounded-0 border radious-0" style="width: 100px; background-color:#46d53b;">Sign In</button>
                                    <span class="p-2">Or</span>
                                    <a href="index.php">Sign Up</a>
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