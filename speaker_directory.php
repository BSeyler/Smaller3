
<?php
include('includes/header.html');
session_start();

if(empty($_SESSION['type'])) {
    header('Location: index.php');
}

if($_SESSION['user_type'] == 'Speaker') {
    header('Location: speaker_home.com');
}

require('db/mysqli_connect-wtia.php');

$user_id = $_SESSION['user_id'];

$query = "SELECT first_name, last_name, bio FROM users WHERE user_type='Professional'";
$result1 = @mysqli_query($dbc, $query);
$rows = mysqli_num_rows($result1);

$query = "SELECT company, job_title, expertise FROM pros";
$result2 = @mysqli_query($dbc, $query);
$rows2 = mysqli_num_rows($result2);

if($rows2 != $rows) {
    echo '<p>The number of rows don\'t match</p>';
    echo "Users: $rows -- Pros: $rows2";
}

echo '<div class="container">';
echo '<h1 class="display-4 mb-5 text-center">Speaker Directory</h1>';

if($result1 && $result2) {
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

    for($i = 0; $i < $rows; $i++) {
        $user_row = mysqli_fetch_assoc($result1);
        $pro_row = mysqli_fetch_assoc($result2);

        echo '
        <tr>
            <td>' . $user_row['first_name'] . ' ' . $user_row['last_name'] . '</td>
            <td>'. $pro_row['job_title'] . '</td>
            <td>'. $pro_row['company'] . '</td>
            <td>'. $pro_row['expertise'] . '</td>
            <td>'. $user_row['bio'] . '</td>
        </tr>
     ';

    }
    echo '
    </tbody>
</table></div>
    ';
} else {
    echo 'Something went wrong with the database.';
}

include('includes/footer.html');
?>

<!--<footer>-->
<!---->
<!--</footer>-->
<!---->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"-->
<!--        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"-->
<!--        crossorigin="anonymous"></script>-->
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"-->
<!--        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"-->
<!--        crossorigin="anonymous"></script>-->
<!--</body>-->
<!--</html>-->



