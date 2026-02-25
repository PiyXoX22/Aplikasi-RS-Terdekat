<!DOCTYPE html>
<html>
<head>
<title>PiyFind - Smart Nearby Finder</title>

<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    height:100vh;
    overflow:hidden;
}

/* ===== CONTAINER ===== */
.container{
    display:flex;
    height:100vh;
}

/* ===== SIDEBAR ===== */
.sidebar{
    width:380px;
    background:white;
    display:flex;
    flex-direction:column;
    padding:30px;
    box-shadow:0 10px 40px rgba(0,0,0,0.06);
    position:relative;
    z-index:10;
}

/* BRAND */
.brand{
    margin-bottom:30px;
}

.brand h1{
    font-size:24px;
    font-weight:700;
    background:linear-gradient(135deg,#2563eb,#4f46e5);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.brand span{
    font-size:13px;
    color:#6b7280;
}

/* CONTROLS */
.controls select{
    width:100%;
    padding:12px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    margin-bottom:15px;
    font-size:14px;
    transition:.2s;
}

.controls select:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.15);
}

.controls button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

.controls button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(37,99,235,0.3);
}

/* RESULTS */
.results{
    margin-top:25px;
    overflow-y:auto;
    flex:1;
    padding-right:5px;
}

/* Custom Scroll */
.results::-webkit-scrollbar{
    width:6px;
}
.results::-webkit-scrollbar-thumb{
    background:#d1d5db;
    border-radius:10px;
}

/* RESULT CARD */
.result-card{
    padding:15px;
    border-radius:15px;
    background:#f9fafb;
    margin-bottom:15px;
    cursor:pointer;
    transition:.3s;
    border:1px solid #eef2f7;
}

.result-card:hover{
    background:white;
    transform:translateY(-3px);
    box-shadow:0 10px 20px rgba(0,0,0,0.08);
}

.result-card strong{
    font-size:15px;
    color:#111827;
}

.result-card small{
    color:#6b7280;
    font-size:12px;
}

/* MAP */
#map{
    flex:1;
}

/* POPUP */
.leaflet-popup-content-wrapper{
    border-radius:18px;
    padding:8px;
}

.popup-title{
    font-weight:600;
    font-size:15px;
    margin-bottom:6px;
}

.popup-distance{
    font-size:13px;
    color:#6b7280;
}

.popup-btn{
    display:inline-block;
    margin-top:10px;
    padding:7px 12px;
    background:#10b981;
    color:white;
    border-radius:10px;
    font-size:12px;
    text-decoration:none;
    transition:.2s;
}

.popup-btn:hover{
    background:#059669;
}

/* LOADING */
.loading{
    text-align:center;
    margin-top:20px;
    font-size:14px;
    color:#6b7280;
}
</style>
</head>

<body>

<div class="container">

<div class="sidebar">

    <div class="brand">
        <h1>🌍 PiyFind</h1>
        <span>Smart Nearby Explorer</span>
    </div>

    <div class="controls">
        <select id="category">
            <option value="hospital">🏥 Hospital</option>
            <option value="restaurant">🍽 Restaurant</option>
            <option value="cafe">☕ Cafe</option>
            <option value="fuel">⛽ Fuel</option>
            <option value="pharmacy">💊 Pharmacy</option>
        </select>

        <select id="radius">
            <option value="1000">1 km</option>
            <option value="3000" selected>3 km</option>
            <option value="5000">5 km</option>
            <option value="10000">10 km</option>
            <option value="20000">20 km</option>
        </select>

        <button onclick="loadPlaces()">Cari Sekarang</button>
    </div>

    <div class="results" id="results"></div>

</div>

<div id="map"></div>

</div>

<script>

let map = L.map('map').setView([-6.2, 106.8], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution:'&copy; OpenStreetMap'
}).addTo(map);

let markers=[];

function clearMarkers(){
    markers.forEach(m=>map.removeLayer(m));
    markers=[];
}

function loadPlaces(){

    const results=document.getElementById("results");
    results.innerHTML="<div class='loading'>Mencari lokasi...</div>";

    navigator.geolocation.getCurrentPosition(function(position){

        let lat=position.coords.latitude;
        let lng=position.coords.longitude;
        let type=document.getElementById("category").value;
        let radius=document.getElementById("radius").value;

        fetch(`/places/nearby?latitude=${lat}&longitude=${lng}&type=${type}&radius=${radius}`)
        .then(res=>res.json())
        .then(data=>{

            clearMarkers();
            results.innerHTML="";
            map.setView([lat,lng],14);

            data.data.forEach(place=>{

                let marker=L.marker([place.latitude,place.longitude])
                .addTo(map)
                .bindPopup(`
                    <div class="popup-title">${place.name}</div>
                    <div class="popup-distance">${place.distance_km} km dari kamu</div>
                    <a class="popup-btn" target="_blank"
                    href="https://www.google.com/maps/dir/?api=1&destination=${place.latitude},${place.longitude}">
                    Navigasi
                    </a>
                `);

                markers.push(marker);

                let card=document.createElement("div");
                card.className="result-card";
                card.innerHTML=`
                    <strong>${place.name}</strong><br>
                    <small>${place.distance_km} km dari kamu</small>
                `;

                card.onclick=()=>{
                    map.setView([place.latitude,place.longitude],16);
                    marker.openPopup();
                };

                results.appendChild(card);
            });

        });

    },function(){
        results.innerHTML="<div class='loading'>Izinkan akses lokasi.</div>";
    });
}

</script>

</body>
</html>