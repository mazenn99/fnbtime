<?php
session_start();
$getTitle = 'Last Booking';
if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    die('Access Denied');
}
$pageTitle = 'booking';
include_once __DIR__ . '/init.php';
require_once $sendEm .'SendEmail.php';
$error = [];
$allowDo = ['Approve', 'Manage', 'Canceled'];
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
        <?php include_once $tpl . 'navbar.php'?>
        <!-- /. NAV TOP  -->
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

                    <li >
                        <a href="users.php"><i class="fa fa-users"></i>Users <span class="badge"><?php echo totalCount('id' , 'users') ?></a>
                    </li>

                    <li class="active-link">
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
                        <h2 class="text-uppercase"><i class="fa fa-users" style="margin: 0 15px;"></i>Booking Dashboard</h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr/>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="searchUser">Search : </label>
                            <input type="search" class="form-control" id="searchUserBooking" placeholder="Search by booking number or name"
                                   name="searchUserBooking">
                        </div>
                    </div>
                </div>

                <!-- /. ROW  -->
                <div class="row pad-top">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="table-data-last-booking">
                                <thead class='bg-danger'>
                                <tr>
                                    <th>ID</th>
                                    <th>name</th>
                                    <th>restaurant</th>
                                    <th>booking Number</th>
                                    <th>Phone</th>
                                    <th>city</th>
                                    <th>Guests</th>
                                    <th>occasion Date</th>
                                    <th>Time</th>
                                    <th>status</th>
                                    <th>control</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (getLastBooking($startFrom , $numPerPage , '= 0') as $result): ?>
                                    <tr>
                                        <td><?php echo $result['id'] ?></td>
                                        <td><?php echo $result['uName'] ?></td>
                                        <td><?php echo $result['resName'] ?></td>
                                        <td><?php echo $result['booking_number'] ?></td>
                                        <td><?php echo $result['phone_coustmer'] ?></td>
                                        <td><?php echo $result['citName'] ?></td>
                                        <td><?php echo $result['person_number'] ?></td>
                                        <td><?php echo $result['occasion_DATE'] ?></td>
                                        <td><?php echo $result['time'] ?></td>
                                        <td>
                                            <?php
                                                switch ($result['status']) {
                                                    case 0 :
                                                        echo "<span class='btn btn-warning btn-sm'>Pending</span>";
                                                        break;
                                                    case 1 :
                                                        echo "<span class='btn btn-success btn-sm'>Success</span>";
                                                        break;
                                                    case 2 :
                                                        echo "<span class='btn btn-danger btn-sm'>Canceled</span>";
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="?do=Approve&bookingid=<?php echo $result['id'] ?>" class="btn btn-sm btn-primary">Approve</a>
                                            <!-- Button trigger modal -->
                                            <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-sm btn-danger">Canceled</button>
                                        </td>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Please Enter The reason</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="<?php echo $_SERVER['PHP_SELF'] . "?do=Canceled&bookingid=$result[id]". '' ?>" method="POST">
                                                            <div class="form-group">
                                                                <label for="cancelMessage">Message</label>
                                                                <textarea class="form-control" name="cancelMessage" id="cancelMessage" cols="30"
                                                                          rows="10" required></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button class="btn btn-primary">Send</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- This to calculate the number of users and echo pagination -->
                            <?php $stmt = $mysql->query("SELECT id FROM booking")->num_rows;
                            $totalPage = ceil($stmt/$numPerPage);
                            for($i = 1 ; $i < $totalPage ; $i++) {
                                echo "<a href='last-booking.php?page=$i' class='btn btn-sm btn-primary'>$i</a>";
                            }
                            ?>
                            <!-- This to calculate the number of users and echo pagination -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>

<?php } elseif($do == 'Approve') {
    $bookingID = isset($_GET['bookingid']) ? mysqli_real_escape_string($mysql , filter_var($_GET['bookingid'] , FILTER_VALIDATE_INT)) : 0;
    $stmt = $mysql->prepare("UPDATE booking SET status = 1 WHERE id = ?");
    $stmt->bind_param('i' , $dbBookingID);
    $dbBookingID = $bookingID;
    $stmt->execute();
    if ($stmt->error) {
        echo 'please try again there\'s someting wrong';
        header('location:' . $_SERVER['HTTP_REFERER']);
        die();
    } else {
        successMessage("Approved Booking $stmt->affected_rows Record successfully");
        $result = getApproveBooking($bookingID);
        $mail = new SendEmail();
        $mail->sendAcceptBooking($result['uEmail'] , $result['name'] , $result['resName'] , $result['booking_number'] , $result['person_number'] , $result['occasion_DATE'] , $result['time']);
        header('refresh:2; url=last-booking.php');
        die();
    }
} elseif ($do == 'Canceled') {
    $message = filter_var($_POST['cancelMessage'] , FILTER_SANITIZE_STRING);
    $bookingID = isset($_GET['bookingid']) ? mysqli_real_escape_string($mysql , filter_var($_GET['bookingid'] , FILTER_VALIDATE_INT)) : 0;
    $stmt = $mysql->prepare("UPDATE booking SET status = 2 WHERE id = ?");
    $stmt->bind_param('i' , $dbBookingID);
    $dbBookingID = $bookingID;
    $stmt->execute();
    if ($stmt->error) {
        echo 'please try again there\'s someting wrong';
        header('location:' . $_SERVER['HTTP_REFERER']);
        die();
    } else {
        successMessage("Canceled Booking $stmt->affected_rows Record successfully");
        $result = getCancelBooking($bookingID);
        $mail = new SendEmail();
        $mail->sendCancelBooking($result['name'] , $result['uEmail'] , $result['booking_number'] , $result['resName'] , $message);
        header('refresh:2; url=last-booking.php');
        die();
    }
} ?>
<?php include_once $tpl . 'footer.php'?>