<?php
session_start();
include "../account/db.php";

$error = "";

if (!isset($_GET['id'])) {
    $error = "Ticket ID not specified.";
    exit;
}

$ticket_id = (int) $_GET['id'];

$sql = "SELECT id, title, ticket_type, price, whats_included, quantity_available FROM tickets WHERE id = ?";
$stmt = $conn->prepare($sql);


$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $error = "Ticket not found.";
    exit;
}

$ticket = $result->fetch_assoc();

if (!$error) {
    $noerror = true;
}

$stmt->close();
$conn->close();
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
    <link rel="stylesheet" href="./tickets.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <script src="../loader.js"></script>
</head>
<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const formData = form.serialize();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = response.redirect;
                    } else {
                        document.getElementsByClassName("alert")[0].classList.add("contains-alert");
                        document.getElementById("error").innerHTML = 'An error occurred: ' + (response.message || 'Unknown error');
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;

                    }
                },
                error: function() {
                        document.getElementsByClassName("alert")[0].classList.add("contains-alert");
                        document.getElementById("error").innerHTML = 'You must be logged in to add to cart';
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                }
            });
        });
        $('form').on('submit', function(e) {
            const quantityVal = $('#product-quantity').val();
            $(this).find('input[name="quantity"]').val(quantityVal);
        });

    });
</script>

<body>
    <div class="php-alert" style="<?php echo $noerror ? 'display: none;' : 'display: block;'; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?= $error ?>
    </div>
    <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <p id="error"></p>
    </div>
    <div class="container">
        <button onclick="goToTop()" id="top_btn"><img src="../assets/arrow.png" alt="Arrow"></button>
        <div class="shop-coverpage">
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
                        <a href="./tickets.php">Tickets</a>
                    </div>
                    <div>
                        <p>05</p>
                        <a href="./merch.html">Merch</a>
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
                    <a href="../account/cart.php">
                        <img src="../assets/cart.ico" alt="Shopping cart">
                    </a>
                    <a href="../account/welcome.php">
                        <img src="../assets/user-icon.png" alt="User icon">
                    </a>
                </div>
            </div>
            <img src="../assets/shop-img.jpg" alt="Festival image" id="ticket-img">
            <div class="ticket-main">
                <h1 class="main-captions">Buy Tickets</h1>
            </div>

        </div>
        <div class="tickets">
            <div class="ticket-content">
                <div id="ticket-details">
                    <div class="first-col">
                        <img src="../assets/ticket.jpg" alt="Ticket image">
                    </div>
                    <div class="second-col">
                        <h2><?= htmlspecialchars($ticket['title']) ?></h2>
                        <p id="category"><strong>Category:</strong> Ticket</p>
                        <p><strong>Type:</strong> <?= htmlspecialchars($ticket['ticket_type']) ?></p>
                        <p id="price"><strong>Price: </strong> €<?= htmlspecialchars($ticket['price']) ?></p>
                        <p id="desc"><strong>What's Included:</strong></p>
                        <ul>
                            <?php
                            $features = explode(". ", $ticket['whats_included']);
                            foreach ($features as $feature):
                            ?>
                                <li><?= htmlspecialchars($feature) ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if ($ticket['quantity_available'] > 0): ?>
                            <form method="POST" action="./add_to_cart.php">
                                <div class="p-input">
                                    <input
                                        type="number"
                                        id="product-quantity"
                                        name="quantity"
                                        value="1"
                                        min="1"
                                        max="50"
                                        oninput="this.value = Math.max(1, Math.min(50, this.value))" />
                                    <select name="size" id="size">
                                        <option value="No size">No size</option>
                                    </select>
                                </div>
                                <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                                <input type="hidden" name="type" value="ticket">
                                <input type="hidden" name="price" value="<?= $ticket['price'] ?>">
                                <button class="add-to-cart">Add to Cart</button>
                            </form>


                        <?php else: ?>
                            <button class="add-to-cart sold-out" disabled>Sold Out</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>

        </script>
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
                            <li><a href="./tickets.php">Tickets</a></li>
                        </ul>
                        <ul>
                            <li><a href="./merch.html">Merch</a></li>
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
    </div>
</body>

</html>

<script src="../universal.js"></script>