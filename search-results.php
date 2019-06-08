    <?php
    /**
     * Bradley Seyler, Aaron Reynolds, Christian Talmadge
     * 6/7/2019
     * search-results.php
     *
     * This file is unconverted from TeamWon's source code. It contains search functions.
     */

     //start session
     session_start();
     
     //check for email
     if(empty($_SESSION['email'])) {
         header('Location: index.php');
     }
     
     $page_title = 'Search Results';
     
     //add header and sql connection
     include('includes/header.html');
     require('db/mysqli_connect-wtia.php');

     //create container
     echo '<div class="container border shadow p-5 mt-5 bg-light">';
     
    //////////pros search events///////////////
    if($_SESSION['type'] == 'Pro') {
        
        //if search set is empty show all events
        if(empty($_GET['search'])) {
            
            $query = "SELECT * FROM opportunity
                     ORDER BY requested_on DESC
                     LIMIT 25;";
                     
            $result = @mysqli_query($dbc, $query);
       
            //counts the number of rows returned
            $num_rows = mysqli_num_rows($result);
            
            //if there is a result
            if($num_rows > 0) {
                echo '<h1 class="display-4 text-center">Showing All Speaking Opportunities</h1>';
            
                //fetch and print all applicable records
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    //get opportunity info
                    $requested_by = $row['requested_by'];
                    $title = $row['title'];
                    $dates = $row['dates'];
                    $days = $row['days'];
                    $times = $row['times'];
                    $city = $row['city'];
                    $description = $row['description'];
                    
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
                    $type = substr($type, 0, -2); //remove last comma
                    
                    //get email of teacher requesting event
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
                                 <h5 class="card-title"><strong>Date Range: ' . $dates . '</h5></strong>
                                 <p class="card-text">Days of the Week: '. $days .'<br> Times of Day: '. $times .'<br>
                                 Location: '. $city . '<br>Format: ' . $type . '<br>Description: ' . $description . '</p>
                                 <h5 class="card-title"><strong>Requested by: ' . $name . '</h5></strong>
                                 <a href="mailto:' . $teacher_email . '" class="btn btn-secondary">Send Email</a>
                                 <a href="" class="btn btn-secondary">Copy Email</a>
                             </div>
                           </div>';
                    $type = '';
                    mysqli_free_result($teacher_info);          
                 }
            mysqli_free_result($result); //stop
            
            echo '</div>';
            include('includes/footer.html');
            exit();
            }
        }
        //save search if valid, trim & escape
        else {
            $search = mysqli_real_escape_string($dbc, trim($_GET['search']));
        }  
        //parse words
        $search_list = explode(" ", $search);
        
        //build where statements
        $where_statements = '';
        foreach($search_list as $search_value) {
            $search_value = trim($search_value);
            $where_statements .= "title LIKE '%$search_value%' OR
                                  description LIKE '%$search_value%' OR
                                  dates LIKE '%$search_value%' OR
                                  city LIKE '%$search_value%' OR ";
        }
        
        //remove last OR
        $where_statements = substr($where_statements, 0, -3);
        
        //create query, searching in appropriate columns
        $query =
                "SELECT * FROM opportunity
                 WHERE ". $where_statements .
                "ORDER BY requested_on DESC
                 LIMIT 25;";
       
       $result = @mysqli_query($dbc, $query);
       
       //counts the number of rows returned
       $num_rows = mysqli_num_rows($result);
       
       //if there is a result
       if($num_rows > 0) {
            
           echo '<h1 class="display-4 text-center">Results for "'. $search . '"</h1>';
            
           //fetch and print all applicable records
           while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                //get opportunity info
                $requested_by = $row['requested_by'];
                $title = $row['title'];
                $dates = $row['dates'];
                $days = $row['days'];
                $times = $row['times'];
                $city = $row['city'];
                $description = $row['description'];
                
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
                $type = substr($type, 0, -2);//remove last comma
                
                //get email of teacher requesting event
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
                             <h5 class="card-title"><strong>Date Range: ' . $dates . '</h5></strong>
                             <p class="card-text">Days of the Week: '. $days .'<br> Times of Day: '. $times .'<br>
                             Location: '. $city . '<br>Format: ' . $type . '<br>Description: ' . $description . '</p>
                             <h5 class="card-title"><strong>Requested by: ' . $name . '</h5></strong>
                             <a href="mailto:' . $teacher_email . '" class="btn btn-secondary">Send Email</a>
                             <a href="" class="btn btn-secondary">Copy Email</a>
                         </div>
                       </div>';
                $type = '';
                mysqli_free_result($teacher_info);          
            }
           mysqli_free_result($result); //stop
       }
       //no records were found with search term
       else {
            echo '<h1 class="display-4 text-center" style="margin-bottom: 300px;">Your search for "' . $search . '" returned no results.</h2>';
       }    
    } //end professional search
    
    /////////////teachers search speakers/////////////
    else if($_SESSION['type'] == 'Teacher') {
    
        //if search set is empty show all speakers
        if(empty($_GET['search'])) {
            $query = "SELECT CONCAT(first_name, ' ', last_name) AS name,
                      bio AS bio, company AS company,
                      job_title AS job_title, expertise AS expertise,
                      email AS email
                      FROM users INNER JOIN pros USING(user_id)
                      LIMIT 25;";
                     
            $result = @mysqli_query($dbc, $query);
       
            //counts the number of rows returned
            $num_rows = mysqli_num_rows($result);
            
            //if there is a result
            if($num_rows > 0) {
                echo '<h1 class="display-4 text-center">Showing All Speakers</h1>';
            
                //fetch and print all applicable records
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                     
                     echo '<div class="card my-5">
                        <h5 class="card-header">' . $row['name'] . '</h5>
                         <div class="card-body">
                            <h5 class="card-title">' . $row['company'] . ', ' . $row['job_title'] . '</h5>
                            <p class="card-text">Expertise: '. $row['expertise'] . '<br>Bio: ' . $row['bio'] . '</p>
                            <a href="mailto:' . $row['email'] . '" class="btn btn-secondary">Send Email</a>
                            <a href="" class="btn btn-secondary">Copy Email</a>
                         </div>
                       </div>';           
                 }
            mysqli_free_result($result); //stop
            echo '</div>';
            include('includes/footer.html');
            exit();
            }
        }
        //save search if valid, trim & escape
        else {
            $search = mysqli_real_escape_string($dbc, trim($_GET['search']));
        }  
        
        //parse words
        $search_list = explode(" ", $search);
        
        //build where statements
        $where_statements = '';
        foreach($search_list as $search_value) {
            $search_value = trim($search_value);
            $where_statements .= "first_name LIKE '%$search_value%' OR
                                  last_name LIKE '%$search_value%' OR
                                  bio LIKE '%$search_value%' OR
                                  company LIKE '%$search_value%' OR
                                  job_title LIKE '%$search_value%' OR 
                                  expertise LIKE '%$search_value%' OR ";
        }
        
        //remove last OR
        $where_statements = substr($where_statements, 0, -3);
    
        //create query, searching in appropriate columns
        $query =
                "SELECT CONCAT(first_name, ' ', last_name) AS name,
                 bio AS bio, company AS company,
                 job_title AS job_title, expertise AS expertise,
                 email AS email
                 FROM users INNER JOIN pros USING(user_id)
                 WHERE " . $where_statements . 
                "LIMIT 25;";
                 
                 
       $result = @mysqli_query($dbc, $query);
       
       //counts the number of rows returned
       $num_rows = mysqli_num_rows($result);
       
       //if there is a result
       if($num_rows > 0) {
            
           echo '<h1 class="display-4 text-center">Results for "'. $search . '"</h1>';
            
           //fetch and print all applicable records
           while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                
                echo '<div class="card my-5">
                        <h5 class="card-header">' . $row['name'] . '</h5>
                         <div class="card-body">
                            <h5 class="card-title">' . $row['company'] . ', ' . $row['job_title'] . '</h5>
                            <p class="card-text">Expertise: '. $row['expertise'] . '<br>Bio: ' . $row['bio'] . '</p>
                            <a href="mailto:' . $row['email'] . '" class="btn btn-secondary">Send Email</a>
                            <a href="" class="btn btn-secondary">Copy Email</a>
                        </div>
                      </div>';
           }
           mysqli_free_result($result); //stop
       }
       //no records were found with search term
       else {
            echo '<h1 class="display-4 text-center" style="margin-bottom: 300px;">Your search for "' . $search . '" returned no results.</h2>';
       }    
    }//end speaker search if
     
    //close db connection
    mysqli_close($dbc);

    //close container, add footer
    echo '</div>';
    include('includes/footer.html')
    ?>
    