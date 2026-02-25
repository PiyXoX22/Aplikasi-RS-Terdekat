<!DOCTYPE html>
<html>
<head>
<title>PiyFind API Documentation</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

html{scroll-behavior:smooth;}

body{
    background:#f8fafc;
    color:#111827;
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:270px;
    background:#111827;
    color:white;
    padding:30px 20px;
    position:fixed;
    height:100vh;
    overflow-y:auto;
}

.sidebar h2{
    margin-bottom:25px;
    font-size:20px;
}

.sidebar a{
    display:block;
    color:#9ca3af;
    text-decoration:none;
    margin-bottom:12px;
    font-size:14px;
    transition:.2s;
}

.sidebar a:hover,
.sidebar a.active{
    color:white;
}

/* CONTENT */
.content{
    margin-left:270px;
    padding:60px;
    max-width:1000px;
}

h1{margin-bottom:15px;}
h2{margin-top:60px;margin-bottom:15px;}
h3{margin-top:30px;margin-bottom:10px;}

.code-wrapper{
    position:relative;
    margin:15px 0;
}

.code{
    background:#1f2937;
    color:#e5e7eb;
    padding:18px;
    border-radius:12px;
    font-size:13px;
    overflow-x:auto;
}

.copy-btn{
    position:absolute;
    top:10px;
    right:10px;
    padding:6px 10px;
    font-size:12px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    background:#2563eb;
    color:white;
}

.table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

.table th,.table td{
    border:1px solid #e5e7eb;
    padding:10px;
    font-size:14px;
}

.badge{
    display:inline-block;
    padding:3px 8px;
    font-size:12px;
    border-radius:6px;
    font-weight:600;
}

.get{background:#10b981;color:white;}
.post{background:#3b82f6;color:white;}

.section{
    margin-bottom:40px;
}

.download-btn{
    padding:10px 15px;
    background:#16a34a;
    color:white;
    border-radius:8px;
    text-decoration:none;
    display:inline-block;
    margin-top:10px;
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>🌍 PiyFind API</h2>
    <a href="#overview">Overview</a>
    <a href="#versioning">Versioning</a>
    <a href="#nearby">GET /places/nearby</a>
    <a href="#route">POST /route</a>
    <a href="#schema">Response Schema</a>
    <a href="#curl">cURL Examples</a>
    <a href="#postman">Postman</a>
    <a href="#env">Environment Variables</a>
    <a href="#deployment">Deployment Guide</a>
    <a href="#performance">Performance & Scaling</a>
</div>

<div class="content">

<h1 id="overview">PiyFind API Documentation</h1>
<p>RESTful API for Nearby Search & Routing built with Laravel.</p>

<h2 id="versioning">API Versioning</h2>
<p>
Current Version: <strong>v1</strong><br>
Future endpoint format example:
</p>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">/api/v1/places/nearby</pre>
</div>

<p>Versioning ensures backward compatibility for future upgrades.</p>

<h2 id="nearby">
<span class="badge get">GET</span> /places/nearby
</h2>

<h3>Example Request</h3>
<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">GET /places/nearby?latitude=-6.2&longitude=106.8&type=hospital&radius=3000</pre>
</div>

<h3>Success Response</h3>
<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">{
  "status": true,
  "total": 3,
  "data": [
    {
      "name": "RS Jakarta",
      "type": "hospital",
      "address": "Jl. Sudirman",
      "latitude": -6.201,
      "longitude": 106.805,
      "distance_km": 1.2
    }
  ]
}</pre>
</div>

<h2 id="route">
<span class="badge post">POST</span> /route
</h2>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">POST /route
Content-Type: application/json

{
  "user_lat": -6.2,
  "user_lng": 106.8,
  "hospital_lat": -6.201,
  "hospital_lng": 106.805
}</pre>
</div>

<h2 id="schema">Response Schema</h2>

<h3>Nearby Response Schema</h3>
<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">{
  status: boolean,
  total: integer,
  data: [
    {
      name: string,
      type: string,
      address: string,
      latitude: float,
      longitude: float,
      distance_km: float
    }
  ]
}</pre>
</div>

<h3>Route Response Schema</h3>
<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">{
  status: boolean,
  distance_km: float,
  duration_min: float,
  geometry: string
}</pre>
</div>

<h2 id="curl">cURL Examples</h2>

<h3>Nearby</h3>
<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">curl -X GET "https://yourdomain.com/places/nearby?latitude=-6.2&longitude=106.8&type=cafe&radius=3000"</pre>
</div>

<h3>Route</h3>
<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">curl -X POST "https://yourdomain.com/route" \
-H "Content-Type: application/json" \
-d '{"user_lat":-6.2,"user_lng":106.8,"hospital_lat":-6.201,"hospital_lng":106.805}'</pre>
</div>

<h2 id="postman">Postman Collection</h2>

<p>Download ready-to-import Postman collection:</p>

<a class="download-btn" download="PiyFind.postman_collection.json"
href="data:application/json,{
  &quot;info&quot;:{&quot;name&quot;:&quot;PiyFind API&quot;,&quot;schema&quot;:&quot;https://schema.getpostman.com/json/collection/v2.1.0/collection.json&quot;},
  &quot;item&quot;:[
    {
      &quot;name&quot;:&quot;Nearby&quot;,
      &quot;request&quot;:{
        &quot;method&quot;:&quot;GET&quot;,
        &quot;url&quot;:&quot;https://yourdomain.com/places/nearby?latitude=-6.2&longitude=106.8&type=hospital&radius=3000&quot;
      }
    }
  ]
}">
Download Postman Collection
</a>

<!-- ================= ENVIRONMENT VARIABLES ================= -->

<h2 id="env">Environment Variables</h2>

<p>
Configure the following variables inside your <code>.env</code> file before deploying.
</p>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
APP_NAME=PiyFind
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

ORS_API_KEY=your_openrouteservice_api_key

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
</pre>
</div>

<table class="table">
<tr>
<th>Variable</th>
<th>Description</th>
</tr>
<tr>
<td>ORS_API_KEY</td>
<td>OpenRouteService API key for route calculation</td>
</tr>
<tr>
<td>APP_ENV</td>
<td>Set to production in live environment</td>
</tr>
<tr>
<td>APP_DEBUG</td>
<td>Must be false in production</td>
</tr>
<tr>
<td>CACHE_DRIVER</td>
<td>Use redis for better performance</td>
</tr>
</table>

<!-- ================= DEPLOYMENT GUIDE ================= -->

<h2 id="deployment">Deployment Guide</h2>

<h3>1️⃣ Install Dependencies</h3>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
composer install --optimize-autoloader --no-dev
</pre>
</div>

<h3>2️⃣ Optimize Laravel</h3>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
php artisan config:cache
php artisan route:cache
php artisan view:cache
</pre>
</div>

<h3>3️⃣ Production Settings</h3>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
APP_ENV=production
APP_DEBUG=false
</pre>
</div>

<h3>4️⃣ Docker Deployment (Optional)</h3>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
docker build -t piyfind .
docker run -p 8000:8000 piyfind
</pre>
</div>

<h3>Recommended Stack</h3>
<ul>
<li>Nginx</li>
<li>PHP 8.2+</li>
<li>Redis (for caching)</li>
<li>Supervisor</li>
<li>Docker (optional)</li>
</ul>

<!-- ================= PERFORMANCE & SCALING ================= -->

<h2 id="performance">Performance & Scaling</h2>

<h3>Performance Optimization</h3>

<ul>
<li>Enable route caching</li>
<li>Enable config caching</li>
<li>Limit Overpass results (max 20 items)</li>
<li>Use Redis for caching</li>
<li>Enable HTTP compression (gzip)</li>
</ul>

<h3>Enable Rate Limiting</h3>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/places/nearby', [HospitalController::class, 'nearby']);
});
</pre>
</div>

<h3>Scaling Architecture</h3>

<ul>
<li>Use Load Balancer</li>
<li>Separate API & Frontend server</li>
<li>Use Redis Cache</li>
<li>Queue heavy tasks</li>
<li>Monitor with Laravel Telescope</li>
</ul>

<h3>Production Scaling Flow</h3>

<div class="code-wrapper">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre class="code">
Client → Load Balancer → Nginx → Laravel API → Redis Cache
                                     ↓
                               Overpass API
                               OpenRouteService
</pre>
</div>

</div>

<script>

/* COPY BUTTON */
function copyCode(button){
    const code=button.nextElementSibling.innerText;
    navigator.clipboard.writeText(code);
    button.innerText="Copied!";
    setTimeout(()=>button.innerText="Copy",2000);
}

/* ACTIVE SIDEBAR */
const sections=document.querySelectorAll("h1,h2");
const navLinks=document.querySelectorAll(".sidebar a");

window.addEventListener("scroll",()=>{
    let current="";
    sections.forEach(section=>{
        const sectionTop=section.offsetTop-100;
        if(scrollY>=sectionTop){
            current=section.getAttribute("id");
        }
    });

    navLinks.forEach(link=>{
        link.classList.remove("active");
        if(link.getAttribute("href")==="#"+current){
            link.classList.add("active");
        }
    });
});
</script>

</body>
</html>