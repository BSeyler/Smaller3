# WTIA Educator-Professional Connection Site

This project is the relational software developed by the Smaller Three group (Bradley Seyler, Aaron Reynolds, and Christian Talmadge) for the Washington Technology Industries Association. This project aims to connect educators to industry professionals who would like to speak at the educator's events.

## Getting Started

To begin, clone the files in this repository to the root of your webserver's public HTML directory. All frameworks such as FatFree and SwiftMailer are included in the vendor folder, however a composer.json file is included to redownload these frameworks if needed. Two SQL files containing an empty database, as well as a prepopulated database are included as well (Labeled as db.sql and db-prepopulated.sql accordingly).

### Prerequisites

This project uses PHP, JavaScript, HTML, as well as FatFree for routing and SwiftMailer for sending emails. A basic knowledge of these is recommended before messing around with any files on the site. Below are references for the FatFree and SwiftMailer frameworks.

```
FatFree Documentation: https://fatfreeframework.com/3.6/user-guide
```
```
SwiftMailer Documentation: https://swiftmailer.symfony.com/docs/introduction.html
```


### Installing

After cloning the repository to your webroot, begin by verifying that FatFree is working. This can be checked by accessing the base root of your website. If it loads the login page, fatfree is working properly. If not, FatFree must not be installed, and will have to be installed using the following step:

Begin by starting a SSH session with your webserver. Once loaded, change the directory to public_html using:

```
cd public_html
```

Once there, run the following command:

```
composer install
```

This will will check the dependencies in composer.json (Which are FatFree and SwiftMailer) and then install them to a folder called vendor. Close the SSH session, and verify that the main page of your site loads now. If it loads, you're set to continue. If not, verify that the following line (line 17) in index.php points to /bcosca/fatfree-core/base.php
```
$fatFree = require 'vendor/bcosca/fatfree-core/base.php';
```

Once that is set up, load up CPanel or your database host, and import either db.sql or db-populated.sql using phpMyAdmin. To do this in phpMyAdmin, select the database you would like to import the tables into, then click the import button and browse to the file you would like to import as shown below.

*Picture of phpMyAdmin here*

Below is a description of each of the sql files.
```
db.sql - This file contains an unpopulated but prebuilt database. 
```
```
db-populated.sql - This file contains an populated and prebuilt database with 
sample data (such as sample events and speaker accounts). 
```

PhpMyAdmin should create 6 tables in your blank database (archived_opportunity, opportunity, pros,
speaking_requests, teachers, and users). Once this is created, assuming you are using PhpMyAdmin from cPanel, click MySQL Databases, and scroll down to create new user. Create a username for the new user (for example, smallert_phpscript in our groups case), and then either generate a password or type one in (The former is recommended). **WRITE DOWN THIS PASSWORD, IF YOU LOOSE IT THERE IS NO WAY TO RECOVER IT**. Now that you have created a user, you must set the following lines in PHP to match your database information. This is found in the model folder. The following explains which lines to change:
* Set line 12 to be the name of the user you created 
* Line 13 to be the corresponding password 
* Line 14 should ***NOT BE MODIFIED*** 
* Line 15 should be the name of the database which contains the table you just created

Below is how this should look within your model.php file.

```php
11    //Set this data according to YOUR database!
12    define('DB_USER', 'user_name');
13    define('DB_PASSWORD', 'user password');
14    define('DB_HOST', 'localhost'); //This should ALWAYS be localhost
15    define('DB_NAME', 'database_name');
```

You should now be able to register a new account using the register account button on the login page. If you can register an account and login, the site should be working properly.

If you would like to skip making an account and are using a pre-populated db, you can just simply use the email test@gmail.com and password "Password" (without quotes) to test the professional login, and for educator use MTeacher@mail.com for the email and password "123Password"


The final step of setup is to setup the SFTP server. To do this, scroll to line 779 of controllers/controller.php. You should see the following lines:

```php
779     //change these lines to your smtp server, smtp username, and password
780     $smtp_server = 'Your SMTP server';
781     $username = 'Your SMTP user';
782	    $password = 'Your SMTP users password';
```

Set line 780 to your SMTP server URL, 781 to your SMTP email user, and 782 to the SMTP user's password. If you're able to send an email without errors from the professional side, all should be good.

## Implemented Features
* A User is able to register as either a educator or a professional, and then login to the respective site based off their account type
* Professionals are presented with a page that has all opportunities that are relevant to them upon logging in
* Educators are able to create events, which professionals are then able to see
* Professionals are able to send an email of interest to the educator who has organized an event
* LinkedIn integration for login is existent, however is disabled from the login page, as during the time of our project we did not have a valid key for WTIA to use LinkedIn requests

## Unimplemented Features
* Geographic search functions have not been researched
* All search features are currently unimplemented
* Educators are unable to decline or accept a professional's invitation on the site itself, and must manually send an email from their email account
* Educators are unable to send an email of interest to professionals from the professional index page


## Additional Help
If any additional help is needed with deployment, or other issues, we'll glady help. The following is our contact information:
*Bradley Seyler* - bradleyseyler@gmail.com
*Aaron Reynolds* - areynolds086@gmail.com
*Christian Talmadge* -

## Built With

* [FatFree](http://www.dropwizard.io/1.0.2/docs/) - The routing framework used
* [SwiftMailer](https://swiftmailer.symfony.com/) - EMail Framework used for sending emails

## Authors

* **Bradley Seyler** - *Routing, Database, E-Mail System* - [BSeyler](https://github.com/bseyler)
* **Aaron Reynolds** - *CSS, LinkedIn API, E-Mail System* - [Areynolds086](https://github.com/areynolds086)
* **Christian Talmadge** - *Routing, CSS, Linked-In API* - [christiantalmadge](https://github.com/christiantalmadge)

## License

This project is licensed under the MIT License

## Acknowledgments

* Special Thanks to TeamWon for the initial site design. Sorry we rewrote the entire site.
* Thanks to Ken Hang for giving us the opportunity to do this project!
* And finally a special thanks to all the Green River Faculty that have helped guide us through our bachelors degree! **We won't forget you all!**

