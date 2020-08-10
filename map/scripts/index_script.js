var map;
var geocoder;
var restaurantsList = [];

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
            }];

            // call info.php to get restaurants in the city or country
            downloadUrl(window.location.origin + '/map/info.php', query, function(data) {
                var xml = data.responseXML;
                var restaurants = xml.documentElement.getElementsByTagName('restaurant');

                // convert xml results to an array of objects
                Array.prototype.forEach.call(restaurants, function(restaurant) {
                    restaurantsList.push({
                        id: restaurant.getAttribute('id'),
                        name: restaurant.getAttribute('name'),
                        city: restaurant.getAttribute('city'),
                        foodtype: restaurant.getAttribute('foodType'),
                        url: restaurant.getAttribute('url'),
                        lat: restaurant.getAttribute('url').match(/\@(-?[\d\.]*)/)[1], // get latitude value from place url
                        lng: restaurant.getAttribute('url').match(/\@-?[\d\.]*\,([-?\d\.]*)/)[1] // get longitude value from place url
                    });
                });

                // iterate the restaurants array
                restaurantsList.forEach(function(restaurant, index) {
                    var location = {
                        lat: parseFloat(restaurant.lat),
                        lng: parseFloat(restaurant.lng)
                    };

                    // find the current restaurant using text search in places API
                    var service2 = new google.maps.places.PlacesService(map);
                    service2.textSearch({
                        location: location,
                        radius: 10,
                        query: restaurant.name
                    }, function(restaurantResults, restaurantStatus) { // use only the first result and ignore the rest
                        console.log(restaurantResults);
                        if(restaurantStatus == google.maps.places.PlacesServiceStatus.OK) {

                            // prepare represntation of price range
                            var price = '';
                            for(var i = 0; i < restaurantResults[0].price_level; i++) { 
                                price += '$';
                            }

                            // add the restaurant to the template with data got from database
                            $('.restaurant-grid-wrapper > div > div').html($('.restaurant-grid-wrapper > div > div').html() + `
                                <div class="GridLex-col-3_sm-4_xs-6_xss-12">
                                    <div class="restaurant-grid-item">
                                        <a href="restaurant-info.php?restaurant=${restaurant.id}">
                                            <div class="image">
                                                <img src="${restaurantResults[0].photos[0].getUrl()}" alt="Image"/>
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
                                                    Cuisine: ${restaurant.foodtype.split(',').map(type => {
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


