let pos;
let map;
let heatmap;
let bounds;
let infoWindow;
let currentInfoWindow;
let service;
let infoPane;
let col = 0;

//User preferences
//let userPreferences = ["aquarium", "amusement park", "clothes"];
let userPlaces = [];

let funPrefs = [];
entertainmentArray.forEach(e => {
    funPrefs.push(e.preference);
    });
    
let foodPrefs = [];
foodArray.forEach(e => {
    foodPrefs.push(e.preference);
    });

let allPrefs = [...foodPrefs.concat(funPrefs)]; 

//Map Initialization
function initMap() {
    // Initialize variables
    bounds = new google.maps.LatLngBounds();
    //Need to find a way to set radius bounds | google.maps.LatLng, 100;
    infoWindow = new google.maps.InfoWindow;
    currentInfoWindow = infoWindow;
    
    //Generic Sidebar Init
    infoPane = document.getElementById('panel');


// Try HTML5 geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            map = new google.maps.Map(document.getElementById('map'), {
                center: pos,
                zoom: 15
            });            
            bounds.extend(pos);

            infoWindow.setPosition(pos);
            infoWindow.setContent('Let\'s Go Loco');
            infoWindow.open(map);
            map.setCenter(pos);

            // Call Places Nearby Search on user's location
            getPrefPlaces(pos, foodPrefs, col);
            setTimeout(() => {
                col+=1;
                getPrefPlaces(pos, funPrefs, col);
                    setTimeout(() => {
                    col+=1;
                    let randomNum = randomEle(allPrefs);
                    getPrefPlaces(pos, allPrefs.splice(randomNum, randomNum+1), col);
                    }, 3000);
            }, 3000);
            

        }, () => {
            // Browser supports geolocation, but user has denied permission
            handleLocationError(true, infoWindow);
            });
    } else {
    // Browser doesn't support geolocation
    handleLocationError(false, infoWindow);
    }

}


function getPrefPlaces (pos, userPreferences, col) {
    userPreferences.forEach(prefVal => {setTimeout(() => {
        getNearbyPlaces(pos, prefVal, col);}, 1000);
    });
    //initHeatmap();
}

// Handle a geolocation error
function handleLocationError(browserHasGeolocation, infoWindow) {
    // Set default location to Sydney, Australia
    pos = {lat: -33.856, lng: 151.215};
    map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 15
    });

    // Display an InfoWindow at the map center
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
    'Geolocation permissions denied. Using default location.' :
    'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
    currentInfoWindow = infoWindow;

    // Call Places Nearby Search on the default location
    getPrefPlaces(pos, userPreferences);

}

// Perform a Places Nearby Search Request
function getNearbyPlaces(position, prefVal, col) {
    let request = {
    location: position,
    rankBy: google.maps.places.RankBy.DISTANCE,
    /*BELOW CAN BE MODIFIED FOR USER PREFERENCE*/
    keyword: prefVal
    };

    service = new google.maps.places.PlacesService(map);

    //
    
    service.nearbySearch(request, nearbyCallback);
    
    //service.nearbySearch(request, nearbyCallback);
}

// Handle the results (up to 20) of the Nearby Search
function nearbyCallback(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        createMarkers(results);
    }
}

// Set markers at the location of each place result
function createMarkers(places) {
    places.forEach(place => {
        let marker = new google.maps.Marker({ 
            position: place.geometry.location,
            map: map,
            title: place.name
            });
        userPlaces.push(marker);
        let request = {
            placeId: place.place_id,
            fields: ['name', 'formatted_address', 'geometry', 'rating',
            'website', 'photos']
        };
    

        menuInit(request);

        // Add click listener to each marker
        google.maps.event.addListener(marker, 'click', () => {
            let request = {
                placeId: place.place_id,
                fields: ['name', 'formatted_address', 'geometry', 'rating',
                'website', 'photos']
            };

            /* Only fetch the details of a place when the user clicks on a marker.
            * If we fetch the details for all place results as soon as we get
            * the search response, we will hit API rate limits. */
            service.getDetails(request, (placeResult, status) => {
                showDetails(placeResult, marker, status)
            });
        });

// Adjust the map bounds to include the location of this marker
        bounds.extend(place.geometry.location);
    });
    

    /* Once all the markers have been placed, adjust the bounds of the map to
    * show all the markers within the visible area. */
    map.fitBounds(bounds);
}

// Builds an InfoWindow to display details above the marker
function showDetails(placeResult, marker, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        let placeInfowindow = new google.maps.InfoWindow();
        let photoURL = "https://img.icons8.com/cotton/2x/party-baloons.png";
        if (placeResult.photos != null) {
            photoURL = placeResult.photos[0].getUrl();
        }
        //TODO: Adjust image width and height, handle errors if there is no image available
        placeInfowindow.setContent('<img class="placeInfoWindowImg" src=\"' + photoURL + '\"></img><div><strong>' + placeResult.name +
        '</strong><br>' + 'Rating: ' + placeResult.rating + '</div>');
        placeInfowindow.open(marker.map, marker);
        currentInfoWindow.close();
        currentInfoWindow = placeInfowindow;
        //showPanel(placeResult);
    } else {
    }
}



function menuInit(request){
    service.getDetails(request, (placeResult, status) => {
        if (col == 0) {
            generateCard(placeResult, "foodCards");
        }
        else if (col == 1) {
            generateCard(placeResult, "funCards");                    
        }
        else {
            generateCard(placeResult, "randomCards");
        }
    
    });
}

//entertainCards
function generateCard(placeResult, column) {
    if(placeResult == null) {
        console.log("Null response from API");
        return;
    }
    let photoURL = "https://img.icons8.com/cotton/2x/party-baloons.png";
    let name = placeResult.name;
    let rating = "No Rating";
    let website = "<p>No Website</p>";
    let address = "No Address";
    if (placeResult.photos != null) {
        photoURL = placeResult.photos[0].getUrl();
    }
    if (placeResult.rating != null) {
        rating = placeResult.rating;
    }
    if (placeResult.website) {
        website = `<a href="${placeResult.website}"><i class="fas fa-globe"> <span style="font-family: 'Nunito', sans-serif">Website</span></i></a>`;
    }
    if(placeResult.formatted_address != null){
        address = placeResult.formatted_address;
    }
    document.getElementById(column).innerHTML +=
    `<div class="card">
    <div class="card-image">
    <figure class="image is-4by3">
        <img src=${photoURL} alt="Placeholder image">
    </figure>
    </div>
    <div class="card-content">
    <div class="media">
        <div class="media-content">
        <p class="title is-4">${name}</p>
        </div>
    </div>

    <div class="content">
    <p>Rating: ${rating}</p>
    <p>${address}</p>
         ${website}
        <br>
    </div>
    </div>
    </div>`;

}  


//Randomize Button
function randomEle(arr){
    const randomElement = Math.floor(Math.random() * arr.length); //Select random location.
    return randomElement;
}
