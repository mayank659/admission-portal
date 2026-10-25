<?php
error_reporting(E_ALL);
include "../connect.php";

if(isset($_GET['fetch'])){
    header('Content-Type:application/json');
    $sql = "select * from documents order by is_active desc,created_at desc";
    $stmt = $connect->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $data=[];
    while($row = $result->fetch_assoc()){
            $data[] = $row;
    }
    echo json_encode($data);
    $stmt->close();
    $connect->close();
    exit;
}
elseif(isset($_GET['document'])){
    $document = $_GET['document'];
    if(trim($document) != ''){
        $sql = "insert into documents(name) values (?)";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s",$document);
        if($stmt->execute()){
            echo json_encode(['status'=>'done']);
        }
        $stmt->close();
    }
    $connect->close();
    exit;
}
elseif(isset($_GET['newname'],$_GET['sno'])){
    $newname = $_GET['newname'];
    $sno = $_GET['sno'];
    if($newname != ""){
        $sql = "update documents set name=? where sno=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("si",$newname,$sno);
        if($stmt->execute()){
            echo json_encode(["status"=>"done"]);
            $stmt->close();
            $connect->close();
            exit;
        }
    }
}
elseif(isset($_GET['action'],$_GET['sno'])){
    $action = $_GET['action'];
    $sno = $_GET['sno'];
    if($action == 'deactivate'){
        $is_active = 0;
    }
    elseif($action == 'activate'){
        $is_active = 1;
    }
    $sql = "update documents set is_active=? where sno=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ii",$is_active,$sno);
    if($stmt->execute()){
        echo json_encode(['status ' => 'done']);
        $stmt->close();
        $connect->close();
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="document.css">
    <link rel="stylesheet" href="../Admission/media.css">
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
        <div class="dashname">Documents</div>
    </div>
    <!-- Heading End -->


    <!-- INPUT -->
    <form class="form">
        <div class="inputmain">
            <input type="text" name="document" id="document" placeholder="Enter Document Name" required>
            <button type="submit">Add Document</button>
        </div>
    </form>

    <!-- END INPUT -->


    <!-- Field -->
    <div class="field">

        <!--  <div class="box">
            <input type="text" value="Document Name"></input>
            <button class="update">Update</button>
            <button class="delete">Delete</button>
        </div> -->


    </div>
    <!-- Field End -->
    <script src="document.js"></script>
</body>

</html>