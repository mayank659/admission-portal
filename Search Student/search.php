<?php

error_reporting(E_ALL);
include "../connect.php";

if(isset($_GET['fetch'])){
    header('Content-Type:application/json');
    $syllabus = isset($_GET['syllabus']) ? $_GET['syllabus'] : "";
    $session = isset($_GET['session']) ? $_GET['session'] : "";
    $name = trim(isset($_GET['name']) ? $_GET['name'] : "");
    $guardian = trim(isset($_GET['guardian']) ? $_GET['guardian'] : "");
    $category = trim(isset($_GET['category']) ? $_GET['category'] : "");
    $admissionid = trim(isset($_GET['admission_id']) ? $_GET['admission_id'] : "");

    
    $name_search = "%$name%"; 
    $guardian_search = "%$guardian%";
    $session_search = "%$session%";

    $sql = "select * from students 
    where (? = '' OR syllabus = ?)
    AND (? = '' OR academic_session LIKE ?)
    AND (? = '' OR candidate_name LIKE ?)
    AND (? = '' OR guardian_name LIKE ?)
    AND (? = '' OR category = ?)
    AND (? = '' OR admission_id = ?)
    order by created_at desc";

    $stmt = $connect->prepare($sql);

    $stmt->bind_param("ssssssssssss",
    $syllabus,$syllabus,
    $session_search,$session_search,
    $name_search,$name_search,
    $guardian_search,$guardian_search,
    $category,$category,
    $admissionid,$admissionid
    );

    $stmt->execute();

    $result=$stmt->get_result();

    $student_info = [];

    while($row = $result->fetch_assoc()){
        $student_info[] = $row;
    }
    echo json_encode($student_info);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Admission/style.css">
    <link rel="stylesheet" href="../Admission/media.css">
    <link rel="stylesheet" href="search.css">
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo"><img src="../images/logo.png" alt="Mahamaya logo"></div>
        <a href="../index.php">Mahamaya polytechnic of information technology , hathras</a>
    </div>
    <!-- Navbar end -->

    <!-- Heading -->
    <div style="display: flex;justify-content:center;">
        <div class="dashname">Search Students</div>
    </div>
    <!-- End of Heading -->

    <!--  Form -->
    <div class="formmain">

        <form>
            <div class="formdiv">
                <!-- Form Content -->
                <div class="formcontent">

                    <!-- Course -->
                    <div class="tile">

                        <div>पाठ्यक्रम / Syllabus</div>
                        <select name="syllabus" class="inputsyllabus">
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
                        <input type="text" class="input" placeholder="Enter Academic Session" name="session">

                    </div>
                    <!-- Academic Session end   -->

                    <!-- Name -->
                    <div class="tile">
                        <div> अभ्यर्थी का नाम / Candidate's Name</div>
                        <input type="text" class="input" placeholder="Enter Candidate Name" name="name">

                    </div>
                    <!-- end of name -->

                    <!-- Name of guardian -->
                    <div class="tile">
                        <div> पिता/संरक्षक का नाम / Name of Father/Guardian</div>
                        <input type="text" class="input" placeholder="Enter Father/Guardian Name" name="guardian">

                    </div>
                    <!-- End tile of guardian -->

                    <!-- Admission Id -->
                     <div class="tile">
                        <div>Admission ID</div>
                        <input type="text" class="input" placeholder="Enter Admission ID" name="admissionid">

                    </div>

                    <!-- End of Admission ID -->

                              <!-- Category tile -->
           <div class="tile">

              <div>वर्ग / Category</div>
               <select name="category" class="inputsyllabus">
                <option value=""> वर्ग चुनें / Select Category</option>
                <option value="सामान्य / Specify General">सामान्य / Specify General</option>
                <option value="पिछड़ी जाति / Backward Class">पिछड़ी जाति / Backward Class</option>
                <option value="अनुसूचित जाति / Scheduled Caste">अनुसूचित जाति / Scheduled Caste</option>
                <option value="अनुसूचित जनजाति / Scheduled Tribe">अनुसूचित जनजाति / Scheduled Tribe</option>
               </select>
            </div>

            <!-- Category tile end -->


                    <!-- Button -->
                    <div class="button">
                        <button type="submit" class="register btn">Search</button>
                        <button type="reset" class="reset btn">Reset</button>
                    </div>
                    <!-- End of button -->

        </form>

    </div>

    <!-- Form end -->

    <!-- Print Screen -->
     <div class="print">

        <div class="cross">
            <!-- Print Buttons -->
            <button class="excel" onclick="excel()">Print In Excel</button>
            <button class="pdf" onclick="pdf()">Print In Pdf</button>
            <!-- Cross Button -->
            <svg class="crossbtn" width="50px" height="50px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
    <defs>

</defs>
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
        <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-568.000000, -1087.000000)" fill="#164f76">
            <path d="M584,1117 C576.268,1117 570,1110.73 570,1103 C570,1095.27 576.268,1089 584,1089 C591.732,1089 598,1095.27 598,1103 C598,1110.73 591.732,1117 584,1117 L584,1117 Z M584,1087 C575.163,1087 568,1094.16 568,1103 C568,1111.84 575.163,1119 584,1119 C592.837,1119 600,1111.84 600,1103 C600,1094.16 592.837,1087 584,1087 L584,1087 Z M589.717,1097.28 C589.323,1096.89 588.686,1096.89 588.292,1097.28 L583.994,1101.58 L579.758,1097.34 C579.367,1096.95 578.733,1096.95 578.344,1097.34 C577.953,1097.73 577.953,1098.37 578.344,1098.76 L582.58,1102.99 L578.314,1107.26 C577.921,1107.65 577.921,1108.29 578.314,1108.69 C578.708,1109.08 579.346,1109.08 579.74,1108.69 L584.006,1104.42 L588.242,1108.66 C588.633,1109.05 589.267,1109.05 589.657,1108.66 C590.048,1108.27 590.048,1107.63 589.657,1107.24 L585.42,1103.01 L589.717,1098.71 C590.11,1098.31 590.11,1097.68 589.717,1097.28 L589.717,1097.28 Z" id="cross-circle" sketch:type="MSShapeGroup">

</path>
        </g>
    </g>
</svg>
</div>
<!-- Cross end -->

<!-- Print -->
        <div class="printmain">
            <p class="printp">Search Result</p>




        </div>

     </div>


<!-- White screen -->
 <div class="screen"></div>

    <script src="search.js"></script>
</body>
</html>