var openNav;
var closeNav;
$(document).ready(function () {
    openNav = function () {
        document.getElementById("sidebar").style.width = "100%";
    }

    closeNav = function () {
        document.getElementById("sidebar").style.width = "0%";
    }
});

function showModal(id) {
    var modalId = "modal" + id;
    var modal = document.getElementById(modalId);
    var imgId = "img" + id
    var img = document.getElementById(imgId);
    var imageId = "img0" + id;
    var modalImg = document.getElementById(imageId);
    modal.style.display = "block";
    modalImg.src = img.src;

    var span = document.getElementsByClassName("close")[id - 1];

    span.onclick = function () {
        modal.style.display = "none";
    }
}

function showThankYou() {
    document.getElementById("thankYou").innerHTML = "Thank you!";
}

let button = document.getElementById("top_btn");
let header = document.getElementsByClassName("header")[0];
window.onscroll = function () { scrollFunction() };

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        button.classList.remove("hide");
        button.classList.add("show");
    }
    else {
        button.classList.remove("show");
        button.classList.add("hide");
    }
}

function goToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

document.getElementById("img1").addEventListener("click", function () { showModal(1); });
document.getElementById("img2").addEventListener("click", function () { showModal(2); });
document.getElementById("img3").addEventListener("click", function () { showModal(3); });
document.getElementById("img4").addEventListener("click", function () { showModal(4); });

document.getElementById("send").addEventListener("click", function () { showThankYou(); });


$( function() {
    $( "#accordion" ).accordion();
  } );