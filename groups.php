<?php 

include('includes/database.php');
include('includes/config.php');
include('includes/functions.php');
include('includes/header.php');

secureRoute();
getMessage();

if(isset($_GET['delete'])){
     if($stm = $connect->prepare("DELETE FROM groups WHERE id = ?")){
        $stm->bind_param('i',$_GET['delete']);
        $stm->execute();
        $stm->close();
        setMessage("Group with ID: ".$_GET['delete']." deleted");
        getMessage();
        unset($_GET['delete']);
    }
    else{
        echo 'Could not prepare statement';
    }
}

if(isset($_SESSION['id'])){

    if($stm = $connect->prepare("SELECT id, name FROM groups")){
        $stm->execute();
        $result = $stm->get_result();
        
        if($result->num_rows > 0){

?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h1 class="display-1 text-center">Groups</h1>
            <a href="groups_add.php"><button class="btn btn-primary">Add Group</button></a>
            <table class="table table-striped table-hover mt-4">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>

                <?php while ($record = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $record['id']?></td>
                    <td><?php echo $record['name']?></td>
                    <td><a href="groups_edit.php?id=<?php echo $record['id']?>"><button
                                class="btn btn-warning">Edit</button></a>
                        <a href="groups.php?delete=<?php echo $record['id']?>"><button
                                class="btn btn-danger">Delete</button></a>
                    </td>
                </tr>

                <?php }?>
            </table>
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
include('includes/footer.php');
?>