<!DOCTYPE html>
<?php
include("include/connection.php");

// Provjeravamo postoji li sesija
if(!isset($_SESSION)) {
    session_start();
}

// Provjeravamo je li korisnik prijavljen
if(!isset($_SESSION['user_email'])){
    header("location: signin.php");
    exit(); // Dodajemo exit nakon header-a kako bi se spriječilo daljnje izvršavanje skripte
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Chat - HOME</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    
    <div class="container main-section">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12 left-sidebar">
                <div class="input-group searchbox">
                    <div class="input-group-btn">
                        <center><a href="include/find_friends.php"><button class="btn btn-default search-icon" name="search_user" type="submit">Add New User</button></a></center>
                    </div>
                </div>
                <div class="left-chat">
                    <ul>
                        <?php include("include/get_users_data.php"); ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12 right-sidebar">
                <div class="row">
                    <!-- Fetching logged in user data -->
                    <?php
                        if (isset($_SESSION['user_email'])) {
                            $user = $_SESSION['user_email'];
                            $get_user = "SELECT * FROM users WHERE user_email='$user'";
                            $run_user = mysqli_query($con, $get_user);

                            if ($run_user && mysqli_num_rows($run_user) > 0) {
                                $row = mysqli_fetch_array($run_user);

                                $user_id = $row['user_id'];
                                $user_name = $row['user_name'];
                            } else {
                                echo "<div class='alert alert-danger'><strong>User not found.</strong></div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'><strong>No user session found.</strong></div>";
                            exit();
                        }
                    ?>
                    <!-- Fetching user data on click -->
                    <?php
                        if (isset($_GET['user_name'])) {
                            $get_username = $_GET['user_name'];
                            $get_user = "SELECT * FROM users WHERE user_name='$get_username'";
                            $run_user = mysqli_query($con, $get_user);

                            if ($run_user && mysqli_num_rows($run_user) > 0) {
                                $row_user = mysqli_fetch_array($run_user);
                                $username = $row_user['user_name'];
                                $user_profile_image = $row_user['user_profile'];
                            } else {
                                echo "<div class='alert alert-danger'><strong>User not found.</strong></div>";
                            }
                        }

                        $total_messages = "SELECT * FROM users_chats WHERE (sender_username = '$user_name' AND receiver_username = '$username') OR (receiver_username = '$user_name' AND sender_username = '$username')";

                        $run_messages = mysqli_query($con, $total_messages);
                        $total = $run_messages ? mysqli_num_rows($run_messages) : 0;
                    ?>
                    <div class="col-md-12 right-header">
                        <div class="right-header-img">
                            <img src="<?php echo $user_profile_image; ?>" alt="">
                        </div>
                        <div class="right-header-detail">
                            <form action="" method="post">
                                <p><?php echo isset($username) ? $username : ''; ?></p>
                                <span><?php echo $total; ?> messages</span>&nbsp &nbsp
                                <button name="logout" class="btn btn-danger">Logout</button>
                            </form>
                            <?php
                                if (isset($_POST['logout'])) {
                                    $update_msg = mysqli_query($con, "UPDATE users SET log_in='Offline' WHERE user_name='$user_name'");
                                    header("Location: logout.php");
                                    exit();
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="scrolling_to_bottom" class="col-md-12 right-header-contentChat">
                        <?php
                            if (isset($username)) {
                                $update_msg = mysqli_query($con, "UPDATE users_chats SET msg_status='read' WHERE sender_username='$user_name' AND receiver_username='$username'");

                                $sel_msg = "SELECT * FROM users_chats WHERE (sender_username='$user_name' AND receiver_username='$username') OR (receiver_username='$user_name' AND sender_username='$username') ORDER BY 1 ASC";

                                $run_msg = mysqli_query($con, $sel_msg);

                                while ($run_msg && $row = mysqli_fetch_array($run_msg)) {
                                    $sender_username = $row['sender_username'];
                                    $receiver_username = $row['receiver_username'];
                                    $msg_content = $row['msg_content'];
                                    $msg_date = $row['msg_date'];
                        ?>
                        <ul>
                            <?php
                                if ($user_name == $sender_username && $username == $receiver_username) {
                                    echo "
                                        <li>
                                            <div class='rightside-chat'>
                                                <span>$username <small>$msg_date</small></span>
                                                <p>$msg_content</p>
                                            </div>
                                        </li>
                                    ";
                                } elseif ($user_name == $receiver_username && $username == $sender_username) {
                                    echo "
                                        <li>
                                            <div class='rightside-chat'>
                                                <span>$username <small>$msg_date</small></span>
                                                <p>$msg_content</p>
                                            </div>
                                        </li>
                                    ";
                                }
                            ?>
                        </ul>
                        <?php 
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 right-chat-textbox">
                        <form method="post">
                            <input type="text" name="msg_content" autocomplete="off" placeholder="Write a message here...">
                            <button class="btn" name="submit"><i class="fa fa-telegram" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        if (isset($_POST['submit'])) {
            $msg = htmlentities($_POST['msg_content']);
            
            if ($msg == "") {
                echo "
                    <div class='alert alert-danger'>
                        <strong><center>Message was not sent</center></strong>
                    </div>
                ";
            } elseif (strlen($msg) > 100) {
                echo "
                    <div class='alert alert-danger'>
                        <strong><center>Message is too long</center></strong>
                    </div>
                ";
            } else {
                $insert = "INSERT INTO users_chats (sender_username, receiver_username, msg_content, msg_status, msg_date) VALUES ('$user_name', '$username', '$msg', 'unread', NOW())";
                $run_insert = mysqli_query($con, $insert);
            }
        }
    ?>
</body>
</html>
