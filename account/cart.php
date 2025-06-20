<?php
session_start();

$error = "";

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
include "db.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT product_id, price, quantity, size, type FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$cartItems = [];

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

if (isset($_POST['remove']) && $_POST['remove'] === 'fail') {
    $error = "Unable to remove item.";
} elseif (isset($_POST['adding']) && $_POST['adding'] === 'fail') {
    $error = "Unable to change quantity.";
} elseif (isset($_POST['shipped']) && $_POST['shipped'] === 'delete_fail') {
    $error = "Unable to proceed to payment.";
} elseif (isset($_POST['shipped']) && $_POST['shipped'] === 'insert_fail') {
    $error = "Unable to proceed to payment.";
}

if (!$error) {
    $noerror = true;
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
                        <a href="./welcome.php">
                            <img src="../assets/user-icon.png" alt="User icon">
                        </a>
                    </div>
                </div>

            </div>
            <div class="login">
                <div class="login-content">
                    <div class="cart">
                        <div class="cart-headings">
                            <h3 class="small-headings">bees like to shop</h3>
                            <h1 class="main-headings">Shopping Cart</h1>
                        </div>
                        <div id="cart-container">
                            <?php if (empty($cartItems)): ?>
                                <p>Your cart is empty.</p>
                            <?php else: ?>
                                <?php
                                include "db.php";
                                $total = 0;

                                foreach ($cartItems as $item):
                                    $product_id = $item['product_id'];
                                    $quantity = $item['quantity'];
                                    $size = $item['size'] ?? 'No size';
                                    $type = $item['type'];
                                    $cart_price = $item['price'];

                                    if ($type === 'merch') {
                                        $stmt = $conn->prepare("SELECT title, price FROM merch WHERE id = ?");
                                    } elseif ($type === 'ticket') {
                                        $stmt = $conn->prepare("SELECT title, price FROM tickets WHERE id = ?");
                                    } else {
                                        continue;
                                    }

                                    $stmt->bind_param("i", $product_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $itemData = $result->fetch_assoc();
                                    $stmt->close();

                                    $title = htmlspecialchars($itemData['title']);
                                    $price = $itemData['price'];

                                    $itemTotal = $price * $quantity;
                                    $total += $itemTotal;
                                ?>
                                    <div class="cart-item">
                                        <p id="cart-title"><?= $title ?> (<?= ucfirst($type) ?>)</p>
                                        <p>Size: <?= htmlspecialchars($size) ?></p>
                                        <p>Price: €<?= number_format($price, 2) ?></p>
                                        <form action="update_cart.php" method="post" style="display:inline;">
                                            <input type="number" class="change-q" name="quantity" min="1" value="<?= $quantity ?>">
                                            <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                            <input type="hidden" name="size" value="<?= $size ?>">
                                            <input type="hidden" name="type" value="<?= $type ?>">
                                            <button type="submit">Update Quantity</button>
                                        </form>
                                        <form action="./remove_cart.php" method="post" style="display:inline;">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
                                            <input type="hidden" name="size" value="<?= htmlspecialchars($size) ?>">
                                            <button type="submit">Remove</button>
                                        </form>

                                        <p>€<?= number_format($itemTotal, 2) ?></p>
                                        <hr>
                                    </div>
                                <?php endforeach; ?>
                                <div class="cart-total">
                                    <h2>Cart Total: €<?= number_format($total, 2) ?></h2>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="promo-pay">
                            <div id="promo-code-box">
                                <h3>Have a promo code?</h3>
                                <input type="text" id="promo-code-input" placeholder="Enter promo code" />
                                <button>Apply</button>
                                <p id="promo-msg"></p>
                            </div>
                            <div class="payment">
                                <form action="buy.php" method="post" style="display:inline;">
                                    <button id="pay">Proceed To Payment</button>

                                </form>
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
    <?php $conn->close(); ?>
</body>

</html>

<script src="../universal.js"></script>