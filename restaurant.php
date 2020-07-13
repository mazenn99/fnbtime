<?php
session_start();
$getTitle = 'Resaturant';
include_once 'init.php';
if ($_SERVER['REQUEST_METHOD'] == 'GET') { # This is to search button from index page and restaurant page
    $search = mysqli_real_escape_string($mysql, filter_var(isset($_GET['search']) ? $_GET['search'] : NULL, FILTER_SANITIZE_STRING));
    $stmt = $mysql->prepare("SELECT restaurant.* , keywords.name AS keywords , country.name AS couName , city.name AS citName FROM restaurant 
                                      INNER JOIN keywords ON keywords.res_id = restaurant.id
                                      INNER JOIN country ON country.id = restaurant.country
                                      INNER JOIN city ON city.id = restaurant.city
                                      WHERE restaurant.name LIKE CONCAT('%' , ? , '%') OR keywords.name LIKE CONCAT('%' , ? , '%')");
    $stmt->bind_param('ss', $dbSearch, $dbSearchByKeyWords);
    $dbSearch = $search;
    $dbSearchByKeyWords = $search;
    $stmt->execute();
    $result = $stmt->get_result();
}
# This is to make a pagination
if (isset($_GET['page'])) {
    $page = mysqli_real_escape_string($mysql, filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT));
} else {
    $page = 1;
}
$numPerPage = 10;
$startFrom = ($page - 1) * 10;
$resData = getAllRestaurant($startFrom, $numPerPage);

# This is to make a pagination
?>
    <body class="full-width-container">

    <!-- start Container Wrapper -->
<div class="container-wrapper">

<?php include_once 'includes/template/navbar.php' ?>

    <!-- start Main Wrapper -->
    <div class="main-wrapper scrollspy-container">

        <!-- start hero-header -->
        <div class="hero hero-sm" style="background-image:url('images/hero-header/hero-image.png');">
            <div class="container">

                <div class="home-search-form mt-20-xs">

                    <div class="clear"></div>

                    <div class="home-search-form" style="display: inline-block;text-align: center">

                        <form action="restaurant.php" method="GET">

                            <div class="form-group location-form">
                                <input type="text" id="search" name="search" class="form-control"
                                       placeholder="What would you like to eat?">
                            </div>

                            <button class="btn btn-primary btn-form">Find a Table</button>

                        </form>

                        <div class="list-group" id="show-list-search" style="display: none;">

                        </div>

                    </div>
                </div>

            </div>

        </div>
        <!-- end hero-header -->

        <div class="container pt-10 pb-30">

            <div class="breadcrumb-wrapper">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Restaurant page</li>
                </ol>
            </div>

            <div class="row">

                <div class="col-sm-4 col-md-3">

                    <div class="section-title-02">
                        <h4><span>Filter Your Result</span></h4>
                    </div>


                    <div class="sidebar-module">
                        <h5>Cuisine Type</h5>
                        <?php $i = 1; foreach (getAllCuisineType() as $getType): ?>
                            <form action="?sort" method="GET">
                                <div class="checkbox-block font-icon-checkbox">
                                    <input class="filterType checkbox" id="filter_cuisine-<?php echo $i ?>" value="<?php echo $getType['name'] ?>" name="<?php echo $getType ?>" type="checkbox">
                                    <label for="filter_cuisine-<?php echo $i ?>"><?php echo $getType['name'] ?></label>
                                </div>
                            </form>
                        <?php $i++; endforeach; ?>

<!--                        <button class="btn btn-more-less" data-toggle="collapse" data-target="#amenities-more-less">-->
<!--                            Show-->
<!--                            more-->
<!--                        </button>-->
                    </div>

                </div>

                <div class="col-sm-8 col-md-9">

<!--                    <div class="sort-by-wrapper clearfix">-->
<!---->
<!--                        <div class="row gap-5">-->
<!---->
<!--                            <div class="col-sm-10">-->
<!---->
<!--                                <div class="text-holder bg-primary">Sort By:</div>-->
<!--                                <ul class="sort-by">-->
<!--                                    <li>-->
<!--                                        <a href="?sort=name" class="btn">Name</a>-->
<!--                                    </li>-->
<!---->
<!--                                    <li>-->
<!--                                        <a href="?sort=highRes" class="btn">High Reservation</a>-->
<!--                                    </li>-->
<!--                                </ul>-->
<!---->
<!--                            </div>-->
<!---->
<!--                            <div class="col-sm-2">-->
<!---->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!---->
<!--                    </div>-->
                    <?php if (isset($_GET['search'])) {
                        if ($result->num_rows) {
                            $data = $result->fetch_all(MYSQLI_ASSOC); ?>
                            <div class="restaurant-list-item-wrapper no-last-bb">
                                <?php foreach ($data as $resDataSearch): ?>
                                    <div class="restaurant-list-item clearfix">

                                        <div class="GridLex-grid-noGutter-equalHeight">

                                            <div class="GridLex-col-3_sm-3_xss-12">
                                                <div class="image"> <?php $img = explode(',', $resDataSearch['picture']); ?>
                                                    <img src="uploads/<?php echo $img[0] ?>" alt="Image"/>
                                                </div>
                                            </div>

                                            <div class="GridLex-col-9_sm-9_xss-12">

                                                <div class="GridLex-grid-noGutter-equalHeight">

                                                    <div class="GridLex-col-9_sm-12 content-wrapper">

                                                        <div class="content">
                                                            <h5>
                                                                <a href="restaurant-info.php?restaurant=<?php echo $resDataSearch['id'] ?>"><?php echo $resDataSearch['name'] ?></a>
                                                            </h5>
                                                            <p class="location"><i
                                                                        class="fa fa-map-marker"></i> <?php echo $resDataSearch['couName'] . ' ' . $resDataSearch['citName'] ?>
                                                            </p>
                                                            <p class="short-info"><?php if (strlen($resDataSearch['description']) > 50)
                                                                    echo substr($resDataSearch['description'], 0, 300);
                                                                ?></p>
                                                            <p class="cuisine">
                                                                Cuisine: <?php $type = explode(',', $resDataSearch['type_food']); # This is get the type of restaurant
                                                                foreach ($type as $types):?>
                                                                    <span><?php echo $types ?></span>
                                                                <?php endforeach; ?>
                                                            </p>
                                                        </div>

                                                    </div>

                                                    <div class="GridLex-col-3_sm-12 meta-wrapper">

                                                        <div class="meta">


                                                            <div class="right-bottom">
                                                                <?php if (getAllReservation($resDataSearch['id'])) { ?>
                                                                    <div class="price">All Reservation
                                                                        <span><?php echo getAllReservation($resDataSearch['id']) ?><i
                                                                                    class="fa fa-bookmark mr-5"
                                                                                    style="margin-left:3px"></i></span>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                <?php } ?>
                                                                <a href="restaurant-info.php?restaurant=<?php echo $resDataSearch['id'] ?>"
                                                                   class="btn btn-primary btn-sm btn-block">Details</a>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                <?php endforeach; ?>

                            </div>
                            ?>
                        <?php } else {
                            echo 'Sorry No Data to get all restaurant <a href="restaurant.php" class="btn btn-sm btn-primary">Click Here</a> ';
                            die();
                        }
                    }
                    ?>

                    <div class="restaurant-list-item-wrapper no-last-bb" id="filterTypeCuisineSearch">
                        <?php foreach ($resData as $data): ?>
                            <div class="restaurant-list-item clearfix">

                                <div class="GridLex-grid-noGutter-equalHeight">

                                    <div class="GridLex-col-3_sm-3_xss-12">
                                        <div class="image"> <?php $img = explode(',', $data['picture']); ?>
                                            <img src="uploads/<?php echo $img[0] ?>" alt="Image"/>
                                        </div>
                                    </div>

                                    <div class="GridLex-col-9_sm-9_xss-12">

                                        <div class="GridLex-grid-noGutter-equalHeight">

                                            <div class="GridLex-col-9_sm-12 content-wrapper">

                                                <div class="content">
                                                    <h5>
                                                        <a href="restaurant-info.php?restaurant=<?php echo $data['id'] ?>"><?php echo $data['name'] ?></a>
                                                    </h5>
                                                    <p class="location"><i
                                                                class="fa fa-map-marker"></i> <?php echo $data['couName'] . ' ' . $data['citName'] ?>
                                                    </p>
                                                    <p class="short-info"><?php if (strlen($data['description']) > 40)
                                                            echo substr($data['description'], 0, 300);
                                                        ?></p>
                                                    <p class="cuisine">
                                                        Cuisine: <?php $type = explode(',', $data['type_food']); # This is get the type of restaurant
                                                        foreach ($type as $types):?>
                                                            <span><?php echo $types ?></span>
                                                        <?php endforeach; ?>
                                                    </p>
                                                </div>

                                            </div>

                                            <div class="GridLex-col-3_sm-12 meta-wrapper">

                                                <div class="meta">

                                                    <div class="right-bottom">
                                                        <?php if (getAllReservation($data['id'])) { ?>
                                                            <div class="price">All Reservation
                                                                <span><?php echo getAllReservation($data['id']) ?><i
                                                                            class="fa fa-bookmark mr-5"
                                                                            style="margin-left:3px"></i></span>
                                                            </div>
                                                            <div class="clear"></div>
                                                        <?php } ?>
                                                        <a href="restaurant-info.php?restaurant=<?php echo $data['id'] ?>"
                                                           class="btn btn-primary btn-sm btn-block">Details</a>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        <?php endforeach; ?>

                    </div>

                    <div class="pagination-wrapper">

                        <div class="GridLex-grid-middle GridLex-grid-noGutter">
                            <div class="GridLex-col-6_sm-12_xs-12">
                                <div class="text-right text-center-sm mb-10-sm">Showing More Restaurant</div>
                            </div>
                            <div class="GridLex-col-6_sm-12_xs-12">
                                <nav>
                                    <ul class="pagination pagination-text-center-sm mb-5-xs">
                                        <li>
                                            <a href="#" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <!-- This to calculate the number of users and echo pagination -->
                                        <?php $stmt = $mysql->query("SELECT id FROM restaurant")->num_rows;
                                        $totalPage = ceil($stmt / $numPerPage);
                                        for ($i = 1; $i <= $totalPage; $i++) { ?>
                                            <li class="active"><a href="restaurant.php?page=<?php echo $i ?>"
                                                                  style="margin: 0 2px;"><?php echo $i ?></a>
                                            </li>
                                        <?php }
                                        ?>
                                        <!-- This to calculate the number of users and echo pagination -->
                                        <!--                                        <li><a href="#">2</a></li>-->
                                        <!--                                        <li><a href="#">3</a></li>-->
                                        <!--                                        <li><a href="#">4</a></li>-->
                                        <!--                                        <li><a href="#">5</a></li>-->
                                        <li>
                                            <a href="#" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    </div>
    <!-- end Main Wrapper -->

    <!-- start Footer Wrapper -->
    <div class="footer-wrapper scrollspy-footer">
<!--google api test-->
<!--        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>-->
<!--        <script>-->
<!--            // https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyCIeMegQWlcJuh6qu_K-fWkyMtgfPz-DT0-->
<!--            function getCode() {-->
<!--                let location = '22 Main st Boston MA';-->
<!--                axios.get('https://maps.googleapis.com/maps/api/geocode/json' , {-->
<!--                    params: {-->
<!--                        address:location,-->
<!--                        key:'AIzaSyCIeMegQWlcJuh6qu_K-fWkyMtgfPz-DT0'-->
<!--                    }-->
<!--                })-->
<!--                .then(function(res){-->
<!--                    console.log(res)-->
<!--                })-->
<!--                .catch(function(error) {-->
<!--                    console.log(error)-->
<!--                })-->
<!--            }-->
<!--            getCode();-->
<!--        </script>-->
<?php include_once $tpl . 'footer.php' ?>