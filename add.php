<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";

if(isset($_POST['cancel'])){
    header("Location: edit.php");
    return;
}

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if(isset($_POST['add'])){
  if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {

        if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
            $_SESSION['error'] = 'All fields are required';
            header("Location: add.php");
            return;
        }
        elseif (!is_numeric($_POST['year'])) {
            $_SESSION['error'] = 'Year must be an integer';
            header("Location: add.php");
            return;
        }
        elseif (!is_numeric($_POST['mileage'])) {
            $_SESSION['error'] = 'Mileage must be an integer';
            header("Location: add.php");
            return;
        }
        elseif (strlen($_POST['make']) < 1 ) {
            $_SESSION['error'] = 'Make is required';
            header("Location: add.php");
            return;
        }

        else {
            $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES ( :make, :model, :year, :mileage)');
            $stmt->execute(array(
                    ':make' => $_POST['make'],
                    ':model' => $_POST['model'],
                    ':year' => $_POST['year'],
                    ':mileage' => $_POST['mileage'])
            );
            $_SESSION['success'] = "Record added.";
            header("Location: index.php");
            return;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Banerjee,Prodipta</title>
</head>
<body>
<div class="container">
    <h1>Tracking Automobiles for <?php echo $_SESSION['name']; ?></h1>
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
            <input type="text" name="make" size="60"/></p>
        <p>Model:
            <input type="text" name="model" size="60"/></p>

        <p>Year:
            <input type="text" name="year"/></p>
        <p>Mileage:
            <input type="text" name="mileage"/></p>
        <input type="submit" name="add" value="Add"/>
        <input type="submit" name="cancel" value="Cancel"/>
    </form>
</div>
</body>
