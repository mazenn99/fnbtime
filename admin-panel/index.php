<?php
$navbar = '';
$getTitle = 'Login';
include_once 'init.php';

session_start();
if(isset($_SESSION['admin'])) {
    header('location:dashboard.php');
    die();
}
$error = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            if($foundUser['group_id'] === 1) {
                $_SESSION = [];
                $_SESSION['admin']  = $foundUser['name'];
                $_SESSION['userid'] = $foundUser['id'];
                header('location:dashboard.php');
                die();
            } else {
                array_push($error , 'sorry you can\'t login here');
            }
        } else {
            array_push($error, 'sorry Email or password not correct');
        }
    } else {
        array_push($error, 'sorry Email or password not correct');
    }

}
?>

<div class="container">
    <div class="admin-img">
        <img src="assets/img/fnbtime-logo.png" alt="fnbtime logo">
    </div>

    <?php include_once 'error.php'?>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" style="max-width: 600px;margin: auto" method="POST">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="password" placeholder="Email ... " class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Password :</label>
            <input type="password" name="password" id="password" placeholder="Password ... " class="form-control">
        </div>

        <button class="btn btn-primary btn-block">Login!</button>
    </form>
</div>

<?php include_once $tpl . 'footer.php' ?>
