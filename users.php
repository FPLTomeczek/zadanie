<?php 

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');
include('includes/header.php');

secureRoute();
getMessage();

if(isset($_GET['delete'])){
     if($stm = $connect->prepare("DELETE FROM users WHERE id = ?")){
        $stm->bind_param('i',$_GET['delete']);
        $stm->execute();
        $stm->close();
        setMessage("User with ID: ".$_GET['delete']." deleted.");
        unset($_GET['delete']);
        header("Location: users.php");
    }
    else{
        echo 'Could not prepare statement';
    }
}

if(isset($_SESSION['id'])){

    if($stm = $connect->prepare("SELECT id, username, first_name, second_name, dob FROM users")){
        $stm->execute();
        $result = $stm->get_result();
        
        if($result->num_rows > 0){

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="display-1 text-center">Users</h1>
            <a href="users_add.php"><button class="btn btn-primary mb-2">Add User</button> </a>
    <table class="table table-striped table-hover">
        <tr>
            <th>Username</th>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Date Of Birth</th>
            <th>Action</th>
        </tr>

        <?php while ($record = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $record['username']?></td>
                <td><?php echo $record['first_name']?></td>
                <td><?php echo $record['second_name']?></td>
                <td><?php echo $record['dob']?></td>
                <td><a href="users_edit.php?id=<?php echo $record['id']?>"><button class="btn btn-warning">Edit</button></a>
            <a href="users.php?delete=<?php echo $record['id']?>"><button class="btn btn-danger">Delete</button></a></td>
            </tr>
            
            <?php }?>
    </table>
    
</div>
</div>
    </div>
</div>

<?php 
        }
        else{
            echo "No users";
        }
        
        $stm->close();
    }else{
        echo "Could not prepare statement";
    }
}
?>

<?php
include('includes/footer.php');
