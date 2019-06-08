<?php
/**
 *  Bradley Seyler, Aaron Reynolds, Christian Talmadge
 *  6/4/2019
 *  controller.php
 *
 *  This file contains functions which load pages
 */

    require('db/model.php');

    //Load Swift Mailer
    require 'includes/swift_config.php';

    session_start();

    /**
     * This function logs a user out by deleting their session
     */
    function processLogout()
    {
        $_SESSION['email'] = null;
        session_destroy();
        header('Location: ../');
    }

    /**
     * This function sends an email using the data from the email form page
     */
    function generateInterestEmail()
    {
        //Get data from the post variable
        $info = getTeacherInfoRequest($_GET['id'], $_GET['opp_id']);
        $opportunity = mysqli_fetch_array($info[1], MYSQLI_ASSOC);

        //Generate and send the email
        generateEmail($opportunity, mysqli_fetch_array($info[0], MYSQLI_ASSOC));
    }

    /**
     * This function loads the success page for emails
     * @param $fatFree FatFree This file contains fatfree information
     */
    function proEmailSuccess($fatFree)
    {
        //Set title and css links
        $fatFree->set('cssURL', "styles/main.css");
        $fatFree->set('title', 'Email Sent!');

        //Then render the page
        echo Template::instance()->render('includes/header.html');
        echo Template::instance()->render('views/pro_email_sent.html');
    }

    /**
     * This function renders the login page
     * @param $fatFree FatFree This file contains fatfree information
     */
    function displayLogin($fatFree){
        //If the session is not empty (User is logged in)
        if(isset($_SESSION['email'])) {


            $email = $_SESSION['email'];
            $query = "SELECT user_type FROM users WHERE email='$email'";
            $result = @mysqli_query(getDBC(), $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $user_type = $row['user_type'];

            if($user_type == "Teacher") {
                $_SESSION['type'] = 'Teacher';
                header('Location: ../Teacher_Home');
                exit;
            } elseif ($user_type == "Professional") {
                $_SESSION['type'] = 'Professional';
                header('Location: ../Speaker_Home');
                exit;
            }
        }

        $fatFree->set('cssURL', "styles/main.css");
        $fatFree->set('title', 'Login');
        //Then render the landing page
        echo Template::instance()->render('views/login.html');
        //Then close the landing pag
    }

    /**
     * This function determines which type of user a user is
     */
    function determineProfileType()
    {
        //If email isn't set in the session
        if(empty($_SESSION['email']))
        {
            //Header the user to the login page
            header('Location: ../');
        }
        else
        {
            //If the user is a teacher, send tot the teacher page, else send to speaker page
            if ($_SESSION['type'] == 'Teacher')
            {
                header('Location: ../Teacher_Profile');
            }
            elseif ($_SESSION['type'] == 'Professional')
            {
                header('Location: ../Speaker_Profile');
            }
        }
    }

    /**
     * This function displays the register speaker page
     * @param $fatFree FatFree
     */
    function displayRegisterSpeaker($fatFree)
    {
        //Set title
        $fatFree->set('title', "Register");

        //Render the registration page
        echo Template::instance()->render('includes/header_no_dropdown.html');
        echo Template::instance()->render('views/it_register.html');
        echo Template::instance()->render('includes/footer.html');
    }


    /**
     * This function displays the register teacher page
     * @param $fatFree FatFree This object contains FatFree information
     */
    function displayRegisterTeacher($fatFree)
    {
        $fatFree->set('title', "Register A Teacher Account");

        //Render the registration page
        echo Template::instance()->render('includes/header_no_dropdown.html');
        echo Template::instance()->render('views/teacher_register.html');
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function displays the forgot password page
     * @param $fatFree FatFree This object contains FatFree information
     */
    function displayForgotPassword($fatFree)
    {
        $fatFree->set('cssURL', "styles/main.css");
        $fatFree->set('title', 'Forgot Password');
        $fatFree->set('email', $_POST['email']);

        echo Template::instance()->render('views/forgot_password.html');
    }

    function displayForgotPasswordPost($fatFree)
    {
        $fatFree->set('cssURL', "styles/main.css");
        $fatFree->set('title', 'Request Successful');

        echo Template::instance()->render('views/forgot_password_success.html');
        changePassword();
    }

    function displayResetPassword($fatFree)
    {
        $q = $_GET['q'];

        $fatFree->set('query', $q);
        $fatFree->set('cssURL', "styles/main.css");

        //Set the user ID. This is hidden on the page.
        $fatFree->set('id', $_GET['id']);
        $fatFree->set('title', 'Reset Password');

        echo Template::instance()->render('views/reset_password.html');
    }

    /**
     * This function registers a teacher profile
     */
    function registerTeacherProfile()
    {
        registerTeacher();
    }

    /**
     * This function displays the speaker edit profile page
     * @param $fatFree FatFree This object contains FatFree information
     */
    function displaySpeakerProfileEdit($fatFree)
    {

        $fatFree->set('title', "Edit Professional Profile");
        $userInfo = loadSpeakerInfo();
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        $fatFree->set('FName', $userInfo[0]['first_name']);
        $fatFree->set('LName', $userInfo[0]['last_name']);
        $fatFree->set('email', $userInfo[0]['email']);
        $fatFree->set('bio', $userInfo[0]['bio']);
        $fatFree->set('company', $userInfo[1]['company']);
        $fatFree->set('expertise', $userInfo[1]['expertise']);
        $fatFree->set('jobtitle', $userInfo[1]['job_title']);
        $fatFree->set('qa', $userInfo[1]['qa_interview']);
        $fatFree->set('formal', $userInfo[1]['lecture']);
        $fatFree->set('panel', $userInfo[1]['panel']);
        $fatFree->set('workshop', $userInfo[1]['workshop']);
        echo Template::instance()->render('includes/header.html');
        echo Template::instance()->render('views/it_profile.html');
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function displays the speaker landing page
     * @param $fatFree FatFree This object contains FatFree information
     */
    function displaySpeakerHome($fatFree)
    {
        //Set crucial variables
        $fatFree->set('title', "Speaker Home");
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        //Load the header
        echo Template::instance()->render('includes/header.html');

        //Get all opportunities for this user
        $opportunities = getOpportunities();
        renderOpportunities($opportunities);

        //Load the footer
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function displays all opportunities
     * @param $fatFree FatFree This object contains FatFree Data
     */
    function displayOpportunities($fatFree)
    {
        //Set page title
        $fatFree->set('title', "Opportunities");

        //Load the header file
        echo Template::instance()->render('includes/search_header.html');

        //Get opportunites, and render the table
        $opportunities = getOpportunities();
        renderOpportunities($opportunities);

        //Load the footer
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function displays the professional directory page
     * @param $fatFree FatFree This object contains FatFree data
     */
    function displayProfessionals($fatFree)
    {
        $fatFree->set('title', "Professional Directory");

        //Load the header
        echo Template::instance()->render('includes/header.html');

        //Get data on professionals
        $professionals = getProfessionals();
        renderProfessionals($professionals);

        //Load footer
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function attempts to reset a users password using data from the reset password form
     */
    function processPasswordReset()
    {
        echo Template::instance()->render('includes/header_no_dropdown.html');
        resetPassword();
    }

    /**
     * This function renders the professional directory page
     * @param $professionals Array This array contains data on the professionals
     */
    function renderProfessionals($professionals)
    {
        //Get rows from the professionals parameter
        $rows = $professionals[0];
        $result1 = $professionals[1];
        $result2 = $professionals[2];

        //Echo HTML and JavaScript
        echo '<div class="container">';
        echo '<h1 class="display-4 mb-5 text-center">Speaker Directory</h1>';

        echo"<script>
        $(document).ready( function () {
            $('#speakers').DataTable();
        } );
        </script>";
        echo '
    <table id="speakers" class="display">
        <thead>
            <tr>
                <th>Name</th>
                <th>Job Title</th>
                <th>Company</th>
                <th>Expertise</th>
                <th>Bio</th>
            </tr>
        </thead>
        <tbody>
        ';

        //For each  professional, make a row with their data
        for($i = 0; $i < $rows; $i++) {
            $user_row = mysqli_fetch_assoc($result1);
            $pro_row = mysqli_fetch_assoc($result2);

            echo '
            <tr>
                <td>' . $user_row['first_name'] . ' ' . $user_row['last_name'] . '</td>
                <td>'. $pro_row['job_title'] . '</td>
                <td>'. $pro_row['company'] . '</td>
                <td>'. $pro_row['expertise'] . '</td>
                <td>'. $user_row['bio'] . '
                </td>
            </tr>
         ';

        }
        echo '
        </tbody>
    </table></div>
        ';
    }

    /**
     * This function processes linkedin integration
     * @param $fatFree
     */
    function processLinkedIn($fatFree)
    {
        //Currently disabled due to issues.
        /*
        include_once("scripts/config.php");
        //include_once("includes/db.php");
        include_once("LinkedIn/http.php");
        include_once("LinkedIn/oauth_client.php");


        if (isset($_GET["oauth_problem"]) && $_GET["oauth_problem"] <> "") {
            // in case if user cancel the login. redirect back to home page.
            $_SESSION["err_msg"] = $_GET["oauth_problem"];
            header("location:index.php");
            exit;
        }
        $client = new oauth_client_class;
        $client->debug = false;
        $client->debug_http = true;
        $client->redirect_uri = $callbackURL;
        $client->client_id = $linkedinApiKey;
        $application_line = __LINE__;
        $client->client_secret = $linkedinApiSecret;
        if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
            die('Please go to LinkedIn Apps page https://www.linkedin.com/secure/developer?newapp= , '.
                'create an application, and in the line '.$application_line.
                ' set the client_id to Consumer key and client_secret with Consumer secret. '.
                'The Callback URL must be '.$client->redirect_uri).' Make sure you enable the '.
            'necessary permissions to execute the API calls your application needs.';
        /* API permissions
         */
        /*
        $client->scope = $linkedinScope;
        if (($success = $client->Initialize())) {
            if (($success = $client->Process())) {
                if (strlen($client->authorization_error)) {
                    $client->error = $client->authorization_error;
                    $success = false;
                } elseif (strlen($client->access_token)) {
                    $success = $client->CallAPI(
                        'http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,location,picture-url,public-profile-url,formatted-name)',
                        'GET', array(
                        'format'=>'json'
                    ), array('FailOnAccessError'=>true), $user);
                }
            }
            $success = $client->Finalize($success);
        }

        if ($client->exit) exit;
        if ($success) {
            //$user_id = $db->checkUser($user);
            $fatFree->set('loggedin_user_id', $_SESSION['user']);
            $fatFree->set('user', $_SESSION['user']);
            header("Register_LinkedIn");
            exit();


        }  else {
            $_SESSION["err_msg"] = $client->error;
        }*/

        header("/Process_LinkedIn");
    }

    /**
     * This function processes linkedin
     * @param $fatFree FatFree
     */
    function renderRegisterLinkedIn($fatFree)
    {
        displayRegisterSpeaker($fatFree);
    }

    /**
     * This function displays all opportunities in the database
     * @param $opportunities mysqli This object contains all opportunities in the database
     */
    function renderOpportunities($opportunities)
    {
        $num = mysqli_num_rows($opportunities);

        $dbc = getDBC();

        echo '<div class="container border shadow p-5 mt-5 bg-light">';
        echo '<h1 class="display-4 mb-5 text-center">Speaking Opportunities For You</h1>';

        if($num > 0) {

            while($row = mysqli_fetch_assoc($opportunities)) {
                $id = $row['requested_by'];
                $opp_id = $row['opp_id'];
                $requested_by = $row['requested_by'];
                $title = $row['title'];
                $date = $row['dates'];
                $city = $row['city'];
                $days = $row['days'];
                $times = $row['times'];
                $desc = $row['description'];

                $type = $_SESSION['type'];
                //check for formats
                if($row['qa_interview'] == 1) {
                    $type .= "QA / Interview, ";
                }
                if($row['lecture'] == 1) {
                    $type .= "Lecture, ";
                }
                if($row['panel'] == 1) {
                    $type .= "Panel, ";
                }
                if($row['workshop'] == 1) {
                    $type .= "Workshop, ";
                }
                $type = substr($type, 0, -2);

                ///get email of teacher requesting event
                $teacher_query = "SELECT email, CONCAT(first_name, ' ', last_name) AS name FROM users WHERE user_id =". $requested_by . ";";
                $teacher_info = @mysqli_query($dbc, $teacher_query);
                if($teacher_info) {
                    $row =  mysqli_fetch_array($teacher_info, MYSQLI_ASSOC);
                    $teacher_email = $row['email'];
                    $name = $row['name'];
                }

                //build card
                echo '<div class="card my-5">
            <h5 class="card-header">' . $title . '</h5>
             <div class="card-body">
                <h5 class="card-title"><strong>Date Range: ' . $date . '</h5></strong>
                <p class="card-text">Days of the Week: '. $days .'<br>Times of Day: '. $times .'<br>
                Location: '. $city. '<br>Format: ' . $type . '<br>Description: ' . $desc . '</p>
                <h5 class="card-title"><strong>Requested by: ' . $name . '</h5></strong>
                <a href="../email_interest?id=' . $id . '&&opp_id='.$opp_id.'" class="btn btn-secondary">Send Email</a>
                <a href="" class="btn btn-secondary">Copy Email</a>
            </div>
        </div>';
                $type = '';
                mysqli_free_result($teacher_info);
            }
        } else {
            echo '<div class="text-center">No events found.</div>';
        }

        mysqli_free_result($opportunities);
        echo '   
    </div>
    ';
    }

    function showTeacherOpportunities($fatFree)
    {
        $fatFree->set('title', "Register A Teacher Account");
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        //Render the registration page
        echo Template::instance()->render('includes/header.html');

        renderTeacherOpportunities();

        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function displays the add a new event page
     * @param $fatFree FatFree This object contains FatFree information
     */
    function displayAddEvent($fatFree)
    {
        $fatFree->set('title', "Register A Teacher Account");
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        //Render the registration page
        echo Template::instance()->render('includes/header.html');
        echo Template::instance()->render('views/add_event.html');
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function adds a new event
     */
    function addEvent()
    {
        addNewEvent();
    }

    /**
     * This function displays the opportunity successfully created page
     * @param $fatFree FatFree This is the FatFree data object
     */
    function displayEventSuccess($fatFree)
    {
        $fatFree->set('title', "Opportunity Created!");
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        //Render the registration page
        echo Template::instance()->render('includes/header.html');
        echo Template::instance()->render('views/opportunity_created.html');
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function renders all opportunities a teacher has posted
     */
    function renderTeacherOpportunities()
    {
        $teacher_info = getTeacherInfo();
        $opportunities = $teacher_info[1];
        $num = mysqli_num_rows($opportunities);

        echo '<div class="container border shadow p-5 mt-5 bg-light">';
        echo '<h1 class="display-4 mb-5 text-center">My Active Speaking Opportunities</h1>';
        echo '<div class="text-center"><a href="../AddEvent"<button class="btn btn-secondary">Add Opportunity</button></a></div>';


        if($num > 0) {

            while($row = mysqli_fetch_assoc($opportunities)) {
                $type = $_SESSION['type'];
                $requested = $row['requested_on'];
                $title = $row['title'];
                $date = $row['dates'];
                $city = $row['city'];
                $days = $row['days'];
                $times = $row['times'];
                $opp_id = $row['opp_id'];

                # $name
                $desc = $row['description'];
                if($row['qa_interview'] == 1) {
                    $type .= "QA / Interview, ";
                } if($row['lecture'] == 1) {
                    $type .= "Lecture, ";
                } if($row['panel'] == 1) {
                    $type .= "Panel, ";
                } if($row['workshop'] == 1) {
                    $type .= "Workshop, ";
                }

                $type = substr($type, 0, -2); //remove last comma

                echo '<div class="card my-5">
            <h5 class="card-header">' . $title . '</h5>
             <div class="card-body">
                <h5 class="card-title"><strong>Date Range: ' . $date . '</h5></strong>
                <p class="card-text">Days of the Week: '. $days .'<br> Times of Day: '. $times .'<br>
                Location: '. $city. '<br>Format: ' . $type . '<br>Description: ' . $desc . '
                </p>
                <form action="archive_event.php" method="post">
                    <input type="hidden" name="opp_id" value="' . $opp_id . '">
                    <button type="submit" class="btn btn-secondary">Archive</button>
                </form>
            </div>
        </div>';
            }
        }
        else {
            echo '<div class="text-center mt-5" style="margin-bottom: 100px;">No opportunities yet!</div>';
        }
    }

    function processSpeakerRegistration()
    {
        registerSpeaker();
    }

    /**
     * This function process the updating of a speaker profile
     */
    function processSpeakerUpdate()
    {
        updateSpeakerProfile();
    }

    /**
     * This function processes a log in
     * @param $fatFree FatFree this object contains FatFree data
     */
    function processLogin($fatFree)
    {
        $dbc = getDBC();

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

        if(empty($errors))
        {
            testLogin($email, $password);
        }

        $fatFree->set('name', $_SESSION['name']);
    }

    function displayFormLinkedIn($fatFree)
    {
        if(isset($_SESSION["loggedin_user_id"]) && !empty($_SESSION["user"]))
        {
            $fatFree->set('user', $_SESSION['user']);

            echo Template::instance()->render('includes/header.html');
            echo Template::instance()->render('views/login.html');
            echo Template::instance()->render('includes/footer.html');

        }
    }

    /**
     * This function displays the edit teacher profile page
     * @param $fatFree FatFree This object contains fatfree data
     */
    function displayTeacherProfileEdit($fatFree)
    {
        $userInfo = getTeacherInfo();
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        $fatFree->set('FName', $userInfo[0]['first_name']);
        $fatFree->set('LName', $userInfo[0]['last_name']);
        $fatFree->set('Email', $_SESSION['email']);
        $fatFree->set('bio', $userInfo[0]['bio']);
        $fatFree->set('school', $userInfo[2]['school']);
        $fatFree->set('district', $userInfo[2]['district']);
        $fatFree->set('grade', $userInfo[2]['grade']);
        $fatFree->set('subject', $userInfo[2]['subject']);
        echo Template::instance()->render('includes/header.html');
        echo Template::instance()->render('views/teacher_profile.html');
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function processes the update to a teacher profile
     */
    function processTeacherUpdate()
    {
        updateTeacherProfile();
    }

    /**
     * This function displays the email interest page which let's the user
     * send an email to an event organizer
     * @param $fatFree FatFree This object contains FatFree information
     */
    function displayEmailInterestPage($fatFree)
    {
        $fatFree->set('title', "Send An Email");
        $fatFree->set('id', $_GET['id']);
        $fatFree->set('opp_id', $_GET['opp_id']);

        //Render email page
        echo Template::instance()->render('includes/header_no_dropdown.html');
        echo Template::instance()->render('views/email_form.html');
        echo Template::instance()->render('includes/footer.html');
    }

    /**
     * This function generates an email and sends it to an educator
     * @param $opportunity
     * @param $edInfo
     */
    function generateEmail($opportunity, $edInfo)
    {
        $name = $_SESSION['name'];
        $addressee = $edInfo['first_name'];
        $edName = $edInfo['first_name']. ' ' . $edInfo['last_name'];

        $message = (new \Swift_Message());
        $message->setSubject($name . " would like to speak for you!");
        $message->setFrom(['webhost@wtia.com' => 'WTIA Webhost']);
        $message->addTo($edInfo['email'], $edName);
        $message->addTo('bseyler@mail.greenriver.edu');

        //Pick type of message to add
        if ($_POST['dfemail'])
        {
            $html = "
            <html lang='en'>
            <head>
            <title> " . $name . " would like to speak for you!</title>
            </head>
            <body>
            <p>Hello " . $addressee . ",</p>
            <p>" . $_SESSION['name'] . " would like to speak at " . $opportunity['title'] . "</p>
    
            <input type=\"button\" value=\"Accept\" onclick=\"location.href='http://smallerthree.greenriverdev.com/accept_professional';\">
            <input type=\"button\" value=\"Decline\" onclick=\"location.href='http://smallerthree.greenriverdev.com/decline_professional';\">
            </body>
            </html>
            ";

        }
        else
        {
            $html = "
            <html lang='en'>
            <head>
            <title> ".$name . " would like to speak for you!</title>
            </head>
            <body>
            <p>Hello " . $addressee . ",</p>
            <p>".$_SESSION['name'] ." would like to speak at ". $opportunity['title']. "</p>
            <p>They have enclosed the following message:". $_POST['emailEntry'] ."</p>
            
            <input type=\"button\" value=\"Accept\" onclick=\"location.href='http://smallerthree.greenriverdev.com/accept_professional';\">
            <input type=\"button\" value=\"Decline\" onclick=\"location.href='http://smallerthree.greenriverdev.com/decline_professional';\">
            </body>
            </html>
            ";

        }

        //Set the message body
        $message->setBody($html);

        //Set headers
        $headers = $message->getHeaders();
        $headers->addIdHeader('Message-ID', "b3eb7202-d2f1-11e4-b9d6-1681e6b88ec1@domain.com");
        $headers->addTextHeader('MIME-Version', '1.0');
        $headers->addTextHeader('X-Mailer', 'PHP v' . phpversion());
        $headers->addParameterizedHeader('Content-type', 'text/html', ['charset' => 'utf-8']);


        //change these lines to your smtp server, smtp username, and password
        $smtp_server = 'Your SMTP server';
        $username = 'Your SMTP user';
        $password = 'Your SMTP users password';

        // create the transport
        $transport = (new \Swift_SmtpTransport($smtp_server, 25))
            ->setUsername($username)
            ->setPassword($password);
        $mailer = (new \Swift_Mailer($transport));
        $result = $mailer->send($message);
        if ($result) {
            echo "Number of emails sent: $result";
        } else {
            echo "Couldn't send email";
        }

        header('Location: ../Pro_Email_Success');
    }


?>