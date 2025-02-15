// Sidebar Functions
function openNav() {
    document.getElementById("sidebar").style.width = "100%";
}

function closeNav() {
    document.getElementById("sidebar").style.width = "0%";
}

// Modal Function
function showModal(id) {
    let modal = document.getElementById(`modal${id}`);
    let img = document.getElementById(`img${id}`);
    let modalImg = document.getElementById(`img0${id}`);
    let span = document.getElementsByClassName("close")[id - 1];

    if (modal && img && modalImg) {
        modal.style.display = "block";
        modalImg.src = img.src;

        span.onclick = function () {
            modal.style.display = "none";
        };
    }
}

// Thank You Message
function showThankYou() {
    document.getElementById("thankYou").innerHTML = "Thank you!";
}

// Scroll To Top Button
let button = document.getElementById("top_btn");

window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        button.classList.remove("hide");
        button.classList.add("show");
    } else {

        button.classList.remove("show");
        button.classList.add("hide");
    }
};

function goToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

// Event Listeners for Images & Button
document.getElementById("img1").addEventListener("click", () => showModal(1));
document.getElementById("img2").addEventListener("click", () => showModal(2));
document.getElementById("img3").addEventListener("click", () => showModal(3));
document.getElementById("img4").addEventListener("click", () => showModal(4));
document.getElementById("send").addEventListener("click", showThankYou);

// Countdown Timer
const countDownDate = new Date("Jul 3, 2025 07:00:00").getTime();

const countdownInterval = setInterval(() => {
    let now = new Date().getTime();
    let distance = countDownDate - now;

    if (distance < 0) {
        clearInterval(countdownInterval);
        document.getElementById("countdown").innerHTML = "See you next year!";
        return;
    }

    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("countdown").innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
}, 1000);

//JQuery

$(document).ready(function () {
    $("#accordion").accordion();
});