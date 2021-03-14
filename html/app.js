let pos;
let map;
let heatmap;
let bounds;
let infoWindow;
let currentInfoWindow;
let service;
let infoPane;
var col = 0;


//User preferences
//let userPreferences = ["aquarium", "amusement park", "clothes"];
let userPlaces = [];

let foodPrefs = ["sushi"];
let foodResults;
let funPrefs = ["aquarium"];
let funResults;




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
            getPrefPlaces(pos, funPrefs, col);
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
    userPreferences.forEach(prefVal => {
        getNearbyPlaces(pos, prefVal, col);
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
        console.log("Callback made" + col);
        col+=1;
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
        
        
        //setTimeout(() => {
        service.getDetails(request, (placeResult, status) => {
            console.log("Pregen column number:" + col);
            if (col == 0) {
                console.log("Case A:" + col);
                generateCard(placeResult, "foodCards");
            }
            else if (col == 1) {
                //console.log("Case B:" + col);
                generateCard(placeResult, "funCards");                    
            }
            else {
                //console.log("Case C:" + col);
                generateCard(placeResult, "randomCards");
            }
        
        });
        //}, 500);

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
        console.log('showDetails failed: ' + status);
    }
}

//entertainCards
function generateCard(placeResult, column) {
    if(placeResult == null) {
        return;
    }
    let photoURL = "https://img.icons8.com/cotton/2x/party-baloons.png";
    let name = placeResult.name;
    let rating = "No Rating";
    let website = "No Website";
    let address = "No Address";
    if (placeResult.photos != null) {
        photoURL = placeResult.photos[0].getUrl();
    }
    if (placeResult.rating != null) {
        rating = placeResult.rating;
    }
    if (placeResult.website) {
        website = placeResult.website;
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
         <a href="${website}">${website}</a>
        <br>
    </div>
    </div>
    </div>`;

}  


    //Randomize Button
function randomSelection(arr){
    const randomElement = Math.floor(Math.random() * arr.length); //Select random location.
    return arr[randomElement];
}
/*


Variables
Picture
Title
Description
URL

let photoURL = "https://img.icons8.com/cotton/2x/party-baloons.png";
let name = placeResult.name;
let rating = "No Rating";
let website = "No Website";
if (placeResult.photos != null) {
    photoURL = placeResult.photos[0].getUrl();
}
if (placeResult.rating != null) {
    rating = placeResult.rating;
}
if (placeResult.website) {
    website = placeResult.website;
}



            <div class="card">
                <div class="card-image">
                  <figure class="image is-4by3">
                    <img src="../../../assets/rest1.jpg" alt="Placeholder image">
                  </figure>
                </div>
                <div class="card-content">
                  <div class="media">
                    <div class="media-content">
                      <p class="title is-4">Test Restaurant</p>
                    </div>
                  </div>
              
                  <div class="content">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                    <a href="#">#css</a> <a href="#">#responsive</a>
                    <br>
                    <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                  </div>
                </div>
              </div>
              */








/* Displays place details in a sidebar
function showPanel(placeResult) {
    // If infoPane is already open, close it
        if (infoPane.classList.contains("open")) {
        infoPane.classList.remove("open");
    }

    // Clear the previous details
    while (infoPane.lastChild) {
        infoPane.removeChild(infoPane.lastChild);
    }

    // Add the primary photo, if there is one
    if (placeResult.photos != null) {
        let firstPhoto = placeResult.photos[0];
        let photo = document.createElement('img');
        photo.classList.add('hero');
        photo.src = firstPhoto.getUrl();
        infoPane.appendChild(photo);
    }


    // Add place details with text formatting
    let name = document.createElement('h1');
    name.classList.add('place');
    name.textContent = placeResult.name;
    infoPane.appendChild(name);
    if (placeResult.rating != null) {
        let rating = document.createElement('p');
        rating.classList.add('details');
        rating.textContent = `Rating: ${placeResult.rating} \u272e`;
        infoPane.appendChild(rating);
    }
    let address = document.createElement('p');
    address.classList.add('details');
    address.textContent = placeResult.formatted_address;
    infoPane.appendChild(address);
    if (placeResult.website) {
        let websitePara = document.createElement('p');
        let websiteLink = document.createElement('a');
        let websiteUrl = document.createTextNode(placeResult.website);
        websiteLink.appendChild(websiteUrl);
        websiteLink.title = placeResult.website;
        websiteLink.href = placeResult.website;
        websitePara.appendChild(websiteLink);
        infoPane.appendChild(websitePara);
    }

    // Open the infoPane
    infoPane.classList.add("open");
}

// HeatMap Options
function toggleHeatmap() {
    heatmap.setMap(heatmap.getMap() ? null : map);
  }

function changeGradient() {
    const gradient = [
        "rgba(0, 255, 255, 0)",
        "rgba(0, 255, 255, 1)",
        "rgba(0, 191, 255, 1)",
        "rgba(0, 127, 255, 1)",
        "rgba(0, 63, 255, 1)",
        "rgba(0, 0, 255, 1)",
        "rgba(0, 0, 223, 1)",
        "rgba(0, 0, 191, 1)",
        "rgba(0, 0, 159, 1)",
        "rgba(0, 0, 127, 1)",
        "rgba(63, 0, 91, 1)",
        "rgba(127, 0, 63, 1)",
        "rgba(191, 0, 31, 1)",
        "rgba(255, 0, 0, 1)",
    ];
    heatmap.set("gradient", heatmap.get("gradient") ? null : gradient);
}

function changeRadius() {
    heatmap.set("radius", heatmap.get("radius") ? null : 20);
}
  
function changeOpacity() {
    heatmap.set("opacity", heatmap.get("opacity") ? null : 0.2);
}

function initHeatmap() {
    //Heatmap Init
            heatmap = new google.maps.visualization.HeatmapLayer({
                data: placesLatLng,
                map: map,
              });
}
*/