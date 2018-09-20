<?php

/*===========================================================
==                       members page
== from here you can add | edit | update | delete members
=============================================================
*/
    session_start();
    $pagetitle = 'Members';
    if (isset($_SESSION['username']))
    {
        include 'init.php';
        $do = isset($_GET['do'])? $_GET['do'] : 'manage';
        if ($do == 'manage')//============================== manage page ===========
        {
            $query = '';
            if (isset($_GET['page'])&& $_GET['page'] == 'pending')
            {
                $query = 'AND RagStatus = 0';
            }
            $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
            $stmt->execute();
            $rows = $stmt->fetchAll();
?>
            <h1 class="text-center">Manage Member</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table">
                        <tr>
                            <td>#ID</td>
                            <td>UserName</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Register Date</td>
                            <td>Control</td>
                        </tr>

                        <?php
            foreach ($rows as $row)
            {
                         ?>
                        <tr>
                            <td><?php echo $row['UserID'] ?></td>
                            <td><?php echo $row['Username'] ?></td>
                            <td><?php echo $row['Email'] ?></td>
                            <td><?php echo $row['FullName'] ?></td>
                            <td><?php echo $row['regdate'] ?></td>
                            <td>
                                <a href="?do=Edit&userid=<?php echo $row['UserID'] ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="?do=Delete&userid=<?php echo $row['UserID'] ?>" class="btn btn-danger confirm"><i class="fa fa-trash"></i> Delete</a>
                              <?php if($row['RagStatus'] == 0)
                              {
                                  echo '<a href="?do=activate&userid= '.$row['UserID'].'" class="btn btn-info activate"><i class="fa fa-check"></i> Activate</a>
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
                <a href="?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Member</a>
            </div>

<?php

        }
        elseif ($do == 'Add')//============================ add page =============
        {
?>
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=insert" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >User Name :</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Password :</label>
                        <div class="col-md-4 ">
                            <input type="password" required="required" class="form-control password" name="password">
                            <i class="show-pass fa fa-eye fa-1x"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Email :</label>
                        <div class="col-md-4 ">
                            <input required="required" type="email" class="form-control" name="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Full Name :</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="full">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >avatar</label>
                        <div class="col-md-4 ">
                            <input required="required" type="file" class="form-control" name="avatar">
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
        }
        elseif ($do == 'insert')//============================ insert page =============
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $avatarname = $_FILES['avatar']['name'];
                $avatarsize = $_FILES['avatar']['size'];
                $avatartmp  = $_FILES['avatar']['tmp_name'];
                $avatartype = $_FILES['avatar']['type'];
                $avatarextension = ['jpeg','jpg','gif','png'];

                $avatarextensiontype = strtolower(end(explode('.',$avatarextension)));
                $user  = $_POST['username'];
                $full  = $_POST['full'];
                $email = $_POST['email'];
                $pas   = $_POST['password'];
                $pass  = sha1($_POST['password']);
                //validate inputes
                $form_errors = [];
                if (empty($user))
                {
                    $form_errors[] = 'Username can\'t be empty';
                }
                if (empty($pas))
                {
                    $form_errors[] = 'Password can\'t be empty';
                }
                if (empty($full))
                {
                    $form_errors[] = 'Full Name can\'t be empty';
                }
                if (empty($email))
                {
                    $form_errors[] = 'Email can\'t be empty';
                }
                if(! empty($avatarname) && ! in_array($avatarextensiontype, $avatarextension))
                {
                    $form_errors[] = "this extension not allowed";
                }
                if(empty($avatarname))
                {
                    $form_errors[] = 'avatar is required';
                }
                if ($avatarsize > 4194304)
                {
                    $form_errors[] = 'avatar can\'t be lager than 4mb';
                }
                echo '<div class="container">';
                foreach ($form_errors as $error)
                {
                    echo '<div class="alert alert-danger">'. $error.'</div>';
                }
                echo '</div>';
                if (empty($form_errors))
                {
                    $check = checkitem('Username','users',$user);
                    if ($check == 0)
                    {
                        $avatar = rand(0 , 100000).'_'.$avatarname;
                        move_uploaded_file($avatartmp,'layout\img\\'.$avatar);
                        $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, regdate, RagStatus, avatar) 
                                                    VALUES( :username, :pass, :email, :fullname, now(),1, :avatar)");
                        $stmt->execute([
                            'username' => $user, 'pass' => $pass, 'email' => $email, 'fullname' => $full, 'avatar' => $avatar
                        ]);
                        $themsg = '<div class="alert alert-success">'. $stmt->rowCount().'Record Saved successfully</div>';
                        redirecthome($themsg,'back');
                    }else
                        {
                            $themsg = '<div class="alert alert-danger">this username exist try another username</div>';
                            redirecthome($themsg,'back');
                        }
                }
            }else
            {
                $themsg= '<div class="alert alert-danger">you can\'t browse this page directly</div>';
                redirecthome($themsg);
            }
        }
        elseif ($do == 'Edit')//============================ edit page =============
        {
            $userid =isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):'';

            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute([$userid]);
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0 )
            {
?>
                <h1 class="text-center">Edit Members</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="post">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <div class="form-group">
                            <label class="col-md-2 col-md-offset-3" >User Name :</label>
                            <div class="col-md-4 ">
                                <input required="required" value="<?php echo $row['Username']; ?>" type="text" class="form-control" name="username">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 col-md-offset-3" >Password :</label>
                            <div class="col-md-4 ">
                                <input type="hidden" name="oldpassword" value="<?php $row['Password'] ?>">
                                <input type="password" placeholder="you can leave it empty" class="form-control" name="newpassword">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 col-md-offset-3" >Email :</label>
                            <div class="col-md-4 ">
                                <input required="required" value="<?php echo $row['Email']; ?>" type="email" class="form-control" name="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 col-md-offset-3" >Full Name :</label>
                            <div class="col-md-4 ">
                                <input required="required" value="<?php echo $row['FullName']; ?>" type="text" class="form-control" name="full">
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
                $id    = $_POST['userid'];
                $user  = $_POST['username'];
                $full  = $_POST['full'];
                $email = $_POST['email'];
                $pass  = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
                //validate inputes
                $form_errors = [];
                if (empty($user))
                {
                    $form_errors[] = 'Username can\'t be empty';
                }
                if (empty($full))
                {
                    $form_errors[] = 'Full Name can\'t be empty';
                }
                if (empty($email))
                {
                    $form_errors[] = 'Email can\'t be empty';
                }
                echo '<div class="container">';
                foreach ($form_errors as $error)
                {
                    echo '<div class="alert alert-danger">'. $error.'</div>';
                }
                echo '</div>';
                if (empty($form_errors))
                {
                    $check1 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                    $check1->execute([$user,$id]);
                    $check = $check1->rowCount();
                    if ($check == 0)
                    {
                        $stmt = $con->prepare("UPDATE users SET Username = ? , Password = ? , Email = ? , FullName = ? WHERE UserID = ? ");
                        $stmt->execute([$user,$pass,$email,$full,$id]);
                        $themsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record updated successfully</div>';
                        redirecthome($themsg,'back');
                    }else
                        {
                            $themsg = '<div class="alert alert-danger">this username exist try another username</div>';
                            redirecthome($themsg,'back');
                        }
                }
            }else
                {
                    $themsg =  "<div class='alert alert-danger'>you can not browse this page directly</div>";
                    redirecthome($themsg);
                }
        }elseif ($do == 'Delete')//============================ delete page =============
        {
            $userid =isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):'';
            $check = checkitem('UserID','users',$userid);

            if ($check > 0 )
            {
                $stmt = $con->prepare("DELETE FROM users WHERE UserID = ? ");
                $stmt->execute([$userid]);
                $themsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Deleted successfully</div>';
                redirecthome($themsg,'back');
            }
        }elseif ( $do == 'activate')//============================ activate page =============
        {
            $userid =isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']):'';
            $check = checkitem('UserID','users',$userid);

            if ($check > 0 )
            {
                $stmt = $con->prepare("UPDATE users SET RagStatus = 1 WHERE UserID = ? ");
                $stmt->execute([$userid]);
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

