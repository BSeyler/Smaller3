<?php
session_start();
if(empty($_SESSION['email'])) {
    header('Location: index.php');
} else {
    require('db/mysqli_connect-wtia.php');
    $email = $_SESSION['email'];
    $query = "SELECT user_type FROM users WHERE email='$email'";
    $result = @mysqli_query($dbc, $query);
    if($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $type = $row['user_type'];
        if($type == 'Teacher') {
            header('Location: teacher_profile.php');
        } elseif($type == 'Professional') {
            header('Location: it_profile.php');
        } else {
            include('includes/header.html');
            echo 'Couldn\'t get teacher type';
            include('includes/footer.html');
        }

    } else {
        include('includes/header.html');
        echo 'Database connection error...';
        include('includes/footer.html');
    }
}

echo 'huh?';
