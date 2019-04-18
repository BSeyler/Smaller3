<?php

require('db/mysqli_connect-wtia.php');
session_start();
if(isset($_SESSION['email'])) {

    $email = $_SESSION['email'];
    $query = "SELECT user_type FROM users WHERE email='$email'";
    $result = @mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $user_type = $row['user_type'];

    if($user_type == "Teacher") {
        $_SESSION['type'] = 'Teacher';
        header('Location: teacher_home.php');
        exit;
    } elseif ($user_type == "Professional") {
        $_SESSION['type'] = 'Pro';
        header('Location: it_home.php');
        exit;
    }
}


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    if (empty($_POST['email'])) {
        $errors[] = 'Please enter your email.';
    } else if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    }

    if (empty($_POST['password'])) {
        $errors[] = 'Please enter your password.';
    } else if (isset($_POST['password'])) {
        $password = mysqli_real_escape_string($dbc, trim($_POST['password']));
    }
    if(empty($errors)) {

        $query = "SELECT email, first_name, user_type, password FROM users WHERE email='$email'";
        $result = @mysqli_query($dbc, $query);
        if($result) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if(hash('sha512', $password) == $row['password']) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['first_name'];
                if($row['user_type'] == "Teacher") {
                    $_SESSION['type'] = 'Teacher';
                    header('Location: teacher_home.php');
                    exit;
                } elseif($row['user_type'] == "Professional") {
                    $_SESSION['type'] = 'Pro';
                    header('Location: it_home.php');
                    exit;
                } else {
                    echo '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Invalid password entered.</div>';

            }
        } else {
            echo '<div class="alert alert-danger">Something went wrong. Please try again.</div>';
        }

    } else {    //turn this into modal?
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="jason_styles.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans" rel="stylesheet">
    <link rel="icon" href="includes/favicon.png">
    <title>Login</title>
</head>
<body style="background-color: #8adae2;"> 
    
<div class="container mx-auto p-3 py-5 border shadow bg-light" style="background-color: #f4f4f4; width: 390px; margin-top: 75px; margin-bottom: 75px;">
    <h1 class="text-center mb-4">WTIA <p class="color">Connections</p></h1>
    <p class="text-center">Sign in</p>
    <form method="post" action="index.php">
        
        <div class="row my-2 mx-4">
            <div class="col">
                <input type="email" class="form-control" placeholder="Email" name="email">
            </div>
        </div>    
        <div class="row my-3 mx-4">    
            <div class="col">
                <input type="password" class="form-control" placeholder="Password" name="password">
            </div>
        </div>
        <div class="row mt-4 text-center">
            <div class="col">
            <input class="btn btn-secondary text-uppercase" style="width: 276px; font-size: 0.8em; padding: 9px;" type="submit" value="Login">
            </div>
        </div>

            <hr class="mt-5 mb-4">

        <p class="text-center mb-4">Create a New Account</p>
        <div class="row text-center mx-2">
            <div class="col">
                <a class="btn btn-secondary text-uppercase" href="it_form.php" style="width: 100px; font-size: 0.8em; padding: 10px;">Speaker</a>
            </div>
            <div class="col">
                <a class="btn btn-secondary text-uppercase" href="teacher_form.php" style="width: 100px; font-size: 0.8em; padding: 10px;">Teacher</a>
            </div>
        </div>

        </div>
    </form>

</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

</body>
</html>
