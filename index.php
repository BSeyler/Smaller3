<?php
    /**
     * Bradley Seyler, Aaron Reynolds, Christian Talmadge
     * 6/4/2019
     * index.php
     *
     * This file defines all routes on the web server
     */

    //Set error reporting to on. Disable this if error messages are not wanted.
    error_reporting(E_ALL);

    //Load the controller file
    require 'controllers/controller.php';

    //load the fat free framework
    $fatFree = require 'vendor/bcosca/fatfree-core/base.php';

    //Set errors to be reported
    $fatFree->set('ONERROR', function($fatFree){
        echo $fatFree->get('ERROR.text');
    });

    //All routes to be set below
    $fatFree->route('GET /', function($fatFree){
        displayLogin($fatFree);
    });

    $fatFree->route('GET /Forgot_Password', function($fatFree)
     {
        displayForgotPassword($fatFree);
     });
    $fatFree->route('POST /Forgot_Password', function($fatFree)
    {
        displayForgotPasswordPost($fatFree);
    });

    $fatFree->route('GET /Reset_Password', function($fatFree)
    {
        displayResetPassword($fatFree);

    });

    $fatFree->route('POST /Reset_Password', function()
    {
        processPasswordReset();
    });


    $fatFree->route('POST /', function($fatFree){
        processLogin($fatFree);
    });

    $fatFree->route('GET /Speaker_Home', function($fatFree){
        displaySpeakerHome($fatFree);
    });

    $fatFree->route('GET /View_Opportunities', function($fatFree){
        displayOpportunities($fatFree);
    });

    $fatFree->route('GET /Process_LinkedIn', function($fatFree){
        processLinkedIn($fatFree);
    });

    $fatFree->route('GET /Register_LinkedIn', function($fatFree){
        renderRegisterLinkedIn($fatFree);
    });

    $fatFree->route('GET /Pro_Directory', function($fatFree){
        displayProfessionals($fatFree);
    });

    $fatFree->route('GET /email_interest', function($fatFree){
        displayEmailInterestPage($fatFree);
    });

    $fatFree->route('POST /Send_Email', function($fatFree){
        generateInterestEmail($fatFree);
    });

    $fatFree->route('GET /Search', function($fatFree){
        //print("Search Attempted!");
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

    $fatFree->route('GET /Pro_Email_Success', function($fatFree){
        proEmailSuccess($fatFree);
    });

    $fatFree->run();
?>

