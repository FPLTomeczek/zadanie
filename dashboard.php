<?php
    include('includes/config.php');
    include('includes/functions.php');
    include('includes/header.php');

    secureRoute();

?>

<div class="dashboard">
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <a href="users.php">Users</a>
    <a href="groups.php">Groups</a>
</div>