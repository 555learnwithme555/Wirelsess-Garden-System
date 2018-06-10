<?php
include '../db.php';
$idnr = $_POST['idnr'];
$namn = $_POST['namn'];
$vilt = $_POST['pump'];

$existerar = 1;


if (isset($idnr, $namn)) {
    $sql = "SELECT * FROM vilt WHERE viltnr = '$idnr'";
    $result = $mysqli->query($sql);

    if ($result->num_rows == 1) {
        $existerar = 1;
        $echodata = array('error' => 'true', 'errorcode' => '0');
        echo json_encode($echodata);
    } else {
        $existerar = 0;
    }

    if ($existerar == 0) {
        if ($insert_stmt = $mysqli->prepare("INSERT INTO vilt (viltnr, namn, typ, aktiverad, manuellt, volt, viltsiren) VALUES (?, ?, '0', '0', '0', '0', ?)")) {
            $insert_stmt->bind_param('ss', $idnr, $namn, $vilt);
            if (! $insert_stmt->execute()) {
                $echodata = array('error' => 'true', 'errorcode' => '1');
                echo json_encode($echodata);
                exit();
            }
            $echodata = array('error' => 'false', 'errorcode' => '0');
            echo json_encode($echodata);
        }
    }
} else {
    $echodata = array('error' => 'true', 'errorcode' => '2');
    echo json_encode($echodata);
}
