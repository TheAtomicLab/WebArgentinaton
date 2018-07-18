<?php
	include_once('include/header.php');
?>
<link rel="stylesheet" href="leaflet/leaflet.css">
<link rel="stylesheet" href="leaflet/leaflet-routing-machine.css">
<script src="leaflet/leaflet.js"></script>
<script src="leaflet/leaflet-routing-machine.js"></script>

<style media="screen">
	#mainMap{
		width: 100vw;
		height: 30vw;
	}
	.listaEventos{
		position: absolute;
		height: 100%;
		width: 300px;
	}
</style>
<div class="containerMap">
	<div class="listaEventos">

	</div>
	<div class="" id="mainMap"></div>
</div>

<script type="text/javascript">
	let eventos = {
		1234565: {
			type: 'entrega',
			date: 1525449989,
			latLan: [51.5, -0.09],
			data: {
				img: 'nene001.jpg',
				desc: 'Hoy entregamos una mano felizmente en londres. Gino no deja de roncar por las noches.'
			}
		},
		2146464: {
			type: 'entrega',
			date: 1525489989,
			latLan: [51.53, -0.091],
		},
		4456464: {
			type: 'entrega',
			date: 1525499989,
			latLan: [51.525, -0.2],
		},
	}

	let tokenMap = 'pk.eyJ1IjoibWF0aWFzZ2YiLCJhIjoiY2pnc205Mmo2MDJsdzMzbjI2dHVsZnNhYSJ9.GHBkK7xLQz1C6DYDmQqXTw';
	let mapAtom = L.map('mainMap').setView([51.5, -0.09],12);
	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+tokenMap, {
		attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
		maxZoom: 18,
		id: 'mapbox.streets',
		accessToken: tokenMap
	}).addTo(mapAtom);

	//icons
	let iconoAtomico = L.icon({
    iconUrl: 'public/img/icons/geoAtomic.svg',
		shadowUrl: 'public/img/icons/sombrageoAtomic.png',
    iconSize:     [64, 64],
		shadowSize:   [64, 64],
		iconAnchor:   [32, 64],
    shadowAnchor: [32, 64],
	});

	let ruta = L.Routing.control({
  	waypoints: [],
  	routeWhileDragging: false,
		draggableWaypoints: false,
		addWaypoints: false,
		createMarker: false,
		lineOptions: {
			styles: [
				{color: '#f27329', opacity: 1, weight: 4},
			]
		},
  	// geocoder: L.Control.Geocoder.nominatim()
	});
	let nodosRuta = [];

	//eventos
	$.each(eventos, function(k,o){
		eventos[k].obj = {};
		eventos[k].obj = L.marker(o.latLan).addTo(mapAtom);
		eventos[k].obj.id = k;
		eventos[k].obj.on('click', markerclick);
		if(o.type=='entrega'){
			eventos[k].obj.setIcon(iconoAtomico);
		}
		nodosRuta.push(L.latLng(o.latLan[0], o.latLan[1]));
	});
	ruta.setWaypoints(nodosRuta);
	ruta.addTo(mapAtom);
	ruta.route();
	console.log(ruta);

	function markerclick(e){
		createPopup(eventos[this.id]);
		console.log(this.id);
	}

	function createPopup(obj){
		let html = '<div class="mainPop">';
		let hasHeadImg = '';
		let headerUrl = false;
		if('data' in obj){
			if('img' in obj.data && obj.data != ''){
				hasHeadImg = ' hasHeaderImg';
				headerUrl = 'public/img/gallery/'+obj.data.img;
			}
		}
		html+='<div class="popHeader'+hasHeadImg+'">';
		if(obj.type=='entrega'){
			html+='<h2>Entrega</h2>';
		}
		if(headerUrl){
			html+='<div class="fondoHead" style="background:url(\''+headerUrl+'\')"></div>';
		}
		html+='</div>';//end header
			html+='<div class="popContent">';
			if('data' in obj && 'desc' in obj.data && obj.data.desc!=''){
				html+='<p class="descripcion">'+obj.data.desc+'</p>';
			}
			html+='</div>';//end content
		html+='</div>';//end main
		// $('body').append(html);
		console.log(obj.latLan);
		let pooop = L.popup()
		.setLatLng(obj.latLan)
		.setContent(html)
		.openOn(mapAtom);
	}
</script>


<?php
	include_once('include/footer.php');
?>
