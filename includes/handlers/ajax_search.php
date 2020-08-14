<?php
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];
$names = explode(" ",$query);
//If query contains an Underscore ,assume user is searching for username
if(strpos($query,'_')!==false)
{
    $usersReturnedQuery = mysqli_query($con,"SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
}
else if(count($names)==2)
{
    $usersReturnedQuery = mysqli_query($con,"SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
}
else
{
    $usersReturnedQuery = mysqli_query($con,"SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}

if($query!="")
{
    while($row = mysqli_fetch_array($usersReturnedQuery))
    {
        $user = new User($con,$userLoggedIn);

        if($row['username']!=$userLoggedIn)
        {
            $mutual_friends = $user->getMutualFriends($userLoggedIn) . "friends in common";
        }
        else
        {
            $mutual_friends ="";
        }
        echo "<div class='resultDisplay'>
                <a href='" . $row['username'] . "' style='color:#1485BD'>
                <div class='liveSearchProfilePic'>
                <img src='" .$row['profile_pic'] . "'</div>
                <div class='liveSearchText'>
                " .$row['first_name'] .  " " . $row['last_name'] ."
                <p id='grey'>" . $mutual_friends . "
                </a>
                </div>";
                
            
    }
}
?>