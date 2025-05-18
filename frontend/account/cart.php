<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ./login.php");
    exit();
}
include "db.php";
$user_id = $_SESSION['user_id'];

$sql = "SELECT product_id, price, quantity, size FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$cartItems = [];

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
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
    <link rel="stylesheet" href="./login.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <script src="../loader.js"></script>
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
                            <a href="../schedule/schedule.html">Schedule</a>
                        </div>

                        <div>
                            <p>03</p>
                            <a href="../facilities/facilities.html">Facilites</a>
                        </div>

                        <div>
                            <p>04</p>
                            <a href="../store/tickets.html">Tickets</a>
                        </div>
                        <div>
                            <p>05</p>
                            <a href="../store/merch.php">Merch</a>
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
                        <div id="cart-container"></div>
                        <div class="promo-pay">
                            <div id="promo-code-box">
                                <h3>Have a promo code?</h3>
                                <input type="text" id="promo-code-input" placeholder="Enter promo code" />
                                <button onclick="applyPromo()">Apply</button>
                                <p id="promo-msg"></p>
                            </div>
                            <div class="payment">
                                <button id="pay">Proceed To Payment</button>
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
                            <li><a href="../schedule/schedule.html">Schedule</a></li>
                            <li><a href="../facilities/facilities.html">Facilities</a></li>
                            <li><a href="../store/tickets.html">Tickets</a></li>
                        </ul>
                        <ul>
                            <li><a href="../store/merch.php">Merch</a></li>
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
    const cartItems = <?php echo json_encode($cartItems); ?>;
    const cartContainer = document.getElementById('cart-container');

    let promoApplied = false;
    let discountPercent = 0;

    async function fetchProduct(productId) {
        const res = await fetch(`https://fakestoreapi.com/products/${productId}`);
        if (!res.ok) throw new Error('Failed to fetch product');
        return res.json();
    }

    async function renderCart() {
        if (cartItems.length === 0) {
            cartContainer.innerHTML = "<p>Your cart is empty.</p>";
            return;
        }

        let totalPrice = 0;
        cartContainer.innerHTML = '';

        for (const item of cartItems) {
            try {
                const product = await fetchProduct(item.product_id);
                const itemTotal = product.price * item.quantity;
                totalPrice += itemTotal;

                const itemDiv = document.createElement('div');
                itemDiv.classList.add('cart-item');

                itemDiv.innerHTML = `
                    <a href="../store/product.html?id=${product.id}">${product.title}</a>
                    <p>Size: ${item.size || '-'}</p>
                    <p>Price: €${product.price.toFixed(2)}</p>
                    <label>
                        Quantity: 
                        <input type="number" min="1" max="50" value="${item.quantity}" data-id="${item.product_id}" data-size="${item.size}" class="quantity-input" />
                    </label>
                    <button class="update-btn" data-id="${item.product_id}" data-size="${item.size}">Update Quantity</button>
                    <button class="remove-btn" data-id="${item.product_id}" data-size="${item.size}">Remove</button>
                    <p>€${itemTotal.toFixed(2)}</p>
                    <hr>
                `;

                cartContainer.appendChild(itemDiv);
            } catch (e) {
                console.error('Failed to load product details for product_id:', item.product_id);
            }
        }
        let finalTotal = totalPrice;
        if (promoApplied) {
            finalTotal = totalPrice - (totalPrice * discountPercent / 100);
        }

        const totalDiv = document.createElement('div');
        totalDiv.innerHTML = `<h2 id="total">Cart Total: €${finalTotal.toFixed(2)}</h2>`;
        cartContainer.appendChild(totalDiv);

        attachEventListeners();
    }

    function applyPromo() {
        const input = document.getElementById('promo-code-input').value.trim().toUpperCase();
        const validCodes = {
            "ILOVEBEES": 15,
        };

        if (validCodes[input]) {
            discountPercent = validCodes[input];
            promoApplied = true;
            document.getElementById('promo-msg').innerHTML = `Promo applied: ${discountPercent}% off!`;
        } else {
            discountPercent = 0;
            promoApplied = false;
            document.getElementById('promo-msg').innerHTML = "Invalid promo code.";
        }

        renderCart();
    }

    function attachEventListeners() {
        const updateButtons = document.querySelectorAll('.update-btn');
        const removeButtons = document.querySelectorAll('.remove-btn');

        updateButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.dataset.id;
                const size = button.dataset.size;
                const quantityInput = document.querySelector(`input.quantity-input[data-id="${productId}"][data-size="${size}"]`);
                const newQuantity = parseInt(quantityInput.value);

                if (isNaN(newQuantity) || newQuantity < 1) {
                    alert('Quantity must be at least 1');
                    return;
                }

                updateQuantity(productId, size, newQuantity);
            });
        });

        removeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.dataset.id;
                const size = button.dataset.size;
                removeItem(productId, size);
            });
        });
    }

    function updateQuantity(productId, size, quantity) {
        fetch('./update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    product_id: productId,
                    size: size,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Failed to update quantity');
                }
            })
            .catch(() => alert('Error updating quantity'));
    }

    function removeItem(productId, size) {
        fetch('./remove_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    product_id: productId,
                    size: size
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Failed to remove item');
                }
            })
            .catch(() => alert('Error removing item'));
    }

    renderCart();
</script>

<script src="../universal.js"></script>