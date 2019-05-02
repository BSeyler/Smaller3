<?php

session_start();
if(!empty($_SESSION['email'])) {
    if($_SESSION['type'] == 'Teacher') {
        header('Location: teacher_home.php');
    } else if($_SESSION['type'] == 'Pro') {
        header('Location: it_home.php');
    } else {
        header('Location: index.php');
    }
}

$page_title = 'Create Your Account';
include('includes/header_no_dropdown.html');

// Form Submission
if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    require('db/model.php'); //connect to db

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
          //display thank you for registering until redirect
          echo '<div class="container mx-auto p-5 border shadow text-center" style="background-color: #f4f4f4; width: 375px; margin-top: 75px; margin-bottom: 75px;">
                <h2>Thank you for registering!</h2>
                <hr class="my-4">
                <h2 class="mb-4">You will now be redirected to the login page.</h2>
                <p><i class="fas fa-spinner fa-spin fa-3x"></i></p>
                </div>';
   
          //redirect to login after 5 seconds
          echo '<script type="text/javascript">
                $(document).ready(setTimeout(function() {
                window.open("index.php", "_self");
                }, 3000));
                </script>';
               
        } else { //the form did not run ok
            echo '<h1>System Error</h1>
                    <p class="error">Your Account was not registered due to a system error. We apologize for any inconvenience.</p>';
        }

        mysqli_close($dbc); //close the db connection
        exit();
    } else {
        echo '<div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Your information is incomplete!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">';
                      
                      echo '<h1>Error!</h1> <p class="error">The following error(s) occurred:<br>';
                      foreach($errors as $msg) {
                         echo " - $msg<br>\n";
                      } 
                      echo '</p><p>Please try again</p><p><br></p>';
                                  
                      echo 
                      '</div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                      </div>
                    </div>
                  </div>
                </div>';
                
          //jquery to show error modal      
          echo '<script>
                $(document).ready(function() {
                $("#exampleModalCenter1").modal("show");
                });
                </script>';  
    }
    mysqli_close($dbc); //close the db
}

?>

<!--Start of html - IT professional create account form-->
<div class="container border shadow bg-light">
    <div class="col-sm-12 text-center pb-5">
        <h1 class="display-4">Create your Speaker Account</h1>
    </div>
    <div class="col-sm-8 offset-md-2 offset-sm-0 justify-content-center">
        <form class="form-group" action="it_form.php" method="post">
            <fieldset class="form-group">
                <div class="form-row col">
                    <div class="form-group col-md-6">
                        <label for="first_name" class="required">First name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" size="25"
                               maxlength="25" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name'] ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name" class="required">Last name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" size="25"
                               maxlength="25" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name'] ?>" required>
                    </div>
                </div>
                <div class="form-group col">
                    <label for="email" class="required">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" size="256" maxlength="256"
                           value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" required>
                </div>
                <div class="form-group col">
                    <label for="password" class="required">Password</label>
                    <div class="form-row">
                        <div class="col">
                            <input type="password" class="form-control" name="password" id="password" size="20" placeholder="Password"
                                   maxlength="20" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>" required>
                        </div>
                        <div class="col">
                            <small id="passwordHelp" class="form-text text-muted col">At least 10 characters</small>
                        </div>
                    </div>
                </div>
                <div class="form-group col">
                    <label for="confirm" class="required">Confirm</label>
                    <div class="form-row">
                        <div class="col">
                            <input type="password" class="form-control" name="confirm" id="confirm" size="20"
                                   maxlength="20" value="" placeholder="Confirm password" required>
                        </div>
                        <div class="col">
                            <small id="passwordHelp" class="form-text text-muted col">Must match password</small>
                        </div>
                    </div>
                </div>
                <div class="form-group col">
                    <label for="company_name">Company -OR- Organization name</label>
                    <input type="text" class="form-control" name="company_name" size="80" maxlength="80"
                           value="<?php if(isset($_POST['company_name'])) echo $_POST['company_name'] ?>" id="company_name">
                </div>
                <div class="form-group col">
                    <label for="job_title">Job title</label>
                    <input type="text" class="form-control" name="job_title" id="job_title" size="40" maxlength="40"
                           value="<?php if(isset($_POST['job_title'])) echo $_POST['job_title'] ?>">
                </div>
                <div class="form-group col">
                    <label for="bio">Short biography about you (third-person)</label>
                    <textarea class="form-control" name="bio" id="bio" maxlength="560" placeholder="Optional, Limit 560 characters"><?php if(isset($_POST['bio'])) echo $_POST['bio'] ?></textarea>
                </div>
                <div class="form-group col">
                    <label for="expertise" class="required">Area of expertise you are comfortable speaking on</label>
                    <small id="expertiseHelp" class="form-text text-muted"><em>Enter each area of expertise separated by ", "</em></small>
                    <input type="text" class="form-control" name="expertise" id="expertise" placeholder="Java, SQL, JavaScript..."
                           value="<?php if(isset($_POST['expertise'])) echo $_POST['expertise'] ?>" required>
                </div>
            </fieldset>
            <fieldset class="form-group col">
                <label class="required">Format you feel comfortable presenting</label>
                <small id="formatHelp" class="form-text text-muted"><em>Check at least one -OR- all that apply</em></small>
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="qa" value="Q & A / Interview"
                        <?php if(isset($_POST['formats'])
                            && in_array("Q & A / Interview", $_POST['formats'])){echo 'checked';}?>>
                    <label class="form-check-label" for="qa">Q & A / Interview</label>
                </div>
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="formal" value="Formal Presentation / Lecture"
                        <?php if(isset($_POST['formats'])
                            && in_array("Formal Presentation / Lecture", $_POST['formats'])){echo 'checked';}?>>
                    <label class="form-check-label" for="formal">Formal Presentation / Lecture</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="panel" value="Panel"
                        <?php if(isset($_POST['formats'])
                            && in_array("Panel", $_POST['formats'])){echo 'checked';}?>>
                    <label class="form-check-label" for="panel">Panel</label>
                </div>
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="formats[]" id="workshop" value="Workshop"
                        <?php if(isset($_POST['formats'])
                            && in_array("Workshop", $_POST['formats'])){echo 'checked';}?>>
                    <label class="form-check-label" for="workshop">Workshop</label>
                </div>
            </fieldset>
            <fieldset class="form-group col">
                <div class="form-check text-left">
                    <input class="form-check-input" type="checkbox" name="weekly_email" id="weekly_email" value="yes"
                        <?php if(isset($_POST['formats'])){echo 'checked';}?>>
                    <label class="form-check-label" for="weekly_email">Receive weekly email updates for speaking opportunity matches?</label>
                </div>
            </fieldset>
            <div class="form-group col mt-5">
                <div class="col text-center">
                    <button type="submit" class="btn btn-secondary" value="Submit">Create Account</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include('includes/footer.html');
?>
