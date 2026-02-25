<!DOCTYPE html>
<html>
<head>
<title>PiyFind - Smart Nearby Explorer</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
    color:white;
    overflow-x:hidden;
    background:linear-gradient(-45deg,#1e3a8a,#2563eb,#3b82f6,#1d4ed8);
    background-size:400% 400%;
    animation:gradientMove 12s ease infinite;
}

@keyframes gradientMove{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

/* PARTICLE CANVAS */
#particles{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:-1;
}

/* NAVBAR */
.navbar{
    position:fixed;
    width:100%;
    top:0;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 60px;
    backdrop-filter:blur(15px);
    background:rgba(0,0,0,0.15);
    z-index:1000;
}

.nav-links a{
    margin-left:25px;
    text-decoration:none;
    color:white;
    font-weight:500;
    transition:.3s;
}

.nav-links a:hover{opacity:.7;}

/* 3D PARALLAX HERO */
.parallax{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    padding:140px 40px 80px;
    perspective:1000px;
}

.hero-content{
    max-width:700px;
    transform-style:preserve-3d;
    transition:transform .2s ease;
}

.hero-content h1{
    font-size:56px;
    margin-bottom:20px;
}

.hero-content p{
    font-size:18px;
    opacity:.9;
    margin-bottom:35px;
}

.btn-primary{
    padding:15px 40px;
    background:white;
    color:#1e3a8a;
    border-radius:50px;
    font-weight:600;
    text-decoration:none;
    transition:.3s;
}

.btn-primary:hover{
    transform:translateY(-4px);
    box-shadow:0 15px 30px rgba(0,0,0,.4);
}

/* WHITE SECTION */
.white-section{
    background:white;
    color:#111827;
    padding:120px 60px;
    text-align:center;
}

.stats{
    display:flex;
    justify-content:center;
    gap:80px;
    flex-wrap:wrap;
}

.stat h2{
    font-size:42px;
    margin-bottom:10px;
    color:#2563eb;
}

/* FEATURE SECTION */
section{
    padding:120px 60px;
    text-align:center;
}

.feature-grid{
    display:flex;
    justify-content:center;
    gap:40px;
    flex-wrap:wrap;
    margin-top:50px;
}

.feature-card{
    width:280px;
    padding:30px;
    border-radius:20px;
    background:white;
    color:#111827;   /* FIX TEXT COLOR */
    box-shadow:0 15px 40px rgba(0,0,0,.1);
    transition:.4s;
}

.feature-card h3{
    margin-bottom:10px;
    color:#1e3a8a;
}

.feature-card:hover{
    transform:translateY(-10px) scale(1.03);
}

/* FOOTER */
footer{
    background:#111827;
    padding:40px;
    text-align:center;
    font-size:13px;
}

/* REVEAL */
.reveal{
    opacity:0;
    transform:translateY(60px);
    transition:1s ease;
}
.reveal.active{
    opacity:1;
    transform:translateY(0);
}

@media(max-width:900px){
    .hero-content h1{font-size:42px;}
}
</style>
</head>

<body>

<canvas id="particles"></canvas>

<div class="navbar">
    <h2>🌍 PiyFind</h2>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/map">Map</a>
        <a href="/docs">Docs</a>
        <a href="/places/nearby?latitude=-6.2&longitude=106.8&type=hospital">API</a>
    </div>
</div>

<div class="parallax">
    <div class="hero-content reveal" id="hero3d">
        <h1>Explore Nearby Places Smarter</h1>
        <p>
            Discover hospitals, cafes, restaurants and more
            with precision, speed and modern experience.
        </p>
        <a href="/map" class="btn-primary">Mulai Sekarang 🚀</a>
    </div>
</div>

<section class="white-section reveal">
    <h2>Trusted & Growing</h2>
    <div class="stats">
        <div class="stat">
            <h2 id="counter1">0</h2>
            <p>Locations Indexed</p>
        </div>
        <div class="stat">
            <h2 id="counter2">0</h2>
            <p>API Calls</p>
        </div>
        <div class="stat">
            <h2 id="counter3">0</h2>
            <p>Supported Categories</p>
        </div>
    </div>
</section>

<section class="reveal">
    <h2>Powerful Capabilities</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <h3>📍 Real-time Location</h3>
            <p>Detect nearby places instantly using OpenStreetMap.</p>
        </div>
        <div class="feature-card">
            <h3>⚡ Fast Performance</h3>
            <p>Optimized backend built with Laravel.</p>
        </div>
        <div class="feature-card">
            <h3>🧭 Smart Navigation</h3>
            <p>One-click Google Maps routing integration.</p>
        </div>
    </div>
</section>

<footer>
© 2026 PiyFind — Smart Nearby Explorer
</footer>

<script>
/* SCROLL REVEAL */
function reveal(){
    document.querySelectorAll('.reveal').forEach(el=>{
        const top=el.getBoundingClientRect().top;
        if(top<window.innerHeight-100){el.classList.add('active');}
    });
}
window.addEventListener('scroll',reveal);
reveal();

/* COUNTER */
function animateCounter(id,target){
    let count=0;
    const speed=target/100;
    const interval=setInterval(()=>{
        count+=speed;
        if(count>=target){count=target;clearInterval(interval);}
        document.getElementById(id).innerText=Math.floor(count);
    },20);
}
animateCounter("counter1",12000);
animateCounter("counter2",850000);
animateCounter("counter3",7);

/* TRUE 3D PARALLAX MOUSE */
const hero=document.getElementById("hero3d");
document.addEventListener("mousemove",(e)=>{
    let x=(window.innerWidth/2-e.pageX)/40;
    let y=(window.innerHeight/2-e.pageY)/40;
    hero.style.transform=`rotateY(${x}deg) rotateX(${y}deg)`;
});

/* PARTICLE BACKGROUND */
const canvas=document.getElementById("particles");
const ctx=canvas.getContext("2d");
canvas.width=window.innerWidth;
canvas.height=window.innerHeight;

let particles=[];
for(let i=0;i<80;i++){
    particles.push({
        x:Math.random()*canvas.width,
        y:Math.random()*canvas.height,
        r:Math.random()*2,
        dx:(Math.random()-0.5),
        dy:(Math.random()-0.5)
    });
}

function draw(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    ctx.fillStyle="rgba(255,255,255,0.7)";
    particles.forEach(p=>{
        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fill();
        p.x+=p.dx;
        p.y+=p.dy;
        if(p.x<0||p.x>canvas.width)p.dx*=-1;
        if(p.y<0||p.y>canvas.height)p.dy*=-1;
    });
    requestAnimationFrame(draw);
}
draw();

window.addEventListener("resize",()=>{
    canvas.width=window.innerWidth;
    canvas.height=window.innerHeight;
});
</script>

</body>
</html>