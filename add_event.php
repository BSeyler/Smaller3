<?php
session_start();
if(empty($_SESSION['email']) || $_SESSION['type'] == 'Pro') {
    header('Location: index.php');
}
?>

<!doctype html>
<html lang="en">
<head>
<!--     Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<!--     Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans" rel="stylesheet">

    <title>Add an Opportunity</title>

<!-- Day & Time Picker -->
<link href="includes/weekDays.css" rel="stylesheet" type="text/css">


<!--     Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="includes/dateTime.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="general_styles.css" type="text/css">
<link rel="icon" href="includes/favicon.png">

</head>
<body style="background-color: #EEFDFE;">

<nav class="navbar sticky-top navbar-expand-sm navbar-light" style="background-color: #8adae2; opacity: 0.9;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-toggle"
            aria-controls="navbar-toggle" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar-toggle">
        <a class="navbar-brand" href="index.php">
            <img src="images/home.svg" width="40" height="40" alt="home" class="ml-lg-3 ml-sm-1 mt-sm-2 my-1">
        </a>

        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 ml-lg-3 ml-sm-1">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <?php echo '' . $_SESSION['name'] . '\'s ' ?> Profile
                </a>
                <div class="dropdown-menu" aria-labelledby="navbar-dropdown">
                    <a class="dropdown-item" href="teacher_profile.php"><?php echo '' . $_SESSION['name'] . '\'s ' ?> Profile</a>
                    <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
            </li>
            
            <?php
                if($_SESSION['type'] == 'Teacher') {
                echo '
                <li class="nav-item">
                    <a class="nav-link" href="speaker_directory.php">Speaker Directory</a>
                </li>
            ';
            }
            ?>
        </ul>

        <form action="search-results.php" method="get" class="form-inline my-2 my-lg-0" >
            <input class="form-control" type="text" placeholder="Search for speakers.." aria-label="Search" name="search">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
</nav>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require('mysqli_connect-wtia.php');

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

    //check topic
//    if(empty($_POST['topic'])) {
//        $errors[] = 'Please select a topic.';
//    } else {
//        $qty = mysqli_real_escape_string($dbc, trim($_POST['topic']));
//    }


    if(empty($errors)) {
        //get teacher's user id to put in event table
        $email = $_SESSION['email'];
//        $email = 'test@one.test';
        $query = "SELECT user_id FROM users WHERE email='$email'";
        $result = @mysqli_query($dbc, $query);

        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        if($result) {
            $user = $row['user_id'];
            $q = "INSERT INTO opportunity 
              (title, requested_by, description, qa_interview, lecture, panel, workshop, address, city, zip, dates, days, times) 
              VALUES ( '$title', '$user', '$desc', '$qai', '$fpl', '$pnl', '$ws','$address', '$city', '$zip', '$date','$days', '$times');";
            $r = @mysqli_query($dbc, $q); //run query
            if($r) { //if ran ok

                //print msg

                echo '</div><div class="container text-center shadow border bg-light" style="margin-bottom: 100px; height: 500px; padding-top: 150px;"> <h1>Thank you!</h1>
                       <p>Your opportunity has been created.</p><p></p>

                
                        <div class="text-center" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <a href="teacher_home.php"><button type="button" class="btn btn-secondary">Back to Home</button></a>              
                            </div>
                            <div class="btn-group mr-2" role="group" aria-label="Second group"">
                                <a href="add_event.php"><button type="button" class="btn btn-secondary">Add Another opportunity</button></a> 
                            </div>
                        </div>  
                        </div>          
                        ';

            } else { //if it didnt run
                echo '</div><h1>System Error</h1>
            <p class="error">Your opportunity could not be created. We apologize for any inconvenience.</p>';

                echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
            }
        } else {
            echo '</div><h1>System Error</h1>
            <p class="error">Your opportunity could not be created. We apologize for any inconvenience.</p>';

            echo '<p>' . mysqli_error($dbc) . '<br><br>Query: ' . $q . '</p>';
        }
        //make query


        mysqli_close($dbc); //close db connection
        include('includes/footer.html');
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
?>


<div class="container mb-4 shadow border bg-light">
    <div class="col-sm-8 offset-md-2 offset-sm-0 justify-content-center">

    <h1 class="text-center mb-5 display-4">Add an Opportunity</h1>

    <form action="add_event.php" class="form-group" method="post">
        <div class="form-group">
            <div class="form-group col">

            <label for="title" class="required">Opportunity Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Include a short unique name..."
                    value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>"  required>
            </div>
            <div class="form-group col">

            <label for="location" class="required">Opportunity Location</label>
            <input type="text" class="form-control" name="address" id="location" placeholder="Venue Address"
                   value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>"  required>
            </div>
        </div>
        <div class="form-row col">
            <div class="form-group col-md-6">
                <label for="city" class="required">City</label>
                <input type="text" class="form-control" name="city" placeholder="City"
                       value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" required>
            </div>
            <div class="form-group col-md-6">
                <label for="zip" class="required">Zip Code</label>
                <input type="text" class="form-control" name="zip" placeholder="Zip"
                       value="<?php if (isset($_POST['zip'])) echo $_POST['zip']; ?>" required>
            </div>
        </div>
        <div class="form-group col">
            <label for="reportRange" class="required">Date Range</label>
            <input name="reportRange" id="reportRange" style="background: #fff; cursor: pointer; padding: 5px 10px;
            border-radius: 4px; border: 1px solid #ccc; width: 100%"><small>*Opportunities get archived after end date has passed</small>
        </div>

        <div class="form-group col">
            <label for="weekDays" class="required">Days of the Week</label>
            <div class="weekDays-selector" id="weekDays">
                <input type="checkbox" id="weekday-sun" class="weekday" name="days[]" value="Sun" <?php if (!empty($_POST['days'])
                    && in_array("Sun", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-sun">Sun</label>
                <input type="checkbox" id="weekday-mon" class="weekday" name="days[]" value="Mon" <?php if (!empty($_POST['days'])
                    && in_array("Mon", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-mon">Mon</label>
                <input type="checkbox" id="weekday-tue" class="weekday" name="days[]" value="Tue" <?php if (!empty($_POST['days'])
                    && in_array("Tue", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-tue">Tue</label>
                <input type="checkbox" id="weekday-wed" class="weekday" name="days[]" value="Wed" <?php if (!empty($_POST['days'])
                    && in_array("Wed", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-wed">Wed</label>
                <input type="checkbox" id="weekday-thu" class="weekday" name="days[]" value="Thu" <?php if (!empty($_POST['days'])
                    && in_array("Thu", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-thu">Thu</label>
                <input type="checkbox" id="weekday-fri" class="weekday" name="days[]" value="Fri" <?php if (!empty($_POST['days'])
                    && in_array("Fri", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-fri">Fri</label>
                <input type="checkbox" id="weekday-sat" class="weekday" name="days[]" value="Sat" <?php if (!empty($_POST['days'])
                    && in_array("Sat", $_POST['days'])) echo 'checked'; ?>/>
                <label for="weekday-sat">Sat</label>
            </div>
        </div>

        <div class="form-group col">
            <label for="time" class="required">Preferred Time(s) of Day</label>
            <div class="time" id="weekDays">
                <input type="checkbox" id="morning" class="weekday" name="times[]" value="Mornings" <?php if (!empty($_POST['times'])
                    && in_array("Morning", $_POST['times'])) echo 'checked'; ?>/>
                <label for="morning">Mornings</label>
                <input type="checkbox" id="afternoon" class="weekday" name="times[]" value="Afternoons" <?php if (!empty($_POST['times'])
                    && in_array("Afternoon", $_POST['times'])) echo 'checked'; ?>/>
                <label for="afternoon">Afternoons</label>
                <input type="checkbox" id="evening" class="weekday" name="times[]" value="Evenings" <?php if (!empty($_POST['times'])
                    && in_array("Evening", $_POST['times'])) echo 'checked'; ?>/>
                <label for="evening">Evenings</label>
            </div>
        </div>

        <div class="form-group col">
        <label for="description" class="required">Opportunity Description</label>
            <textarea class="form-control" id="description" aria-label="With textarea" name="description" rows="6"><?php if (isset($_POST['description'])) echo $_POST['description']; ?></textarea>
        </div>

        <div class="form-group col">
        <label for="format[]" class="required">Format of Opportunity</label>
            <small id="formatHelp" class="form-text text-muted"><em>Check at least one -OR- all that apply</em></small>

            <div class="form-check" id="format">
                <input class="form-check-input" type="checkbox" id="QA" value="QA" name="format[]"<?php if
                (!empty($_POST['format']) && in_array("QA", $_POST['format'])) echo 'checked'; ?>>
                <label class="form-check-label" for="QA">
                    Q & A / Interview
                </label>
             </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="Formal" name="format[]" value="Formal"
                    <?php if (!empty($_POST['format']) && in_array("Formal", $_POST['format']))
                        echo 'checked'; ?>>
                <label class="form-check-label" for="Formal">
                    Formal Presentation / Lecture
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="Panel" name="format[]" value="Panel" <?php if
                (!empty($_POST['format']) && in_array("Panel", $_POST['format'])) echo 'checked'; ?>>
                <label class="form-check-label" for="Panel">
                    Panel
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="Workshop" name="format[]" value="Workshop" <?php if
                (!empty($_POST['format']) && in_array("Workshop", $_POST['format'])) echo 'checked'; ?>>
                <label class="form-check-label" for="Workshop">
                    Workshop
                </label>
            </div>
        </div>

        <div class="form-group col">
        <p style="margin-top: 50px; text-align: center">
                <input class="btn btn-secondary text-center" type="submit" value="Add Opportunity">
            </p>
        </div>
    </form>
    </div>
</div>

<!--footer-->

<footer class="footer">
    <div class="pt-4 pb-3 text-center text-secondary">
        <p>@Copyright 2019<br>WTIA Connections</p>
    </div>
</footer>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>