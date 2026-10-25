<?php
error_reporting(E_ALL);

include "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_document') {
    header('Content-Type: application/json;charset=utf-8');

    $student = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
    $document = filter_input(INPUT_POST, 'document_id', FILTER_VALIDATE_INT);
    $accepted = filter_input(INPUT_POST, 'accepted', FILTER_VALIDATE_INT);

    if (!$student || !$document || !in_array($accepted, [0, 1], true)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid document selection.']);
        exit;
    }

    $studentStmt = $connect->prepare("SELECT id FROM students WHERE id = ?");
    $studentStmt->bind_param("i", $student);
    $studentStmt->execute();
    $studentExists = $studentStmt->get_result()->num_rows > 0;
    $studentStmt->close();

    $documentStmt = $connect->prepare("SELECT sno FROM documents WHERE sno = ? AND is_active = 1");
    $documentStmt->bind_param("i", $document);
    $documentStmt->execute();
    $documentExists = $documentStmt->get_result()->num_rows > 0;
    $documentStmt->close();

    if (!$studentExists || !$documentExists) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Student or active document not found.']);
        exit;
    }

    if ($accepted === 1) {
        $checkStmt = $connect->prepare(
            "SELECT id FROM student_documents WHERE student_id = ? AND document_id = ? LIMIT 1"
        );
        $checkStmt->bind_param("ii", $student, $document);
        $checkStmt->execute();
        $alreadyAccepted = $checkStmt->get_result()->num_rows > 0;
        $checkStmt->close();

        if (!$alreadyAccepted) {
            $saveStmt = $connect->prepare(
                "INSERT INTO student_documents (student_id, document_id) VALUES (?, ?)"
            );
            $saveStmt->bind_param("ii", $student, $document);
            $saveStmt->execute();
            $saveStmt->close();
        }
    } else {
        $removeStmt = $connect->prepare(
            "DELETE FROM student_documents WHERE student_id = ? AND document_id = ?"
        );
        $removeStmt->bind_param("ii", $student, $document);
        $removeStmt->execute();
        $removeStmt->close();
    }

    echo json_encode(['status' => 'done', 'accepted' => $accepted]);
    $connect->close();
    exit;
}

if (isset($_GET['user']) && isset($_GET['id'])) {
    header('Content-Type:application/json;charset=utf-8');
    $student = $_GET['id'];
    $sql = "SELECT
                d.sno AS document_id,
                d.name AS document_name,
                CASE
                    WHEN sd.id IS NULL THEN 0
                    ELSE 1
                END AS accepted
            FROM documents d
            LEFT JOIN student_documents sd
                ON sd.document_id = d.sno
                AND sd.student_id = ?
            WHERE d.is_active = 1
            ORDER BY d.name ASC";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $student);
    $stmt->execute();

    $document = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    echo json_encode($document);
    exit;


}

if (isset($_GET['id'])) {
    $sno = $_GET['id'];
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $session = trim($_POST['session'] ?? '');
    $admission = trim($_POST['admission'] ?? '');
    $guardian = trim($_POST['guardian'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $permanent_address = trim($_POST['permanent_address'] ?? '');
    $mailing_address = trim($_POST['mailing_address'] ?? '');
    $phone1 = trim($_POST['phone1'] ?? '');
    $phone2 = trim($_POST['phone2'] ?? '');
    $category = $_POST['category'] ?? '';
    $other = $_POST['other'] ?? '';
    $school = trim($_POST['school'] ?? '');

    $highschoolyear = trim($_POST['highschoolyear'] ?? '');
    $highschoolboardname = trim($_POST['highschoolboardname'] ?? '');
    $highschoolmarks = trim($_POST['highschoolmarks'] ?? '');
    $highschoolpercentage = trim($_POST['highschoolpercentage'] ?? '');
    $highschoolcategory = trim($_POST['highschoolcategory'] ?? '');

    $intermediateyear = trim($_POST['intermediateyear'] ?? '');
    $intermediateboardname = trim($_POST['intermediateboardname'] ?? '');
    $intermediatemarks = trim($_POST['intermediatemarks'] ?? '');
    $intermediatepercentage = trim($_POST['intermediatepercentage'] ?? '');
    $intermediatecategory = trim($_POST['intermediatecategory'] ?? '');

    $otheryear = trim($_POST['otheryear'] ?? '');
    $otherboardname = trim($_POST['otherboardname'] ?? '');
    $othermarks = trim($_POST['othermarks'] ?? '');
    $otherpercentage = trim($_POST['otherpercentage'] ?? '');
    $education_othercategory = trim($_POST['othercategory'] ?? '');

    try {
        $connect->begin_transaction();

        /* Update students table */
        $sql = "UPDATE students SET
                    candidate_name = ?,
                    syllabus = ?,
                    academic_session = ?,
                    admission_id = ?,
                    guardian_name = ?,
                    dob = ?,
                    permanent_address = ?,
                    mailing_address = ?,
                    contact1 = ?,
                    contact2 = ?,
                    category = ?,
                    other_category = ?,
                    previous_school = ?
                WHERE id = ?";

        $stmt = $connect->prepare($sql);

        $stmt->bind_param(
            "sssssssssssssi",
            $name,
            $branch,
            $session,
            $admission,
            $guardian,
            $dob,
            $permanent_address,
            $mailing_address,
            $phone1,
            $phone2,
            $category,
            $other,
            $school,
            $sno
        );

        $stmt->execute();
        $stmt->close();

        /* Update student_education table */
        $sql = "UPDATE student_education SET
                    highschoolyear = ?,
                    highschoolboardname = ?,
                    highschoolmarks = ?,
                    highschoolpercentage = ?,
                    highschoolcategory = ?,
                    intermediateyear = ?,
                    intermediateboardname = ?,
                    intermediatemarks = ?,
                    intermediatepercentage = ?,
                    intermediatecategory = ?,
                    otheryear = ?,
                    otherboardname = ?,
                    othermarks = ?,
                    otherpercentage = ?,
                    education_othercategory = ?
                WHERE student_id = ?";

        $stmt = $connect->prepare($sql);

        $stmt->bind_param(
            "sssssssssssssssi",
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
            $education_othercategory,
            $sno
        );

        $stmt->execute();
        $stmt->close();

        $connect->commit();

        echo "<script>alert('Updated !!')</script>";
        header("Location: detail.php?id=" . $sno . "&updated=1");
        exit;

    } catch (Throwable $e) {
        $connect->rollback();
        error_log($e->getMessage());
        $message = "Update failed. Please check the database columns and try again.";
        echo "<script>alert('Something went wrong')</script>";
    }
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
                    <input type="tel" name="phone1" value="<?= $student['contact1'] ?? '' ?>" minlength="10"
                        maxlength="10">
                </div>
                <!--end of phone1 -->

                <!-- phone2 -->
                <div class="tile_detail">
                    <p>Contact Phone No.(2) : </p>
                    <input type="tel" name="phone2" value="<?= $student['contact2'] ?? '' ?>" minlength="10"
                        maxlength="10">
                </div>
                <!--end of phone2 -->

                <!-- category -->
                <div class="tile_detail address">
                    <p>Category : </p>
                    <select name="category">
                        <option value="" <?= ($student['category'] ?? '') === '' ? 'selected' : '' ?>> वर्ग चुनें / Select
                            Category</option>
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
                        <option value="लागू नहीं / सामान्य (None / Not Applicable)" <?= ($student['other_category'] ?? '') === 'लागू नहीं / सामान्य (None / Not Applicable)' ? 'selected' : '' ?>>लागू नहीं / सामान्य
                            (None / Not
                            Applicable)</option>
                        <option value="विकलांग / Physically Handicapped" <?= ($student['other_category'] ?? '') === 'विकलांग / Physically Handicapped' ? 'selected' : '' ?>>विकलांग / Physically
                            Handicapped</option>
                        <option value="सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)"
                            <?= ($student['other_category'] ?? '') === 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)' ? 'selected' : '' ?>>सैनिक आश्रित (Freedom
                            Fighter / Armed Forces Dependent)</option>
                        <option value="राजनैतिक पीड़ित / Political Sufferer" <?= ($student['other_category'] ?? '') === 'राजनैतिक पीड़ित / Political Sufferer' ? 'selected' : '' ?>>राजनैतिक पीड़ित /
                            Political Sufferer
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
                        <td><input type="text" name="highschoolyear" placeholder="Year of passing"
                                value="<?= $education['highschoolyear'] ?>"></td>
                        <td><input type="text" name="highschoolboardname"
                                placeholder="बोर्ड / संस्था का नाम / Name of the Board / Organization"
                                value="<?= $education['highschoolboardname'] ?>"></td>
                        <td><input type="text" name="highschoolmarks" placeholder="प्राप्तांक / Marks obtained"
                                value="<?= $education['highschoolmarks'] ?>"></td>
                        <td><input type="text" name="highschoolpercentage" placeholder="प्रतिशत / Percentage"
                                value="<?= $education['highschoolpercentage'] ?>"></td>
                        <td><input type="text" name="highschoolcategory" placeholder="श्रेणी / Category"
                                value="<?= $education['highschoolcategory'] ?>"></td>
                    </tr>
                    <tr>
                        <td>इण्टरमीडिएट / Intermediate</td>
                        <td><input type="text" name="intermediateyear" placeholder="Year of passing"
                                value="<?= $education['intermediateyear'] ?>"></td>
                        <td><input type="text" name="intermediateboardname"
                                placeholder="बोर्ड / संस्था का नाम / Name of the Board / Organization"
                                value="<?= $education['intermediateboardname'] ?>"></td>
                        <td><input type="text" name="intermediatemarks" placeholder="प्राप्तांक / Marks obtained"
                                value="<?= $education['intermediatemarks'] ?>"></td>
                        <td><input type="text" name="intermediatepercentage" placeholder="प्रतिशत / Percentage"
                                value="<?= $education['intermediatepercentage'] ?>"></td>
                        <td><input type="text" name="intermediatecategory" placeholder="श्रेणी / Category"
                                value="<?= $education['intermediatecategory'] ?>"></td>
                    </tr>
                    <tr>
                        <td>अन्य (यदि कोई हो) / Other (if any)</td>
                        <td><input type="text" name="otheryear" placeholder="Year of passing"
                                value="<?= $education['otheryear'] ?>"></td>
                        <td><input type="text" name="otherboardname"
                                placeholder="बोर्ड / संस्था का नाम / Name of the Board / Organization"
                                value="<?= $education['otherboardname'] ?>"></td>
                        <td><input type="text" name="othermarks" placeholder="प्राप्तांक / Marks obtained"
                                value="<?= $education['othermarks'] ?>"></td>
                        <td><input type="text" name="otherpercentage" placeholder="प्रतिशत / Percentage"
                                value="<?= $education['otherpercentage'] ?>"></td>
                        <td><input type="text" name="othercategory" placeholder="श्रेणी / Category"
                                value="<?= $education['education_othercategory'] ?>"></td>
                    </tr>
                </table>
            </div>
            <!-- table end -->
            <div class="button">
                <button type="submit">Update</button>
            </div>

        </div>

    </form>
    <!-- form end -->

    <!--document heading -->
    <div style="display: flex;justify-content:center;">
        <div class="dashname" style="width: 90%; margin-bottom:20px;">Documents</div>
    </div>
    <!-- End of document heading -->

    <!-- Field -->
    <div class="field">



    </div>
    <!-- Field End -->

    <div class="printmain">
        <div class="print">
            <button class="pdf" onclick="window.print()">Print In Pdf</button>
        </div>
    </div>

    <script src="details.js"></script>
</body>

</html>