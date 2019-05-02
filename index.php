<?php
    error_reporting(E_ALL);

    //Load the controller file
    require 'controllers/controller.php';



    //load the fat free framework
    $fatFree = require 'vendor/bcosca/fatfree-core/base.php';

    //Set errors to be reported
    $fatFree->set('ONERROR', function($fatFree){
        echo $fatFree->get('ERROR.text');
    });

    $fatFree->route('GET /', function($fatFree){
        displayLogin($fatFree);
    });

    $fatFree->route('POST /', function($fatFree){
        processLogin($fatFree);
    });

    $fatFree->route('GET /Speaker_Home', function($fatFree){
        displaySpeakerHome($fatFree);
    });

$fatFree->route('GET /logout', function(){
    processLogout();
});

    $fatFree->route('GET /Profile', function(){
        determineProfileType();
    });

$fatFree->route('GET /Speaker_Profile', function($fatFree){
    displaySpeakerProfileEdit($fatFree);
});

$fatFree->route('POST /Speaker_Profile', function(){
    processSpeakerUpdate();
});

    $fatFree->route('GET /Register_Speaker', function($fatFree){
        displayRegisterSpeaker($fatFree);
    });

    $fatFree->route('POST /Register_Speaker', function(){
        processSpeakerRegistration();
    });

    $fatFree->route('GET /Register_Teacher', function($fatFree){
        displayRegisterTeacher($fatFree);
    });

    $fatFree->run();
/*
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
*/
?>

