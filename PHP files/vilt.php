<?php
//This is the file for animal sensor when registrate a movement
include 'db.php';
$idag = date("Y-m-d");
$nu = date("H:i:s");
$datum = date("Y-m-d", strtotime($idag));
$tid = date("H:i:s", strtotime($nu));

$sensor = $_GET['sensor'];
$viltreg = $_GET['vilt'];
$viltvolt = $_GET['volt'];

$raknaut = $viltvolt / 1000;
$viltvolt = round($raknaut, 2);


$sql = "SELECT * FROM vilt WHERE viltnr = '$sensor'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $typ = $row["typ"];
        $aktiverad = $row["aktiverad"];
        $manuellt = $row["manuellt"];
        $volt = $row["volt"];
        if ($typ == 0) {
            $sensorOk = 1;
        } else {
            $sensorOk = 0;
        }
    }
} else {
    $sensorOk = 0;
}

if (isset($viltvolt)) {
    if ($sensorOk == 1) {
        if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET volt = ? WHERE viltnr = ?")) {
            $insert_stmt->bind_param('ss', $viltvolt, $sensor);
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    }
}

if (isset($viltreg)) {
    if ($sensorOk == 1) {
        if ($aktiverad == 1) {
            if ($viltreg == 1) {
                if ($insert_stmt = $mysqli->prepare("INSERT INTO viltstatus (datum, tid, viltnr) VALUES (?, ?, ?)")) {
                    $insert_stmt->bind_param('sss', $datum, $tid, $sensor);
                    if (! $insert_stmt->execute()) {
                        exit();
                    }
                }
                if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET manuellt = '1' WHERE viltnr = ?")) {
                    $insert_stmt->bind_param('s', $sensor);
                    if (! $insert_stmt->execute()) {
                        exit();
                    }
                }
            }
        }
    }
}

$pumpar = array();
$status = array();
$vilt = array();
$aktiverad = array();
$manuell = array();
$siren = array();

$sql = "SELECT * FROM pumpar";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pumpar[] = $row["pumpnr"];
        $status[] = $row["aktiverad"];
    }
}

$sql = "SELECT * FROM vilt";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $snr = $row["viltsiren"];
        $vilt[] = $row["viltnr"];
        $aktiverad[] = $row["aktiverad"];
        $manuell[] = $row["manuellt"];
        $sql2 = "SELECT * FROM sirenvilt WHERE id = '$snr'";
        $result2 = $mysqli->query($sql2);

        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                $siren[] = $row2["sirennr"];
            }
        }
    }
}

$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $torrjord = $row["torrjord"];
        $blotjord = $row["blotjord"];
        $lastemp = $row["lastemp"];
        $omstart = $row["omstart"];
        $sirentid = $row["sirentid"];
        $torregn = $row["torrregn"];
        $blotregn = $row["blotregn"];
    }
}

if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET lastemp = '0' WHERE id = '1'")) {
    if (! $insert_stmt->execute()) {
        exit();
    }
}

$json = json_encode($pumpar, JSON_NUMERIC_CHECK);
$json1 = json_encode($status, JSON_NUMERIC_CHECK);
$json2 = json_encode($vilt, JSON_NUMERIC_CHECK);
$json3 = json_encode($aktiverad, JSON_NUMERIC_CHECK);
$json4 = json_encode($manuell, JSON_NUMERIC_CHECK);
$json5 = json_encode($siren, JSON_NUMERIC_CHECK);

if ($insert_stmt = $mysqli->prepare("UPDATE vilt SET manuellt = '0' WHERE manuellt = '1'")) {
    if (! $insert_stmt->execute()) {
        exit();
    }
}

if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET omstart = '0' WHERE omstart = '1'")) {
    if (! $insert_stmt->execute()) {
        exit();
    }
}

if ($insert_stmt = $mysqli->prepare("UPDATE uppgifter SET datum = ?, tid = ? WHERE id = '1'")) {
    $insert_stmt->bind_param('ss', $datum, $tid);
    if (! $insert_stmt->execute()) {
        exit();
    }
}

echo '{ "pumpar": ' .$json. ', "status": ' .$json1.', "vilt": ' .$json2.', "vstatus": ' .$json3.', "manuell": ' .$json4.', "siren": ' .$json5.', "torrjord": "'.$torrjord.'", "blotjord": "'.$blotjord.'", "lastemp": "'.$lastemp.'", "omstart": "'.$omstart.'", "sirentid": "'.$sirentid.'", "torregn": "'.$torregn.'", "blotregn": "'.$blotregn.'" }';
