<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
<?php
require_once 'core/init.php';

if(Session::exists('home')) {
    echo Session::flash('home');
}

$user = new User(); // current user

if($user->isLoggedIn()) {
    
?>
    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></p>
    <ul>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="update.php">Update details</a></li>
        <li><a href="changepassword.php">Change password</a></li>
    </ul>
<?php
if($user->hasPermission('admin')) {
    echo 'You are an administrator';
}

} else {
    echo 'you need to <a href="login.php">login</a> or <a href="register.php">register</a>';
}
?>

</body>
</html>

