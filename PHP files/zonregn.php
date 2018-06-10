<?php
include 'i18n_setup.php';
include 'db.php';
include("check.php");
$idet = $_GET["id"];
$existerar = 0;
$sensorfinns = 0;

$sql = "SELECT * FROM zoner WHERE id = '$idet'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    $existerar = 1;
} else {
    $existerar = 0;
}

$sql = "SELECT * FROM regnsensor";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $sensorfinns = 1;
} else {
    $sensorfinns = 0;
}

$sql = "SELECT * FROM zoner WHERE id = '$idet'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $azonid = $row["id"];
        $azonnamn = $row["namn"];
    }
}
$sql = "SELECT * FROM uppgifter WHERE id = '1'";
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    while ($row = $result->fetch_assoc()) {
        $sys_name = $row["namn"];
    }
}

$currentPage = 'zonregn_'.$idet;
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $sys_name; ?> - <?=gettext('Lägg till regnsensor i zon')?> </title>
    <!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
      <!-- Meta -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="description" content="Wireless Watering System" />
      <meta name="keywords" content="arduino, water" />
      <meta name="author" content="Andreas Olsson" />
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="files/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/pages/waves/css/waves.min.css" type="text/css" media="all">
      <link rel="stylesheet" type="text/css" href="assets/icon/feather/css/feather.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/themify-icons/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/icofont/css/icofont.css">
      <link rel="stylesheet" type="text/css" href="assets/icon/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="assets/css/style.css">
      <link rel="stylesheet" type="text/css" href="assets/css/pages.css">

  </head>

  <body>
  <?php include 'include/header.php'; ?>
                  <div class="pcoded-content">
                      <div class="page-header">
                          <div class="page-block">
                              <div class="row align-items-center">
                                  <div class="col-md-8">
                                      <div class="page-header-title">
                                          <h4 class="m-b-10"><?=gettext('Addera regnsensor till zon')?></h4>
                                      </div>
                                      <ul class="breadcrumb">
                                          <li class="breadcrumb-item">
                                              <a href="index.html">
                                                  <i class="feather icon-home"></i>
                                              </a>
                                          </li>
                                          <li class="breadcrumb-item"><a href="#!"><?=gettext('Odling')?></a>
                                          </li>
                                          <li class="breadcrumb-item">
                                              <a href="#!"><?=gettext('Addera regnsensor')?></a>
                                          </li>
                                      </ul>
                                  </div>
                              </div>
                          </div>
                      </div>

                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5><?=gettext('Lägg till en ny regnsensor till odlingen')?></h5>
                                                        <span><?=gettext('Koppla en ny regnsensor till denna odling. Välj en befintlig regnsensor från listan för att koppla den.')?></span>
                                                    </div>
                                                    <?php if ($existerar == 1 && $sensorfinns == 1) {
     ?>
                                                    <div class="card-block">
                                                        <form id="main" method="post" action="functions/zonregn.php" novalidate>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"><?=gettext('Regnsensor')?></label>
                                                                <div class="col-sm-10">
                                                                    <select name="sensor" class="form-control" id="sensor">
                                                                      <?php
                                                                      $sql = "SELECT * FROM regnsensor";
     $result = $mysqli->query($sql);

     if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
             $pumpid = $row["id"];
             $pumpnr = $row["regnid"];
             $pumpnamn = $row["namn"]; ?>
                                                                            <option value="<?php echo $pumpid; ?>"><?php echo $pumpnr; ?> - <?php echo $pumpnamn; ?></option>
                                                                            <?php
         }
     } ?>
                                                                    </select>
                                                                    <span class="messages"></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2"></label>
                                                                <div class="col-sm-10">
                                                                  <input type="hidden" name="zonid" value="<?php echo $idet; ?>">
                                                                    <button type="submit" class="btn btn-primary m-b-0"><?=gettext('Skicka')?></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                  <?php
 } else {
     ?>
                                                    <div class="card-block">
                                                      <?php if ($existerar == 0) {
         ?>
                                                        <p><?=gettext('Kan inte hitta någon zon med detta id nummer')?></P>
                                                        <?php
     } ?>
                                                        <?php if ($sensorfinns == 0) {
         ?>
                                                          <p><?=gettext('Du måste lägga till regnsensorer först')?></P>
                                                    <?php
     } ?>
                                                    </div>
                                              <?php
 } ?>
                                                </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Warning Section Starts -->
<!-- Older IE warning message -->
    <!--[if lt IE 10]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jquery -->
<script type="text/javascript" src="files/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="files/jquery-ui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="files/popper.js/js/popper.min.js"></script>
<script type="text/javascript" src="files/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/pages/waves/js/waves.min.js"></script>
<script type="text/javascript" src="files/jquery-slimscroll/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="files/modernizr/js/modernizr.js"></script>
<script type="text/javascript" src="files/modernizr/js/css-scrollbars.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script type="text/javascript" src="assets/pages/form-validation/validate.js"></script>
<script type="text/javascript" src="assets/pages/form-validation/zonregn.js.php"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/vertical/vertical-layout.min.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="assets/js/script.js.php"></script>
</body>

</html>