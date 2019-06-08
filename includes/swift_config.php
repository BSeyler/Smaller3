<?php
/*
 * $loader needs to be a relative path to an autoloader script.
 * Swift Mailer's autoloader is swift_required.php in the lib directory.
 * If you used Composer to install Swift Mailer, use vendor/autoload.php.
 */
$loader = __DIR__ . '/../vendor/swiftmailer/swiftmailer/vendor/autoload.php';

require_once $loader;

//require 'vendor/egulias/EmailValidator/EmailValidator.php';
//require 'vendor/egulias/EmailValidator/EmailLexer.php';
//require 'vendor/egulias/EmailValidator/EmailValidator/EmailParser.php';

/*
 * Login details for mail server
 */
$smtp_server = 'mail.smallerthree.greenriverdev.com';
$username = 'webmaster';
$password = 'l(D#O~MOYsjR';
