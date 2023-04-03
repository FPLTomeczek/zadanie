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
        setMessage("User with ID: ".$_GET['deleteU']." deleted from group with ID: ".$_GET['deleteG']);
        header("Location: users_edit.php?id=".$_GET['deleteU']);
        die();
        }
    else{
            echo 'Could not prepare delete users_groups statement';
    }
}

if(isset($_GET['id'])){
    if($stm = $connect->prepare("SELECT * FROM users WHERE id = ?")){
        $stm->bind_param('i',$_GET['id']);
        $stm->execute();
        $result = $stm->get_result();
        $stm->close();
        $user = $result->fetch_assoc();
    }
    else{
        echo 'Could not prepare statement';
    }
    if($stm = $connect->prepare("SELECT g.id, g.name
                FROM groups g 
                JOIN users_groups ug ON g.id=ug.group_id
                JOIN users u ON ug.user_id = u.id
                WHERE u.id = ?")){
                    $stm->bind_param('i', $_GET['id']);
                    $stm->execute();
                    $resultGroups = $stm->get_result();
                    $stm->close();
                  }
                else{
                    echo 'Could not prepare users statement';
                }

    if(isset($_POST['first_name'])){
        $validateQuery = validateUser($_POST['first_name'], $_POST['second_name'], $_POST['dob'], $_POST['username'], $_POST['password']);
    
        if($validateQuery){

        if($stm = $connect->prepare("UPDATE users SET username=?, first_name=?, second_name=?, dob=?, password=? WHERE id = ?")){
            $hashed = SHA1($_POST['password']);
            $stm->bind_param('sssssi',$_POST['username'], $_POST['first_name'],$_POST['second_name'],$_POST['dob'],$hashed, $_GET['id']);
            $stm->execute();
            $stm->close();
            setMessage("User: ".$_POST['username']." edited.");
         }
     else{
        echo 'Could not prepare statement';
     }
     header("Location: users.php");
    }
    }
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">

                <div class="form-outline <?php echo (isset($_SESSION['e_first_name']) ? "mb-1":"form-outline mb-4") ?>">
                    <input type="text" id="first_name" name="first_name" class="form-control"
                        value=<?php echo $user['first_name']?>>
                    <label for="first_name" class="form-label">First Name: </label>
                </div>
                <?php 
			if(isset($_SESSION['e_first_name'])){
				echo '<span class="text-danger">'.$_SESSION['e_first_name'].'</span>';
				unset($_SESSION['e_first_name']);
			}
		?>

                <div
                    class="form-outline <?php echo (isset($_SESSION['e_second_name']) ? "mb-1":"form-outline mb-4") ?>">
                    <input type="text" id="second_name" name="second_name" class="form-control"
                        value=<?php echo $user['second_name']?>>
                    <label for="second_name" class="form-label">Second Name: </label>
                </div>

                <?php 
			if(isset($_SESSION['e_second_name'])){
				echo '<span class="text-danger">'.$_SESSION['e_second_name'].'</span>';
				unset($_SESSION['e_second_name']);
			}
		?>

                <div class="form-outline <?php echo (isset($_SESSION['e_dob']) ? "mb-1":"form-outline mb-4") ?>">
                    <input type="date" id="dob" name="dob" class="form-control" value=<?php echo $user['dob']?>>
                    <label for="dob" class="form-label">Date of Birth: </label>
                </div>

                <?php 
			if(isset($_SESSION['e_dob'])){
				echo '<span class="text-danger">'.$_SESSION['e_dob'].'</span>';
				unset($_SESSION['e_dob']);
			}
		?>

                <div class="form-outline <?php echo (isset($_SESSION['e_username']) ? "mb-1":"form-outline mb-4") ?>">
                    <input type="text" id="username" name="username" class="form-control"
                        value=<?php echo $user['username']?>>
                    <label for="email" class="form-label">Username: </label>
                </div>

                <?php 
			if(isset($_SESSION['e_username'])){
				echo '<span class="text-danger">'.$_SESSION['e_username'].'</span>';
				unset($_SESSION['e_username']);
			}
		?>

                <div class="form-outline <?php echo (isset($_SESSION['e_password']) ? "mb-1":"form-outline mb-4") ?>">
                    <input type="password" id="password" class="form-control" name="password">
                    <label for="password" class="form-label">Password: </label>
                </div>

                <?php 
			if(isset($_SESSION['e_password'])){
				echo '<span class="text-danger">'.$_SESSION['e_password'].'</span><br/>';
				unset($_SESSION['e_password']);
			}
		?>
                <button type="submit" class="btn btn-primary">Edit User</button>

            </form>
            <h4 class="display-4 mt-4">User Groups</h4>
            <table class="table table-striped table-hover mt-4">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>

                <?php while ($record = mysqli_fetch_assoc($resultGroups)) { ?>
                <tr>
                    <td><?php echo $record['id']?></td>
                    <td><?php echo $record['name']?></td>
                    <td>
                        <a href="users_edit.php?deleteU=<?php echo $_GET['id']?>&deleteG=<?php echo $record['id']?>"><button
                                class="btn btn-danger">Leave</button></a>
                    </td>
                </tr>

                <?php }?>
            </table>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');