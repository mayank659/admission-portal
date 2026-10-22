<?php
error_reporting(0);

include '../connect.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

/* Student details */
  $syllabus = $_POST['syllabus'] ?? "";
  $session = $_POST['session'] ?? "";
  $name = $_POST['name'] ?? "" ;
  $father = $_POST['father'] ?? "";
  $birth = $_POST['birth'] ?? "";
  $permanentaddress = $_POST['permanentaddress'] ?? "" ;
  $mailingaddress = $_POST['mailingaddress'] ?? "" ;
  $firstphone = $_POST['firstphone'] ?? "";
  $secondphone = $_POST['secondphone'] ?? "";
  $category = $_POST['category'] ?? "";
  $other = $_POST['other'] ?? "";
  $previousschool = $_POST['previousschool'] ?? "";

  /* Education details */
  /* highschool */
  $highschoolyear = $_POST['highschoolyear'] ?? "";
  $highschoolboardname = $_POST['highschoolboardname'] ?? "";
  $highschoolmarks = $_POST['highschoolmarks'] ?? "";
  $highschoolpercentage = $_POST['highschoolpercentage'] ?? "";
  $highschoolcategory = $_POST['highschoolcategory'] ?? "";

  /* intermediate */
  $intermediateyear = $_POST['intermediateyear'] ?? "";
  $intermediateboardname = $_POST['intermediateboardname'] ?? "";
  $intermediatemarks = $_POST['intermediatemarks'] ?? "";
  $intermediatepercentage = $_POST['intermediatepercentage'] ?? "";
  $intermediatecategory = $_POST['intermediatecategory'] ?? "";

  /* other */
  $otheryear = $_POST['otheryear'] ?? "";
  $otherboardname = $_POST['otherboardname'] ?? "";
  $othermarks = $_POST['othermarks'] ?? "";
  $otherpercentage = $_POST['otherpercentage'] ?? "";
  $othercategory = $_POST['othercategory'] ?? "";

  $connect->begin_transaction();
  try{
    /* student form */
        $sql = "INSERT INTO students (
        syllabus,
        academic_session,
        candidate_name,
        guardian_name,
        dob,
        permanent_address,
        mailing_address,
        contact1,
        contact2,
        category,
        other_category,
        previous_school
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connect->prepare($sql);

    $stmt->bind_param(
        "ssssssssssss",
        $syllabus,
        $session,
        $name,
        $father,
        $birth,
        $permanentaddress,
        $mailingaddress,
        $firstphone,
        $secondphone,
        $category,
        $other,
        $previousschool
    );

    $stmt->execute();
    /* Admission id  */

     $student_id = $connect->insert_id;

     $admission_id = "MMIT" . date("Y") . str_pad($student_id, 4, "0", STR_PAD_LEFT);
     $sql_update = "UPDATE students SET admission_id = ? WHERE id = ?";
     $stmt_update = $connect->prepare($sql_update);
     $stmt_update->bind_param("si", $admission_id, $student_id);
     $stmt_update->execute();
     $stmt_update->close();

     $stmt->close();

    /* education form */

    $sql2 = "INSERT INTO student_education (
        student_id,
        highschoolyear,
        highschoolboardname,
        highschoolmarks,
        highschoolpercentage,
        highschoolcategory,
        intermediateyear,
        intermediateboardname,
        intermediatemarks,
        intermediatepercentage,
        intermediatecategory,
        otheryear,
        otherboardname,
        othermarks,
        otherpercentage,
        education_othercategory
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt2 = $connect->prepare($sql2);

    $stmt2->bind_param(
        "isssssssssssssss",
        $student_id,
        $highschoolyear,
        $highschoolboardname,
        $highschoolmarks,
        $highschoolpercentage,
        $highschoolcategory,
        $intermediateyear,
        $intermediateboardname,
        $intermediatemarks,
        $intermediatepercentage,
        $intermediatecategory,
        $otheryear,
        $otherboardname,
        $othermarks,
        $otherpercentage,
        $othercategory
    );

    $stmt2->execute();
    $stmt2->close();

    $connect->commit();
    echo "<div class='alert alert-success'>Form submitted successfully!</div>";

  }catch(Throwable $e){
        $connect->rollback();
        error_log($e->getMessage());
        echo "<div class='alert alert-danger'>Please check form again !!</div>";
  }
  $connect->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="media.css">
</head>
<body>
    <div class="main">

      <!-- Navbar -->
      <div class="navbar">
        <div class="logo"><img src="../images/logo.png" alt="Mahamaya logo"></div>
      <a href="../index.php">Mahamaya polytechnic of information technology , hathras</a>
      </div>
      <!-- Navbar end -->
       
      <!-- Admission form -->
      <form action="" method="POST">
        <div class="formdiv">
          <div class="admissionname">Application Form for Admission to the First Year (Hathras)</div>
            <!-- Form Content -->
          <div class="formcontent">
            
            <!-- Course -->
            <div class="tile">

              <div>पाठ्यक्रम / Syllabus</div>
               <select name="syllabus" class="inputsyllabus" required>
                <option value="">पाठ्यक्रम चुनें / Select Syllabus</option>
                <option value="CSE">Computer Science and Enginnering</option>
                <option value="IT">Information Technology</option>
                <option value="Electronic">Electronic</option>
               </select>
            </div>

            <!-- Course End -->

            <!-- Academic Session -->
            <div class="tile">
                <div> शैक्षिक सत्र / Academic Session</div>
                 <input type="text" class="input" placeholder="Enter Academic Session" name="session" required>
          
          </div>
          
          <!-- Academic Session End -->

            <!-- Name of Student -->
             <div class="tile">
                <div> अभ्यर्थी का नाम / Candidate's Name</div>
                 <input type="text" class="input" placeholder="Enter Candidate Name" name="name" required>
       
          </div>

          <!-- End Name of Student -->

          <!-- Name of guardian -->
           <div class="tile">
                <div> पिता/संरक्षक का नाम / Name of Father/Guardian</div>
                 <input type="text" class="input" placeholder="Enter Father/Guardian Name" name="father" required>
       
          </div>
          <!-- End tile of guardian -->

          <!-- BirthDate -->
           <div class="tile">
                <div> जन्मतिथि / Date of birth</div>
                 <input type="date" class="input" id="date" min="1980-01-01" name="birth" required>
          </div>
         <!--  End BirthDate Tile -->

          <!-- Permanent Address -->
           <div class="tile">
                <div> स्थायी पता / Permanent Address</div>
                <input type="text" class="input" placeholder="Enter Permanent Address" name="permanentaddress" required>
          </div>
          <!-- End tile of Permanent Address -->

          <!-- Mailing Address -->
           <div class="tile">
                <div> पत्र व्यवहार का पता / Mailing Address</div>
                <input type="text" class="input" placeholder="Enter Mailing Address" name="mailingaddress" required>
          </div>
          <!-- End tile of Mailing Address -->

          <!-- Contact Number Tiles -->
            <!-- First Number -->

           <div class="tile">
                <div> सम्पर्क दूरभाष सं० / Contact Phone No.(1)</div>
                <input type="tel" class="input" placeholder="Enter Contact Phone No.(1)" name="firstphone" required>
          </div>
              <!-- Second Number -->
          <div class="tile">
                <div> सम्पर्क दूरभाष सं० / Contact Phone No.(2)</div>
                <input type="tel" class="input" placeholder="Enter Contact Phone No.(2)" name="secondphone" required>
          </div>

          <!-- End of Contact Number tile -->

          <!-- Category tile -->
           <div class="tile">

              <div>वर्ग / Category</div>
               <select name="category" class="inputsyllabus" required>
                <option value=""> वर्ग चुनें / Select Category</option>
                <option value="सामान्य / Specify General">सामान्य / Specify General</option>
                <option value="पिछड़ी जाति / Backward Class">पिछड़ी जाति / Backward Class</option>
                <option value="अनुसूचित जाति / Scheduled Caste">अनुसूचित जाति / Scheduled Caste</option>
                <option value="अनुसूचित जनजाति / Scheduled Tribe">अनुसूचित जनजाति / Scheduled Tribe</option>
               </select>
            </div>

            <!-- Category tile end -->

            <!-- other Category -->
             <div class="tile">

              <div>अन्य / Other</div>
               <select name="other" class="inputsyllabus" required>
                <option value="लागू नहीं / सामान्य (None / Not Applicable)">लागू नहीं / सामान्य (None / Not Applicable)</option>
                <option value="विकलांग / Physically Handicapped">विकलांग / Physically Handicapped</option>
                <option value="सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)">सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)</option>
                <option value="राजनैतिक पीड़ित / Political Sufferer">राजनैतिक पीड़ित / Political Sufferer</option>
               </select>
            </div>
              <!-- End of Other tile -->

              <!-- Ex-school tile -->
               <div class="tile">
                <div> पूर्व में शिक्षा प्राप्त करने वाले विद्यालय का नाम / Name of the school previously attended</div>
                <input type="text" class="input" name="previousschool" placeholder="Enter name of the school previously attended" required>
          </div>
          <!-- End of Ex-school tile -->

        </div>

       <!--  TABLE -->
        <div class="table">
          <table class="realtable">
            <tr>
              <th>परीक्षा का नाम / Name of the examination</th>
              <th>उत्तीर्ण का वर्ष / Year of passing</th>
              <th>बोर्ड / संस्था का नाम / Name of the Board / Organization</th>
              <th>प्राप्तांक / Marks obtained</th>
              <th>प्रतिशत / Percentage</th>
              <th>श्रेणी / Category</th>
            </tr>
            <tr>
              <td>हाईस्कूल / High School</td>
              <td><input type="text" name="highschoolyear" placeholder="Year of passing" value="none" required></td>
              <td><input type="text" value="none" name="highschoolboardname" placeholder="बोर्ड / संस्था का नाम / Name of the Board / Organization" required></td>
              <td><input type="text" value="none" name="highschoolmarks" placeholder="प्राप्तांक / Marks obtained" required></td>
              <td><input type="text" value="none" name="highschoolpercentage" placeholder="प्रतिशत / Percentage" required></td>
              <td><input type="text" value="none" name="highschoolcategory" placeholder="श्रेणी / Category" required></td>
            </tr>
            <tr>
              <td>इण्टरमीडिएट / Intermediate</td>
              <td><input type="text" value="none" name="intermediateyear" placeholder="Year of passing" required></td>
              <td><input type="text" value="none" name="intermediateboardname" placeholder="बोर्ड / संस्था का नाम / Name of the Board / Organization" required></td>
              <td><input type="text" value="none" name="intermediatemarks" placeholder="प्राप्तांक / Marks obtained" required></td>
              <td><input type="text" value="none" name="intermediatepercentage" placeholder="प्रतिशत / Percentage" required></td>
              <td><input type="text" value="none" name="intermediatecategory" placeholder="श्रेणी / Category" required></td>
            </tr>
            <tr>
              <td>अन्य (यदि कोई हो) / Other (if any)</td>
              <td><input type="text" value="none" name="otheryear" placeholder="Year of passing" required></td>
              <td><input type="text" value="none" name="otherboardname" placeholder="बोर्ड / संस्था का नाम / Name of the Board / Organization" required></td>
              <td><input type="text" value="none" name="othermarks" placeholder="प्राप्तांक / Marks obtained" required></td>
              <td><input type="text" value="none" name="otherpercentage" placeholder="प्रतिशत / Percentage" required></td>
              <td><input type="text" value="none" name="othercategory" placeholder="श्रेणी / Category" required></td>
            </tr>
          </table>
        </div>
        <!-- END OF TABLE -->

        <!-- Button -->
         <div class="button">
           <button type="submit" class="register btn">Register</button>
           <button type="reset" class="reset btn">Reset</button>
         </div>
         <!-- End of button -->

      </form>
    </div>

    <script src="script.js"></script>
</body>
</html>