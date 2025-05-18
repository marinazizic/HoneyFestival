// Sidebar Functions
function openNav() {
    document.getElementById("sidebar").style.width = "100%";
}

function closeNav() {
    document.getElementById("sidebar").style.width = "0%";
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


