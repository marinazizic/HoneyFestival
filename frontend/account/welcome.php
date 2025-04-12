<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$error = "";
require "db.php";

$username = $_SESSION["username"];

$stmt = $conn->prepare("SELECT f_name, l_name, gender, email, account_id, created_at, country, birthday FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($f_name, $l_name, $gender, $email, $account_id, $created_at, $country, $birthday);
    $stmt->fetch();
} else {
    header("Location: login.php");
    exit();
}

$date = new DateTime($birthday);
$selected_day = $date->format('d');
$selected_month = $date->format('m');
$selected_year = $date->format('Y');

$stmt->close();

if (isset($_POST["send-changes"])) {
    $selected_day = $_POST['day'];
    $selected_month = $_POST['month'];
    $selected_year = $_POST['year'];
    $stringDate = $selected_year . '-' . $selected_month . '-' . $selected_day;
    $userDate = new DateTime($stringDate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($userDate);


    if ($age->y < 18) {
        $error = "You must be at least 18 years old.";
    } else {
        $new_username = trim($_POST["new-username"]);
        $new_email = trim($_POST["new-email"]);
        $new_fname = trim($_POST["new-fname"]);
        $new_lname = trim($_POST["new-lname"]);
        $new_gender = trim($_POST["gender"]);
        $new_country = trim($_POST["country"]);

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND username != ?");
        $stmt->bind_param("ss", $new_username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "That username is already taken.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND username != ?");
            $stmt->bind_param("ss", $new_email, $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "That email is already in use.";
            } else {
                $updated_birthday = $stringDate;
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, birthday = ?, f_name = ?, l_name = ?, country = ?, gender = ? WHERE username = ?");
                $stmt->bind_param("ssssssss", $new_username, $new_email, $updated_birthday, $new_fname, $new_lname, $new_country, $new_gender, $username);

                if ($stmt->execute()) {
                    $_SESSION["username"] = $new_username;
                    $username = $new_username;
                } else {
                    $error = "Failed to update profile: " . $stmt->error;
                }
            }
        }
    }
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
                    <div class="dashboard">
                        <div class="dashboard-info">
                            <h3 class="small-headings">welcome to your account</h3>
                            <h1 class="main-headings">Dashboard</h1>
                        </div>
                        <div id="tabs">
                            <ul id="ul">
                                <li>
                                    <div class="short-info">
                                        <a href="#tabs-1">
                                            <img src="../assets/black-user-icon.png" alt="User icon" id="mobile-user-icon">
                                        </a>
                                    </div>
                                </li>

                                <li class="all-li"><a href="#tabs-2"><img src="../assets/music-icon.svg" alt="Icon"></a></li>
                                <li class="all-li"><a href="#tabs-3"><img src="../assets/wallet-icon.png" alt="Icon"></a></li>
                                <li class="all-li"><a href="#tabs-4"><img src="../assets/money-icon.png" alt="Icon"></a></li>
                                <li class="all-li"><a href="#tabs-5"><img src="../assets/redeem-icon.png" alt="Icon"></a></li>
                                <li class="all-li"><a href="#tabs-6"><img src="../assets/settings-icon.png" alt="Icon"></a></li>
                                <div id="spacer"></div>
                                <li class="all-li"><a href="logout.php"><img src="../assets/logout-icon.png" alt="Icon"></a></li>
                            </ul>
                            <div id="tabs-1" class="all-tabs">
                                <h2 class="tab-heading">My Honey Passport</h2>
                                <div class="account-banner">
                                    <div>
                                        <img src="../assets/bee.png" alt="Bee icon">
                                    </div>
                                    <div>
                                        <h2><?php echo htmlspecialchars(($f_name) . " " . htmlspecialchars($l_name)); ?></h2>
                                        <p class="tiny-headings">Honey Passport ID:</p>
                                        <p id="acc-id"><?php echo htmlspecialchars(($account_id)); ?></p>
                                    </div>
                                </div>
                                <div class="account-details">
                                    <p class="account-p">Passport Details:</p>
                                    <div class="account-grid">
                                        <div>
                                            <p class="tiny-headings">Email:</p>
                                            <p><?php echo htmlspecialchars($email) ?></p>
                                        </div>
                                        <div>
                                            <p class="tiny-headings">Birthday:</p>
                                            <p><?php echo htmlspecialchars($birthday) ?></p>
                                        </div>
                                        <div>
                                            <p class="tiny-headings">Gender:</p>
                                            <p><?php echo htmlspecialchars($gender) ?></p>
                                        </div>
                                        <div>
                                            <p class="tiny-headings">Location:</p>
                                            <p><?php echo htmlspecialchars($country) ?></p>
                                        </div>
                                        <div>
                                            <p class="tiny-headings">Created:</p>
                                            <p><?php echo htmlspecialchars($created_at) ?></p>
                                        </div>
                                    </div>
                                </div>
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

                            </div>
                            <div id="tabs-6" class="all-tabs">
                                <h2 class="tab-heading">Edit My Account</h2>
                                <form method="POST" id="change-form">
                                    <div class="edit-details">
                                        <div class="account-grid1">
                                            <div>
                                                <p class="tiny-headings">Email:</p>
                                                <input type="email" name="new-email" id="" value="<?php echo htmlspecialchars($email) ?>">
                                            </div>
                                            <div>
                                                <p class="tiny-headings">Username:</p>
                                                <input type="text" name="new-username" id="" value="<?php echo htmlspecialchars($username) ?>">
                                            </div>
                                            <div>
                                                <p class="tiny-headings">First name:</p>
                                                <input type="text" name="new-fname" id="" value="<?php echo htmlspecialchars($f_name) ?>">
                                            </div>
                                            <div>
                                                <p class="tiny-headings">Last name:</p>
                                                <input type="text" name="new-lname" id="" value="<?php echo htmlspecialchars($l_name) ?>">
                                            </div>
                                            <div>
                                                <p class="tiny-headings">Country:</p>
                                                <select id="country" name="country" class="form-control">
                                                    <option value="Afghanistan" <?php if ($country === 'Afghanistan') echo 'selected'; ?>>Afghanistan</option>
                                                    <option value="Åland Islands" <?php if ($country === 'Åland Islands') echo 'selected'; ?>>Åland Islands</option>
                                                    <option value="Albania" <?php if ($country === 'Albania') echo 'selected'; ?>>Albania</option>
                                                    <option value="Algeria" <?php if ($country === 'Algeria') echo 'selected'; ?>>Algeria</option>
                                                    <option value="American Samoa" <?php if ($country === 'American Samoa') echo 'selected'; ?>>American Samoa</option>
                                                    <option value="Andorra" <?php if ($country === 'Andorra') echo 'selected'; ?>>Andorra</option>
                                                    <option value="Angola" <?php if ($country === 'Angola') echo 'selected'; ?>>Angola</option>
                                                    <option value="Anguilla" <?php if ($country === 'Anguilla') echo 'selected'; ?>>Anguilla</option>
                                                    <option value="Antarctica" <?php if ($country === 'Antarctica') echo 'selected'; ?>>Antarctica</option>
                                                    <option value="Antigua and Barbuda" <?php if ($country === 'Antigua and Barbuda') echo 'selected'; ?>>Antigua and Barbuda</option>
                                                    <option value="Argentina" <?php if ($country === 'Argentina') echo 'selected'; ?>>Argentina</option>
                                                    <option value="Armenia" <?php if ($country === 'Armenia') echo 'selected'; ?>>Armenia</option>
                                                    <option value="Aruba" <?php if ($country === 'Aruba') echo 'selected'; ?>>Aruba</option>
                                                    <option value="Australia" <?php if ($country === 'Australia') echo 'selected'; ?>>Australia</option>
                                                    <option value="Austria" <?php if ($country === 'Austria') echo 'selected'; ?>>Austria</option>
                                                    <option value="Azerbaijan" <?php if ($country === 'Azerbaijan') echo 'selected'; ?>>Azerbaijan</option>
                                                    <option value="Bahamas" <?php if ($country === 'Bahamas') echo 'selected'; ?>>Bahamas</option>
                                                    <option value="Bahrain" <?php if ($country === 'Bahrain') echo 'selected'; ?>>Bahrain</option>
                                                    <option value="Bangladesh" <?php if ($country === 'Bangladesh') echo 'selected'; ?>>Bangladesh</option>
                                                    <option value="Barbados" <?php if ($country === 'Barbados') echo 'selected'; ?>>Barbados</option>
                                                    <option value="Belarus" <?php if ($country === 'Belarus') echo 'selected'; ?>>Belarus</option>
                                                    <option value="Belgium" <?php if ($country === 'Belgium') echo 'selected'; ?>>Belgium</option>
                                                    <option value="Belize" <?php if ($country === 'Belize') echo 'selected'; ?>>Belize</option>
                                                    <option value="Benin" <?php if ($country === 'Benin') echo 'selected'; ?>>Benin</option>
                                                    <option value="Bermuda" <?php if ($country === 'Bermuda') echo 'selected'; ?>>Bermuda</option>
                                                    <option value="Bhutan" <?php if ($country === 'Bhutan') echo 'selected'; ?>>Bhutan</option>
                                                    <option value="Bolivia" <?php if ($country === 'Bolivia') echo 'selected'; ?>>Bolivia</option>
                                                    <option value="Bosnia and Herzegovina" <?php if ($country === 'Bosnia and Herzegovina') echo 'selected'; ?>>Bosnia and Herzegovina</option>
                                                    <option value="Botswana" <?php if ($country === 'Botswana') echo 'selected'; ?>>Botswana</option>
                                                    <option value="Bouvet Island" <?php if ($country === 'Bouvet Island') echo 'selected'; ?>>Bouvet Island</option>
                                                    <option value="Brazil" <?php if ($country === 'Brazil') echo 'selected'; ?>>Brazil</option>
                                                    <option value="British Indian Ocean Territory" <?php if ($country === 'British Indian Ocean Territory') echo 'selected'; ?>>British Indian Ocean Territory</option>
                                                    <option value="Brunei Darussalam" <?php if ($country === 'Brunei Darussalam') echo 'selected'; ?>>Brunei Darussalam</option>
                                                    <option value="Bulgaria" <?php if ($country === 'Bulgaria') echo 'selected'; ?>>Bulgaria</option>
                                                    <option value="Burkina Faso" <?php if ($country === 'Burkina Faso') echo 'selected'; ?>>Burkina Faso</option>
                                                    <option value="Burundi" <?php if ($country === 'Burundi') echo 'selected'; ?>>Burundi</option>
                                                    <option value="Cambodia" <?php if ($country === 'Cambodia') echo 'selected'; ?>>Cambodia</option>
                                                    <option value="Cameroon" <?php if ($country === 'Cameroon') echo 'selected'; ?>>Cameroon</option>
                                                    <option value="Canada" <?php if ($country === 'Canada') echo 'selected'; ?>>Canada</option>
                                                    <option value="Cape Verde" <?php if ($country === 'Cape Verde') echo 'selected'; ?>>Cape Verde</option>
                                                    <option value="Cayman Islands" <?php if ($country === 'Cayman Islands') echo 'selected'; ?>>Cayman Islands</option>
                                                    <option value="Central African Republic" <?php if ($country === 'Central African Republic') echo 'selected'; ?>>Central African Republic</option>
                                                    <option value="Chad" <?php if ($country === 'Chad') echo 'selected'; ?>>Chad</option>
                                                    <option value="Chile" <?php if ($country === 'Chile') echo 'selected'; ?>>Chile</option>
                                                    <option value="China" <?php if ($country === 'China') echo 'selected'; ?>>China</option>
                                                    <option value="Christmas Island" <?php if ($country === 'Christmas Island') echo 'selected'; ?>>Christmas Island</option>
                                                    <option value="Cocos (Keeling) Islands" <?php if ($country === 'Cocos (Keeling) Islands') echo 'selected'; ?>>Cocos (Keeling) Islands</option>
                                                    <option value="Colombia" <?php if ($country === 'Colombia') echo 'selected'; ?>>Colombia</option>
                                                    <option value="Comoros" <?php if ($country === 'Comoros') echo 'selected'; ?>>Comoros</option>
                                                    <option value="Congo" <?php if ($country === 'Congo') echo 'selected'; ?>>Congo</option>
                                                    <option value="Congo, The Democratic Republic of The" <?php if ($country === 'Congo, The Democratic Republic of The') echo 'selected'; ?>>Congo, The Democratic Republic of The</option>
                                                    <option value="Cook Islands" <?php if ($country === 'Cook Islands') echo 'selected'; ?>>Cook Islands</option>
                                                    <option value="Costa Rica" <?php if ($country === 'Costa Rica') echo 'selected'; ?>>Costa Rica</option>
                                                    <option value="Cote d'Ivoire" <?php if ($country === "Cote d'Ivoire") echo 'selected'; ?>>Cote d'Ivoire</option>
                                                    <option value="Croatia" <?php if ($country === 'Croatia') echo 'selected'; ?>>Croatia</option>
                                                    <option value="Cuba" <?php if ($country === 'Cuba') echo 'selected'; ?>>Cuba</option>
                                                    <option value="Cyprus" <?php if ($country === 'Cyprus') echo 'selected'; ?>>Cyprus</option>
                                                    <option value="Czech Republic" <?php if ($country === 'Czech Republic') echo 'selected'; ?>>Czech Republic</option>
                                                    <option value="Denmark" <?php if ($country === 'Denmark') echo 'selected'; ?>>Denmark</option>
                                                    <option value="Djibouti" <?php if ($country === 'Djibouti') echo 'selected'; ?>>Djibouti</option>
                                                    <option value="Dominica" <?php if ($country === 'Dominica') echo 'selected'; ?>>Dominica</option>
                                                    <option value="Dominican Republic" <?php if ($country === 'Dominican Republic') echo 'selected'; ?>>Dominican Republic</option>
                                                    <option value="Ecuador" <?php if ($country === 'Ecuador') echo 'selected'; ?>>Ecuador</option>
                                                    <option value="Egypt" <?php if ($country === 'Egypt') echo 'selected'; ?>>Egypt</option>
                                                    <option value="El Salvador" <?php if ($country === 'El Salvador') echo 'selected'; ?>>El Salvador</option>
                                                    <option value="Equatorial Guinea" <?php if ($country === 'Equatorial Guinea') echo 'selected'; ?>>Equatorial Guinea</option>
                                                    <option value="Eritrea" <?php if ($country === 'Eritrea') echo 'selected'; ?>>Eritrea</option>
                                                    <option value="Estonia" <?php if ($country === 'Estonia') echo 'selected'; ?>>Estonia</option>
                                                    <option value="Ethiopia" <?php if ($country === 'Ethiopia') echo 'selected'; ?>>Ethiopia</option>
                                                    <option value="Falkland Islands (Malvinas)" <?php if ($country === 'Falkland Islands (Malvinas)') echo 'selected'; ?>>Falkland Islands (Malvinas)</option>
                                                    <option value="Faroe Islands" <?php if ($country === 'Faroe Islands') echo 'selected'; ?>>Faroe Islands</option>
                                                    <option value="Fiji" <?php if ($country === 'Fiji') echo 'selected'; ?>>Fiji</option>
                                                    <option value="Finland" <?php if ($country === 'Finland') echo 'selected'; ?>>Finland</option>
                                                    <option value="France" <?php if ($country === 'France') echo 'selected'; ?>>France</option>
                                                    <option value="French Guiana" <?php if ($country === 'French Guiana') echo 'selected'; ?>>French Guiana</option>
                                                    <option value="French Polynesia" <?php if ($country === 'French Polynesia') echo 'selected'; ?>>French Polynesia</option>
                                                    <option value="French Southern Territories" <?php if ($country === 'French Southern Territories') echo 'selected'; ?>>French Southern Territories</option>
                                                    <option value="Gabon" <?php if ($country === 'Gabon') echo 'selected'; ?>>Gabon</option>
                                                    <option value="Gambia" <?php if ($country === 'Gambia') echo 'selected'; ?>>Gambia</option>
                                                    <option value="Georgia" <?php if ($country === 'Georgia') echo 'selected'; ?>>Georgia</option>
                                                    <option value="Germany" <?php if ($country === 'Germany') echo 'selected'; ?>>Germany</option>
                                                    <option value="Ghana" <?php if ($country === 'Ghana') echo 'selected'; ?>>Ghana</option>
                                                    <option value="Gibraltar" <?php if ($country === 'Gibraltar') echo 'selected'; ?>>Gibraltar</option>
                                                    <option value="Greece" <?php if ($country === 'Greece') echo 'selected'; ?>>Greece</option>
                                                    <option value="Greenland" <?php if ($country === 'Greenland') echo 'selected'; ?>>Greenland</option>
                                                    <option value="Grenada" <?php if ($country === 'Grenada') echo 'selected'; ?>>Grenada</option>
                                                    <option value="Guadeloupe" <?php if ($country === 'Guadeloupe') echo 'selected'; ?>>Guadeloupe</option>
                                                    <option value="Guam" <?php if ($country === 'Guam') echo 'selected'; ?>>Guam</option>
                                                    <option value="Guatemala" <?php if ($country === 'Guatemala') echo 'selected'; ?>>Guatemala</option>
                                                    <option value="Guernsey" <?php if ($country === 'Guernsey') echo 'selected'; ?>>Guernsey</option>
                                                    <option value="Guinea" <?php if ($country === 'Guinea') echo 'selected'; ?>>Guinea</option>
                                                    <option value="Guinea-bissau" <?php if ($country === 'Guinea-bissau') echo 'selected'; ?>>Guinea-bissau</option>
                                                    <option value="Guyana" <?php if ($country === 'Guyana') echo 'selected'; ?>>Guyana</option>
                                                    <option value="Haiti" <?php if ($country === 'Haiti') echo 'selected'; ?>>Haiti</option>
                                                    <option value="Heard Island and Mcdonald Islands" <?php if ($country === 'Heard Island and Mcdonald Islands') echo 'selected'; ?>>Heard Island and Mcdonald Islands</option>
                                                    <option value="Holy See (Vatican City State)" <?php if ($country === 'Holy See (Vatican City State)') echo 'selected'; ?>>Holy See (Vatican City State)</option>
                                                    <option value="Honduras" <?php if ($country === 'Honduras') echo 'selected'; ?>>Honduras</option>
                                                    <option value="Hong Kong" <?php if ($country === 'Hong Kong') echo 'selected'; ?>>Hong Kong</option>
                                                    <option value="Hungary" <?php if ($country === 'Hungary') echo 'selected'; ?>>Hungary</option>
                                                    <option value="Iceland" <?php if ($country === 'Iceland') echo 'selected'; ?>>Iceland</option>
                                                    <option value="India" <?php if ($country === 'India') echo 'selected'; ?>>India</option>
                                                    <option value="Indonesia" <?php if ($country === 'Indonesia') echo 'selected'; ?>>Indonesia</option>
                                                    <option value="Iran" <?php if ($country === 'Iran') echo 'selected'; ?>>Iran</option>
                                                    <option value="Iraq" <?php if ($country === 'Iraq') echo 'selected'; ?>>Iraq</option>
                                                    <option value="Ireland" <?php if ($country === 'Ireland') echo 'selected'; ?>>Ireland</option>
                                                    <option value="Isle of Man" <?php if ($country === 'Isle of Man') echo 'selected'; ?>>Isle of Man</option>
                                                    <option value="Israel" <?php if ($country === 'Israel') echo 'selected'; ?>>Israel</option>
                                                    <option value="Italy" <?php if ($country === 'Italy') echo 'selected'; ?>>Italy</option>
                                                    <option value="Jamaica" <?php if ($country === 'Jamaica') echo 'selected'; ?>>Jamaica</option>
                                                    <option value="Japan" <?php if ($country === 'Japan') echo 'selected'; ?>>Japan</option>
                                                    <option value="Jersey" <?php if ($country === 'Jersey') echo 'selected'; ?>>Jersey</option>
                                                    <option value="Jordan" <?php if ($country === 'Jordan') echo 'selected'; ?>>Jordan</option>
                                                    <option value="Kazakhstan" <?php if ($country === 'Kazakhstan') echo 'selected'; ?>>Kazakhstan</option>
                                                    <option value="Kenya" <?php if ($country === 'Kenya') echo 'selected'; ?>>Kenya</option>
                                                    <option value="Kiribati" <?php if ($country === 'Kiribati') echo 'selected'; ?>>Kiribati</option>
                                                    <option value="Kuwait" <?php if ($country === 'Kuwait') echo 'selected'; ?>>Kuwait</option>
                                                    <option value="Kyrgyzstan" <?php if ($country === 'Kyrgyzstan') echo 'selected'; ?>>Kyrgyzstan</option>
                                                    <option value="Laos" <?php if ($country === 'Laos') echo 'selected'; ?>>Laos</option>
                                                    <option value="Latvia" <?php if ($country === 'Latvia') echo 'selected'; ?>>Latvia</option>
                                                    <option value="Lebanon" <?php if ($country === 'Lebanon') echo 'selected'; ?>>Lebanon</option>
                                                    <option value="Lesotho" <?php if ($country === 'Lesotho') echo 'selected'; ?>>Lesotho</option>
                                                    <option value="Liberia" <?php if ($country === 'Liberia') echo 'selected'; ?>>Liberia</option>
                                                    <option value="Libyan Arab Jamahiriya" <?php if ($country === 'Libyan Arab Jamahiriya') echo 'selected'; ?>>Libyan Arab Jamahiriya</option>
                                                    <option value="Liechtenstein" <?php if ($country === 'Liechtenstein') echo 'selected'; ?>>Liechtenstein</option>
                                                    <option value="Lithuania" <?php if ($country === 'Lithuania') echo 'selected'; ?>>Lithuania</option>
                                                    <option value="Luxembourg" <?php if ($country === 'Luxembourg') echo 'selected'; ?>>Luxembourg</option>
                                                    <option value="Macao" <?php if ($country === 'Macao') echo 'selected'; ?>>Macao</option>
                                                    <option value="Madagascar" <?php if ($country === 'Madagascar') echo 'selected'; ?>>Madagascar</option>
                                                    <option value="Malawi" <?php if ($country === 'Malawi') echo 'selected'; ?>>Malawi</option>
                                                    <option value="Malaysia" <?php if ($country === 'Malaysia') echo 'selected'; ?>>Malaysia</option>
                                                    <option value="Maldives" <?php if ($country === 'Maldives') echo 'selected'; ?>>Maldives</option>
                                                    <option value="Mali" <?php if ($country === 'Mali') echo 'selected'; ?>>Mali</option>
                                                    <option value="Malta" <?php if ($country === 'Malta') echo 'selected'; ?>>Malta</option>
                                                    <option value="Marshall Islands" <?php if ($country === 'Marshall Islands') echo 'selected'; ?>>Marshall Islands</option>
                                                    <option value="Martinique" <?php if ($country === 'Martinique') echo 'selected'; ?>>Martinique</option>
                                                    <option value="Mauritania" <?php if ($country === 'Mauritania') echo 'selected'; ?>>Mauritania</option>
                                                    <option value="Mauritius" <?php if ($country === 'Mauritius') echo 'selected'; ?>>Mauritius</option>
                                                    <option value="Mayotte" <?php if ($country === 'Mayotte') echo 'selected'; ?>>Mayotte</option>
                                                    <option value="Mexico" <?php if ($country === 'Mexico') echo 'selected'; ?>>Mexico</option>
                                                    <option value="Micronesia, Federated States of" <?php if ($country === 'Micronesia, Federated States of') echo 'selected'; ?>>Micronesia, Federated States of</option>
                                                    <option value="Moldova, Republic of" <?php if ($country === 'Moldova, Republic of') echo 'selected'; ?>>Moldova, Republic of</option>
                                                    <option value="Monaco" <?php if ($country === 'Monaco') echo 'selected'; ?>>Monaco</option>
                                                    <option value="Mongolia" <?php if ($country === 'Mongolia') echo 'selected'; ?>>Mongolia</option>
                                                    <option value="Montenegro" <?php if ($country === 'Montenegro') echo 'selected'; ?>>Montenegro</option>
                                                    <option value="Montserrat" <?php if ($country === 'Montserrat') echo 'selected'; ?>>Montserrat</option>
                                                    <option value="Morocco" <?php if ($country === 'Morocco') echo 'selected'; ?>>Morocco</option>
                                                    <option value="Mozambique" <?php if ($country === 'Mozambique') echo 'selected'; ?>>Mozambique</option>
                                                    <option value="Myanmar" <?php if ($country === 'Myanmar') echo 'selected'; ?>>Myanmar</option>
                                                    <option value="Namibia" <?php if ($country === 'Namibia') echo 'selected'; ?>>Namibia</option>
                                                    <option value="Nauru" <?php if ($country === 'Nauru') echo 'selected'; ?>>Nauru</option>
                                                    <option value="Nepal" <?php if ($country === 'Nepal') echo 'selected'; ?>>Nepal</option>
                                                    <option value="Netherlands" <?php if ($country === 'Netherlands') echo 'selected'; ?>>Netherlands</option>
                                                    <option value="New Caledonia" <?php if ($country === 'New Caledonia') echo 'selected'; ?>>New Caledonia</option>
                                                    <option value="New Zealand" <?php if ($country === 'New Zealand') echo 'selected'; ?>>New Zealand</option>
                                                    <option value="Nicaragua" <?php if ($country === 'Nicaragua') echo 'selected'; ?>>Nicaragua</option>
                                                    <option value="Niger" <?php if ($country === 'Niger') echo 'selected'; ?>>Niger</option>
                                                    <option value="Nigeria" <?php if ($country === 'Nigeria') echo 'selected'; ?>>Nigeria</option>
                                                    <option value="Niue" <?php if ($country === 'Niue') echo 'selected'; ?>>Niue</option>
                                                    <option value="Norfolk Island" <?php if ($country === 'Norfolk Island') echo 'selected'; ?>>Norfolk Island</option>
                                                    <option value="North Korea" <?php if ($country === 'North Korea') echo 'selected'; ?>>North Korea</option>
                                                    <option value="Northern Mariana Islands" <?php if ($country === 'Northern Mariana Islands') echo 'selected'; ?>>Northern Mariana Islands</option>
                                                    <option value="Norway" <?php if ($country === 'Norway') echo 'selected'; ?>>Norway</option>
                                                    <option value="Oman" <?php if ($country === 'Oman') echo 'selected'; ?>>Oman</option>
                                                    <option value="Pakistan" <?php if ($country === 'Pakistan') echo 'selected'; ?>>Pakistan</option>
                                                    <option value="Palau" <?php if ($country === 'Palau') echo 'selected'; ?>>Palau</option>
                                                    <option value="Panama" <?php if ($country === 'Panama') echo 'selected'; ?>>Panama</option>
                                                    <option value="Papua New Guinea" <?php if ($country === 'Papua New Guinea') echo 'selected'; ?>>Papua New Guinea</option>
                                                    <option value="Paraguay" <?php if ($country === 'Paraguay') echo 'selected'; ?>>Paraguay</option>
                                                    <option value="Peru" <?php if ($country === 'Peru') echo 'selected'; ?>>Peru</option>
                                                    <option value="Philippines" <?php if ($country === 'Philippines') echo 'selected'; ?>>Philippines</option>
                                                    <option value="Pitcairn" <?php if ($country === 'Pitcairn') echo 'selected'; ?>>Pitcairn</option>
                                                    <option value="Poland" <?php if ($country === 'Poland') echo 'selected'; ?>>Poland</option>
                                                    <option value="Portugal" <?php if ($country === 'Portugal') echo 'selected'; ?>>Portugal</option>
                                                    <option value="Puerto Rico" <?php if ($country === 'Puerto Rico') echo 'selected'; ?>>Puerto Rico</option>
                                                    <option value="Qatar" <?php if ($country === 'Qatar') echo 'selected'; ?>>Qatar</option>
                                                    <option value="Romania" <?php if ($country === 'Romania') echo 'selected'; ?>>Romania</option>
                                                    <option value="Russia" <?php if ($country === 'Russia') echo 'selected'; ?>>Russia</option>
                                                    <option value="Rwanda" <?php if ($country === 'Rwanda') echo 'selected'; ?>>Rwanda</option>
                                                    <option value="Reunion" <?php if ($country === 'Reunion') echo 'selected'; ?>>Reunion</option>
                                                    <option value="Saint Barthelemy" <?php if ($country === 'Saint Barthelemy') echo 'selected'; ?>>Saint Barthelemy</option>
                                                    <option value="Saint Helena" <?php if ($country === 'Saint Helena') echo 'selected'; ?>>Saint Helena</option>
                                                    <option value="Saint Kitts and Nevis" <?php if ($country === 'Saint Kitts and Nevis') echo 'selected'; ?>>Saint Kitts and Nevis</option>
                                                    <option value="Saint Lucia" <?php if ($country === 'Saint Lucia') echo 'selected'; ?>>Saint Lucia</option>
                                                    <option value="Saint Martin (French part)" <?php if ($country === 'Saint Martin (French part)') echo 'selected'; ?>>Saint Martin (French part)</option>
                                                    <option value="Saint Pierre and Miquelon" <?php if ($country === 'Saint Pierre and Miquelon') echo 'selected'; ?>>Saint Pierre and Miquelon</option>
                                                    <option value="Saint Vincent and the Grenadines" <?php if ($country === 'Saint Vincent and the Grenadines') echo 'selected'; ?>>Saint Vincent and the Grenadines</option>
                                                    <option value="Samoa" <?php if ($country === 'Samoa') echo 'selected'; ?>>Samoa</option>
                                                    <option value="San Marino" <?php if ($country === 'San Marino') echo 'selected'; ?>>San Marino</option>
                                                    <option value="Sao Tome and Principe" <?php if ($country === 'Sao Tome and Principe') echo 'selected'; ?>>Sao Tome and Principe</option>
                                                    <option value="Saudi Arabia" <?php if ($country === 'Saudi Arabia') echo 'selected'; ?>>Saudi Arabia</option>
                                                    <option value="Senegal" <?php if ($country === 'Senegal') echo 'selected'; ?>>Senegal</option>
                                                    <option value="Serbia" <?php if ($country === 'Serbia') echo 'selected'; ?>>Serbia</option>
                                                    <option value="Seychelles" <?php if ($country === 'Seychelles') echo 'selected'; ?>>Seychelles</option>
                                                    <option value="Sierra Leone" <?php if ($country === 'Sierra Leone') echo 'selected'; ?>>Sierra Leone</option>
                                                    <option value="Singapore" <?php if ($country === 'Singapore') echo 'selected'; ?>>Singapore</option>
                                                    <option value="Sint Maarten (Dutch part)" <?php if ($country === 'Sint Maarten (Dutch part)') echo 'selected'; ?>>Sint Maarten (Dutch part)</option>
                                                    <option value="Slovakia" <?php if ($country === 'Slovakia') echo 'selected'; ?>>Slovakia</option>
                                                    <option value="Slovenia" <?php if ($country === 'Slovenia') echo 'selected'; ?>>Slovenia</option>
                                                    <option value="Solomon Islands" <?php if ($country === 'Solomon Islands') echo 'selected'; ?>>Solomon Islands</option>
                                                    <option value="Somalia" <?php if ($country === 'Somalia') echo 'selected'; ?>>Somalia</option>
                                                    <option value="South Africa" <?php if ($country === 'South Africa') echo 'selected'; ?>>South Africa</option>
                                                    <option value="South Georgia and the South Sandwich Islands" <?php if ($country === 'South Georgia and the South Sandwich Islands') echo 'selected'; ?>>South Georgia and the South Sandwich Islands</option>
                                                    <option value="South Korea" <?php if ($country === 'South Korea') echo 'selected'; ?>>South Korea</option>
                                                    <option value="South Sudan" <?php if ($country === 'South Sudan') echo 'selected'; ?>>South Sudan</option>
                                                    <option value="Spain" <?php if ($country === 'Spain') echo 'selected'; ?>>Spain</option>
                                                    <option value="Sri Lanka" <?php if ($country === 'Sri Lanka') echo 'selected'; ?>>Sri Lanka</option>
                                                    <option value="Sudan" <?php if ($country === 'Sudan') echo 'selected'; ?>>Sudan</option>
                                                    <option value="Suriname" <?php if ($country === 'Suriname') echo 'selected'; ?>>Suriname</option>
                                                    <option value="Svalbard and Jan Mayen" <?php if ($country === 'Svalbard and Jan Mayen') echo 'selected'; ?>>Svalbard and Jan Mayen</option>
                                                    <option value="Sweden" <?php if ($country === 'Sweden') echo 'selected'; ?>>Sweden</option>
                                                    <option value="Switzerland" <?php if ($country === 'Switzerland') echo 'selected'; ?>>Switzerland</option>
                                                    <option value="Syrian Arab Republic" <?php if ($country === 'Syrian Arab Republic') echo 'selected'; ?>>Syrian Arab Republic</option>
                                                    <option value="Taiwan" <?php if ($country === 'Taiwan') echo 'selected'; ?>>Taiwan</option>
                                                    <option value="Tajikistan" <?php if ($country === 'Tajikistan') echo 'selected'; ?>>Tajikistan</option>
                                                    <option value="Tanzania, United Republic of" <?php if ($country === 'Tanzania, United Republic of') echo 'selected'; ?>>Tanzania, United Republic of</option>
                                                    <option value="Thailand" <?php if ($country === 'Thailand') echo 'selected'; ?>>Thailand</option>
                                                    <option value="Timor-Leste" <?php if ($country === 'Timor-Leste') echo 'selected'; ?>>Timor-Leste</option>
                                                    <option value="Togo" <?php if ($country === 'Togo') echo 'selected'; ?>>Togo</option>
                                                    <option value="Tokelau" <?php if ($country === 'Tokelau') echo 'selected'; ?>>Tokelau</option>
                                                    <option value="Tonga" <?php if ($country === 'Tonga') echo 'selected'; ?>>Tonga</option>
                                                    <option value="Trinidad and Tobago" <?php if ($country === 'Trinidad and Tobago') echo 'selected'; ?>>Trinidad and Tobago</option>
                                                    <option value="Tunisia" <?php if ($country === 'Tunisia') echo 'selected'; ?>>Tunisia</option>
                                                    <option value="Turkey" <?php if ($country === 'Turkey') echo 'selected'; ?>>Turkey</option>
                                                    <option value="Turkmenistan" <?php if ($country === 'Turkmenistan') echo 'selected'; ?>>Turkmenistan</option>
                                                    <option value="Tuvalu" <?php if ($country === 'Tuvalu') echo 'selected'; ?>>Tuvalu</option>
                                                    <option value="Uganda" <?php if ($country === 'Uganda') echo 'selected'; ?>>Uganda</option>
                                                    <option value="Ukraine" <?php if ($country === 'Ukraine') echo 'selected'; ?>>Ukraine</option>
                                                    <option value="United Arab Emirates" <?php if ($country === 'United Arab Emirates') echo 'selected'; ?>>United Arab Emirates</option>
                                                    <option value="United Kingdom" <?php if ($country === 'United Kingdom') echo 'selected'; ?>>United Kingdom</option>
                                                    <option value="United States" <?php if ($country === 'United States') echo 'selected'; ?>>United States</option>
                                                    <option value="Uruguay" <?php if ($country === 'Uruguay') echo 'selected'; ?>>Uruguay</option>
                                                    <option value="Uzbekistan" <?php if ($country === 'Uzbekistan') echo 'selected'; ?>>Uzbekistan</option>
                                                    <option value="Vanuatu" <?php if ($country === 'Vanuatu') echo 'selected'; ?>>Vanuatu</option>
                                                    <option value="Venezuela" <?php if ($country === 'Venezuela') echo 'selected'; ?>>Venezuela</option>
                                                    <option value="Viet Nam" <?php if ($country === 'Viet Nam') echo 'selected'; ?>>Viet Nam</option>
                                                    <option value="Western Sahara" <?php if ($country === 'Western Sahara') echo 'selected'; ?>>Western Sahara</option>
                                                    <option value="Yemen" <?php if ($country === 'Yemen') echo 'selected'; ?>>Yemen</option>
                                                    <option value="Zambia" <?php if ($country === 'Zambia') echo 'selected'; ?>>Zambia</option>
                                                    <option value="Zimbabwe" <?php if ($country === 'Zimbabwe') echo 'selected'; ?>>Zimbabwe</option>
                                                </select>
                                            </div>
                                            <div>
                                                <p class="tiny-headings">Gender:</p>
                                                <select name="gender" id="gender">
                                                    <option value="male" <?php if ($gender === 'male') echo 'selected'; ?>>Male</option>
                                                    <option value="female" <?php if ($gender === 'female') echo 'selected'; ?>>Female</option>
                                                    <option value="non-binary" <?php if ($gender === 'non-binary') echo 'selected'; ?>>Non-binary</option>
                                                </select>
                                            </div>
                                            <div>
                                                <p class="tiny-headings">Birthday:</p>
                                                <div id="select-birthday">
                                                    <select name="day" id="day">
                                                        <?php
                                                        for ($day = 31; $day >= 1; $day--) {
                                                            echo "<option value=\"$day\" " . ($selected_day == $day ? 'selected' : '') . ">$day</option>";
                                                        } ?>
                                                    </select>
                                                    <select name="month" id="month">
                                                        <?php
                                                        for ($month = 12; $month >= 1; $month--) {
                                                            echo "<option value=\"$month\" " . ($selected_month == $month ? 'selected' : '') . ">$month</option>";
                                                        } ?>
                                                    </select>
                                                    <select name="year" id="year">
                                                        <?php
                                                        for ($year = 2007; $year >= 1907; $year--) {
                                                            echo "<option value=\"$year\" " . ($selected_year == $year ? 'selected' : '') . ">$year</option>";
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="submit" id="edit-btn" value="Confirm changes" name="send-changes">
                                    <p><?= $error ?></p>

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
                        <a href="#" id="ctn-btn">Contact us</a>
                    </div>
                    <div class="column-two">
                        <ul>
                            <li><a href="../homepage/index.html">General</a></li>
                            <li><a href="../schedule/schedule.html">Schedule</a></li>
                            <li><a href="../facilities/facilities.html">Facilities</a></li>
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
        $("#tabs").tabs({
            active: 0
        }).addClass("ui-tabs-vertical ui-helper-clearfix");
        $("#tabs li").removeClass("ui-corner-top").addClass("ui-corner-left");
    });
</script>
<script src="../universal.js"></script>