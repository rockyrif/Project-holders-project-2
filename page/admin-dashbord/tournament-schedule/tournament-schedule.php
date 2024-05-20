<?php
session_start();
if (isset($_SESSION["username"]) && $_SESSION["privilage"] === "admin") {
?>
   <?php
   include $_SERVER['DOCUMENT_ROOT'] . "/project-holders-project-2/db_conn.php";


   // Check if form is submitted
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get form data and sanitize it
      $name = mysqli_real_escape_string($conn, $_POST['tournament-name']);
      $type = mysqli_real_escape_string($conn, $_POST['type']);
      $grade = mysqli_real_escape_string($conn, $_POST['grade']);
      $start_date = mysqli_real_escape_string($conn, $_POST['start-date']);
      $end_date = mysqli_real_escape_string($conn, $_POST['end-date']);
      
      $description = mysqli_real_escape_string($conn, $_POST['description']);

      // Process age-category array
      if (isset($_POST['age-category'])) {
         $age_category = implode(',', $_POST['age-category']);
         $age_category = mysqli_real_escape_string($conn, $age_category);
      } else {
         $age_category = '';
      }

      // Construct SQL query
      $sql = "INSERT INTO tournament_schedule (name, type, grade, start_date, end_date, `age_category[]`, description) 
            VALUES ('$name', '$type', '$grade', '$start_date', '$end_date', '$age_category', '$description')";

      // Execute the query
      if ($conn->query($sql) === TRUE) {
         $_SESSION['response'] = "New tournament created successfully";
      } else {
         echo "Error: " . $sql . "<br>" . $conn->error;
      }

      // Close the connection
      $conn->close();
   }
   ?>


   <!DOCTYPE html>
   <html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- Bootstrap -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

      <!-- Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

      <!-- online fonts start -->
      <link href="https://db.onlinewebfonts.com/c/1f182a2cd2b60d5a6ac9667a629fbaae?family=PF+Din+Stencil+W01+Bold" rel="stylesheet">
      <!-- online fonts end -->

      <title>ADTC add</title>
   </head>

   <body>
      <?php
      include '../../../components/navbar/navbar.php';
      ?>

      <div class="container" style="margin-top:93px;">


         <div class="text-center mb-4">
            <h3>Add New Tournament</h3>
            <p class="text-muted">Complete the form below to add a Tournament</p>
         </div>

         <div class="container d-flex justify-content-center">
            <form id="tournament-form" action="" method="post" style="width:50vw; min-width:300px;">
               <div class="mb-3">
                  <label class="form-label" for="tournament-name-dropdown">Name of Tournament:</label>
                  <select class="form-select" name="tournament-name-dropdown" id="tournament-name-dropdown" required>
                     <option value="Fruit Juice">Fruit Juice</option>
                     <option value="Beach Tennis">Beach Tennis</option>
                     <option value="Ranking">Ranking</option>
                     <option value="Inter School">Inter School</option>
                     <option value="Year End">Year End</option>
                     <option value="Other">Other</option>
                  </select>
               </div>
               <div class="mb-3 tournament-name-text-div" id="tournament-name-text-div" style="display: none;">
                  <label class="form-label">Enter Tournament Name:</label>
                  <input type="text" class="form-control" name="tournament-name-text" id="tournament-name-text">
               </div>
               <input type="hidden" name="tournament-name" id="tournament-name">

               <script>
                  document.getElementById('tournament-name-dropdown').addEventListener('change', function() {
                     var otherInputDiv = document.getElementById('tournament-name-text-div');
                     if (this.value === 'Other') {
                        otherInputDiv.style.display = 'block';
                     } else {
                        otherInputDiv.style.display = 'none';
                     }
                  });

                  document.getElementById('tournament-form').addEventListener('submit', function(event) {
                     var dropdownValue = document.getElementById('tournament-name-dropdown').value;
                     var otherValue = document.getElementById('tournament-name-text').value.trim();
                     var combinedValue;

                     if (dropdownValue === 'Other') {
                        if (otherValue === '') {
                           event.preventDefault(); // Prevent form submission if "Other" is selected but no name is entered
                           alert('Please enter a tournament name.');
                           return;
                        }
                        combinedValue = otherValue;
                     } else {
                        combinedValue = dropdownValue;
                     }

                     document.getElementById('tournament-name').value = combinedValue;
                  });
               </script>

               <div class="mb-3">
                  <label class="form-label" for="type">Type:</label>
                  <select class="form-select" name="type" id="type" required>
                     <option value="SLTA Tennis Tour">SLTA Tennis Tour</option>
                     <option value="Club Tennis">Club Tennis</option>
                  </select>
               </div>

               <div class="mb-3">
                  <label class="form-label" for="grade">Grade:</label>
                  <select class="form-select" name="grade" id="grade" required>
                     <option value="Grade 1">Grade 1</option>
                     <option value="Grade 2">Grade 2</option>
                     <option value="Grade 3">Grade 3</option>
                     <option value="Grade 4">Grade 4</option>
                     <option value="Grade 5">Grade 5</option>
                     <option value="Not defined">Not defined</option>
                  </select>
               </div>

               <div class="mb-3">
                  <label class="form-label">Start date:</label>
                  <input type="date" class="form-control" name="start-date" placeholder="1999-06-22" required>
               </div>

               <div class="mb-3">
                  <label class="form-label">End date:</label>
                  <input type="date" class="form-control" name="end-date" placeholder="1999-06-22" required>
               </div>




               <div id="tournament-format-singles" class="mb-3">
                  <div class="text-center mb-3">
                     <h3>SINGLE MATCHES</h3>
                  </div>
                  <div class="d-flex justify-content-between">
                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Boys:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="BS">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-18" value="BS-18">
                              <label class="form-check-label" for="age-under-18">
                                 Under 18
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-16" value="BS-16">
                              <label class="form-check-label" for="age-under-16">
                                 Under 16
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-14" value="BS-14">
                              <label class="form-check-label" for="age-under-14">
                                 Under 14
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-12" value="BS-12">
                              <label class="form-check-label" for="age-under-12">
                                 Under 12
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-10" value="BS-10">
                              <label class="form-check-label" for="age-under-10">
                                 Under 10
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-8" value="BS-8">
                              <label class="form-check-label" for="age-under-8">
                                 Under 8
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-7" value="BS-7">
                              <label class="form-check-label" for="age-under-7">
                                 Under 7
                              </label>
                           </div>
                        </fieldset>
                     </div>

                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Girls:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="GS">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-18" value="GS-18">
                              <label class="form-check-label" for="age-under-18">
                                 Under 18
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-16" value="GS-16">
                              <label class="form-check-label" for="age-under-16">
                                 Under 16
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-14" value="GS-14">
                              <label class="form-check-label" for="age-under-14">
                                 Under 14
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-12" value="GS-12">
                              <label class="form-check-label" for="age-under-12">
                                 Under 12
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-10" value="GS-10">
                              <label class="form-check-label" for="age-under-10">
                                 Under 10
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-8" value="GS-8">
                              <label class="form-check-label" for="age-under-8">
                                 Under 8
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-7" value="GS-7">
                              <label class="form-check-label" for="age-under-7">
                                 Under 7
                              </label>
                           </div>
                        </fieldset>
                     </div>

                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Women's:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="WS">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>


                        </fieldset>
                     </div>

                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Men's:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="MS">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>
                        </fieldset>
                     </div>
                  </div>
               </div>

               <div id="tournament-format-doubles" class="mb-3">
                  <div class="text-center mb-3">
                     <h3>DOUBLE MATCHES</h3>
                  </div>
                  <div class="d-flex justify-content-between">
                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Boys:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="BD">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-18" value="BD-18">
                              <label class="form-check-label" for="age-under-18">
                                 Under 18
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-16" value="BD-16">
                              <label class="form-check-label" for="age-under-16">
                                 Under 16
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-14" value="BD-14">
                              <label class="form-check-label" for="age-under-14">
                                 Under 14
                              </label>
                           </div>
                        </fieldset>
                     </div>

                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Girls:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="GD">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-18" value="GD-18">
                              <label class="form-check-label" for="age-under-18">
                                 Under 18
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-16" value="GD-16">
                              <label class="form-check-label" for="age-under-16">
                                 Under 16
                              </label>
                           </div>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-14" value="GD-14">
                              <label class="form-check-label" for="age-under-14">
                                 Under 14
                              </label>
                           </div>

                        </fieldset>
                     </div>
                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Women's:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="WD">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>


                        </fieldset>
                     </div>

                     <div>
                        <fieldset class="form-group">
                           <label class="form-label" for="age-category[]">Men's:</label>
                           <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="age-category[]" id="age-open" value="MD">
                              <label class="form-check-label" for="age-open">
                                 Open
                              </label>
                           </div>
                        </fieldset>
                     </div>
                  </div>
                  <div>
                     <fieldset class="form-group">
                        <label class="form-label" for="age-category[]">Mixed:</label>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="age-category[]" id="age-adult-open" value="AX">
                           <label class="form-check-label" for="age-adult-open">
                              Adult Open
                           </label>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="age-category[]" id="age-kids-open" value="KX">
                           <label class="form-check-label" for="age-kids-open">
                              Kids Open
                           </label>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-18" value="XD-18">
                           <label class="form-check-label" for="age-under-18">
                              Under 18
                           </label>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-16" value="XD-16">
                           <label class="form-check-label" for="age-under-16">
                              Under 16
                           </label>
                        </div>
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="age-category[]" id="age-under-14" value="XD-14">
                           <label class="form-check-label" for="age-under-14">
                              Under 14
                           </label>
                        </div>

                     </fieldset>
                  </div>
               </div>



               <div class="mb-3">
                  <label class="form-label">Description:</label>
                  <input type="text" class="form-control" name="description" required>
               </div>

               <div class="mb-3">
                  <button type="submit" onclick="prepareFormData()" class="btn btn-success" name="submit">Save</button>
                  <a href="admin-dashbord.php" class="btn btn-danger ">Cancel</a>
               </div>
            </form>
         </div>
      </div>

      <!-- Bootstrap -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

   </body>

   </html>
<?php } else {
   header("Location: ../../../index.php");
} ?>