<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

$error = [];
$pageTitle = 'Edit Profile';
include_once 'init.php';
if (!(isset($_SESSION['username'])) && empty($_SESSION['username'])) {
    header('location:login.php');
    die();
}
$result = getUserInformation($_SESSION['userid']);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($mysql, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
    $email = mysqli_real_escape_string($mysql, filter_var($_POST['email'], FILTER_SANITIZE_STRING));
    $password = mysqli_real_escape_string($mysql, filter_var($_POST['password'], FILTER_SANITIZE_STRING));
    $country = mysqli_real_escape_string($mysql, filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT));
    $city = mysqli_real_escape_string($mysql, filter_var($_POST['city'], FILTER_SANITIZE_NUMBER_INT));
    $phone = mysqli_real_escape_string($mysql, filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));


    if (empty($username)) {
        array_push($error, 'sorry please fill you\'re name');
    }
    if (empty($email)) {
        array_push($error, 'sorry please fill you\'re email');
        array_push($error, 'sorry please fill you\'re email');
    }
    if ($country == 0) {
        array_push($error, 'sorry please fill you\'re country');
    }
    if ($city == 0) {
        array_push($error, 'sorry please fill you\'re city');
    }
    if (empty($phone)) {
        array_push($error, 'sorry please fill you\'re phone');
    }


    if (!count($error)) { # This to update users information From Admin
        $stmt = $mysql->prepare("UPDATE users set name = ? , email = ? , password = ? , phone = ? , country = ? , 
                                    city = ?  WHERE id = ?");
        $stmt->bind_param('ssssiii', $dbName, $dbEmail, $dbPassword, $dbPhone, $dbCountry, $dbCity, $dbUserID);
        $dbName = $username;
        $dbEmail = $email;
        $dbPassword = isset($_POST['password']) ? password_hash($password, PASSWORD_DEFAULT) : $result['password'];
        $dbPhone = $phone;
        $dbCountry = $country;
        $dbCity = $city;
        $dbUserID = $_SESSION['userid'];
        $stmt->execute();
        if ($stmt->error) {
            echo 'please try again there\'s someting wrong';
            header('location:' . $_SERVER['HTTP_REFERER']);
            die();
        } else { ?>
            <div class="alert alert-success alert-icon">
                <i class="fa fa-check-circle"></i>
                <h4> Updated You're Information successfully</h4>
            </div>
            <?php
            header('refresh:2; url=edit.php');
            die();
        }
    }

}
?>
    <body>
    <!-- start Container Wrapper -->
<div class="container-wrapper">

<?php include_once 'includes/template/navbar.php' ?>


    <!-- start Main Wrapper -->
    <div class="main-wrapper scrollspy-container">

    <!-- start hero-header -->
    <div class="hero hero-breadcrumb" style="background-image:url('images/hero-header/hero-image.png');">

        <div class="container">

            <h1>Edit You're <?php echo $_SESSION['username'] ?> Profile</h1>

        </div>

    </div>
    <div class="container pt-10 pb-30">

        <div class="breadcrumb-wrapper">

            <ol class="breadcrumb">

                <li><a href="index.php">Home</a></li>
                <li class="active">Edit Profile</li>

            </ol>

        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="col-lg-offset-4 col-md-offset-4" method="POST">
            <div class="width-50">
                <?php include "error.php"; ?>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Name : </label>
                        <input type="text" class="form-control" value="<?php echo $result['name'] ?>" id="name"
                               placeholder="Enter you're Name" name="name" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="email">email : </label>
                        <input type="email" class="form-control" value="<?php echo $result['email'] ?>" id="email-login"
                               placeholder="Enter you're email" name="email" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="password">password : </label>
                        <input type="password" class="form-control" id="password"
                               placeholder="Leave it if you don't want to change it" name="password">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="country">country</label>
                    <select class="form-control" id="country" name="country" onchange="changeCountry()">
                        <option value="<?php echo $result['country'] ?>"
                                selected><?php echo $result['couName'] ?></option>
                        <?php $stmt = $mysql->query("SELECT * FROM country ORDER BY name ASC");
                        while ($row = $stmt->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php }
                        $stmt->close(); ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="city">city</label>
                    <select class="form-control" id="city" name="city">
                        <option value="<?php echo $result['city'] ?>"><?php echo $result['citName'] ?></option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="phone">phone : </label>
                        <input type="number" class="form-control" value="<?php echo $result['phone'] ?>" id="phone"
                               placeholder="Enter you're phone" name="phone" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <button name="sign-up" class="btn btn-primary btn-block">Update!</button>
                    </div>
                </div>
            </div>
        </form>
    </div>



<?php include_once $tpl . 'footer.php'; ?>