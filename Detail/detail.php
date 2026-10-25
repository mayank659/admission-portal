<?php
error_reporting(E_ALL);

include "../connect.php";

if (!isset($_GET['id'])) {
    exit("Invalid student ID.");
}

$sno = (int) $_GET['id'];

/* Student details fetch */
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $sno);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    exit("Student not found.");
}

/* Education details fetch */
$sql = "SELECT * FROM student_education WHERE student_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $sno);
$stmt->execute();

$result = $stmt->get_result();
$education = $result->fetch_assoc();

if (!$education) {
    $education = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Documents/document.css">
    <link rel="stylesheet" href="detail.css">
    <link rel="stylesheet" href="../Admission/media.css">
</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo"><img src="../images/logo.png" alt="Mahamaya logo"></div>
        <a href="../index.php">Mahamaya polytechnic of information technology , hathras</a>
    </div>
    <!-- Navbar end -->

    <form action="" method="post" id="form">

        <div class="formmain">
            <!-- heading -->
            <div style="display: flex;justify-content:center;">
                <div class="dashname">Student Details</div>
            </div>
            <!-- End heading -->


            <div class="tiles">

                <!-- Name -->
                <div class="tile_detail">
                    <p>Name : </p>
                    <input type="text" name="name" value="<?= $student['candidate_name'] ?? '' ?>">
                </div>
                <!-- End of Name -->


                <!-- Branch -->
                <div class="tile_detail">
                    <p>Branch : </p>
                    <input type="text" name="branch" value="<?= $student['syllabus'] ?? '' ?>">
                </div>
                <!-- End of Branch -->


                <!-- Session -->
                <div class="tile_detail">
                    <p>Academic Session : </p>
                    <input type="text" name="session" value="<?= $student['academic_session'] ?? '' ?>">
                </div>
                <!-- End of Session -->

                <!-- Admission ID -->
                <div class="tile_detail">
                    <p>Admission Id : </p>
                    <input type="text" name="admission" value="<?= $student['admission_id'] ?? '' ?>">
                </div>
                <!-- End of Admission ID -->

                <!-- guardian -->
                <div class="tile_detail">
                    <p>Guardian : </p>
                    <input type="text" name="guardian" value="<?= $student['guardian_name'] ?? '' ?>">
                </div>
                <!-- guardian -->

                <!-- date of birth -->
                <div class="tile_detail">
                    <p>Date of Birth : </p>
                    <input type="text" name="dob" value="<?= $student['dob'] ?? '' ?>">
                </div>
                <!--end of date of birth -->

                <!-- Permanent Address -->
                <div class="tile_detail address">
                    <p>Permanent Address : </p>
                    <input type="text" name="permanent_address" value="<?= $student['permanent_address'] ?? '' ?>">
                </div>
                <!-- end of Permanent address -->

                <!-- Mailing Address -->
                <div class="tile_detail address">
                    <p>Mailing Address : </p>
                    <input type="text" name="mailing_address" value="<?= $student['mailing_address'] ?? '' ?>">
                </div>
                <!-- end of Mailing address -->

                <!-- phone1 -->
                <div class="tile_detail">
                    <p>Contact Phone No.(1) : </p>
                    <input type="tel" name="phone1" value="<?= $student['contact1'] ?? '' ?>" min="10" max="10">
                </div>
                <!--end of phone1 -->

                <!-- phone2 -->
                <div class="tile_detail">
                    <p>Contact Phone No.(2) : </p>
                    <input type="tel" name="phone2" value="<?= $student['contact2'] ?? '' ?>" min="10" max="10">
                </div>
                <!--end of phone2 -->

                <!-- category -->
                <div class="tile_detail address">
                    <p>Category : </p>
                    <select name="category">
                        <option value="" <?= ($student['category'] ?? '') === '' ? 'selected' : '' ?>> वर्ग चुनें / Select Category</option>
                        <option value="सामान्य / Specify General" <?= ($student['category'] ?? '') === 'सामान्य / Specify General' ? 'selected' : '' ?>>सामान्य / Specify General</option>
                        <option value="पिछड़ी जाति / Backward Class" <?= ($student['category'] ?? '') === 'पिछड़ी जाति / Backward Class' ? 'selected' : '' ?>>पिछड़ी जाति / Backward Class</option>
                        <option value="अनुसूचित जाति / Scheduled Caste" <?= ($student['category'] ?? '') === 'अनुसूचित जाति / Scheduled Caste' ? 'selected' : '' ?>>अनुसूचित जाति / Scheduled Caste</option>
                        <option value="अनुसूचित जनजाति / Scheduled Tribe" <?= ($student['category'] ?? '') === 'अनुसूचित जनजाति / Scheduled Tribe' ? 'selected' : '' ?>>अनुसूचित जनजाति / Scheduled Tribe</option>
                    </select>
                </div>
                <!--end of category -->

                <!-- other Category -->
                <div class="tile_detail address">

                    <p>Other</p>
                    <select name="other">
                        <option value="लागू नहीं / सामान्य (None / Not Applicable)" <?= ($student['other_category'] ?? '') === 'लागू नहीं / सामान्य (None / Not Applicable)' ? 'selected' : '' ?>>लागू नहीं / सामान्य (None / Not
                            Applicable)</option>
                        <option value="विकलांग / Physically Handicapped" <?= ($student['other_category'] ?? '') === 'विकलांग / Physically Handicapped' ? 'selected' : '' ?>>विकलांग / Physically Handicapped</option>
                        <option value="सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)" <?= ($student['other_category'] ?? '') === 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)' ? 'selected' : '' ?>>सैनिक आश्रित (Freedom
                            Fighter / Armed Forces Dependent)</option>
                        <option value="राजनैतिक पीड़ित / Political Sufferer"  <?= ($student['other_category'] ?? '') === 'राजनैतिक पीड़ित / Political Sufferer' ? 'selected' : '' ?>>राजनैतिक पीड़ित / Political Sufferer
                        </option>
                    </select>
                </div>
                <!-- End of Other tile -->


                <!-- phone2 -->
                <div class="tile_detail address">
                    <p>Previous School : </p>
                    <input type="tel" name="school" value="<?= $student['previous_school'] ?>">
                </div>
                <!--end of phone2 -->

            </div>


            <!-- Table -->
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
             <!-- table end -->
            <div class="button">
                <button type="submit">Update</button>
            </div>

        </div>

    </form>

    <script src="details.js"></script>
</body>

</html>