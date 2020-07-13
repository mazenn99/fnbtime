<?php


    /*
     * This function to set title of page
     *
     */

    function getTitle() {
        global $getTitle;
        if(isset($getTitle))
            echo $getTitle;
        else
            echo 'Fnbtime';
    }

  /*
   * This function to show success Event
   * param($msg) This take a message you want to show it
   * successMessage v1.0
   */

  function successMessage($msg) { ?>
          <div class="alert container width-50 text-darker alert-success" role="alert">
              <?php echo $msg ?>
          </div>
      <?php }

function errorMessage($msg) { ?>
    <div class="alert container width-50 text-darker alert-danger" role="alert">
        <?php echo $msg ?>
    </div>
<?php }

  /*
   * This function Check the user if exist in database or not
   * param($select) What you want to selected from table
   * $(table) The table you selected from database
   * getNumRows() v1.0
   * return (num rows)
   */

  function getNumRows($select , $from , $value) {
      global $mysql;
      $stmt = $mysql->prepare("SELECT $select FROM $from  WHERE $select = ? ");
      $stmt->bind_param('s' , $dbValue);
      $dbValue = $value;
      $stmt->execute();
      $numRows = $stmt->get_result();
      return $numRows->num_rows;
  }

  /*
   * Get User information on (edit.php) page
   */

    function getUserInformation($userId) {
        global $mysql;
        $stmt = $mysql->prepare("SELECT users.* , country.name AS couName , city.name AS citName FROM users
                                      INNER JOIN country ON country.id = users.country
                                      INNER JOIN city ON city.id = users.city
                                      WHERE users.id = ?");
        $stmt->bind_param('i' , $dbUserID);
        $dbUserID = $userId;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

  /*
   * This function to get users data from database when login
   */
  function login($email) {
      global $mysql;
      $stmt = $mysql->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->bind_param('s' , $dbEmail);
      $dbEmail = $email;
      $stmt->execute();
      return $stmt->get_result()->fetch_assoc();
  }

  /*
   * This function to get users verification to can reserve or not
   * param($ID) to select spicify user by id
   * NOTICE : ID store in session[userid]
   */
  function getVerify($ID) {
      global $mysql;
      $stmt = $mysql->prepare("SELECT verification FROM users WHERE id = ?");
      $stmt->bind_param('i' , $dbID);
      $dbID = $ID;
      $stmt->execute();
      $result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
      return $result;
  }

  /*
   * This function to get all users on users admin page
   * $startFrom for pagination and $endFrom
   * getUsers
   */

  function getUsers($startFrom = '' , $endForm = '') {
      global $mysql;
      $stmt = $mysql->prepare("SELECT users.* , country.name AS couName , city.name AS citName FROM users
                                    INNER JOIN country ON country.id = users.country
                                    INNER JOIN city    ON city.id    = users.city
                                    ORDER BY id DESC LIMIT $startFrom,$endForm");
      $stmt->execute();
      return $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  /*
   * This function to get you last booking to admin
   * $startFrom for pagination and $endFrom
   * $bookingDetails This param to get What booking you want to show
   * expample WHERE booking.status != 1 , = 2
   */

    function getLastBooking($startFrom = '' , $endFrom = '' , $bookingDetails = '') {
        global $mysql;
        $stmt = $mysql->query("SELECT booking.* , users.name AS uName , users.email AS uEmail , restaurant.name resName , city.name AS citName FROM booking
                                      INNER JOIN users ON users.id = booking.user_id
                                      INNER JOIN restaurant ON restaurant.id = booking.res_id
                                      INNER JOIN city ON city.id = restaurant.city
                                      WHERE booking.status $bookingDetails 
                                      ORDER BY booking.id DESC LIMIT $startFrom,$endFrom")->fetch_all(MYSQLI_ASSOC);
        return $stmt;
    }

    /*
     * This function to get Information for cancel booking
     */

    function getCancelBooking($bookingID) {
        global $mysql;
        $stmt = $mysql->prepare("SELECT  booking.user_id ,  booking.res_id , booking. booking_number ,  booking.name , 
                                      users.email AS uEmail , restaurant.name AS resName  FROM booking
                                      INNER JOIN users ON users.id = booking.user_id 
                                      INNER JOIN restaurant ON restaurant.id = booking.res_id 
                                      WHERE booking.id = ?");
        $stmt->bind_param('i' , $dbBookingID);
        $dbBookingID = $bookingID;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    /*
     * This function get username and email
     * to send information to client
     * when he asked new password
     */

    function getUsernameAndEmail($userID) {
        global $mysql;
        $stmt = $mysql->query("SELECT users.name , users.email FROM users WHERE id = $userID")->fetch_assoc();
        return $stmt;
    }

    /*
    * This token to update password for user
    */
    function getTokenForUpdatePassword($token) {
        $now = date("Y-m-d H:i:s");
        global $mysql;
        $stmt = $mysql->prepare("SELECT password_reset.* , users.* FROM password_reset
                                          INNER JOIN users ON users.id = password_reset.user_id 
                                          WHERE token = ? AND expire_at > '$now'");
        $stmt->bind_param('s' , $dbToken);
        $dbToken = $token;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    /*
     * This function to get information of approved booking
     */
    function getApproveBooking($bookingID) {
        global $mysql;
        $stmt = $mysql->prepare("SELECT booking.* , users.name AS uName , users.email AS uEmail , restaurant.name resName , city.name AS citName FROM booking
                                      INNER JOIN users ON users.id = booking.user_id
                                      INNER JOIN restaurant ON restaurant.id = booking.res_id
                                      INNER JOIN city ON city.id = restaurant.city
                                      WHERE booking.id = ? 
                                      ");
        $stmt->bind_param('i' , $dbBookingID);
        $dbBookingID = $bookingID;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

/*
 * This function return the status of user if verify or not
 * $userid this param take user ID
 */

    function getUserVerification($userID) {
        global $mysql;
        $stmt = $mysql->query("SELECT verification FROM users WHERE id = $userID")->fetch_array();
        return $stmt;
    }

    /*
     * This function get All information of restaurant.php admin page
     * $startFrom for pagination and $endFrom
     */

    function getAllRestaurantAdminPage($startFrom = '' , $endFrom = '') {
        global $mysql;
        $stmt = $mysql->query("SELECT restaurant.* , country.name AS couName , city.name AS citName FROM restaurant 
                                                            INNER JOIN country ON country.id = restaurant.country
                                                            INNER JOIN city ON city.id = restaurant.city
                                                            ORDER BY id DESC LIMIT $startFrom,$endFrom")->fetch_all(MYSQLI_ASSOC);
        return $stmt;
    }

  /*
   * This function to get restaurant on homepage
   */

   function getRestaurant($limit = '') {
       global $mysql;
       $value = '';
       if(!empty($limit)) {
            $value = $limit;
       }
       $stmt = $mysql->query("SELECT restaurant.* , country.name AS couName , city.name AS citName 
                                                        FROM restaurant 
                                                        INNER JOIN country ON country.id = restaurant.country 
                                                        INNER JOIN city ON city.id = restaurant.city
                                                        $value
                                                        ");
    return $stmt;
   }

   /*
    * This function get All favorite restaurant to users on client Page
    * param(userID) take userid from session
    */

    function getAllFavorite($userID) {
        global $mysql;
        $stmt = $mysql->prepare("SELECT favorite.* , users.name AS uName , restaurant.name AS resName , 
                                                    country.name AS couName , city.name AS citName ,restaurant.picture AS resPic FROM favorite
                                                    INNER JOIN users ON users.id = favorite.user_id
                                                    INNER JOIN restaurant ON restaurant.id = favorite.res_id
                                                    INNER JOIN country ON country.id = restaurant.country
                                                    INNER JOIN city ON city.id = restaurant.city
                                                    WHERE favorite.user_id = ?");
        $stmt->bind_param('i'  , $dbUserID);
        $dbUserID = $userID;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }



    /*
     * This function to get restaurant of (restaurant.php) page
     */

    function getAllRestaurant($startFrom = '' , $endFrom = '') {
        global $mysql;
        $stmt = $mysql->prepare("SELECT restaurant.* , keywords.name AS keywords , country.name AS couName , city.name AS citName FROM restaurant 
                                      INNER JOIN keywords ON keywords.res_id = restaurant.id
                                      INNER JOIN country ON country.id = restaurant.country
                                      INNER JOIN city ON city.id = restaurant.city
                                      ORDER BY restaurant.name
                                      LIMIT $startFrom,$endFrom");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    function getClientBooking($userId) {
        global $mysql;
        $stmt = $mysql->prepare("SELECT booking.* , restaurant.name AS resName , restaurant.picture AS resPic FROM booking
                                      INNER JOIN restaurant ON restaurant.id = booking.res_id
                                      WHERE booking.user_id = ?
                                      ");
        $stmt->bind_param('i' , $dbUserID);
        $dbUserID = $userId;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    /*
     * This function to get application delivery on
     * restaurant-info page it's take restaurant ID
     */

    function getAppDelivery($resID) {
        global $mysql;
        $stmt = $mysql->prepare("SELECT * FROM apps WHERE res_id = ?");
        $stmt->bind_param('i' , $dbResID);
        $dbResID = $resID;
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

  /*
   * This function to calculate number of items (users , restaurant)
   * and get the count it
   * param($select) Like id
   * param($from) Like users
   * example SELECT (id) FROM (users)
   */

  function totalCount($select , $from , $where = '') {
        global $mysql;
        $value = '';
        if(!empty($where)) {
            $value = $where;
        }
        $stmt = $mysql->query("SELECT $select FROM $from $value")->fetch_all(MYSQLI_ASSOC);
        return count($stmt);
  }


/*
 * This function to get a token when verify a email
 * param($token) This token giving From $_GET[]
 * To make sure token is valid in database
 * getToken($token) v1.0
 */
      function getToken($token) {
          $now = date("Y-m-d H:i:s");
          global $mysql;
          $stmt = $mysql->prepare("SELECT verification.* , users.* FROM verification
                                        INNER JOIN users ON users.id = verification.user_id WHERE token = ? AND expire_at > '$now'");
          $stmt->bind_param('s' , $dbToken);
          $dbToken = $token;
          $stmt->execute();
          $result = $stmt->get_result();
          $stmt->close();
          return $result->fetch_assoc();
      }

  /*
   * This function to confirm verify users from database
   * param($userID) To take id from user and verify it
   * verifyUser($userID) v1.0
   */
      function verifyUser($userID) {
          global $mysql;
          $stmt = $mysql->prepare("UPDATE users SET verification = 1 WHERE id = ?");
          $stmt->bind_param('i' , $dbUserID);
          $dbUserID = $userID;
          $stmt->execute();
          if($stmt->error)
              echo 'please try again there\'s someting wrong';
          else
              return true;
      }

  /*
   * This function return number of reservation
   * on page restaurant.php
   * param($resID) this id of restaurant
   */
      function getAllReservation($resID) {
          global $mysql;
          $stmt = $mysql->prepare("SELECT id FROM booking WHERE res_id = ?");
          $stmt->bind_param('i' , $dbResID);
          $dbResID = $resID;
          $stmt->execute();
          $result = $stmt->get_result()->fetch_all();
          return count($result);
      }


  /*
   * this function get All cuisine type
   * in restaurant page
   */
      function getAllCuisineType() {
          global $mysql;
          $stmt = $mysql->query("SELECT name FROM type_food ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
          return $stmt;
      }

      /*
       * This function to delete all token
       * when user click to resend token again
       * in index page
       */

      function deleteTokens($userid) {
          global $mysql;
          $stmt = $mysql->query("DELETE FROM verification WHERE user_id = $userid");

      }