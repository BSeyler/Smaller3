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

    $fatFree->route('GET /Teacher_Profile', function($fatFree){
        displayTeacherProfileEdit($fatFree);
    });

    $fatFree->route('POST /Teacher_Profile', function(){
        processTeacherUpdate();
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

    $fatFree->route('POST /Register_Teacher', function(){
        registerTeacherProfile();
    });

    $fatFree->route('GET /Teacher_Home', function($fatFree){
        showTeacherOpportunities($fatFree);
    });

$fatFree->route('GET /AddEvent', function($fatFree){
    displayAddEvent($fatFree);
});

$fatFree->route('POST /AddEvent', function(){
    addEvent();
});

$fatFree->route('GET /EventCreated', function($fatFree){
    displayEventSuccess($fatFree);
});

    $fatFree->run();
?>

