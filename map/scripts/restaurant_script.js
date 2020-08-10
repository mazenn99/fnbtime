var map;
var restaurant;
var restaurantsList = [];
var params = getParams(window.location.href);

/**
* introLoader - Preloader
*/
$("#introLoaderRestaurant").introLoader({
    animation: {
            name: 'gifLoader',
            options: {
                    ease: "easeInOutCirc",
                    style: 'dark bubble',
                    delayBefore: 1500,
                    delayAfter: 0,
                    exitTime: 300
            }
    }
});

// Start the map by finding current client location
function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}

// If geolocation was successful, do the rest
function showPosition(position) {

    // define map
    map = new google.maps.Map(document.getElementById("hotel-detail-map"), {
        center: { lat: 26.8349514, lng: 26.3822562 },
        zoom: 5,
        disableDefaultUI: true,
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_TOP
        }
    });

    var query = [{
        key: 'id',
        value: params.restaurant
    }];

    // call details.php to get the restaurant by id
    downloadUrl(window.location.origin + '/map/details.php', query, function(data) {
        var xml = data.responseXML;
        var restaurantElement = xml.documentElement.getElementsByTagName('restaurant')[0];

        // convert xml result to JSON object
        restaurant = {
            id: restaurantElement.getAttribute('id'),
            name: restaurantElement.getAttribute('name'),
            city: restaurantElement.getAttribute('city'),
            url: restaurantElement.getAttribute('url'),
            lat: parseFloat(restaurantElement.getAttribute('url').match(/\@(-?[\d\.]*)/)[1]), // get latitude value from place url
            lng: parseFloat(restaurantElement.getAttribute('url').match(/\@-?[\d\.]*\,([-?\d\.]*)/)[1]) // get longitude value from place url
        };

        // find the current restaurant using text search in places API
        var service = new google.maps.places.PlacesService(map);
        service.textSearch({
            location: {
                lat: restaurant.lat,
                lng: restaurant.lng
            },
            radius: 10,
            query: restaurant.name
        }, function(results, status) {
            if(status == google.maps.places.PlacesServiceStatus.OK) {

                // get distance between client and restaurant location using distance matrix API
                var distanceService = new google.maps.DistanceMatrixService();
                distanceService.getDistanceMatrix({
                    origins: [new google.maps.LatLng(position.coords.latitude, position.coords.longitude)],
                    destinations: [new google.maps.LatLng(restaurant.lat, restaurant.lng)],
                    travelMode: 'DRIVING'
                }, function(distanceResult, distanceStatus) {
                    if (distanceStatus == 'OK') {

                        // get restaurant details
                        var detailsService = new google.maps.places.PlacesService(map);
                        detailsService.getDetails({
                            placeId: results[0].place_id
                        }, function(detailsResult, detailsStatus) {
                            if(detailsStatus == 'OK') {
                                console.log(detailsResult);
                                
                                // add info window to map with distance value
                                var infowindow = new google.maps.InfoWindow({
                                    content: `
                                        <p style="margin: 10px"><i class="fa fa-car"></i> ${distanceResult.rows[0].elements[0].distance.text}</p>
                                    `
                                });

                                // add marker to restaurant location in the map
                                var marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(restaurant.lat, restaurant.lng),
                                    map: map,
                                    title: detailsResult.name,
                                    icon: 'images/map-marker/00.png'
                                });

                                // show info window when marker is clicked
                                marker.addListener('click', function() {
                                    infowindow.open(map, marker);
                                });

                                // zoom to restaurant location in the map
                                map.setCenter(new google.maps.LatLng(restaurant.lat, restaurant.lng));
                                map.setZoom(13);

                                // change page title
                                document.title = restaurant.name;

                                // add restaurant image as page cover
                                $('.main-wrapper .hero.hero-detail').css('background-image', `url('${detailsResult.photos[0].getUrl()}')`);
                                
                                // add restaurant name, address, rating, and reviews to the page
                                $('.main-wrapper .hero.hero-detail .detail-header-inner').html(`
                                    <h3>${restaurant.name}</h3>
                                    <p class="location"><i class="fa fa-map-marker"></i> ${detailsResult.adr_address}</p>
                                    <div class="rating-wrapper">
                                        <div class="rating-item"><input type="hidden" class="rating" data-filled="oi oi-star" data-empty="oi oi-star" data-fractions="2" data-readonly value="${(Math.round(detailsResult.rating * 2) / 2).toFixed(1)}"/></div>
                                        <span class="texting"> based on ${detailsResult.user_ratings_total} reviews</span>
                                    </div>
                                `);

                                $('.detail-content-for-sticky-menu.for-detail-page .detail-content-section p.font500').html(`
                                    ${restaurant.description}
                                `);

                                // add address to contact information section
                                if(typeof detailsResult.adr_address != 'undefined') {
                                    $('.detail-content-for-sticky-menu.for-detail-page .contact-list').append(`
                                        <li>
                                            <div class="icon">
                                                <i class="ti-home"></i>
                                            </div>
                                            <div class="content">
                                                <p>${detailsResult.adr_address}</p>
                                            </div>
                                        </li>
                                    `);
                                }

                                // add phone number to contact information section
                                if(typeof detailsResult.international_phone_number != 'undefined') {
                                    $('.detail-content-for-sticky-menu.for-detail-page .contact-list').append(`
                                        <li>
                                            <div class="icon">
                                                <i class="ti-mobile"></i>
                                            </div>
                                            <div class="content">
                                                <p>${detailsResult.international_phone_number}</p>
                                            </div>
                                        </li>
                                    `);
                                }

                                // add website to contact information section
                                if(typeof detailsResult.website != 'undefined') {
                                    $('.detail-content-for-sticky-menu.for-detail-page .contact-list').append(`
                                        <li>
                                            <div class="icon">
                                                <i class="ti-link"></i>
                                            </div>
                                            <div class="content">
                                                <p>
                                                    <a href="${detailsResult.website}" target="_blank">${detailsResult.website}</a>
                                                </p>
                                            </div>
                                        </li>
                                    `);
                                }

                                $('.detail-content-for-sticky-menu.for-detail-page .contact-list').next().attr('href', restaurant.url);

                                // fill working hours table
                                var openingTimes = '';
                                for(var i = 0; i < detailsResult.opening_hours.weekday_text.length; i++) {
                                    var parts = detailsResult.opening_hours.weekday_text[i].split(': ');
                                    openingTimes += `
                                        <li>
                                            <div class="row">
                                                <div class="col-xs-6 col-sm-6">
                                                    <span class="day">
                                                        ${parts[0]}
                                                    </span>
                                                </div>
                                                <div class="col-xs-6 col-sm-6">
                                                    <span class="time">
                                                        ${parts[1]}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    `;
                                }
                                $('.detail-content-for-sticky-menu.for-detail-page .open-time-box .open-time-list').html(openingTimes);

                                // add values of rating and reviews in the reviews section header
                                $('#detail-content-sticky-nav-02 .average-score').html(`
                                    <div class="progress-radial progress-radial-md progress-${(Math.round(detailsResult.rating * 2) / 2).toFixed(1) * 20}">
                                        <div class="overlay">
                                            <div class="progress-radial-inner">
                                                <div class="caption">
                                                    <p class="number">${detailsResult.rating}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="texting">
                                        <a href="#">(based on ${detailsResult.user_ratings_total} reviews)</a>
                                    </div>
                                `);

                                // add user reviews to the reviews section
                                var reviews = '';
                                for(var review of detailsResult.reviews) {
                                    reviews += `
                                        <li class="clearfix">
                                            <div class="review-man">
                                                <div class="image">
                                                    <img src="${review.profile_photo_url}" alt="images" />
                                                </div>
                                                <h6>${review.author_name}</h6>
                                            </div>
                                            <div class="review-content">
                                                <div class="review-arrow">
                                                    <span></span>
                                                </div>
                                                <div class="meta-top">
                                                    <div class="progress-radial progress-radial-sm progress-${(Math.round(review.rating * 2) / 2).toFixed(1) * 20}">
                                                        <div class="overlay">
                                                            <div class="progress-radial-inner">
                                                                <div class="caption">
                                                                    <p class="number">${review.rating}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    reviewed ${review.relative_time_description}
                                                </div>
                                                <div class="review-entry">
                                                    <p>${review.text}</p>
                                                </div>
                                            </div>
                                        </li>
                                    `;
                                }

                                $('.review-item-wrapper .review-item-list').html(reviews);

                                // add restaurant images to the photo section
                                $('#detail-food-photo').imagesGrid({
                                    images: [
                                            { src: detailsResult.photos[0].getUrl(), alt: restaurant.name + ' photo 1', title: '', caption: restaurant.name + ' photo 1' },
                                            { src: detailsResult.photos[1].getUrl(), alt: restaurant.name + ' photo 2', title: '', caption: restaurant.name + ' photo 2' },
                                            { src: detailsResult.photos[2].getUrl(), alt: restaurant.name + ' photo 3', title: '', caption: restaurant.name + ' photo 3' },
                                            { src: detailsResult.photos[3].getUrl(), alt: restaurant.name + ' photo 4', title: '', caption: restaurant.name + ' photo 4' },
                                            { src: detailsResult.photos[4].getUrl(), alt: restaurant.name + ' photo 5', title: '', caption: restaurant.name + ' photo 5' },
                                            { src: detailsResult.photos[5].getUrl(), alt: restaurant.name + ' photo 6', title: '', caption: restaurant.name + ' photo 6' },
                                            { src: detailsResult.photos[6].getUrl(), alt: restaurant.name + ' photo 7', title: '', caption: restaurant.name + ' photo 7' },
                                            { src: detailsResult.photos[7].getUrl(), alt: restaurant.name + ' photo 8', title: '', caption: restaurant.name + ' photo 8' },
                                            { src: detailsResult.photos[8].getUrl(), alt: restaurant.name + ' photo 9', title: '', caption: restaurant.name + ' photo 9' },
                                            { src: detailsResult.photos[9].getUrl(), alt: restaurant.name + ' photo 10', title: '', caption: restaurant.name + ' photo 10' }
                                    ],
                                    cells: 5,
                                    align: true
                                });

                                // show if the restaurant is open or closed
                                $(`.working-status`).html(`
                                    Status: ${detailsResult.opening_hours.isOpen() ? '<span style="color: green;"> Open</span> <br>Distance: ' + distanceResult.rows[0].elements[0].distance.text : '<span style="color: red;"> Closed</span>'}
                                `);

                                // this fixes when rating not showing
                                setTimeout(() => {
                                    $('.rating').rating();
                                }, 1000);
                            }
                        })
                    }
                });
            }
        });
    });

    getRecommendations(position)
}

// get other restaurant in client location
function getRecommendations(position) {
    // translate geolocation position to latlng object that maps API understand
    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    geocoder = new google.maps.Geocoder();
    // Geocode the current position to get current city or country
    geocoder.geocode({
        'location': latlng
    }, function(userResults, userStatus) {
        if(userStatus === 'OK') {
            console.log(userResults[0]);

            var query = [{
                key: 'city',
                value: userResults[0].address_components[2].long_name
            }, {
                key: 'id',
                value: params.restaurant
            }];

            // call info.php to get restaurants in the city or country except current restaurant
            downloadUrl(window.location.origin + '/map/info.php', query, function(data) {
                var xml = data.responseXML;
                var restaurants = xml.documentElement.getElementsByTagName('restaurant');

                // convert xml results to an array of objects
                Array.prototype.forEach.call(restaurants, function(element) {
                    restaurantsList.push({
                        id: element.getAttribute('id'),
                        name: element.getAttribute('name'),
                        city: element.getAttribute('city'),
                        foodtype: element.getAttribute('foodType'),
                        url: element.getAttribute('url'),
                        lat: element.getAttribute('url').match(/\@(-?[\d\.]*)/)[1],
                        lng: element.getAttribute('url').match(/\@-?[\d\.]*\,([-?\d\.]*)/)[1]
                    });
                });

                // iterate the restaurants array
                restaurantsList.forEach(function(element, index) {
                    var location = {
                        lat: parseFloat(element.lat),
                        lng: parseFloat(element.lng)
                    };

                    // find the current restaurant using text search in places API
                    var service = new google.maps.places.PlacesService(map);
                    service.textSearch({
                        location: location,
                        radius: 10,
                        query: element.name
                    }, function(restaurantResults, restaurantStatus) { // use only the first result and ignore the rest
                        console.log(restaurantResults);
                        if(restaurantStatus == google.maps.places.PlacesServiceStatus.OK) {
                            // prepare represntation of price range
                            var price = '';
                            for(var i = 0; i < restaurantResults[0].price_level; i++) { 
                                price += '$';
                            }

                            // add the restaurant to the template with data got from database
                            $('.GridLex-gap-30 .GridLex-grid-noGutter-equalHeight').html(
                                $('.GridLex-gap-30 .GridLex-grid-noGutter-equalHeight').html() + `
                                <div class="GridLex-col-3_sm-4_xs-6_xss-12">
                                    <div class="restaurant-grid-item">
                                        <a href="restaurant-info.php?restaurant=${element.id}">
                                            <div class="image">
                                                <img src="${restaurantResults[0].photos[0].getUrl()}" alt="Image" />
                                            </div>
                                            <div class="content">
                                                <h5>${restaurantResults[0].name}</h5>
                                                <p class="location">
                                                    <i class="fa fa-map-marker"></i> ${restaurantResults[0].formatted_address}
                                                </p>
                                                <div class="rating-wrapper">
                                                    <div class="rating-item">
                                                        <input type="hidden" class="rating" data-filled="oi oi-star" data-empty="oi oi-star" data-fractions="2" data-readonly value="${(Math.round(restaurantResults[0].rating * 2) / 2).toFixed(1)}"/>
                                                    </div>
                                                    <span class="texting"> (${restaurantResults[0].user_ratings_total} reviews)</span>
                                                </div>
                                                <p class="cuisine">
                                                    Cuisine: ${element.foodtype.split(',').map(type => {
                                                        return `<span>${type}</span>`;
                                                    })}
                                                </p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            `);
                        }
                    });
                });

                // this fixes when rating not showing
                setTimeout(() => {
                    $('.rating').rating();
                }, 1000);
            });
        }
    });
}