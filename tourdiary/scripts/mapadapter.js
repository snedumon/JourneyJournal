var mymap;

function initMap(latitude, longitude) {
	mymap = L.map('mapid').setView([latitude, longitude], 11);
	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		minZoom: 1.5,
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);
	mymap.zoomControl.setPosition('topright').st;
}
function setNote(latitude, longitude, headline, photos, description){
	L.marker([latitude, longitude]).addTo(mymap)
		.bindPopup('<div class="note">'+headline+'<ul class="photos">'+
		photos+'</ul><div class="description">'+
		description+'</div></div><div class="detailsButton">Подробнее</div>');
}

// function onMapClick(e) {
// 	L.popup()
// 		.setLatLng(e.latlng)
// 		.setContent("Широта и долгота " + e.latlng.toString())
// 		.openOn(mymap);
// 	return e.latlng;
// }
// mymap.on('click', onMapClick);
