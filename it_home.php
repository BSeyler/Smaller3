<?php

session_start();
if(empty($_SESSION['email']) || $_SESSION['type'] == 'Teacher') {
    header('Location: index.php');
}


$page_title = 'Opportunities';
include('includes/header.html');

$email = $_SESSION['email'];

require('db/mysqli_connect-wtia.php');

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

$opportunities = @mysqli_query($dbc, $query);
$num = mysqli_num_rows($opportunities);

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
include('includes/footer.html');
?>


