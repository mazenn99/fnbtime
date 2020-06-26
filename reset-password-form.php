<?php
$error = [];
$getTitle = 'Reset Password Form';
include_once 'init.php';
if (!isset($_GET['token']) || !$_GET['token']) {
    header("refresh:4;url=index.php");
    die("Sorry Access Denied");
}
$token = isset($_GET['token']) ? mysqli_real_escape_string($mysql, filter_var($_GET['token'], FILTER_SANITIZE_STRING)) : NULL; # This to get a token send an email

$result = getTokenForUpdatePassword($token);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = mysqli_real_escape_string($mysql , filter_var($_POST['password'] , FILTER_SANITIZE_STRING));
    $password_confirmation = mysqli_real_escape_string($mysql , filter_var($_POST['confirm_password'] , FILTER_SANITIZE_STRING));

    if($password != $password_confirmation)
        array_push($error , "Sorry password didn't much");

    if(empty($password) && empty($password_confirmation))
        array_push($error , "New password is require");


    if(!count($error)) {
        $hashPassword = password_hash($password , PASSWORD_DEFAULT);
        $userID = $result['user_id'];
        $mysql->query("UPDATE users SET password = '$hashPassword' WHERE id = $userID");
        $mysql->query("DELETE FROM password_reset WHERE id = $userID");
        $mysql->query("UPDATE password_reset SET verify = 1 WHERE user_id = $userID");
        $success = true;
        header("refresh:5;url=login.php");
    }
}

if (!empty($result)) {
$now = date("Y-m-d H:i:s");
if ($result['expire_at'] > $now && $result['verify'] == 0) { ?>
<body class="full-width-container">
<div id="introLoader" class="introLoading"></div>

<!-- start Container Wrapper -->
<div class="container-wrapper">
    <?php include_once $tpl . 'navbar.php'; ?>
    <div class="main-wrapper scrollspy-container">
        <div class="container pt-10 pb-50">
            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Forget Password</li>
                </ol>
            </div>
        </div>
        <?php     include_once 'error.php'
        ?>
        <div class="container mt-90 login-page-form">
            <h1 class="text-center">Please Enter You're New Password</h1>
            <?php if(isset($success) && $success == true)
                successMessage("Password Change Successfully We Direct You After 5 Second");?>
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?token=$_GET[token]" ?> " class="col-lg-offset-4 col-md-offset-4"
                  method="POST">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="password">Password : </label>
                            <input type="password" class="form-control" id="password" placeholder="Enter New Password"
                                   name="password" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="confirm_password">Password Confirm : </label>
                            <input type="password" class="form-control" id="confirm_password" placeholder="Enter You're password Again"
                                   name="confirm_password" required>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mb-20">Update Password</button>
            </form>
        </div>
    </div>
</div>
<?php } else {
    array_push($error, 'sorry Time is out please try again later');
}
} else {
    array_push($error, 'sorry there is something wrong please try another time or send verification again');
}
include_once 'error.php'

?>

<?php include_once $tpl . 'footer.php'?>

