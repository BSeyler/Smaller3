<?php
    /**
     * Bradley Seyler, Aaron Reynolds, Christian Talmadge
     * 6/5/2019
     * model.php
     *
     * This file contains all database related code, such as user
     * info retrieval and updating user info
     */

    //Set these according to your database info!
    define('DB_USER', 'DB USER HERE');
    define('DB_PASSWORD', 'DB USER PASSWORD HERE');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'DB NAME HERE');

    /**
     * This function connects to the database
     * @return mysqli This is the connection to the database
     */
    function getDBC()
    {
        $dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
        OR die('Could not connect to MySQL: ' . mysqli_connect_error());

        return $dbc;
    }

    /**
     * This function retrieves the corresponding information for
     * the given teacher
     * @return array of teacher information
     */
    function getTeacherInfo()
    {
        $email = $_SESSION['email'];
        $dbc = getDBC();

        $query = "SELECT user_id, first_name, last_name, bio, password
          FROM users WHERE email='$email'";
        $user_info = @mysqli_query($dbc, $query);
        $user_row = [];
        if($user_info) {
            $user_row = mysqli_fetch_array($user_info, MYSQLI_ASSOC);
            $user = $user_row['user_id'];
        }

        $query2 = "SELECT school, district, grade, subject FROM teachers WHERE user_id='$user'";

        $result2 = @mysqli_query($dbc, $query2);
        $teacher_info = mysqli_fetch_array($result2, MYSQLI_ASSOC);



        $query = "SELECT * FROM opportunity 
          WHERE requested_by='$user'
          ORDER BY requested_on DESC;";

        $opportunities = @mysqli_query($dbc, $query);

        return $returnArray = [$user_row, $opportunities, $teacher_info];
    }

    /**
     * This function retrieves teacher info with a opportunity, which is used for sending emails
     * @param $id
     * @param $opp_id
     * @return array
     */
    function getTeacherInfoRequest($id, $opp_id)
    {
        $dbc = getDBC();

        $query = "SELECT first_name, last_name, email
              FROM users WHERE user_id = '$id'";
        $user_info = @mysqli_query($dbc, $query);

        $query = "SELECT * FROM opportunity 
              WHERE opp_id='$opp_id';";

        $opportunity = @mysqli_query($dbc, $query);

        return $returnArray = [$user_info, $opportunity];
    }

    function getProfessionals()
    {
        $dbc = getDBC();

        $query = "SELECT first_name, last_name, bio FROM users WHERE user_type='Professional'";
        $result1 = @mysqli_query($dbc, $query);
        $rows = mysqli_num_rows($result1);

        $query = "SELECT company, job_title, expertise FROM pros";
        $result2 = @mysqli_query($dbc, $query);
        $rows2 = mysqli_num_rows($result2);

        if($rows2 != $rows) {
            echo '<p>The number of rows don\'t match</p>';
            echo "Users: $rows -- Pros: $rows2";
            return;
        }

        return $returnArray = [$rows, $result1, $result2];
    }

    /**
     * This function grabs form data from the register speaker form and registers
     * the user when successful
     */
    function registerSpeaker()
    {
        $dbc = getDBC();

        $errors = []; // error array

        //check for first name
        if (empty($_POST['first_name'])) {
            $errors[] = 'Please enter your first name.';
        } else if (isset($_POST['first_name'])) {
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        }

        //check for last name
        if (empty($_POST['last_name'])) {
            $errors[] = 'Please enter your last name.';
        } else if (isset($_POST['last_name'])) {
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        }

        //check for email address   --extra step to make sure its a valid email** note to check
        if (empty($_POST['email'])) {
            $errors[] = 'Please enter your email.';
        } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email provided.';
        } else if (isset($_POST['email'])) {
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));

            //make the query to see if email exists
            $query = "SELECT user_id FROM users WHERE email='$e'";
            $result = @mysqli_query($dbc, $query);

            if (mysqli_num_rows($result) == 1) {
                $errors[] = 'Email has already been registered in the system. Please provide a different email.';
            }
        }

        //check for F  --extra step to make it secure** not to check Brian's suggestion
        if (empty($_POST['password'])) { //|| strlen(trim($_POST['password']) < 10)) {
            $errors[] = 'Please enter a password that is at least 10 characters long.';
        } else if (empty($_POST['confirm'])) {
            $errors[] = 'Please enter your password again for confirmation.';
        } else if($_POST['password'] != $_POST['confirm']) {
            $errors[] = 'Passwords don\'t match. Please try again.';
        } else if (isset($_POST['password'])) {
            $pw = hash('sha512', trim($_POST['password']));
        }

        //capture input company/organization name (Optional can be blank)
        if (empty($_POST['company_name']) || ($_POST['company_name']) == '') {
            $cmp = NULL; // Optional field allowed to be null
        } else if (isset($_POST['company_name'])) {
            $cmp = mysqli_real_escape_string($dbc, trim($_POST['company_name']));
        }

        //capture input job title (Optional can be blank)
        if (empty($_POST['job_title']) || ($_POST['job_title']) == '') {
            $jt = NULL; // Optional field allowed to be null
        } else if (isset($_POST['job_title'])){
            $jt = mysqli_real_escape_string($dbc, trim($_POST['job_title']));
        }

        //capture input for bio (Optional can be blank)
        if (empty($_POST['bio']) || ($_POST['bio']) == '') {
            $bi = NULL; // Optional field allowed to be null
        } else if (strlen($_POST['bio']) > 560) {
            $errors[] = 'Biography can only be 560 characters.';
        } else if (isset($_POST['bio'])) {
            $bi = mysqli_real_escape_string($dbc, trim($_POST['bio']));
        }

        //check for expertise  -- note** what are we validating here?? not null or empty string ""
        if ((empty($_POST['expertise']) || ($_POST['expertise']) == '')) {
            $errors[] = 'Please include at least one area of expertise.';
        } else if (isset($_POST['expertise'])) {
            $exp = mysqli_real_escape_string($dbc, trim($_POST['expertise']));
        }

        //check for format checkbox selections (formats[] array) at least one selection
        $num = count($_POST['formats']);
        if(empty($_POST['formats']) || $num == 0) {
            $errors[] = 'Please choose at least one format you are comfortable with presenting';
        } else {
            //set boolean true for selections sent to the db if selected format(s) in formats array
            foreach ($_POST['formats'] as $format) {
                if($format == "Q & A / Interview") {
                    $qai = 1;
                }
                if($format == "Formal Presentation / Lecture") {
                    $fpl = 1;
                }
                if($format == "Panel") {
                    $pnl = 1;
                }
                if($format == "Workshop") {
                    $ws = 1;
                }
            }
        }

        //check if the user would like to receive weekly speaking opportunity emails
        if(isset($_POST['weekly_email'])) {
            $wklyE = 1;
        } else {
            $wklyE = 0;
        }

        // If there are no errors make the query and run in db
        if (empty($errors)) {

            //insert data into the users db table
            $query = "INSERT INTO users (email, first_name, last_name, password, user_type, bio, last_logon, weekly_msg) 
                  VALUES ('$e', '$fn', '$ln', '$pw', 'Professional', '$bi', NOW(), '$wklyE')";
            $result = @mysqli_query($dbc, $query); //run query

            //grab the user_id from the db to link to FK in the pro table
            $query = "SELECT user_id FROM users WHERE email='$e'";
            $result = @mysqli_query($dbc, $query); //
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $userid = $row['user_id'];

            //insert specific IT Professional data into pros db table
            $query = "INSERT INTO pros (user_id, company, job_title, expertise, qa_interview, lecture, panel, workshop) 
                  VALUES ('$userid', '$cmp', '$jt', '$exp', '$qai', '$fpl', '$pnl', '$ws')";
            $result = @mysqli_query($dbc, $query);

            // if the query ran ok.
            if ($result) {
                header('Location: ../');
            } else { //the form did not run ok
                echo '<h1>System Error</h1>
                    <p class="error">Your Account was not registered due to a system error. We apologize for any inconvenience.</p>';
            }

            mysqli_close($dbc); //close the db connection
            exit();
        } else {
            header('Location: ../Register_Speaker');
        }
        mysqli_close($dbc); //close the db
    }

    /**
     * This function generates a change password email
     */
    function changePassword()
    {
        //Get connection to the database
        $dbc = getDBC();

        if(isset($_POST["ForgotPassword"]))
        {
            //get submitted e-mail address
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
            {
                $email = $_POST["email"];
            }
            else
            {
                echo "Email is not valid";
                exit;
            }
            $query = "SELECT email, user_id FROM users WHERE email = '".$email."'";
            $userExists = @mysqli_query($dbc, $query);
            $rows = mysqli_num_rows($userExists);
            $userExists = mysqli_fetch_array($userExists, MYSQLI_ASSOC);

           // var_dump($userExists);

            if($rows > 0)
            {
                //Create unique salt. This will never leave PHP unencrypted
                $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

                //Create unique user pass word reset key
                $password = hash('sha512', $salt.$userExists["email"]);

                //Create URL which the user will be redirected to in order to reset password
                $pwurl = "www.smallerthree.greenriverdev.com/Reset_Password?q=".$password . "&&id=" .$userExists["user_id"];

                //Email them their key
                $mailbody = "Dear user,\n\n
                If this e-mail does not apply to you please ignore it. 
                It appears that you have requested a password reset at our website 
                www.smallerthree.greenriverdev.com\n\n
                To reset your password, please click the link below. 
                If you cannot click it, please paste it into your web browser's address bar.\n\n"
                    . $pwurl . "\n\nThanks,\nWTIA";

                mail($userExists["email"], "www.smallerthree.greenriverdev.com - Password Reset", $mailbody);

                //print success message
                //echo "Your password reset request has been successful.
                     // Check your email and your junk/spam folder.";
                echo "<p class='text-center'> Your password reset request has been succcessful.
                      Please check your email and your junk/spam folder.</p>";
            }
            else
            {
                //print error message
                echo "No user with that email address exists.";
            }

        }
        //close db
        mysqli_close($dbc);

    }

    /**
     * This function grabs the data from the reset password form and updates the DB
     */
    function resetPassword()
    {
        $dbc = getDBC();

        if(isset($_POST["ResetPasswordForm"]))
        {
            //gather the post data
            $id = $_POST["id"];
            $password = $_POST["password"];
            $confirmpassword = $_POST["confirmpassword"];
            $hash = $_POST["q"];

            $query = "SELECT email FROM users WHERE user_id=" . $id;
            $userExists = @mysqli_query($dbc, $query);
            $userExists = mysqli_fetch_array($userExists, MYSQLI_ASSOC);

            //use same salt from change_password method
            $salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

            //generate the reset key
            $resetKey = hash('sha512', $salt.$userExists['email']);

            //Does the new reset key match the old one?
            if($resetKey == $hash)
            {
                if($password == $confirmpassword)
                {
                        //hash and secure the password
                        $password = hash('sha512', $password);

                        //Update users password
                        $query = "UPDATE users SET password = '" . $password . "' WHERE user_id=".$id;
                        $userExists = @mysqli_query($dbc, $query);

                        echo "<p class='text-center'>Your password has successfully been reset</p>";
                        echo "<div style=\"text-align:center\">
                                <form action=\"http://smallerthree.greenriverdev.com\">
                                <input type=\"submit\" value=\"Return to Login Page\" />
                            </div>
                            </form>";

                }
                else
                {
                    echo "Your password's do not match";
                }
            }
            mysqli_close($dbc);
        }
    }

    /**
     * This function is responsible for registering the users
     * teacher account into the database
     */
    function registerTeacher()
    {
        $dbc = getDBC();

        $errors = []; // error array

        //check for first name
        if (empty($_POST['first_name'])) {
            $errors[] = 'Please enter your first name.';
        } else if (isset($_POST['first_name'])) {
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        }

        //check for last name
        if (empty($_POST['last_name'])) {
            $errors[] = 'Please enter your last name.';
        } else if (isset($_POST['last_name'])) {
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        }

        //check for email address   --extra step to make sure its a valid email** note to check
        if (empty($_POST['email'])) {
            $errors[] = 'Please enter your email.';
        } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email provided.';
        } else if (isset($_POST['email'])) {
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));

            //make the query to see if email exists
            $query = "SELECT user_id FROM users WHERE email='$e'";
            $result = @mysqli_query($dbc, $query);

            if (mysqli_num_rows($result) == 1) {
                $errors[] = 'Email has already been registered in the system. Please provide a different email.';
            }
        }

        //check for password  --extra step to make it secure** not to check Brian's suggestion
        if (empty($_POST['password'])) { //|| strlen(trim($_POST['password']) < 10)) {
            $errors[] = 'Please enter a password that is at least 10 characters long.';
        } else if (empty($_POST['confirm'])) {
            $errors[] = 'Please enter your password again for confirmation.';
        } else if($_POST['password'] != $_POST['confirm']) {
            $errors[] = 'Passwords don\'t match. Please try again.';
        } else if (isset($_POST['password'])) {
            $pw = hash('sha512', trim($_POST['password']));
        }

        //capture input company/organization name (Optional can be blank)
        if (empty($_POST['school']) || ($_POST['school']) == '') {
            $school = NULL; // Optional field allowed to be null
        } else if (isset($_POST['school'])) {
            $school = mysqli_real_escape_string($dbc, trim($_POST['school']));
        }

        //capture input job title (Optional can be blank)
        if (empty($_POST['district']) || ($_POST['district']) == '') {
            $district = NULL; // Optional field allowed to be null
        } else if (isset($_POST['district'])){
            $district = mysqli_real_escape_string($dbc, trim($_POST['district']));
        }

        //capture input job title (Optional can be blank)
        if (empty($_POST['grade']) || $_POST['grade'] == '' || !is_numeric(['grade'])) {
            $grade = NULL; // Optional field allowed to be null
        } else if (isset($_POST['grade'])){
            $grade = mysqli_real_escape_string($dbc, trim($_POST['grade']));
        }

        //capture input for bio (Optional can be blank)
        if (empty($_POST['bio']) || ($_POST['bio']) == '') {
            $bi = NULL; // Optional field allowed to be null
        } else if (strlen($_POST['bio']) > 560) {
            $errors[] = 'Biography can only be 560 characters.';
        } else if (isset($_POST['bio'])) {
            $bi = mysqli_real_escape_string($dbc, trim($_POST['bio']));
        }

        //check for subject
        if ((empty($_POST['subject']) || ($_POST['subject']) == '')) {
            $subject = null;
        } else if (isset($_POST['subject'])) {
            $subject = mysqli_real_escape_string($dbc, trim($_POST['subject']));
        }

        //check if the user would like to receive weekly speaking opportunity emails
        if(isset($_POST['weekly_email'])) {
            $wklyE = 1;
        } else {
            $wklyE = 0;
        }

        // If there are no errors make the query and run in db
        if (empty($errors)) {

            //insert data into the users db table
            $query =
                "INSERT INTO users (email, first_name, last_name, password, user_type, bio, last_logon, weekly_msg)
        VALUES ('$e', '$fn', '$ln', '$pw', 'Teacher', '$bi', NOW(), '$wklyE')";
            $result = @mysqli_query($dbc, $query); //run query

            //grab the user_id from the db to link to FK in the pro table
            $query = "SELECT user_id FROM users WHERE email='$e'";
            $result = @mysqli_query($dbc, $query); //
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $userid = $row['user_id'];

            //insert specific IT Professional data into pros db table
            $query =
                "INSERT INTO teachers (user_id, school, district, grade, subject)
        VALUES ('$userid', '$school', '$district', '$grade', '$subject')";
            $result = @mysqli_query($dbc, $query);

            // if the query ran ok.
            if ($result) {
                header('Location: ../');
            } else { //the form did not run ok
                echo '<h1>Error!</h1> <p class="error">The following error(s) occurred:<br>';
                foreach($errors as $msg) {
                    echo " - $msg<br>\n";
                }
            }

            mysqli_close($dbc); //close the db connection
            exit();
        } else {
            // it did not run ok print the errors
            echo '<h1>Error!</h1> <p class="error">The following error(s) occurred:<br>';
            foreach($errors as $msg) {
                echo " - $msg<br>\n";
            }
            echo '</p><p>Please try again</p><p><br></p>';
        }
        mysqli_close($dbc); //close the db
    }

    /**
     * This function tests a users login data
     * @param $email String this is the email the user entered
     * @param $password String this is the password the user entered
     */
    function testLogin($email, $password)
    {
        $dbc = getDBC();

        $query = "SELECT user_id, email, first_name, user_type, password FROM users WHERE email='$email'";
        $result = @mysqli_query($dbc, $query);
        if($result) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if(hash('sha512', $password) == $row['password']) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['first_name'];
                $_SESSION['user_id'] = $row['user_id'];
                if($row['user_type'] == "Teacher") {
                    $_SESSION['type'] = 'Teacher';
                    header('Location: ../Teacher_Home');
                    exit;
                } elseif($row['user_type'] == "Professional") {
                    $_SESSION['type'] = 'Professional';
                    header('Location: ../Speaker_Home');
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
    }

    /**
     * This function returns all opportunities in the database
     * @return bool|mysqli_result The return type will be a boolean data retrieval fails,
     * or an array of opportunities
     */
    function getOpportunities()
    {
        $email = $_SESSION['email'];

        $dbc = getDBC();

        //get user
        $user_query = "SELECT user_id, first_name, last_name, bio
          FROM users WHERE email='$email';";
        $user_info = @mysqli_query($dbc, $user_query);
        if($user_info) {
            $row = mysqli_fetch_array($user_info, MYSQLI_ASSOC);
            $user = $row['user_id'];
            $name = $row['first_name'] . " " . $row['last_name'];
            $bio = $row['bio'];
        }

        //get pro
        $pro_query = "SELECT expertise FROM pros WHERE user_id = $user;";
        $pro_info = @mysqli_query($dbc, $pro_query);
        if($pro_info) {
            $row =  mysqli_fetch_array($pro_info, MYSQLI_ASSOC);
            $expertise = $row['expertise'];
        }

        //get all expertise values
        $expertise_list = explode(", ", $expertise);

        //create where statement checking event description and title with each expertise value
        $where_statements = '';
        foreach($expertise_list as $expertise_value) {
            $expertise_value = trim($expertise_value);
            $where_statements .= "description LIKE '%$expertise_value%' OR ";
            $where_statements .= "title LIKE '%$expertise_value%' OR ";
        }

        //remove last OR
        $where_statements = substr($where_statements, 0, -3);

        //create query
        $query = "SELECT * FROM opportunity
          WHERE " . $where_statements .
            "ORDER BY requested_on DESC
          LIMIT 10;";

        return @mysqli_query($dbc, $query);
    }

    /**
     * This function returns a speakers info
     * @return array|string This array contains a speakers info
     */
    function loadSpeakerInfo()
    {
        $email = $_SESSION['email'];

        $query = "SELECT user_id, email, first_name, last_name, password, bio FROM users WHERE email='$email'";
        $result = @mysqli_query(getDBC(), $query);
        $user_info = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $user_id = $user_info['user_id'];

        $query2 = "SELECT company, job_title, expertise, qa_interview, lecture, panel, workshop FROM pros WHERE user_id='$user_id'";
        $result2 = @mysqli_query(getDBC(), $query2);
        $pro_info = mysqli_fetch_array($result2, MYSQLI_ASSOC);


        if(!$result || !$result2) {
            echo 'user query did not work';
            echo "<a href='../Speaker_Home'>Click Here to Return to The Opportunities Page</a>";
        }else{
            $returnArray = [$user_info,$pro_info];

            return $returnArray;
        }

        return "error";
    }

    /**
     * This function updates a speaker's profile
     */
    function updateSpeakerProfile()
    {
        $fullInfo = loadSpeakerInfo();
        $user_info = $fullInfo[0];
        $pro_info = $fullInfo[1];

        $errors = []; // error array
        $dbc = getDBC();

        //check for first name
        if (isset($_POST['first_name'])) {
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        } else {
            $fn = $user_info['first_name'];
        }

        //check for last name
        if (isset($_POST['last_name'])) {
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        } else {
            $ln = $user_info['last_name'];
        }

        //check for email address   --extra step to make sure its a valid email** note to check
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email provided.';
        } else if (isset($_POST['email'])) {
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        } else {
            $e = $user_info['email'];
//        $_SESSION['email'] = $e;
        }

        //check for password  --extra step to make it secure** not to check Brian's suggestion
        if ($_POST['password'] != $_POST['confirm']) { //|| strlen(trim($_POST['password']) < 10)) {
            $errors[] = 'Passwords don\'t match.';
        } else if (isset($_POST['password']) && trim($_POST['password']) != '') {
            $pw = hash('sha512', trim($_POST['password']));
        } else {
            $pw = $user_info['password'];
        }

        //capture input company/organization name (Optional can be blank)
        if (isset($_POST['company']) && $_POST['company'] != '') {
            $company = mysqli_real_escape_string($dbc, trim($_POST['company']));
        } else {
            $company = $pro_info['company'];
        }

        if (isset($_POST['job']) && $_POST['job'] != '') {
            $job = mysqli_real_escape_string($dbc, trim($_POST['job']));
        } else {
            $job = $pro_info['job_title'];
        }

        //capture input for bio (Optional can be blank)
        if (strlen($_POST['bio']) > 560) {
            $errors[] = 'Biography can only be 560 characters.';
        } else if (isset($_POST['bio']) && $_POST['bio'] != '') {
            $bio = mysqli_real_escape_string($dbc, trim($_POST['bio']));
        } else {
            $bio = $user_info['bio'];
        }

        if (isset($_POST['expertise']) && $_POST['expertise'] != '') {
            $expertise = mysqli_real_escape_string($dbc, trim($_POST['expertise']));
        } else {
            $expertise = $pro_info['expertise'];
        }

        $num = count($_POST['formats']);
        if(empty($_POST['formats']) || $num == 0) {
            $errors[] = 'Please choose at least one format you are comfortable with presenting';
        } else {
            //set boolean true for selections sent to the db if selected format(s) in formats array
            foreach ($_POST['formats'] as $format) {
                if($format == "Q & A / Interview") {
                    $qai = 1;
                } else {
                    $qai = 0;
                }
                if($format == "Formal Presentation / Lecture") {
                    $fpl = 1;
                } else {
                    $fpl = 0;
                }
                if($format == "Panel") {
                    $pnl = 1;
                } else {
                    $pnl = 0;
                }
                if($format == "Workshop") {
                    $ws = 1;
                } else {
                    $ws = 0;
                }
            }
        }

        //check if the user would like to receive weekly speaking opportunity emails
        if(isset($_POST['weekly_email'])) {
            $wklyE = 1;
        } else {
            $wklyE = 0;
        }

        // If there are no errors make the query and run in db
        if (empty($errors)) {
            //insert data into the users db table
            $query = "UPDATE users
                  SET email='$e', first_name='$fn', last_name='$ln', password='$pw', bio='$bio', weekly_msg='$wklyE'
                  WHERE email='" . $_SESSION['email'] ."'";
            $result = @mysqli_query($dbc, $query); //run query

            $query2 = "UPDATE pros
                  SET company='$company', job_title='$job', expertise='$expertise', qa_interview=$qai, lecture=$fpl, panel=$pnl, workshop=$ws 
                  WHERE user_id='".$_SESSION['user_id']."'";
            $result2 = @mysqli_query($dbc, $query2);

            // if the query ran ok.
            if ($result) {
                $_SESSION['email'] = $e;
                if($result2) {
                    // header('Location: teacher_profile.php');
                    header('Location: ../');
                } else {
                    echo "Database error. Some fields not updated. Please refresh the page.";
                }
            } else { //the form did not run ok
                echo '<h1>System Error</h1>
                    <p class="error">Your Account was not updated due to a system error. We apologize for any inconvenience.</p>';
            }

            mysqli_close($dbc); //close the db connection
        } else {
            // it did not run ok print the errors
            echo '<h1>Error!</h1> <p class="error">The following error(s) occurred:<br>';
            foreach($errors as $msg) {
                echo " - $msg<br>\n";
            }
            echo '</p><p>Please try again</p><p><br></p>';
        }
    }

    /**
     * This function adds a new event to the database
     */
    function addNewEvent()
    {
        $dbc = getDBC();

        $errors = []; //error array

        //check days
        if (empty($_POST['days'])) {
            $errors[] = 'Please select days of the week.';
        } else {
            $days = implode(', ', $_POST['days']);
        }

        //check time
        if (empty($_POST['times'])) {
            $errors[] = 'Please select the time(s) of day.';
        } else {
            $times = implode(', ', $_POST['times']);
        }

        //check title
        if (empty($_POST['title'])) {
            $errors[] = 'You forgot to enter a title.';
        } else {
            $title = mysqli_real_escape_string($dbc, trim($_POST['title']));
        }

        //check address
        if (empty($_POST['address'])) {
            $errors[] = 'You forgot to enter an address.';
        } else {
            $address = mysqli_real_escape_string($dbc, trim($_POST['address']));
        }

        //check city
        if (empty($_POST['city'])) {
            $errors[] = 'You forgot to enter a city.';
        } else {
            $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
        }

        //check zip
        if (empty($_POST['zip'])) {
            $errors[] = 'You forgot to enter a zip.';
        } else {
            $zip = mysqli_real_escape_string($dbc, trim($_POST['zip']));
        }


        if (empty($_POST['description'])) {
            $errors[] = 'You forgot to enter a description.';
        } else {
            $desc = mysqli_real_escape_string($dbc, trim($_POST['description']));
        }

        //check for format checkbox selections (formats[] array) at least one selection
        $num = count($_POST['format']);
        if(empty($_POST['format']) || $num == 0) {
            $errors[] = 'Please choose at least one format you are comfortable with presenting';
        } else {
            //set boolean true for selections sent to the db if selected format(s) in formats array
            foreach ($_POST['format'] as $format) {
                if($format == "QA") {
                    $qai = 1;
                }
                if($format == "Formal") {
                    $fpl = 1;
                }
                if($format == "Panel") {
                    $pnl = 1;
                }
                if($format == "Workshop") {
                    $ws = 1;
                }
            }
        }

        $date = mysqli_real_escape_string($dbc, trim($_POST['reportRange']));


        if(empty($errors)) {
            //get teacher's user id to put in event table
            $email = $_SESSION['email'];
            $query = "SELECT user_id FROM users WHERE email='$email'";
            $result = @mysqli_query($dbc, $query);

            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($result) {
                $user = $row['user_id'];
                $q = "INSERT INTO opportunity 
              (title, requested_by, description, qa_interview, lecture, panel, workshop, address, city, zip, dates, days, times) 
              VALUES ( '$title', '$user', '$desc', '$qai', '$fpl', '$pnl', '$ws','$address', '$city', '$zip', '$date','$days', '$times');";
                $r = @mysqli_query($dbc, $q); //run query
                if($r) {
                    header('Location: ../EventCreated');

                } else { //if it didnt run
                    echo '</div><h1>System Error</h1>
            <p class="error">Your opportunity could not be created. We apologize for any inconvenience.</p>';

                }
            } else {
                echo '</div><h1>System Error</h1>
            <p class="error">Your opportunity could not be created. We apologize for any inconvenience.</p>';
            }


            mysqli_close($dbc); //close db connection
            exit();

        } else { //report errors
            echo '</div><h1>Error!</h1>
        <p class="error">The following error(s) occurred:<br>';
            foreach ($errors as $msg) {
                echo " - $msg<br>\n";
            }
            echo '</p><p>Please try again.</p><p><br></p>';
        }
        mysqli_close($dbc);
    }

    /**
     * This function updates a teacher profile
     */
    function updateTeacherProfile()
    {
        //connect to db
        $dbc = getDBC();
        $email = $_SESSION['email'];

        $query = "SELECT user_id, email, first_name, last_name, password, bio, weekly_msg FROM users WHERE email='$email'";
        $result = @mysqli_query($dbc, $query);
        $user_info = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $user_id = $user_info['user_id'];

        $query2 = "SELECT school, district, grade, subject FROM teachers WHERE user_id='$user_id'";
        $result2 = @mysqli_query($dbc, $query2);
        $teacher_info = mysqli_fetch_array($result2, MYSQLI_ASSOC);

        $errors = []; // error array

        //check for first name
        if (isset($_POST['first_name'])) {
            $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
        } else {
            $fn = $user_info['first_name'];
        }

        //check for last name
        if (isset($_POST['last_name'])) {
            $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
        } else {
            $ln = $user_info['last_name'];
        }

        //check for email address   --extra step to make sure its a valid email** note to check
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email provided.';
        } else if (isset($_POST['email'])) {
            $e = mysqli_real_escape_string($dbc, trim($_POST['email']));
        } else {
            $e = $user_info['email'];
//        $_SESSION['email'] = $e;
        }

        //check for password  --extra step to make it secure** not to check Brian's suggestion
        if ($_POST['password'] != $_POST['confirm']) { //|| strlen(trim($_POST['password']) < 10)) {
            $errors[] = 'Passwords don\'t match.';
        } else if (isset($_POST['password']) && trim($_POST['password']) != '') {
            $pw = hash('sha512', trim($_POST['password']));
        } else {
            $pw = $user_info['password'];
        }

        //capture input company/organization name (Optional can be blank)
        if (isset($_POST['school']) && $_POST['school'] != '') {
            $school = mysqli_real_escape_string($dbc, trim($_POST['school']));
        } else {
            $school = $teacher_info['school'];
        }

        //capture input job title (Optional can be blank)
        if (isset($_POST['district']) && $_POST['district'] != ''){
            $district = mysqli_real_escape_string($dbc, trim($_POST['district']));
        } else {
            $district = $teacher_info['district'];
        }

        //capture input job title (Optional can be blank)
        if (isset($_POST['grade']) && $_POST['grade'] != ''){
            $grade = mysqli_real_escape_string($dbc, trim($_POST['grade']));
        } else {
            $grade = $teacher_info['grade'];
        }

        //capture input for bio (Optional can be blank)
        if (strlen($_POST['bio']) > 560) {
            $errors[] = 'Biography can only be 560 characters.';
        } else if (isset($_POST['bio']) && $_POST['bio'] != '') {
            $bio = mysqli_real_escape_string($dbc, trim($_POST['bio']));
        } else {
            $bio = $user_info['bio'];
        }

        //check for subject  -- note** what are we validating here?? not null or empty string ""
        if (isset($_POST['subject']) && $_POST['subject'] != '') {
            $subject = mysqli_real_escape_string($dbc, trim($_POST['subject']));
        } else {
            $subject = $teacher_info['subject'];
        }

        //check if the user would like to receive weekly speaking opportunity emails
        if(isset($_POST['weekly_email'])) {
            $wklyE = 1;
        } else {
            $wklyE = 0;
        }

        // If there are no errors make the query and run in db
        if (empty($errors)) {
            //insert data into the users db table
            $query = "UPDATE users
                  SET email='$e', first_name='$fn', last_name='$ln', password='$pw', bio='$bio', weekly_msg=$wklyE
                  WHERE email='".$_SESSION['email']."'";
            $result = @mysqli_query($dbc, $query); //run query

            $query = "UPDATE teachers
                  SET school='$school', district='$district', grade='$grade', subject='$subject'
                  WHERE user_id='".$_SESSION['user_id']."'";
            $result2 = @mysqli_query($dbc, $query);

            // if the query ran ok.
            if ($result) {
                $_SESSION['email'] = $e;
                if($result2) {
                    // header('Location: teacher_profile.php');
                    header('Location: ../');
                } else {
                    echo "Database error. Some fields not updated. Please refresh the page.";
                }
            } else { //the form did not run ok
                echo '<h1>System Error</h1>
                    <p class="error">Your Account was not updated due to a system error. We apologize for any inconvenience.</p>';
            }

        } else {
            // it did not run ok print the errors
            echo '<h1>Error!</h1> <p class="error">The following error(s) occurred:<br>';
            foreach($errors as $msg) {
                echo " - $msg<br>\n";
            }
            echo '</p><p>Please try again</p><p><br></p>';
        }
        mysqli_close($dbc);
    }
?>