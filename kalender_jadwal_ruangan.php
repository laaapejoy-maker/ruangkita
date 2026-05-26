<?php 
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "koneksi.php"; 

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>RuangKita - Kalender</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

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
    background:
    linear-gradient(
        180deg,
        #f8fbff,
        #eef4ff
    );
}

.sidebar{
    width:295px;
    background:#ffffff;
    padding:24px 18px;
    border-right:1px solid #e5e7eb;
    position:fixed;
    left:0;
    top:0;
    height:100vh;
    z-index:999;
    transition:0.3s ease;
    overflow-y:auto;
    transform:translateX(0);
}

.sidebar.closed{
    transform:translateX(-100%);
}

.sidebar-toggle{
    position:fixed;
    top:18px;
    left:18px;
    z-index:1001;
    width:52px;
    height:52px;
    border:none;
    border-radius:18px;
    background:linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
    color:white;
    font-size:26px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:
      0 12px 28px rgba(37,99,235,0.25);
}

.sidebar-logo{
    display:flex;
    align-items:flex-start;
    gap:14px;
    margin-bottom:28px;
    padding-left:55px;
}

.sidebar-logo img{
    width:48px;
    height:48px;
    object-fit:contain;
    border-radius:14px;
    position:relative;
    top:-5px;
}

.sidebar-logo h2{
    font-size:20px;
    color:#111827;
    margin:0;
    font-weight:800;
}

.sidebar-logo p{
    font-size:13px;
    color:#6b7280;
    margin-top:0;
}

.profile-box{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    padding:18px;
    border-radius:20px;
    margin-bottom:30px;
}

.profile-name{
    font-size:15px;
    font-weight:700;
    color:#111827;
}

.profile-email{
    font-size:13px;
    color:#6b7280;
    margin-top:5px;
    word-break:break-word;
}

.sidebar-menu{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.sidebar-menu a{
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:14px;
    padding:15px 17px;
    border-radius:18px;
    color:#374151;
    font-weight:600;
    font-size:15px;
    transition:all 0.25s ease;
}

.sidebar-menu a:hover{
    transform:translateX(5px);
    color:#2563eb;
}

.sidebar-menu a.active{
    background:linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
    color:white;
    box-shadow:
      0 12px 28px rgba(37,99,235,0.28);
}

.sidebar-menu a i{
    font-size:22px;
}

.sidebar-bottom{
    margin-top:20px;
    padding-top:20px;
    border-top:1px solid #e5e7eb;
}

.sidebar-bottom .sidebar-menu a{
    color:#dc2626;
}

.main{
    flex:1;
    display:flex;
    flex-direction:column;
    margin-left:295px;
    position:relative;
    z-index:1;
    transition:0.3s ease;
}

.main.full{
    margin-left:0;
}

.header{
    height:70px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 25px;

    background:white;

    border-bottom:1px solid #dbeafe;

    box-shadow:
    0 4px 20px rgba(37,99,235,0.08);

    position:relative;
}

@keyframes glowMove{
    0%{background-position:0% 50%;}
    50%{background-position:100% 50%;}
    100%{background-position:0% 50%;}
}

.title{
    font-size:20px;
    font-weight:800;
    color:#1e3a8a;
}

.clock{
    font-size:13px;
    color:#64748b;
    font-weight:600;
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
    z-index:9999;
}

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:9999;
}

.modal-box{
    background:white;
    padding:20px;
    border-radius:12px;
    width:320px;
}

.modal-box span{
    cursor:pointer;
    float:right;
}

@media(max-width:900px){

    .sidebar{
        width:280px;
    }

    .main{
        margin-left:0;
    }

}

</style>
</head>

<body>

<button class="sidebar-toggle"
        onclick="toggleSidebar()">
  ☰
</button>

<div class="sidebar" id="sidebar">

    <div class="sidebar-logo">

        <img src="img/logo.png" alt="Logo">

        <div>
            <h2>RuangKita</h2>
            <p>User Panel</p>
        </div>

    </div>

    <div class="profile-box">

        <div class="profile-name">
            <?= $_SESSION['nama']; ?>
        </div>

        <div class="profile-email">
            <?= $_SESSION['email'] ?? ''; ?>
        </div>

    </div>

    <div class="sidebar-menu">

        <a href="user_dashboard.php">
            <i class="ri-dashboard-3-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="memilih_ruangan.php">
            <i class="ri-calendar-schedule-fill"></i>
            <span>Booking Ruangan</span>
        </a>

        <a href="kalender.php" class="active">
            <i class="ri-calendar-check-fill"></i>
            <span>Kalender</span>
        </a>

    </div>

    <div class="sidebar-bottom">

        <div class="sidebar-menu">

            <a href="logout.php">
                <i class="ri-logout-circle-r-fill"></i>
                <span>Logout</span>
            </a>

        </div>

    </div>

</div>

<div class="main" id="main">

    <div class="header">

        <div class="title">
            Jadwal Ruangan
        </div>

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

const sidebar = document.getElementById('sidebar');
const main = document.getElementById('main');

function toggleSidebar(){

    sidebar.classList.toggle('closed');
    main.classList.toggle('full');

}

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

            window.location.href =
            "user_dashboard.php?date=" + info.dateStr;

        }

    });

    calendar.render();

});

</script>

</body>
</html>