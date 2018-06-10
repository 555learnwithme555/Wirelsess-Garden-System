<?php
include '../db.php';

setlocale(LC_ALL, 'sv_SE', 'sv_SE.UTF-8');
date_default_timezone_set('Europe/Stockholm');
setlocale(LC_TIME, 'sv_SE', 'sv_SE.UTF-8');

$sql = "SELECT * FROM sensorer";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Hämta sensor nr för varje sensor
    while ($row = $result->fetch_assoc()) {
        $sensornr = $row["sensornr"];
        //Kopiera sedan över ett värde till dag statistik
        $sql2 = "SELECT * FROM jordfukt WHERE DATE(datum) = DATE(NOW()) AND sensor = '$sensornr' ORDER BY RAND() LIMIT 1";
        $result2 = $mysqli->query($sql2);

        if ($result2->num_rows == 1) {
            // Räkna ihop antalet reserverade stolar
            while ($row2 = $result2->fetch_assoc()) {
                $sensor = $row2["sensor"];
                $fukt = $row2["fukt"];
                $jordtemp = $row2["jordtemp"];
                $volt = $row2["volt"];
                $datum = $row2["datum"];
                $tid = $row2["tid"];

                if ($insert_stmt = $mysqli->prepare("INSERT INTO jordfuktdag (sensor, fukt, jordtemp, volt, datum, tid) VALUES (?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('ssssss', $sensor, $fukt, $jordtemp, $volt, $datum, $tid);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        exit();
                    }
                }
            }
        }
    }
}

$sql = "SELECT * FROM regnsensor";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Hämta sensor nr för varje sensor
    while ($row = $result->fetch_assoc()) {
        $regnnr = $row["regnid"];
        //Kopiera sedan över ett värde till dag statistik
        $sql2 = "SELECT * FROM regndata WHERE DATE(datum) = DATE(NOW()) AND regnnr = '$regnnr' ORDER BY RAND() LIMIT 1";
        $result2 = $mysqli->query($sql2);

        if ($result2->num_rows == 1) {
            // Räkna ihop antalet reserverade stolar
            while ($row2 = $result2->fetch_assoc()) {
                $regnsensor = $row2["regnnr"];
                $regnar = $row2["regnar"];
                $regnfukt = $row2["regnfukt"];
                $rvolt = $row2["volt"];
                $datum = $row2["datum"];
                $tid = $row2["tid"];

                if ($insert_stmt = $mysqli->prepare("INSERT INTO regndatadag (regnnr, regnar, regnfukt, volt, datum, tid) VALUES (?, ?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param('ssssss', $regnsensor, $regnar, $regnfukt, $rvolt, $datum, $tid);
                    // Execute the prepared query.
                    if (! $insert_stmt->execute()) {
                        exit();
                    }
                }
            }
        }
    }
}


$sql = "SELECT * FROM luftfukt WHERE DATE(datum) = DATE(NOW()) ORDER BY RAND() LIMIT 1";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Räkna ihop antalet reserverade stolar
    while ($row = $result->fetch_assoc()) {
        $temp = $row["temp"];
        $fukt = $row["fukt"];
        $heat = $row["heat"];
        $datum = $row["datum"];
        $tid = $row["tid"];

        if ($insert_stmt = $mysqli->prepare("INSERT INTO luftfuktdag (temp, fukt, heat, datum, tid) VALUES (?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param('sssss', $temp, $fukt, $heat, $datum, $tid);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                exit();
            }
        }
    }
}
