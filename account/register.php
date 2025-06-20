<?php
session_start();
require "db.php";

$error = "";

function generateAccountID($conn)
{
    do {
        $randomID = rand(10000, 999999);
        $stmt = $conn->prepare("SELECT account_id FROM users WHERE account_id = ?");
        $stmt->bind_param("i", $randomID);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    return $randomID;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $f_name = trim($_POST["f_name"]);
    $l_name = trim($_POST["l_name"]);
    $gender = trim($_POST["gender"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $account_id = generateAccountID($conn);
    $birthday = '2000-01-01';

    $check_email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();

    $check_username_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_username_stmt->bind_param("s", $username);
    $check_username_stmt->execute();
    $check_username_stmt->store_result();

    if ($check_email_stmt->num_rows > 0) {
        $error = "An account with this email already exists.";
    } elseif ($check_username_stmt->num_rows > 0) {
        $error = "An account with this username already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, f_name, l_name, gender, email, password, account_id, birthday) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $f_name, $l_name, $gender, $email, $password, $account_id, $birthday);

        if ($stmt->execute()) {
            $_SESSION["username"] = $username;
            $_SESSION["f_name"] = $f_name;
            $_SESSION["l_name"] = $l_name;
            header("Location: welcome.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}

if (!$error) {
    $noerror = true;
}

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
    <script src="../loader.js"></script>
</head>

<body>
    <div class="alert" style="<?php echo $noerror ? 'display: none;' : 'display: block;'; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?= $error ?>
    </div>
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
                            <a href="../schedule/schedule.html">Schedule</a>
                        </div>

                        <div>
                            <p>03</p>
                            <a href="../facilities/facilities.html">Facilites</a>
                        </div>

                        <div>
                            <p>04</p>
                            <a href="../store/tickets.php">Tickets</a>
                        </div>
                        <div>
                            <p>05</p>
                            <a href="../store/merch.html">Merch</a>
                        </div>

                        <div>
                            <p>06</p>
                            <a href="../gallery/gallery.html">Gallery</a>
                        </div>
                    </div>
                </div>

                <div class="header">
                    <span onclick="openNav()" class="open"><img src="../assets/hamburger-menu.svg" alt="Menu"
                            id="menu-img"></span>
                    <div class="accountbtn">
                        <a href="./cart.php">
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

                    <div class="form">
                        <h3 class="small-headings">make an account</h3>
                        <h1 class="main-headings">Register</h1>

                        <form method="POST" class="forms" id="r-form">
                            <input type="text" class="register-input" name="username" placeholder="Username" required>
                            <div class="names">
                                <input type="text" name="f_name" placeholder="First name"
                                    id="first-input" required>
                                <input type="text" name="l_name" placeholder="Last name" required>
                            </div>
                            <input type="email" name="email" class="register-input" placeholder="Email" required>
                            <select name="gender" id="" class="register-input" class="r-select" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="Non-binary">Non-binary</option>
                            </select>
                            <input type="password" class="register-input" name="password" placeholder="Password" required>
                            <div class="accept-privacy">
                                <label class="label">
                                    <input type="checkbox" name="checkbox" id="" required>
                                    <span class="checkmark"></span>
                                    I agree to the <a href="../privacy-terms/terms.html">Terms of Service</a> and <a
                                        href="../privacy-terms/privacy.html">Privacy Policy</a> of Honey Festival
                                </label>
                            </div>
                            <br>
                            <button type="submit" id="acc-btn">Register</button>
                        </form>
                        <br>
                        <a href="login.php">Already have an account? Login</a>
                        <br>
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
                        <a href="../contact/contact.html" id="ctn-btn">Contact us</a>
                    </div>
                    <div class="column-two">
                        <ul>
                            <li><a href="../homepage/index.html">General</a></li>
                            <li><a href="../schedule/schedule.html">Schedule</a></li>
                            <li><a href="../facilities/facilities.html">Facilities</a></li>
                            <li><a href="../store/tickets.php">Tickets</a></li>
                        </ul>
                        <ul>
                            <li><a href="../store/merch.html">Merch</a></li>
                            <li><a href="../involvement/get-involved.html">Join us</a></li>
                            <li><a href="../contact/contact.html">Contact</a></li>
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

<script src="../universal.js"></script>