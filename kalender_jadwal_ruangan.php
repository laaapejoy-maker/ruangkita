<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>RuangKita - Kalender</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    height:100vh;
    display:flex;
    overflow:hidden;
    background:linear-gradient(135deg,#0a1f44 0%, #ff7a00 45%, #ffffff 100%);
}

body::before{
    content:"";
    position:fixed;
    inset:0;
    background:
        radial-gradient(circle at 20% 20%, rgba(255,122,0,0.25), transparent 40%),
        radial-gradient(circle at 80% 30%, rgba(10,31,68,0.35), transparent 45%),
        radial-gradient(circle at 50% 80%, rgba(255,255,255,0.4), transparent 50%);
    z-index:0;
}

.sidebar{
    width:240px;
    padding:25px 20px;
    display:flex;
    flex-direction:column;
    position:relative;
    z-index:1;
    color:white;
    overflow:hidden;

    background:linear-gradient(135deg,#0a1f44 0%, #1b3a73 40%, #ff7a00 120%);
    background-size:200% 200%;
    animation:sidebarFlow 9s ease infinite;

    border-right:1px solid rgba(255,255,255,0.15);
}

@keyframes sidebarFlow{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

.sidebar::before{
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.25);
    backdrop-filter:blur(10px);
    z-index:0;
}

.sidebar *{
    position:relative;
    z-index:1;
}

.logo{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:22px;
    font-weight:700;
    margin-bottom:25px;
    letter-spacing:1px;
}

.logo img{
    width:34px;
    height:34px;
    object-fit:contain;
    border-radius:6px;
}

.nav a{
    display:block;
    padding:12px;
    margin-bottom:10px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    font-size:14px;
    background:rgba(255,255,255,0.08);
    transition:0.25s;
}

.nav a:hover{
    background:rgba(255,255,255,0.2);
    transform:translateX(5px);
}

.main{
    flex:1;
    display:flex;
    flex-direction:column;
}


.header{
    height:65px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 25px;

    background:linear-gradient(90deg,#0a1f44,#ff7a00,#ffffff);
    background-size:200% 200%;
    animation:glowMove 8s ease infinite;

    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    position:relative;
}

@keyframes glowMove{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

.title{
    font-size:18px;
    font-weight:700;
    color:white;
}

.clock{
    font-size:13px;
    color:white;
}

.calendar-wrapper{
    flex:1;
    padding:15px 20px;
}

#calendar{
    height:100%;
    background:#ffffff;
    border-radius:16px;
    padding:12px;
}

.tooltip{
    position:absolute;
    background:#111;
    color:white;
    padding:6px 10px;
    font-size:12px;
    border-radius:6px;
    display:none;
}

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    display:none;
    justify-content:center;
    align-items:center;
}

.modal-box{
    background:white;
    padding:20px;
    border-radius:12px;
    width:320px;
}
</style>
</head>

<body>

<div class="sidebar">
    <div class="logo">
        <img src="img/logo.png" alt="Logo RuangKita">
        <span>RuangKita</span>
    </div>

    <div class="nav">
        <a href="index.php">📋 Booking Ruangan</a>
        <a href="kalender.php">📅 Jadwal Kalender</a>
        <a href="#">📊 Statistik</a>
    </div>
</div>

<div class="main">
    <div class="header">
        <div class="title">Jadwal Ruangan</div>
        <div class="clock" id="clock"></div>
    </div>

    <div class="calendar-wrapper">
        <div id="calendar"></div>
    </div>
</div>

<div class="tooltip" id="tooltip"></div>

<div class="modal" id="modal">
    <div class="modal-box">
        <span onclick="tutupModal()">✖</span>
        <h3>Detail Booking</h3>
        <p id="detail"></p>
    </div>
</div>

<script>
setInterval(()=>{
    document.getElementById("clock").innerHTML =
        new Date().toLocaleString();
},1000);

function bukaModal(text){
    document.getElementById("detail").innerText = text;
    document.getElementById("modal").style.display="flex";
}

function tutupModal(){
    document.getElementById("modal").style.display="none";
}

let tooltip = document.getElementById("tooltip");

document.addEventListener('DOMContentLoaded', function () {

    let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {

        initialView: 'dayGridMonth',
        height: '100%',
        events: 'get_events.php',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        eventDidMount: function(info){
            info.el.addEventListener("mouseenter", function(){
                tooltip.style.display = "block";
                tooltip.innerHTML = info.event.title;
            });

            info.el.addEventListener("mousemove", function(e){
                tooltip.style.top = e.pageY + 10 + "px";
                tooltip.style.left = e.pageX + 10 + "px";
            });

            info.el.addEventListener("mouseleave", function(){
                tooltip.style.display = "none";
            });
        },

        eventClick: function(info){
            let text =
                "📌 " + info.event.title +
                "\n\n🕒 Mulai: " + info.event.start.toLocaleString() +
                "\n🕒 Selesai: " + info.event.end.toLocaleString();

            bukaModal(text);
        },

        dateClick: function(info){
            window.location.href = "index.php?date=" + info.dateStr;
        }

    });

    calendar.render();
});
</script>

</body>
</html>