<?php

session_start();
if(empty($_SESSION['email']) || $_SESSION['type'] == "Pro") {
    header('Location: index.php');
}

$page_title = 'My Events';
include('includes/header.html');

$email = $_SESSION['email'];
//$email = 'test@one.test';
require('db/mysqli_connect-wtia.php');

$query = "SELECT user_id, first_name, last_name, bio
          FROM users WHERE email='$email'";
$user_info = @mysqli_query($dbc, $query);
if($user_info) {
    $row = mysqli_fetch_array($user_info, MYSQLI_ASSOC);
    $user = $row['user_id'];
    $name = $row['first_name'] . " " . $row['last_name'];
    $bio = $row['bio'];
}

$query = "SELECT school, district, grade, subject
           FROM teachers WHERE user_id='$user'";
$teacher_info = @mysqli_query($dbc, $query);

if($teacher_info) {
    $row = mysqli_fetch_array($teacher_info, MYSQLI_ASSOC);

    if($row['school'] != null) {
        $school = $row['school'];
    }
    if($row['district'] != null) {
        $district = $row['district'];
    }
    if($row['grade'] != null) {
        $district = $row['grade'];
    }
    if($row['subject'] != null) {
        $district = $row['subject'];
    }
} else {
    echo 'Database error.';
}

$query = "SELECT * FROM opportunity 
          WHERE requested_by='$user'
          ORDER BY requested_on DESC;";

$opportunities = @mysqli_query($dbc, $query);
$num = mysqli_num_rows($opportunities);

echo '<div class="container border shadow p-5 mt-5 bg-light">';
echo '<h1 class="display-4 mb-5 text-center">My Active Speaking Opportunities</h1>';
echo '<div class="text-center"><a href="add_event.php"<button class="btn btn-secondary">Add Opportunity</button></a></div>';


if($num > 0) {

    while($row = mysqli_fetch_assoc($opportunities)) {
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

echo '   
    </div>
    ';   
?>

<div class="container border shadow p-5 mt-5 bg-light">
    <h1 class="display-4 mb-5 text-center">My Archived Opportunities</h1>
    <div class="text-center" style="margin-bottom: 100px;"><p>Your opportunities will go here once the end date has passed, or if you decide to archive them.<br>
    Archived opportunities can always be re-opened.</p></div>
</div>

<?php
include('includes/footer.html');
?>

