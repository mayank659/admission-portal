<?php
error_reporting(E_ALL);
include "../connect.php";

if (isset($_GET['fetch'])) {
    header('Content-Type:application/json');
    $limit = 20;
    $offset = max(0, (int) ($_GET['offset'] ?? 0));
    $branch = $_GET['branch'] ?? '';
    $session = $_GET['session'] ?? '';

    $sql = "select * from students where (? ='' OR syllabus = ?) AND (? = '' OR academic_session = ?) order by created_at desc limit ? offset ?";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ssssii", $branch, $branch, $session, $session, $limit, $offset);
    $stmt->execute();

    $result = $stmt->get_result();

    $students = [];

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);

    $stmt->close();
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
    <link rel="stylesheet" href="maya.css">
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo"><img src="../images/logo.png" alt="Mahamaya logo"></div>
        <a href="../index.php">Mahamaya polytechnic of information technology , hathras</a>
    </div>
    <!--  Navbar end -->
    <div style="display: flex;justify-content:center;">
        <div class="dashname">All Students</div>
    </div>

    <!-- Filter -->
    <div class="filter">

        <p>Filter</p>
        <!-- Branch filter -->
        <select name="branch" class="branch" id="branch">
            <option value="">पाठ्यक्रम / Branch</option>
            <option value="CSE">Computer Science and Enginnering</option>
            <option value="IT">Information Technology</option>
            <option value="Electronic">Electronic</option>
        </select>

        <!-- Session filter -->
        <select name="session" class="branch" id="session">
            <option value="">शैक्षिक सत्र / Academic Session</option>
        </select>

        <!-- Layout filter -->
        <div class="layout">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M3.75 4.5L4.5 3.75H10.5L11.25 4.5V10.5L10.5 11.25H4.5L3.75 10.5V4.5ZM5.25 5.25V9.75H9.75V5.25H5.25ZM12.75 4.5L13.5 3.75H19.5L20.25 4.5V10.5L19.5 11.25H13.5L12.75 10.5V4.5ZM14.25 5.25V9.75H18.75V5.25H14.25ZM3.75 13.5L4.5 12.75H10.5L11.25 13.5V19.5L10.5 20.25H4.5L3.75 19.5V13.5ZM5.25 14.25V18.75H9.75V14.25H5.25ZM12.75 13.5L13.5 12.75H19.5L20.25 13.5V19.5L19.5 20.25H13.5L12.75 19.5V13.5ZM14.25 14.25V18.75H18.75V14.25H14.25Z"
                    fill="#f7f6fd" />
            </svg>
            <input type="checkbox" name="biglayout" id="check" hidden><label for="check" style="cursor:pointer">Change layout</label>
        </div>

    </div>

    <!-- tiles -->
    <div class="All_student" id="studentsContainer">

        <!-- <div class="alltile"><p>Admission ID : MMIT005</p><p> Student Name : Mayank</p><p>Branch : CSE</p><p>Admission Session : 2026-27</p></div>
        <div class="alltile"><p>Admission ID : MMIT005</p><p> Student Name : Mayank</p><p>Branch : CSE</p><p>Admission Session : 2026-27</p></div>
        <div class="alltile"><p>Admission ID : MMIT005</p><p> Student Name : Mayank</p><p>Branch : CSE</p><p>Admission Session : 2026-27</p></div>
        <div class="alltile"><p>Admission ID : MMIT005</p><p> Student Name : Mayank</p><p>Branch : CSE</p><p>Admission Session : 2026-27</p></div> -->

    </div>

    <div id="loading" style="text-align: center; padding: 20px;">
        Loading students...
    </div>

<!-- PRINT -->
    <div class="printmain">
        <div class="print">
            <button class="excel" onclick="excel()">Print In Excel</button>
            <button class="pdf" onclick="pdf()">Print In Pdf</button>
        </div>
    </div>

<!-- White screen -->
 <div class="screen"></div>

    <script src="maya.js"></script>
</body>

</html>