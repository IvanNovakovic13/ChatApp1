<?php
include("../include/connection.php");
include("../include/header.php");

// Check if session is set
if (!isset($_SESSION)) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("location: signin.php");
    exit();
}

// Handle password change form submission
if (isset($_POST['change_pass'])) {
    $user = $_SESSION['user_email'];
    $current_pass = $_POST['current_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // Fetch the user's current password from the database
    $get_user = "SELECT * FROM users WHERE user_email='$user'";
    $run_user = mysqli_query($con, $get_user);

    if (!$run_user) {
        die("Error: " . mysqli_error($con));
    }

    $row = mysqli_fetch_array($run_user);
    $user_pass = $row['user_pass'];

    // Check if the current password is correct
    if ($current_pass !== $user_pass) {
        echo "<script>alert('Current password is incorrect.')</script>";
    } elseif ($new_pass !== $confirm_pass) {
        echo "<script>alert('New password and confirm password do not match.')</script>";
    } else {
        // Update the password in the database
        $update_pass = "UPDATE users SET user_pass='$new_pass' WHERE user_email='$user'";
        $run_update = mysqli_query($con, $update_pass);

        if ($run_update) {
            echo "<script>alert('Password updated successfully.')</script>";
            echo "<script>window.open('account_settings.php', '_self')</script>";
        } else {
            echo "<script>alert('Failed to update password. Please try again.')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/account_settings.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <form action="" method="post">
                    <table class="table table-bordered table-hover">
                        <tr align="center">
                            <td colspan="2" class="active">
                                <h2>Change Password</h2>
                            </td>
                        </tr>

                        <tr>
                            <td style="font-weight: bold;">Current Password</td>
                            <td>
                                <input type="password" name="current_pass" class="form-control" placeholder="Enter Current Password" required>
                            </td>
                        </tr>

                        <tr>
                            <td style="font-weight: bold;">New Password</td>
                            <td>
                                <input type="password" name="new_pass" class="form-control" placeholder="Enter New Password" required>
                            </td>
                        </tr>

                        <tr>
                            <td style="font-weight: bold;">Confirm New Password</td>
                            <td>
                                <input type="password" name="confirm_pass" class="form-control" placeholder="Confirm New Password" required>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" name="change_pass" class="btn btn-primary" value="Change Password">
                            </td>
                        </tr>

                    </table>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
