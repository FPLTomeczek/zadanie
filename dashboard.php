<?php
    include('includes/config.php');
    include('includes/functions.php');
    include('includes/header.php');

    secureRoute();

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Welcome, <?php echo $_SESSION['username']; ?></h1>
            <div class="d-flex justify-content-center">
                <a href="users.php"><button type="button" class="btn btn-primary mx-2">Users</button></a>
                <a href="groups.php"><button type="button" class="btn btn-primary mx-2">Groups</button></a>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');