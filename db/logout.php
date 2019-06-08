<?php
/**
 * Bradley Seyler, Aaron Reynolds, Christian Talmadge
 * 6/4/2019
 * logout.php
 *
 * This file handles logging out.
 */

session_start();
$_SESSION['email'] = null;

//Destroy the session and header the user to the landing page
session_destroy();
header('Location: ../');
?>