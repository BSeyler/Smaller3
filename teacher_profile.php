<?php
session_start();
if(empty($_SESSION['email']) || $_SESSION['type'] == 'Pro') {
    header('Location: index.php');
}

$email = $_SESSION['email'];
$page_title = 'Your Profile';
include('includes/header.html');

require('db/mysqli_connect-wtia.php');

$query = "SELECT user_id, email, first_name, last_name, password, bio, weekly_msg FROM users WHERE email='$email'";
$result = @mysqli_query($dbc, $query);
$user_info = mysqli_fetch_array($result, MYSQLI_ASSOC);

$user_id = $user_info['user_id'];

$query2 = "SELECT school, district, grade, subject FROM teachers WHERE user_id='$user_id'";
$result2 = @mysqli_query($dbc, $query2);
$teacher_info = mysqli_fetch_array($result2, MYSQLI_ASSOC);


if(!$result) {
    echo 'first did not work';
}

if(!$result2) {
    echo 'this didn\'t';
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
                  SET email='$e', first_name='$fn', last_name='$ln', password='$pw', bio='$bio', weekly_msg='$wklyE'
                  WHERE email='$email'";
        $result = @mysqli_query($dbc, $query); //run query

        $query = "UPDATE teachers
                  SET school='$school', district='$district', grade='$grade', subject='$subject'
                  WHERE user_id='$user_id'";
        $result2 = @mysqli_query($dbc, $query);

        // if the query ran ok.
        if ($result) {
            $_SESSION['email'] = $e;
            if($result2) {
                // header('Location: teacher_profile.php');
                header('Location: teacher_home.php');
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
<div class="container shadow border bg-light">
    <div class="col-sm-8 offset-md-2 offset-sm-0 justify-content-center">
        <div class="col-sm-12 text-center pb-4">
            <h1 class="display-4"><?php echo $user_info['first_name'] . "'s" . ' Profile'?></h1>
        </div>
        <form class="form-group" action="teacher_profile.php" method="post">
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
                <div class="form-group col">
                    <label for="district">District</label>
                    <input type="text" class="form-control" name="district" size="80" maxlength="80"
                           value="<?php echo $teacher_info['district'] ?>" id="district">
                </div>

                <div class="form-row col">
                    <div class="form-group col-6">
                        <label for="school">School</label>
                        <input type="text" class="form-control" name="school" id="school" size="40" maxlength="40"
                               value="<?php echo $teacher_info['school'] ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="grade">Grade</label>
                        <select class="custom-select" name="grade" id="grade">
                            <option selected>Select a Grade</option>
                            <option value="6"<?php if($teacher_info['grade'] == '6') {
                                echo 'selected="selected"';} ?>>6th Grade</option>
                            <option value="7"<?php if($teacher_info['grade'] == '7') {
                                echo 'selected="selected"';} ?>>7th Grade</option>
                            <option value="8"<?php if($teacher_info['grade'] == '8') {
                                echo 'selected="selected"';} ?>>8th Grade</option>
                            <option value="9"<?php if($teacher_info['grade'] == '9') {
                                echo 'selected="selected"';} ?>>9th Grade</option>
                            <option value="10"<?php if($teacher_info['grade'] == '10') {
                                echo 'selected="selected"';} ?>>10th Grade</option>
                            <option value="11"<?php if($teacher_info['grade'] == '11') {
                                echo 'selected="selected"';} ?>>11th Grade</option>
                            <option value="12"<?php if($teacher_info['grade'] == '12') {
                                echo 'selected="selected"';} ?>>12th Grade</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col">
                    <label for="bio">Short biography about you (third-person)</label>
                    <textarea class="form-control" name="bio" id="bio" maxlength="560"
                              placeholder="Optional, Limit 560 characters"><?php echo $user_info['bio'] ?></textarea>
                </div>
                <div class="form-group col">
                    <label for="subject">Subject</label>
                    <input type="text" class="form-control" name="subject" id="subject"
                           value="<?php echo $teacher_info['subject'] ?>">
                </div>
            </fieldset>

            <fieldset class="form-group col">
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="weekly_email" id="weekly_email" value="yes"
                        <?php if($user_info['weekly_msg'] == 1){echo 'checked';}?>>
                    <label class="form-check-label" for="weekly_email">Receive weekly email updates for Speaking Opportunity matches? </label>
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
