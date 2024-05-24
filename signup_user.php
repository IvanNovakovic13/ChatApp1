<?php
include("include/connection.php");

if (isset($_POST['sign_up'])) {
    $name = htmlentities(mysqli_real_escape_string($con, $_POST['user_name']));
    $pass = htmlentities(mysqli_real_escape_string($con, $_POST['user_pass']));
    $email = htmlentities(mysqli_real_escape_string($con, $_POST['user_email']));
    $country = htmlentities(mysqli_real_escape_string($con, $_POST['user_country']));
    $gender = htmlentities(mysqli_real_escape_string($con, $_POST['user_gender']));
    $rand = rand(1, 2);

    if (empty($name)) {
        echo "<script>alert('Ne mozemo da verifikujemo tvoje ime')</script>";
        exit();
    }

    if (strlen($pass) < 8) {
        echo "<script>alert('Sifra mora imati bar 8 karaktera')</script>";
        exit();
    }

    $check_email = "SELECT * FROM users WHERE user_email='$email'";
    $run_email = mysqli_query($con, $check_email);

    $check = mysqli_num_rows($run_email);

    if ($check == 1) {
        echo "<script>alert('Email vec postoji, probajte opet')</script>";
        echo "<script>window.open('signup.php', '_self')</script>";
        exit();
    }

    if ($rand == 1) {
        $profile_pic = "images/slika1.jpg";
    } else {
        $profile_pic = "images/slika2.jpg";
    }

    $insert = "INSERT INTO users (user_name, user_pass, user_email, user_profile, user_country, user_gender) VALUES ('$name', '$pass', '$email', '$profile_pic', '$country', '$gender')";

    $query = mysqli_query($con, $insert);

    if ($query) {
        echo "<script>alert('Cestitke, $name, vas nalog je uspesno kreiran')</script>";
        echo "<script>window.open('signin.php', '_self')</script>";
    } else {
        echo "<script>alert('Nazalost, vas nalog nije kreiran')</script>";
        echo "<script>window.open('signup.php', '_self')</script>";
    }
}
?>
