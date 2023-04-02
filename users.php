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
<div class="users">
    <table>
        <tr>
            <th>Username</th>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Date Of Birth</th>
        </tr>

        <?php while ($record = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $record['username']?></td>
                <td><?php echo $record['first_name']?></td>
                <td><?php echo $record['second_name']?></td>
                <td><?php echo $record['dob']?></td>
                <td><a href="users_edit.php?id=<?php echo $record['id']?>">Edit</a>
            <a href="users.php?delete=<?php echo $record['id']?>">Delete</a></td>
            </tr>
            
            <?php }?>
    </table>
    <a href="users_add.php">Add User</a>
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
