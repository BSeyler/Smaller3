<?php
session_start();
if(empty($_SESSION['email']) || $_SESSION['type'] == 'Pro') {
    header('Location: index.php');
}
/* couldnt get it to work, no time 
$opp_id = $_POST['opp_id'];
var_dump($opp_id);

if(isset($_POST['archive-event'])) {
    if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
        
        var_dump($opp_id);
    } 
}*/

$page_title = 'Archive Event';
include("includes/header.html");

?>

<div class="container border shadow p-5 mt-5 bg-light text-center" style="height:500px; width:400px;">
    <h2 class="mb-5">Are you sure you want to archive this opportunity?</h2>
    
    <!--<form action="archive_event.php" method="post">
    <p><button type="submit" class="btn btn-secondary mb-2" name="archive-event" style="width:100px;">Yes</button></p>
    </form>-->
    
    <p><a href="teacher_home.php" class="btn btn-secondary"style="width:100px;">Yes</a></p>
    <p><a href="teacher_home.php" class="btn btn-secondary"style="width:100px;">No</a></p>
    
    
</div>



<?php
include("includes/footer.html");
?>