<?php
    require('db/model.php');

    session_start();

    function processLogout()
    {
        $_SESSION['email'] = null;
        session_destroy();
        header('Location: ../');
    }

    function displayLogin($fatFree){
        if(isset($_SESSION['email'])) {

            $email = $_SESSION['email'];
            $query = "SELECT user_type FROM users WHERE email='$email'";
            $result = @mysqli_query(getDBC(), $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $user_type = $row['user_type'];

            if($user_type == "Teacher") {
                $_SESSION['type'] = 'Teacher';
                header('Location: ../Speaker_Home');
                exit;
            } elseif ($user_type == "Professional") {
                $_SESSION['type'] = 'Professional';
                header('Location: ../Speaker_Home');
                exit;
            }
        }

        $fatFree->set('cssURL', "styles/main.css");
        $fatFree->set('pageTitle', 'Login');
        //Then render the landing page
        echo Template::instance()->render('views/login.html');
        //Then close the landing pag
    }

    function determineProfileType()
    {
        if(empty($_SESSION['email']))
        {
            header('Location: ../');
        }
        else
        {
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

    function displayRegisterSpeaker($fatFree)
    {
        $fatFree->set('pageTitle', "Register");

        //Render the registration page
        echo Template::instance()->render('includes/header_no_dropdown.html');
        echo Template::instance()->render('views/it_register.html');
        echo Template::instance()->render('includes/footer.html');
    }

    function displayRegisterTeacher($fatFree)
    {
        $fatFree->set('pageTitle', "Register A Teacher Account");

        //Render the registration page
        echo Template::instance()->render('includes/header_no_dropdown.html');
        echo Template::instance()->render('views/teacher_register.html');
        echo Template::instance()->render('includes/footer.html');
    }

    function displaySpeakerProfileEdit($fatFree)
    {
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

    function displaySpeakerHome($fatFree)
    {
        $fatFree->set('pageTitle', "Register A Teacher Account");
        $fatFree->set('name', $_SESSION['name']);
        $fatFree->set('type', $_SESSION['type']);

        //Render the registration page
        echo Template::instance()->render('includes/header.html');

        $opportunities = getOpportunities();
        renderOpportunities($opportunities);

        echo Template::instance()->render('includes/footer.html');
    }

    function renderOpportunities($opportunities)
    {
        $num = mysqli_num_rows($opportunities);

        $dbc = getDBC();

        echo '<div class="container border shadow p-5 mt-5 bg-light">';
        echo '<h1 class="display-4 mb-5 text-center">Speaking Opportunities For You</h1>';

        if($num > 0) {

            while($row = mysqli_fetch_assoc($opportunities)) {
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
                <a href="mailto:' . $teacher_email . '" class="btn btn-secondary">Send Email</a>
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

    function processSpeakerRegistration()
    {
        registerSpeaker();
    }

function processSpeakerUpdate()
{
    updateSpeakerProfile();
}

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
            echo "Congrats, you can log in once the DB is connected!";
        }

        $fatFree->set('name', $_SESSION['name']);
    }




    ?>