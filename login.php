<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$getTitle = 'Login';
if (isset($_SESSION['username'])) {
    header('location:index.php');
    die();
}
$error = [];
require_once 'init.php';
require_once $sendEm . 'SendEmail.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) { # This is to login users
        $email = mysqli_real_escape_string($mysql, filter_var($_POST['email'], FILTER_SANITIZE_STRING));
        $password = mysqli_real_escape_string($mysql, filter_var($_POST['password'], FILTER_SANITIZE_STRING));

        if (empty($email)) {
            array_push($error, 'sorry please fill you\'re email');
        }
        if (empty($password)) {
            array_push($error, 'sorry please fill you\'re password');
        }

        $emailCheck = getNumRows('email', 'users', $email); # Check Database have This Email or not
        if ($emailCheck) {
            $foundUser = login($email);
            if (password_verify($password, $foundUser['password'])) {
                $_SESSION = [];
                $_SESSION['username'] = $foundUser['name'];
                $_SESSION['email'] =  $email;
                $_SESSION['userid'] = $foundUser['id'];
                header('location:index.php');
                die();
            } else {
                array_push($error, 'sorry Email or password not correct');
            }
        } else {
            array_push($error, 'sorry Email or password not correct');
        }

    } elseif (isset($_POST['sign-up'])) { # This is to Sign up user new account
        $username = mysqli_real_escape_string($mysql, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
        $email = mysqli_real_escape_string($mysql, filter_var($_POST['email'], FILTER_SANITIZE_STRING));
        $password = mysqli_real_escape_string($mysql, filter_var($_POST['password'], FILTER_SANITIZE_STRING));
        $pass_confirm = mysqli_real_escape_string($mysql, filter_var($_POST['password_confirm'], FILTER_SANITIZE_STRING));
        $country = mysqli_real_escape_string($mysql, filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT));
        $city = mysqli_real_escape_string($mysql, filter_var($_POST['city'], FILTER_SANITIZE_NUMBER_INT));
        $phone = mysqli_real_escape_string($mysql, filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));
        $subsc = isset($_POST['subscription']) ? 1 : 0;


        if (empty($username)) {
            array_push($error, 'sorry please fill you\'re name');
        }
        if (empty($email)) {
            array_push($error, 'sorry please fill you\'re email');
        }
        if (empty($password) || strlen($password) < 5) {
            array_push($error, 'password shouldn\'t less than 5 characters');
        }
        if (empty($pass_confirm)) {
            array_push($error, 'sorry please fill you\'re confirm password');
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
        if ($password != $pass_confirm) {
            array_push($error, 'password did\'t match');
        }

        $emailCheck = getNumRows('email', 'users', $email); # This to check Email already Exists On database
        $phoneCheck = getNumRows('phone', 'users', $phone); # This to check Phone already Exists On database

        if ($emailCheck) {
            array_push($error, 'sorry Try Another Email');
        }
        if ($phoneCheck) {
            array_push($error, 'sorry Try Another Phone Number');
        }

        if (!count($error)) {
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysql->prepare("INSERT INTO users (email , password , name , phone , country , city  , create_at , subscription)
                                              VALUES (? , ? , ? , ? , ? , ? , now() , ?)");
            $stmt->bind_param('ssssiis', $dbEmail, $dbPassword, $dbName, $dbPhone, $dbCountry, $dbCity , $dbSubscription);
            $dbEmail = $email;
            $dbPassword = $pass;
            $dbName = $username;
            $dbPhone = $phone;
            $dbCountry = $country;
            $dbCity = $city;
            $dbSubscription = $subsc;
            $stmt->execute();
            if ($stmt->error) {
                echo 'please try again there\'s someting wrong';
                header('location:' . $_SERVER['HTTP_REFERER']);
                die();
            } else {
                try {
                    $token = bin2hex(random_bytes(30));
                } catch (Exception $e) {
                    echo $e;
                }
                $expire = date('Y-m-d H:i:s', strtotime('+120 min'));
                $_SESSION['userid'] = $stmt->insert_id;
                $stmt = $mysql->query("INSERT INTO `verification` (`user_id` ,  `token` , `create_at` , `expire_at`)
                                            VALUES('$_SESSION[userid]' , '$token' , now() , '$expire')");
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $sendEmail = new SendEmail();
                $sendEmail->sendVerificationEmail($email , $username , $token);
                header('location:index.php');
                die();
            }
        }
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
                <li class="active">Login-Sign up</li>
            </ol>
        </div>
    </div>
    <div class="container mt-90 login-page-form">
        <h1 class="text-center">
            <span data-class="login" class="selected-form">Login</span> | <span data-class="signup">Sign up</span>
        </h1>
            <?php include 'error.php' ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="col-lg-offset-4 col-md-offset-4 login" name="login"
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

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="password">Password : </label>
                        <input type="password" class="form-control" id="password-login" placeholder="Your Name required"
                               name="password" required>
                    </div>
                </div>
            </div>
            <button name="login" class="btn btn-primary mb-10">Login</button>
            <a href="reset-password.php">Forget You're Password Click Here</a>
        </form>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="col-lg-offset-4 col-md-offset-4 signup" name="signup"
              method="POST">


            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Name : </label>
                        <input type="text" class="form-control" id="name" placeholder="Enter you're Name" name="name"
                               required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="email">email : </label>
                        <input type="email" class="form-control" id="email-login" placeholder="Enter you're email"
                               name="email" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="password">password : </label>
                        <input type="password" class="form-control" id="password" placeholder="Enter you're password"
                               name="password" required>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="password_confirm">Password Confirm : </label>
                        <input type="password" class="form-control" id="password_confirm"
                               placeholder="Enter you're password" name="password_confirm" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="country">country</label>
                    <select class="form-control" id="country" name="country" onchange="changeCountry()">
                        <option value="0" selected>...</option>
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

                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="phone">phone : </label>
                        <input type="number" class="form-control" id="phone" placeholder="Enter you're phone"
                               name="phone" required>
                    </div>
                </div>
            </div>
            <div class="checkbox-block font-icon-checkbox mb-10">
                <input class="filterType checkbox" name="subscription" id="filter_cuisine" type="checkbox">
                <label for="filter_cuisine">Subscription to Our Newsletter ?</label>
            </div>
            <button name="sign-up" class="btn btn-primary mb-10">Sign-up</button>
        </form>
    </div>
</div>

<?php include_once $tpl . 'footer.php' ?>