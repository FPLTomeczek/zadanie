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
<div class="users">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
        </tr>

        <?php while ($record = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $record['id']?></td>
                <td><?php echo $record['name']?></td>
                <td><a href="groups_edit.php?id=<?php echo $record['id']?>">Edit</a>
            <a href="groups.php?delete=<?php echo $record['id']?>">Delete</a></td>
            </tr>
            
            <?php }?>
    </table>
    <a href="groups_add.php">Add Group</a>
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
