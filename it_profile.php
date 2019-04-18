<?php
session_start();
if(empty($_SESSION['email']) || $_SESSION['type'] == 'Teacher') {
    header('Location: index.php');
}

$email = $_SESSION['email'];
$page_title = 'Your Profile';
include('includes/header.html');

require('db/mysqli_connect-wtia.php');

$query = "SELECT user_id, email, first_name, last_name, password, bio FROM users WHERE email='$email'";
$result = @mysqli_query($dbc, $query);
$user_info = mysqli_fetch_array($result, MYSQLI_ASSOC);

$user_id = $user_info['user_id'];

$query2 = "SELECT company, job_title, expertise, qa_interview, lecture, panel, workshop FROM pros WHERE user_id='$user_id'";
$result2 = @mysqli_query($dbc, $query2);
$pro_info = mysqli_fetch_array($result2, MYSQLI_ASSOC);


if(!$result) {
    echo 'user query did not work';
}

if(!$result2) {
    echo "user id: $user_id";
    echo "email: $email";
    echo "email: " . $user_info['email'];
    echo 'pro query did not work';
}

// Form Submission
if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    //connect to db

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
                  WHERE email='$email'";
        $result = @mysqli_query($dbc, $query); //run query

        $query = "UPDATE pros
                  SET company='$company', job_title='$job', expertise='$expertise', qa_interview='$qai', lecture='$fpl', panel='$pnl', workshop='$ws' 
                  WHERE user_id='$user_id'";
        $result2 = @mysqli_query($dbc, $query);

        // if the query ran ok.
        if ($result) {
            $_SESSION['email'] = $e;
            if($result2) {
                // header('Location: teacher_profile.php');
                header('Location: it_home.php');
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
    mysqli_close($dbc); //close the db
}

?>

<!--Start of html - IT professional create account form-->
<div class="container border shadow bg-light">
    <div class="col-sm-8 offset-md-2 offset-sm-0 justify-content-center">
        <div class="col-sm-12 text-center pb-4">
            <h1 class="display-4"><?php echo $user_info['first_name'] . "'s" . ' Profile'?></h1>
        </div>
        <form class="form-group" action="it_profile.php" method="post">
            <fieldset class="form-group">
                <div class="form-row col">
                    <div class="form-group col-md-6">
                        <label for="first_name">First name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" size="25"
                               maxlength="25" value="<?php echo $user_info['first_name'] ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name">Last name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" size="25"
                               maxlength="25" value="<?php echo $user_info['last_name'] ?>">
                    </div>
                </div>
                <div class="form-group col">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" size="256" maxlength="256"
                           value="<?php echo $user_info['email'] ?>">
                </div>
                <div class="form-group col">
                    <label for="password">Password</label>
                    <div class="form-row">
                        <div class="col">
                            <input type="password" class="form-control" name="password" id="password" size="20"
                                   maxlength="20" value="" placeholder="password" >
                        </div>
                        <div class="col">
                            <small id="passwordHelp" class="form-text text-muted col">At least 10 characters</small>
                        </div>
                    </div>
                </div>
                <div class="form-group col">
                    <label for="confirm">Confirm</label>
                    <div class="form-row">
                        <div class="col">
                            <input type="password" class="form-control" name="confirm" id="confirm" size="20"
                                   maxlength="20" value="" placeholder="confirm password" >
                        </div>
                        <div class="col">
                            <small id="passwordHelp" class="form-text text-muted col">Must match password</small>
                        </div>
                    </div>
                </div>


                <div class="form-row col">
                    <div class="form-group col-6">
                        <label for="company">Company</label>
                        <input type="text" class="form-control" name="company" id="company" size="40" maxlength="40"
                               value="<?php echo $pro_info['company'] ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="job">Job Title</label>
                        <input type="text" class="form-control" name="job" id="job" size="40" maxlength="40"
                               value="<?php echo $pro_info['job_title'] ?>">
                    </div>
                </div>

                <div class="form-group col">
                    <label for="bio">Short biography about you (third-person)</label>
                    <textarea class="form-control" name="bio" id="bio" maxlength="560" placeholder="Optional, Limit 560 characters"><?php echo $user_info['bio'] ?></textarea>
                </div>

                <div class="form-group col">
                    <label for="expertise">Expertise</label>
                    <input type="text" class="form-control" name="expertise" size="80" maxlength="80"
                           value="<?php echo $pro_info['expertise'] ?>" id="expertise">
                </div>

            </fieldset>

            <fieldset class="form-group col">
                <label>Format you feel comfortable presenting</label>
                <small id="formatHelp" class="form-text text-muted"><em>Check at least one -OR- all that apply</em></small>
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="qa" value="Q & A / Interview"
                        <?php if($pro_info['qa_interview'] == 1){echo 'checked';}?>>
                    <label class="form-check-label" for="qa">Q & A / Interview</label>
                </div>
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="formal" value="Formal Presentation / Lecture"
                        <?php if($pro_info['lecture'] == 1){echo 'checked';}?>>
                    <label class="form-check-label" for="formal">Formal Presentation / Lecture</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="panel" value="Panel"
                        <?php if($pro_info['panel'] == 1){echo 'checked';}?>>
                    <label class="form-check-label" for="panel">Panel</label>
                </div>
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="workshop" value="Workshop"
                        <?php if($pro_info['workshop'] == 1){echo 'checked';}?>>
                    <label class="form-check-label" for="workshop">Workshop</label>
                </div>
            </fieldset>

            <fieldset class="form-group col">
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="weekly_email" id="weekly_email" value="yes"
                        <?php if($user_info['weekly_msg'] == 1){echo 'checked';}?>>
                    <label class="form-check-label" for="weekly_email">Receive weekly email updates for Speaking Opportunity matches?</label>
                </div>
            </fieldset>
            <div class="form-group col">
                <div class="col text-center mt-5">
                    <button type="submit" class="btn btn-secondary" value="Submit">Update Account</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include('includes/footer.html');
?>
