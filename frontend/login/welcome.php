<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require "db.php";

$username = $_SESSION["username"];

$stmt = $conn->prepare("SELECT f_name, l_name, gender, email FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($f_name, $l_name, $gender, $email);
    $stmt->fetch();
} else {
    header("Location: login.php");
    exit();
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honey Festival</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="../universal.css">
    <link rel="stylesheet" href="./login.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/site.webmanifest">
</head>

<body>
    <div class="container">
        <div class="tg">
            <img src="../assets/cover-img.jpg" alt="Festival image" id="bg-img">
            <button onclick="goToTop()" id="top_btn"><img src="../assets/arrow.png" alt="Arrow"></button>
            <div class="login-coverpage">
                <div id="sidebar" class="overlay">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <div class="overlay-content">
                        <div>
                            <p>01</p>
                            <a href="../homepage/index.html">Home</a>
                        </div>
                        <div>
                            <p>02</p>
                            <a href="#">Schedule</a>
                        </div>

                        <div>
                            <p>03</p>
                            <a href="../facilities/facilities.html">Facilites</a>
                        </div>

                        <div>
                            <p>04</p>
                            <a href="#">Tickets</a>
                        </div>
                        <div>
                            <p>05</p>
                            <a href="#">Merch</a>
                        </div>

                        <div>
                            <p>06</p>
                            <a href="#">Gallery</a>
                        </div>
                    </div>
                </div>

                <div class="header">
                    <span onclick="openNav()" class="open"><img src="../assets/hamburger-menu.svg" alt="Menu"
                            id="menu-img"></span>
                    <div class="accountbtn">
                        <a href="../cart/cart.php">
                            <img src="../assets/cart.ico" alt="Shopping cart">
                        </a>
                        <a href="welcome.php">
                            <img src="../assets/user-icon.png" alt="User icon">
                        </a>
                    </div>
                </div>

            </div>
            <div class="login">
                <div class="login-content">
                    <div class="dashboard">
                        <div class="dashboard-info">
                            <h3 class="small-headings">welcome to your account</h3>
                            <h1 class="main-headings">Dashboard</h1>
                        </div>
                        <div id="tabs">
                            <ul>
                                <div class="short-info">
                                    <img src="../assets/user-icon.png" alt="User icon" id="user-icon">
                                    <h2><?php echo htmlspecialchars("Hi, " . ($f_name) . " " . htmlspecialchars($l_name) . "!"); ?></h2>
                                </div>
                                <li><a href="#tabs-1"><img src="../assets/music-icon.svg" alt="Icon">My Lineup</a></li>
                                <li><a href="#tabs-2"><img src="../assets/wallet-icon.png" alt="Icon">My Tickets</a></li>
                                <li><a href="#tabs-3"><img src="../assets/money-icon.png" alt="Icon">Purchase History</a></li>
                                <li><a href="#tabs-4"><img src="../assets/redeem-icon.png" alt="Icon">Redeem</a></li>
                                <li><a href="#tabs-5"><img src="../assets/settings-icon.png" alt="Icon">Account Settings</a></li>
                                <div id="spacer"></div>
                                <li><a href="logout.php"><img src="../assets/logout-icon.png" alt="Icon">Logout</a></li>
                            </ul>
                            <div id="tabs-1" class="all-tabs">
                                <h2>Content heading 1</h2>
                                <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                            </div>
                            <div id="tabs-2" class="all-tabs">
                                <h2>Content heading 2</h2>
                                <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
                            </div>
                            <div id="tabs-3" class="all-tabs">
                                <h2>Content heading 3</h2>
                                <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.</p>
                                <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
                            </div>
                            <div id="tabs-4" class="all-tabs">
                                <h2>Content heading 4</h2>
                                <p>
                                    Cras id nisl elementum, ornare ligula at, ornare eros. Curabitur porttitor neque turpis, at luctus orci finibus sed. Vestibulum mollis, orci ac egestas pharetra, ligula mauris ultricies sem, eget congue quam orci gravida turpis.
                                </p>
                            </div>
                            <div id="tabs-5" class="all-tabs">
                                <h2>Content heading 5</h2>
                                <p>
                                    Sed sed blandit nulla, eu dictum dolor. Aliquam eleifend, mauris non pulvinar porttitor, libero nisl interdum nisl, in aliquet lacus neque vitae lorem. Vestibulum sollicitudin eleifend ullamcorper. Nunc venenatis consectetur ante ac volutpat. Pellentesque eget accumsan sem, nec molestie ex. Cras sagittis massa vel orci dignissim, eu tempor ipsum aliquam. Duis risus lorem, vulputate nec tempor ac, aliquam non elit. Donec pharetra leo non enim laoreet cursus.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="footer">
            <video id="background-videoo" autoplay loop muted>
                <source src="../assets/bg-video1.mp4" type="video/mp4">
            </video>
            <div class="footer-content">
                <div class="motto">
                    <h1 class="main-heading" id="footer-heading">
                        A Hive of Music, Art, and Energy.
                    </h1>
                </div>

                <div class="hr">
                    <hr>
                </div>
                <div class="footer-grid">
                    <div class="column-one">
                        <img src="../assets/logofooter.png" id="footer-logo" alt="Logo">
                        <div class="socials">
                            <a href="https://www.facebook.com/" class="social-links">
                                <img src="../assets/icons8-facebook.svg" alt="Facebook">
                            </a>
                            <a href="https://www.youtube.com/" class="social-links">
                                <img src="../assets/icons8-youtube.svg" alt="YouTube">
                            </a>
                            <a href="https://www.instagram.com/" class="social-links">
                                <img src="../assets/icons8-instagram-64.svg" alt="Instagram">

                            </a>
                        </div>
                        <a href="#" id="ctn-btn">Contact us</a>
                    </div>
                    <div class="column-two">
                        <ul>
                            <li><a href="../homepage/index.html">General</a></li>
                            <li><a href="">Schedule</a></li>
                            <li><a href="">Facilities</a></li>
                            <li><a href="">Tickets</a></li>
                        </ul>
                        <ul>
                            <li><a href="">Merch</a></li>
                            <li><a href="../involvement/get-involved.html">Join us</a></li>
                            <li><a href="">Contact</a></li>
                            <li><a href="../privacy-terms/privacy.html">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="column-three">
                        <p>Our goal is to create an experience that exceeds expectations, inspires people, and
                            brings
                            moments of joy with every interaction. The Honey Festival is more than just an event –
                            it’s
                            a lifestyle, a source of energy, and a trusted space for those seeking to refresh their
                            body, mind, and soul.
                        </p>
                    </div>
                </div>

                <div class="copyright">
                    <p>Copyright – All rights reserved 2025.</p>
                    <a href="../privacy-terms/terms.html">Terms & conditions</a>
                    <a href="../privacy-terms/privacy.html">Privacy policy</a>
                    <p>Made with ♡ by Marina Žižić</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(function() {
        $("#tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
        $("#tabs li").removeClass("ui-corner-top").addClass("ui-corner-left");
    });
</script>
<script src="../universal.js"></script>