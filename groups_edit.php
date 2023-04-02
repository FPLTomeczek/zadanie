<?php

include('includes/database.php');
include('includes/config.php');
include('includes/header.php');
include('includes/functions.php');

secureRoute();
getMessage();

if(isset($_GET['deleteU'])){
    if($stm = $connect->prepare("DELETE FROM users_groups WHERE user_id = ? AND group_id = ?")){
        $stm->bind_param('ii',$_GET['deleteU'], $_GET['deleteG']);
        $stm->execute();
        setMessage("User with ID: ".$_GET['deleteU']." deleted.");
        header("Location: groups_edit.php?id=".$_GET['deleteG']);
        }
    else{
            echo 'Could not prepare delete users_groups statement';
    }
}

if(isset($_GET['id'])){
    if($stm = $connect->prepare("SELECT * FROM groups WHERE id = ?")){
        $stm->bind_param('i',$_GET['id']);
        $stm->execute();
        $result = $stm->get_result();
        $stm->close();
        $group = $result->fetch_assoc();

        if($stm = $connect->prepare("SELECT u.id, u.username, u.first_name, u.second_name, u.dob
        FROM users u 
        JOIN users_groups ug ON u.id=ug.user_id
        JOIN groups g ON ug.group_id = g.id
        WHERE g.id = ?")){
            $stm->bind_param('i', $_GET['id']);
            $stm->execute();
            $resultUsers = $stm->get_result();
            $stm->close();
        }
        else{
            echo 'Could not prepare users statement';
        }

        if($stm = $connect->prepare("SELECT id, username FROM users ")){
            $stm->execute();
            $resultAddUsers = $stm->get_result();
            $stm->close();
        }
        else{
            echo 'Could not prepare users statement';
        }



    }
    else{
        echo 'Could not prepare groups statement';
    }

    if(isset($_POST['name'])){
        if($stm = $connect->prepare("UPDATE groups SET name=? WHERE id=?")){
        $stm->bind_param('si',$_POST['name'], $_GET['id']);
        $stm->execute();
        $stm->close();
        setMessage("Group: ".$_POST['name']." edited.");
     }
     else{
        echo 'Could not prepare statement';
     }
     header("Location: groups.php");
    }
}

if(isset($_POST['add_user'])){

    if($stm = $connect->prepare("SELECT u.*
        FROM users u 
        JOIN users_groups ug ON u.id=ug.user_id
        JOIN groups g ON ug.group_id = g.id
        WHERE g.id = ? AND u.id = ?")){
        $stm->bind_param('ii',$_GET['id'], $_POST['add_user']);
        $stm->execute();
        $result = $stm->get_result();
        $stm->close();
        if($result->num_rows > 0){
            //setMessage()
        }
        else{
            if($stm = $connect->prepare("INSERT INTO users_groups (user_id, group_id) VALUES (?,?)")){
                $stm->bind_param('ii',$_POST['add_user'],$_GET['id']);
                $stm->execute();
                $stm->close();
                setMessage("User with ID: ".$_POST['add_user']." added.");
                getMessage();

                if($stm = $connect->prepare("SELECT u.id, u.username, u.first_name, u.second_name, u.dob
                FROM users u 
                JOIN users_groups ug ON u.id=ug.user_id
                JOIN groups g ON ug.group_id = g.id
                WHERE g.id = ?")){
                    $stm->bind_param('i', $_GET['id']);
                    $stm->execute();
                    $resultUsers = $stm->get_result();
                    $stm->close();
                  }
                else{
                    echo 'Could not prepare users statement';
                }
            }else{
                echo 'Could not prepare statement Insert';
            }
         }
    
}else{
    echo 'Could not prepare statement Select';
}
}

?>

<div>
    <form method="post">

        <div class="form-element">
            <label for="id">ID: </label>
            <input type="text" id="id" name="id" value=<?php echo $group['id']?> disabled>
        </div>      
 
        
        <div class="form-element">
            <label for="name">Group Name: </label>
            <input type="text" id="name" name="name" value=<?php echo $group['name']?>>
        </div>      


      <div class="users">
    <table>
        <tr>
            <th>Username</th>
            <th>First Name</th>
            <th>Second Name</th>
            <th>Date Of Birth</th>
        </tr>

        <?php while ($record = mysqli_fetch_assoc($resultUsers)) { ?>
            <tr>
                <td><?php echo $record['username']?></td>
                <td><?php echo $record['first_name']?></td>
                <td><?php echo $record['second_name']?></td>
                <td><?php echo $record['dob']?></td>
                <td><a href="groups_edit.php?deleteU=<?php echo $record['id']?>&deleteG=<?php echo $_GET['id']?>">Delete</a></td>
            </tr>
            
            <?php }?>
    </table>
</div>



        

        <button type="submit">Edit Group</button>

    </form>


    <form method="post">
            <div class="form-element">
            <select name="add_user" id="add_user" >
                <?php while ($record = mysqli_fetch_assoc($resultAddUsers)) { ?>
                    <option value=<?php echo $record['id']?>><?php echo $record['username']?></option>
            <?php }?>
            </select>
        </div>

        <button type="submit">Add To Group</button>

    </form>
</div>