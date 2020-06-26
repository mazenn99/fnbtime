<?php
$header = $navbar = '';
include_once 'init.php';
if (isset($_GET['country']) && intval($_GET['country'])) { # This is to get a city of related country
    $cityId = mysqli_real_escape_string($mysql, filter_var($_GET['country'], FILTER_SANITIZE_NUMBER_INT));
    $stmt = $mysql->prepare("SELECT id , name FROM city WHERE country_id = ? ORDER BY name ASC");
    $stmt->bind_param('i', $dbCountryID);
    $dbCountryID = $cityId;
    $stmt->execute();
    $city = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode($city);
}

if (isset($_POST['searchUser'])) {
    $search = mysqli_real_escape_string($mysql, filter_var($_POST['searchUser'], FILTER_SANITIZE_STRIPPED));
    $stmt = $mysql->prepare("SELECT users.* , country.name AS couName , city.name AS citName FROM users
                                    INNER JOIN country ON country.id = users.country
                                    INNER JOIN city    ON city.id    = users.city
                                    WHERE users.email LIKE CONCAT('%' , ? , '%')
                                    ");
    $stmt->bind_param('s', $dbSearchUser);
    $dbSearchUser = $search;
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    if ($result->num_rows) {
        $output = "<thead class='bg-danger'>
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
                            <tbody>";
        foreach ($users as $user) {
            if ($user['verification'] == 1) {
                $verify = "<span class=\"btn btn-primary btn-sm\"><i class=\"fa fa-check-circle\"
                                                                                        style=\"margin:0 7px 0 0\"></i>Verified</span>";
            } else {
                $verify = "<span class=\"btn btn-danger btn-sm\"><i class=\"fa fa-times-circle\"
                                                                                        style=\"margin:0 7px 0 0\"></i>Not Verified</span>";
            }
            $output .= "<tr>
                                <td>" . $user['id'] . "</td>
                                <td>" . $user['email'] . "</td>
                                <td>" . $user['name'] . "</td>
                                <td>" . $user['phone'] . "</td>
                                <td>" . $user['couName'] . "</td>
                                <td>" . $user['citName'] . "</td>
                                <td>" . $verify . "</td>
                                 <td>
                                    <a href=\"?do=Edit&userid=" . $user['id'] . " \"
                                       class=\"btn btn-success\" style=\"margin:3px 0\"><i
                                                class=\"fa fa-edit\" style=\"margin:0 7px 0 0\"></i>Edit</a>
                                    <a onclick=\"return confirm('Are You Sure ?')\"
                                       href=\"?do=Delete&userid=" . $user['id'] . "\"
                                       class=\"btn btn-danger\" style=\"margin:3px 0\"><i
                                                class=\"fa fa-trash-o\" style=\"margin:0 7px 0 0\"></i>Delete
                                    </a>
                                </td>                                               
                              </tr>
                              ";
        }
        $output .= "</body>";
        echo $output;
    } else {
        echo "<h4 class='text-center text-danger' style='display: block'>sorry There is no data to show</h4>";
    }

}

if (isset($_POST['searchBooking'])) { # This search for booking last-booking page and allbooking
    $searchBooking = mysqli_real_escape_string($mysql, filter_var($_POST['searchBooking'], FILTER_SANITIZE_STRING));
    $stmt = $mysql->prepare("SELECT booking.* , users.name AS uName , restaurant.name resName , city.name AS citName FROM booking
                                      INNER JOIN users ON users.id = booking.user_id
                                      INNER JOIN restaurant ON restaurant.id = booking.res_id
                                      INNER JOIN city ON city.id = restaurant.city
                                      WHERE booking.booking_number LIKE CONCAT('%' , ? , '%') OR users.name LIKE CONCAT('%' , ? , '%')
                                      AND booking.status != 1
                                      ORDER BY booking.id DESC 
                                      ");
    $stmt->bind_param('ss', $dbSearchBookingByNumber, $dbSearchBookingByName);
    $dbSearchBookingByNumber = $searchBooking;
    $dbSearchBookingByName = $searchBooking;
    $stmt->execute();
    $data = $stmt->get_result();
    if ($data->num_rows) {
        $found = $data->fetch_all(MYSQLI_ASSOC);
        $output = "<thead class='bg-danger'>
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
                            <tbody>";

        foreach ($found as $booking) {
            if ($booking['status'] == 0) {
                $data = "<span class='btn btn-warning btn-sm'>Pending</span>";
            } elseif ($booking['status'] == 1) {
                $data = "<span class='btn btn-success btn-sm'>Success</span>";
            } else {
                $data = "<span class='btn btn-danger btn-sm'>Canceled</span>";
            }
            $output .= "<tr>
                                        <td>" . $booking['id'] . "</td>
                                        <td>" . $booking['uName'] . "</td>
                                        <td>" . $booking['resName'] . "</td>
                                        <td>" . $booking['booking_number'] . "</td>
                                        <td>" . $booking['phone_coustmer'] . "</td>
                                        <td>" . $booking['citName'] . "</td>
                                        <td>" . $booking['person_number'] . "</td>
                                        <td>" . $booking['occasion_DATE'] . "</td>
                                        <td>" . $booking['time'] . "</td>
                                        <td>" . $data . "</td>
                                        <td>
                                            <a href=\"?do=Approve&bookingid=" . $booking['id'] . "\" class=\"btn btn-sm btn-primary\">Approve</a>
                                            <!-- Button trigger modal -->
                                            <button type=\"button\" data-toggle=\"modal\" data-target=\"#exampleModal\" class=\"btn btn-sm btn-danger\">Canceled</button>
                                        </td>                                               
                                      </tr>";
        }
        $output .= "</body>";
        echo $output;
    } else {
        echo "<h4 class='text-center text-danger' style='display: block'>sorry There is no data to show</h4>";
    }

}
# This search for booking last-booking page and allbooking

# This search for restaurant page and all restaurant

if (isset($_POST['searchRestaurant'])) {
    $search = mysqli_real_escape_string($mysql, filter_var($_POST['searchRestaurant'], FILTER_SANITIZE_STRING));
    $stmt = $mysql->prepare("SELECT restaurant.* , country.name AS couName , city.name AS citName FROM restaurant 
                                                            INNER JOIN country ON country.id = restaurant.country
                                                            INNER JOIN city ON city.id = restaurant.city
                                                            WHERE restaurant.name LIKE CONCAT('%' , ? , '%')
                                                            ORDER BY id DESC ");
    $stmt->bind_param('s', $dbSearchRestaurant);
    $dbSearchRestaurant = $search;
    $stmt->execute();
    $result = $stmt->get_result();
    $output = '';
    if ($result->num_rows) {
        $foundRes = $result->fetch_all(MYSQLI_ASSOC);
        $output .="<thead class='bg-danger'>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>country</th>
                                    <th>city</th>
                                    <th>type food</th>
                                    <th>Restaurant Number</th>
                                    <th>Added At</th>
                                    <th>All Booking</th>
                                    <th>control</th>
                                </tr>
                                </thead>
                                <tbody>";

foreach ($foundRes as $found) {
            $output .= "<tr>
                            <td>" . $found['id'] . "</td>
                            <td>" . $found['name'] . "</td>
                            <td>" . $found['couName'] . "</td>
                            <td>" . $found['citName'] . "</td>
                            <td>" . $found['type_food'] . "</td>
                            <td>" . $found['number'] . "</td>
                            <td>" . $found['create_at'] . "</td>
                            <td>" . getAllReservation($found['id']) . "</td>
                            <td>
                                <a onclick=\"return confirm('Are You Sure ?')\"
                                   href=\"?do=Delete&resid=" . $found['id'] . "\"
                                   class=\"btn btn-danger\" style=\"margin:3px 0\"><i
                                            class=\"fa fa-trash-o\" style=\"margin:0 7px 0 0\"></i>Delete
                                </a>
                            </td>
                        </tr>";
        }
        $output .= "</body>";
        echo $output;
    } else {
        echo "<h4 class='text-center text-danger' style='display: block'>sorry There is no data to show</h4>";
    }

}
# This search for restaurant page and all restaurant
