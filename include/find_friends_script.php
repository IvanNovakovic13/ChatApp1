<?php
$con = mysqli_connect("localhost", "root", "", "mychat") or die("Connection was not established");

function search_user()
{
    global $con;

    if (isset($_GET['search_btn'])) {
        $search_query = htmlentities($_GET['search_query']);
        $get_user = "SELECT * FROM users WHERE user_name LIKE '%$search_query%' OR user_country LIKE '%$search_query%'";
    } else {
        $get_user = "SELECT * FROM users ORDER BY user_country, user_name DESC LIMIT 5";
    }

    $run_user = mysqli_query($con, $get_user);

    while ($row_user = mysqli_fetch_array($run_user)) {
        $user_name = $row_user['user_name'];
        $user_profile = $row_user['user_profile'];
        $country = $row_user['user_country'];
        $gender = $row_user['user_gender'];

        echo "
        <div style='display: flex; flex-direction: column; align-items: center; justify-content: center;'>
        <div class='card' style='width: 18rem; margin: 10px; border: 1px solid #999; padding: 10px;'>
        <img src='../$user_profile' alt='Profile Picture' style='width:100px;height:100px;'>
            <h2>$user_name</h2>
            <p>Country: $country</p>
            <p>Gender: $gender</p>
            <form action='' method='post'>
                <p> <button name='add_friend' class='btn btn-primary'>Add Friend</button> </p>
            </form>

        </div>
        </div>
        <br>
        <br>
        ";

        if (isset($_POST['add_friend'])) {
            echo "<script> window.open('../home.php?user_name=$user_name', '_self') </script>";
        }
    }
}
