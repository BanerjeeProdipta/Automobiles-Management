<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if(isset($_POST['cancel'])){
    header("Location: edit.php");
    return;
}

if(isset($_POST['save']))
{
  if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year'])
        && isset($_POST['mileage']) && isset($_POST['autos_id'])) {
        if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1
        || strlen($_POST['mileage']) < 1) {
            $_SESSION['error'] = 'All fields are required';
            header("Location: edit.php?autos_id=".$_POST['autos_id']);
            return;
        }
        if (strlen($_POST['make']) < 1) {
         $_SESSION['error'] = 'Make is required';
         header("Location: edit.php?autos_id=".$_POST['autos_id']);
         return;
       }
        if (!is_numeric($_POST['year'])) {
            $_SESSION['error'] = 'Year must be numeric';
            header("Location: edit.php?autos_id=".$_POST['autos_id']);
            return;
        }
        if (!is_numeric($_POST['mileage'])) {
            $_SESSION['error'] = 'Mileage must be numeric';
            header("Location: edit.php?autos_id=".$_POST['autos_id']);
            return;
        }

  $sql = "UPDATE autos SET make = :make,
          model = :model, year = :year, mileage = :mileage
          WHERE autos_id = :autos_id";
  //echo $_POST['make'].$_POST['model'].$_POST['year'].$_POST['mileage'].$_POST['autos_id'];

  $stmt = $pdo->prepare($sql);

  $stmt->execute(array(
          ':make' => $_POST['make'],
          ':model' => $_POST['model'],
          ':year' => $_POST['year'],
          ':mileage' => $_POST['mileage'],
          ':autos_id' => $_POST['autos_id'])
  );
  $_SESSION['success'] = 'Record updated';
  header('Location: index.php');
  return;
  }
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['autos_id'])) {

    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}
$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header('Location: index.php');
    return;
}
$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Banerjee,Prodipta</title>
<?php require_once "bootstrap.php"; ?>
</head>
<div class = "container">
<h2>Editing Automobile</h2>
<?php
if ( isset($_SESSION['error']) ) {
echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
unset($_SESSION['error']);
}
elseif( isset($_SESSION['success']) ) {
echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
unset($_SESSION['success']);
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $make ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $model ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $year ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mileage ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p>
<input type="submit" name="save" value="Save"/>
<input type="submit" name="cancel" value="Cancel"/></a>
</p>
</form>
</div>
</html>
