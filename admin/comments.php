<?php
session_start();
$pagetitle = 'Comments';
if (isset($_SESSION['username']))
{
    include 'init.php';
    $do = isset($_GET['do'])? $_GET['do'] : 'manage';
    if ($do == 'manage')//============================== manage page ===========
    {
        $stmt = $con->prepare("SELECT comments.*, items.name, users.Username FROM comments
                                       INNER JOIN items ON item_id = item
                                       INNER JOIN  users ON UserID = user_id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Add Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($rows as $row)
                    {
                    ?>
                    <tr>
                        <td><?php echo $row['c_id'] ?></td>
                        <td><?php echo $row['comment'] ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['Username'] ?></td>
                        <td><?php echo $row['comment_date'] ?></td>
                        <td>
                            <a href="?do=Edit&comid=<?php echo $row['c_id'] ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="?do=Delete&comid=<?php echo $row['c_id'] ?>" class="btn btn-danger confirm"><i class="fa fa-trash"></i> Delete</a>
                            <?php if($row['status'] == 0)
                            {
                                echo '<a href="comments.php?do=activate&comid= '.$row['c_id'].'" class="btn btn-info activate"><i class="fa fa-check"></i> Activate</a>
';
                            }
                            ?>
                        </td>
                        <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>

        <?php

    }
    elseif ($do == 'Edit')//============================ edit page =============
    {
        $comid =isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):'';

        $stmt = $con->prepare("SELECT * FROM comments 
                                        
                                        WHERE c_id = ? LIMIT 1");
        $stmt->execute([$comid]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0 )
        {
            ?>
            <h1 class="text-center">Edit Comments</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update" method="post">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>">
                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Comment</label>
                        <div class="col-md-4 ">
                            <textarea required="required" class="form-control" rows="10" name="comment"><?php echo $row['comment']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class=" col-md-offset-5 col-md-4 ">
                            <input type="submit" class="btn btn-primary" value="save">
                        </div>
                    </div>
                </form>

            </div>
            <?php
        }else
        {
            $themsg = '<div class="alert alert-danger">there\'s not such ID</div>';
            redirecthome($themsg);
        }
    }elseif ($do == 'update')//======================= update page ================
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $comid    = $_POST['comid'];
            $comment  = $_POST['comment'];

            //validate inputes
            $form_errors = [];
            if (empty($comment))
            {
                $form_errors[] = 'Comment can\'t be empty';
            }
            echo '<div class="container">';
            foreach ($form_errors as $error)
            {
                echo '<div class="alert alert-danger">'. $error.'</div>';
            }
            echo '</div>';
            if (empty($form_errors))
            {

                    $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ? ");
                    $stmt->execute([$comment,$comid]);
                    $themsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record updated successfully</div>';
                    redirecthome($themsg,'back');

            }
        }else
        {
            $themsg =  "<div class='alert alert-danger'>you can not browse this page directly</div>";
            redirecthome($themsg);
        }
    }elseif ($do == 'Delete')//============================ delete page =============
    {
        $comid =isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):'';
        $check = checkitem('c_id','comments',$comid);

        if ($check > 0 )
        {
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = ? ");
            $stmt->execute([$comid]);
            $themsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Deleted successfully</div>';
            redirecthome($themsg);
        }
    }elseif ( $do == 'activate')//============================ activate page =============
    {
        $comid =isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']):'';
        $check = checkitem('c_id','comments',$comid);

        if ($check > 0 )
        {
            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ? ");
            $stmt->execute([$comid]);
            $themsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record activated successfully</div>';
            redirecthome($themsg);
        }
    }
}
else
{
    header('location:index1.php');
    exit();
}


?>





<?php include 'includes/templates/footer.php'; ?>

