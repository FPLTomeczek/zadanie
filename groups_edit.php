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
        die();
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
        if(validateGroup($_POST['name'])){
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
            $_SESSION['e_add_user'] = "User already in group.";
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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <form method="post">

                <div class="form-outline mb-4">
                    <input type="text" id="id" name="id" class="form-control" value=<?php echo $group['id']?> disabled>
                    <label for="id" class="form-label">ID: </label>
                </div>


                <div class="form-outline <?php echo (isset($_SESSION['e_name']) ? "mb-1":"form-outline mb-2") ?>">
                    <input type="text" id="name" name="name" class="form-control" value=<?php echo $group['name']?>>
                    <label for="name" class="form-label">Group Name: </label>
                </div>

                <?php 
                if(isset($_SESSION['e_name'])){
                    echo '<span class="text-danger">'.$_SESSION['e_name'].'</span><br/>';
                    unset($_SESSION['e_name']);
                }
            ?>
                <button type="submit" class="btn btn-warning">Edit Group</button>

            </form>
            <h4 class="display-4 mt-4">Users</h4>
            <table class="table table-striped table-hover mt-2">
                <tr>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Second Name</th>
                    <th>Date Of Birth</th>
                    <th>Action</th>
                </tr>

                <?php while ($record = mysqli_fetch_assoc($resultUsers)) { ?>
                <tr>
                    <td><?php echo $record['username']?></td>
                    <td><?php echo $record['first_name']?></td>
                    <td><?php echo $record['second_name']?></td>
                    <td><?php echo $record['dob']?></td>
                    <td><a href="groups_edit.php?deleteU=<?php echo $record['id']?>&deleteG=<?php echo $_GET['id']?>"><button
                                class="btn btn-danger">Delete</button></a></td>
                </tr>

                <?php }?>
            </table>
        </div>
    </div>
</div>


<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-element">
                    <select name="add_user" id="add_user" class="form-select mb-2">
                        <?php while ($record = mysqli_fetch_assoc($resultAddUsers)) { ?>
                        <option value=<?php echo $record['id']?>><?php echo $record['username']?></option>
                        <?php }?>
                    </select>
                </div>
                <?php 
			if(isset($_SESSION['e_add_user'])){
				echo '<span class="text-danger">'.$_SESSION['e_add_user'].'</span><br/>';
				unset($_SESSION['e_add_user']);
			}
		?>
                <button type="submit" class="btn btn-primary">Add To Group</button>

            </form>
        </div>
    </div>
</div>


<?php
include('includes/footer.php');