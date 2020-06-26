<?php
$error = [];
$success = false;
$getTitle = 'Confirm Account';
include_once 'init.php';
include_once $tpl . 'header.php';
$token = isset($_GET['token']) ? mysqli_real_escape_string($mysql, filter_var($_GET['token'], FILTER_SANITIZE_STRING)) : NULL; # This to get a token send an email

$result = getToken($token);

if (!empty($result)) {
    $now = date("Y-m-d H:i:s");
    if ($result['expire_at'] > $now && $result['verified'] == 0 && $result['verification'] == 0) {
        if (verifyUser($result['user_id'])) {
            $stmt = $mysql->query("UPDATE verification SET verified = 1 WHERE user_id = '$result[user_id]'");
            $success = true;
        }
    } else {
        array_push($error, 'sorry somethings wrong');
    }
} else {
    array_push($error, 'sorry there is something wrong please try another time or send verification again');
}

?>

<div class="container">
    <?php
    if (count($error)) {
        include_once 'error.php';
        die();
    }
    if ($success) { ?>
        <div class="alert alert-success alert-icon">
            <i class="fa fa-check-circle"></i>
            <h4> Thank you | successfully verify you're email</h4>
        </div>
        <?php header("Refresh:4; url=index.php");
        die();
    }
    ?>
</div>

