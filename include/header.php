<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="#">
            <?php
            include("../include/connection.php");
            $user = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : "";
            $get_user = "SELECT * FROM users WHERE user_email = '$user'";
            $run_user = mysqli_query($con, $get_user);
            if ($run_user) {
                $row = mysqli_fetch_array($run_user);
                $user_name = isset($row['user_name']) ? $row['user_name'] : "";
                echo "<a href='../home.php?user_name=$user_name' class='navbar-brand'>MyChat</a>";
            } else {
                echo "<a href='#' class='navbar-brand'>MyChat</a>";
            }
            ?>
        </a>
        <ul class="navbar-nav">
            <li><a href="./account_settings.php" class="nav-link">Setting</a></li>
        </ul>
    </nav>
</body>

</html>
