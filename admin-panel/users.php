<?php 
session_start();
$getTitle = 'Users';
if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    die('Access Denied');
}
include_once 'init.php';
$error = [];
$allowDo = ['Delete', 'Add', 'Manage', 'Edit', 'Insert', 'Update'];
$do = isset($_GET['do']) && in_array($_GET['do'], $allowDo) ? $_GET['do'] : 'Manage';
# This is to make a pagination
if(isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysql , filter_var($_GET['page'] , FILTER_SANITIZE_NUMBER_INT));
} else {
    $page = 1;
}
$numPerPage = 15;
$startFrom = ($page - 1) * 15;
# This is to make a pagination
if ($do == 'Manage') {
    ?>
    <div id="wrapper">
        <?php include_once $tpl . 'navbar.php' ?>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">


                    <li>
                        <a href="dashboard.php"><i class="fa fa-desktop "></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="https://sg2plcpnl0079.prod.sin2.secureserver.net:2096/" target="_blank"><i class="fa fa-envelope-o"></i>Mail box</a>
                    </li>

                    <li>
                        <a href="restaurant.php"><i class="fa fa-cutlery"></i>Restaurant <span class="badge"><?php echo totalCount('id' , 'restaurant') ?></span></a>
                    </li>

                    <li class="active-link">
                        <a href="users.php"><i class="fa fa-users"></i>Users <span class="badge"><?php echo totalCount('id' , 'users') ?></a>
                    </li>

                    <li>
                        <a href="last-booking.php"><i class="fa fa-tags"></i>Last Booking <span class="badge"><?php echo totalCount('id' , 'booking' , 'WHERE status = 0') ?></span></a>
                    </li>
                    <li>
                        <a href="allbooking.php"><i class="fa fa-bar-chart-o"></i>All booking <span class="badge"><?php echo totalCount('id' , 'booking') ?></span></a>
                    </li>
                    <li>
                        <a href="logout.php"></i><i class="fa fa-key"></i>Logout</span></a>
                    </li>

                </ul>
            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="text-uppercase"><i class="fa fa-users" style="margin: 0 15px;"></i>Users Dashboard
                        </h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr/>
                <a href="?do=Add" class="btn btn-primary btn-lg text-capitalize"><i class="fa fa-plus"></i> add new
                    users</a>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="searchUser">Search : </label>
                            <input type="search" class="form-control" id="searchUser" placeholder="Search By Email"
                                   name="searchUser">
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <div class="row pad-top">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="table-data">
                                <thead class='bg-danger'>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>name</th>
                                    <th>phone</th>
                                    <th>country</th>
                                    <th>city</th>
                                    <th>verify</th>
                                    <th>control</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (getUsers($startFrom , $numPerPage) as $result): ?>
                                    <tr>
                                        <td><?php echo $result['id'] ?></td>
                                        <td><?php echo $result['email'] ?></td>
                                        <td><?php echo $result['name'] ?></td>
                                        <td><?php echo $result['phone'] ?></td>
                                        <td><?php echo $result['couName'] ?></td>
                                        <td><?php echo $result['citName'] ?></td>
                                        <td>
                                            <?php
                                            if ($result['verification']) { ?>
                                                <span class="btn btn-primary btn-sm"><i class="fa fa-check-circle"
                                                                                        style="margin:0 7px 0 0"></i>Verified</span>
                                            <?php } else { ?>
                                                <span class="btn btn-danger btn-sm"><i class="fa fa-times-circle"
                                                                                       style="margin:0 7px 0 0"></i>Not Verified</span>
                                            <?php }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="?do=Edit&userid=<?php echo $result['id'] ?>"
                                               class="btn btn-success" style="margin:3px 0"><i
                                                        class="fa fa-edit" style="margin:0 7px 0 0"></i>Edit</a>
                                            <a onclick="return confirm('Are You Sure ?')"
                                               href="?do=Delete&userid=<?php echo $result['id'] ?>"
                                               class="btn btn-danger" style="margin:3px 0"><i
                                                        class="fa fa-trash-o" style="margin:0 7px 0 0"></i>Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- This to calculate the number of users and echo pagination -->
                            <?php $stmt = $mysql->query("SELECT id FROM users")->num_rows;
                            $totalPage = ceil($stmt/$numPerPage);
                            for($i = 1 ; $i < $totalPage ; $i++) {
                                echo "<a href='users.php?page=$i' class='btn btn-sm btn-primary'>$i</a>";
                            }
                            ?>
                            <!-- This to calculate the number of users and echo pagination -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
    </div>
<?php } elseif ($do == 'Add') { ?>
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="panel panel-default">
                <div class="panel-heading text-uppercase "><h3 class="text-primary">add new user</h3></div>
                <div class="panel-body">
                    <form action="?do=Insert" method="POST">
                        <div class="form-group">
                            <label for="name">Name : </label>
                            <input type="text" class="form-control" id="name" placeholder="Enter you're Name"
                                   name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">email : </label>
                            <input type="email" class="form-control" id="email-login" placeholder="Enter you're email"
                                   name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">password : </label>
                            <input type="password" class="form-control" id="password"
                                   placeholder="Enter you're password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirm">Password Confirm : </label>
                            <input type="password" class="form-control" id="password_confirm"
                                   placeholder="Enter you're password" name="password_confirm" required>
                        </div>

                        <label for="country">country</label>
                        <select class="form-control" id="country" name="country" onchange="changeCountry()" ">
                        <option value="0" selected>...</option>
                        <?php $stmt = $mysql->query("SELECT * FROM country ORDER BY name ASC");
                        while ($row = $stmt->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php }
                        $stmt->close(); ?>
                        </select>

                        <label for="city">city</label>
                        <select class="form-control" id="city" name="city">

                        </select>

                        <div class="form-group">
                            <label for="phone">phone : </label>
                            <input type="number" class="form-control" id="phone" placeholder="Enter you're phone"
                                   name="phone" required>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="admin"> Admin
                            </label>
                        </div>

                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php } elseif ($do == 'Delete') { # This to delete users from database
    $userID = isset($_GET['userid']) ? mysqli_real_escape_string($mysql, filter_var($_GET['userid'], FILTER_VALIDATE_INT)) : 0;
    $stmt = $mysql->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param('i', $dbUserID);
    $dbUserID = $userID;
    $stmt->execute();
    if ($stmt->error) {
        echo 'please try again there\'s someting wrong';
        header('location:' . $_SERVER['HTTP_REFERER']);
        die();
    } else {
        successMessage("Deleted $stmt->affected_rows Record successfully");
        header('refresh:2; url=users.php');
        die();
    }


} elseif ($do == 'Edit') {
    # This to get information from users
    $userID = isset($_GET['userid']) ? mysqli_real_escape_string($mysql, filter_var($_GET['userid'], FILTER_VALIDATE_INT)) : 0;
    $stmt = $mysql->prepare("SELECT users.* , country.name AS couName , city.name AS citName FROM users
                                  INNER JOIN country ON country.id = users.country 
                                  INNER JOIN city    ON city.id    = users.city
                                  WHERE users.id = ?");
    $stmt->bind_param('i', $dbUserID);
    $dbUserID = $userID;
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    # This to get information from users
    ?>
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="panel panel-default">
                <div class="panel-heading text-uppercase "><h3 class="text-primary"><i class="fa fa-pencil"
                                                                                       style="margin: 0 15px;"></i>Update
                        users</h3></div>
                <div class="panel-body">
                    <form action="?do=Update" method="POST">
                        <input type="hidden" value="<?php echo $data['id'] ?>" name="userid">
                        <div class="form-group">
                            <label for="name">Name : </label>
                            <input type="text" value="<?php echo $data['name'] ?>" class="form-control" id="name"
                                   placeholder="Enter you're Name"
                                   name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">email : </label>
                            <input type="email" class="form-control" value="<?php echo $data['email'] ?>"
                                   id="email-login" placeholder="Enter you're email"
                                   name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">password : </label>
                            <input type="hidden" value="<?php echo $data['password'] ?>" name="oldPassword">
                            <input type="password" class="form-control" id="password"
                                   placeholder="Leave It if you don't want to change it" name="newPassword">
                        </div>

                        <label for="country">country</label>
                        <select class="form-control" id="country" name="country" onchange="changeCountry()" ">
                        <option value="<?php echo $data['country'] ?>" selected><?php echo $data['couName'] ?></option>
                        <?php $stmt = $mysql->query("SELECT * FROM country ORDER BY name ASC");
                        while ($row = $stmt->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php }
                        $stmt->close(); ?>
                        </select>

                        <label for="city">city</label>
                        <select class="form-control" id="city" name="city">
                            <option value="<?php echo $data['city'] ?>" selected><?php echo $data['citName'] ?></option>
                        </select>

                        <div class="form-group">
                            <label for="phone">phone : </label>
                            <input type="number" class="form-control" value="<?php echo $data['phone'] ?>" id="phone"
                                   placeholder="Enter you're phone"
                                   name="phone" required>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="admin" <?php if ($data['group_id']) echo 'checked' ?>>
                                Admin
                            </label>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="verify" <?php if ($data['verification']) echo 'checked' ?>>
                                verify
                            </label>
                        </div>

                        <button class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
<?php } elseif ($do == 'Update') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userID = mysqli_real_escape_string($mysql, filter_var($_POST['userid'], FILTER_SANITIZE_NUMBER_INT));
        $username = mysqli_real_escape_string($mysql, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
        $email = mysqli_real_escape_string($mysql, filter_var($_POST['email'], FILTER_SANITIZE_STRING));
        $password = mysqli_real_escape_string($mysql, filter_var($_POST['newPassword'], FILTER_SANITIZE_STRING));
        $country = mysqli_real_escape_string($mysql, filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT));
        $city = mysqli_real_escape_string($mysql, filter_var($_POST['city'], FILTER_SANITIZE_NUMBER_INT));
        $phone = mysqli_real_escape_string($mysql, filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));
        $admin = isset($_POST['admin']) ? 1 : 0;
        $verify = isset($_POST['verify']) ? 1 : 0;

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
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysql->prepare("UPDATE users set name = ? , email = ? , password = ? , phone = ? , country = ? , 
                                    city = ? , group_id = ? , verification = ? WHERE id = ?");
            $stmt->bind_param('ssssiiiii', $dbName, $dbEmail, $dbPassword, $dbPhone, $dbCountry, $dbCity, $admin, $verify, $dbUserID);
            $dbName = $username;
            $dbEmail = $email;
            $dbPassword = isset($_POST['newPassword']) ? password_hash($password, PASSWORD_DEFAULT) : $_POST['oldPassword'];
            $dbPhone = $phone;
            $dbCountry = $country;
            $dbCity = $city;
            $dbUserID = $userID;
            $stmt->execute();
            if ($stmt->error) {
                echo 'please try again there\'s someting wrong';
                header('location:' . $_SERVER['HTTP_REFERER']);
                die();
            } else {
                successMessage("Updated $stmt->affected_rows Record successfully");
                header('refresh:2; url=users.php');
                die();
            }
        } else {
            include '../error.php';
            header('refresh:10 ; url = ' . $_SERVER['HTTP_REFERER']);
        }

    } else {
        errorMessage('sorry you can\'t access this page directly');
        header('refresh:4; url=index.php');
        die();
    }

} elseif ($do == 'Insert') {# This is to Register user or new admin new account
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = mysqli_real_escape_string($mysql, filter_var($_POST['name'], FILTER_SANITIZE_STRING));
        $email = mysqli_real_escape_string($mysql, filter_var($_POST['email'], FILTER_SANITIZE_STRING));
        $password = mysqli_real_escape_string($mysql, filter_var($_POST['password'], FILTER_SANITIZE_STRING));
        $pass_confirm = mysqli_real_escape_string($mysql, filter_var($_POST['password_confirm'], FILTER_SANITIZE_STRING));
        $country = mysqli_real_escape_string($mysql, filter_var($_POST['country'], FILTER_SANITIZE_NUMBER_INT));
        $city = mysqli_real_escape_string($mysql, filter_var($_POST['city'], FILTER_SANITIZE_NUMBER_INT));
        $phone = mysqli_real_escape_string($mysql, filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));
        $admin = isset($_POST['admin']) ? 1 : 0;

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
            $stmt = $mysql->prepare("INSERT INTO users (email , password , name , phone , country , city  , create_at , group_id , verification)
                                                             VALUES (? , ? , ? , ? , ? , ? , now() , $admin , 1)");
            $stmt->bind_param('ssssii', $dbEmail, $dbPassword, $dbName, $dbPhone, $dbCountry, $dbCity);
            $dbEmail = $email;
            $dbPassword = $pass;
            $dbName = $username;
            $dbPhone = $phone;
            $dbCountry = $country;
            $dbCity = $city;
            $stmt->execute();
            if ($stmt->error) {
                echo 'please try again there\'s someting wrong';
                header('location:' . $_SERVER['HTTP_REFERER']);
                die();
            } else {
                successMessage("Inserted $stmt->affected_rows Record successfully");
                header('refresh:2; url=users.php');
                die();
            }
        } else {
            include '../error.php';
            header('refresh:10 ; url = ' . $_SERVER['HTTP_REFERER']);
        }
    } else {
        errorMessage('sorry you can\'t access this page directly');
        header('refresh:4; url=index.php');
        die();
    }
}
include $tpl . 'footer.php' ?>