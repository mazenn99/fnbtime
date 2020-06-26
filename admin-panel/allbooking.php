<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location:index.php');
    die('Access Denied');
}
$getTitle = 'All Booking';
require_once 'init.php';
# This is to make a pagination
if(isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysql , filter_var($_GET['page'] , FILTER_SANITIZE_NUMBER_INT));
} else {
    $page = 1;
}
$numPerPage = 15;
$startFrom = ($page - 1) * 15;
# This is to make a pagination
?>
    <div id="wrapper">
        <?php include_once $tpl . 'navbar.php'?>
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

                    <li>
                        <a href="users.php"><i class="fa fa-users"></i>Users <span class="badge"><?php echo totalCount('id' , 'users') ?></a>
                    </li>

                    <li>
                        <a href="last-booking.php"><i class="fa fa-tags"></i>Last Booking <span class="badge"><?php echo totalCount('id' , 'booking' , 'WHERE status = 0') ?></span></a>
                    </li>

                    <li class="active-link">
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
                        <h2 class="text-uppercase"><i class="fa fa-tags" style="margin: 0 15px;"></i>all booking</h2>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr/>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="searchUser">Search : </label>
                            <input type="search" class="form-control" id="searchUserBooking" placeholder="Enter name Or booking Number"
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
                                    <th>persons</th>
                                    <th>occasion Date</th>
                                    <th>Time</th>
                                    <th>status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (getLastBooking($startFrom , $numPerPage) as $result): ?>
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
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- This to calculate the number of users and echo pagination -->
                            <?php $stmt = $mysql->query("SELECT id FROM booking")->num_rows;
                            $totalPage = ceil($stmt/$numPerPage);
                            for($i = 1 ; $i < $totalPage ; $i++) {
                                echo "<a href='allbooking.php?page=$i' class='btn btn-sm btn-primary'>$i</a>";
                            }
                            ?>
                            <!-- This to calculate the number of users and echo pagination -->
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->

                <!-- /. ROW  -->
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>

<?php include_once $tpl . 'footer.php'?>