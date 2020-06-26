<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$getTitle = 'Reset Password';
if (isset($_SESSION['username'])) {
    header('location:index.php');
    die();
}
$error = [];
require_once 'init.php';
require_once $sendEm . 'SendEmail.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = mysqli_real_escape_string($mysql , filter_var($_POST['email'] , FILTER_SANITIZE_STRING));
        if(empty($email)) {
            array_push($error ,'sorry Please Enter You\'re email');
        }
        $checkNumRows = getNumRows('email' , 'users' , $email);
        if($checkNumRows) {
            $stmt = $mysql->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->bind_param('s' , $dbEmail);
            $dbEmail = $email;
            $stmt->execute();
            if($stmt->error) {
                echo 'there is some error please try again later';
                header('refresh:10;url='.$_SERVER['HTTP_REFERER']);
            } else {
                $userID = $stmt->get_result()->fetch_assoc()['id'];

                $token = bin2hex(random_bytes(30));
                $expire_at = date('Y-m-d H:i:s' , strtotime('+120 min'));
                $insert = $mysql->query("INSERT INTO `password_reset` (`user_id`, `token`, `create_at`, `expire_at`)
                                              VALUES('$userID' , '$token' , now() , '$expire_at')");
                $forgetPassData = getUsernameAndEmail($userID);
                $mail = new SendEmail();
                $mail->sendForgetPassword($forgetPassData['name'] , $forgetPassData['email'] , $token);
                $success = true;
            }
        } else {
            array_push($error , "sorry There's no email $_POST[email] in Our Record <a href='login.php'>please Click Here sign up</a>");
        }
    }
?>
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

        <div class="container mt-90 login-page-form">
            <h1 class="text-center">Please Enter You're Email</h1>
            <?php include 'error.php' ?>
            <?php if(isset($success) && $success == true)
                successMessage("We Send An Email To $_POST[email] Please check it");?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="col-lg-offset-4 col-md-offset-4"
                  method="POST">

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="email">Email : </label>
                            <input type="email" class="form-control" id="email" placeholder="Your Name required"
                                   name="email" required>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mb-20">Send Password Reset</button>
            </form>
        </div>
    </div>

<?php include_once $tpl . 'footer.php' ?>