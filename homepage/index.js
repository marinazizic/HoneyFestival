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

function showThankYou() {
    document.getElementById("thankYou").innerHTML = "Thank you!";
}

document.getElementById("img1").addEventListener("click", () => showModal(1));
document.getElementById("img2").addEventListener("click", () => showModal(2));
document.getElementById("img3").addEventListener("click", () => showModal(3));
document.getElementById("img4").addEventListener("click", () => showModal(4));
document.getElementById("send").addEventListener("click", showThankYou);

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

$(document).ready(function () {
    $("#accordion").accordion();
});

