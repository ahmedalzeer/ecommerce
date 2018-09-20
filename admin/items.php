<?php

session_start();

$pagetitle = '';

if (isset($_SESSION['username']))
{

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage')//============================== manage page ===========
    {
        $stmt = $con->prepare("SELECT items.*, categories.Cat_Name, users.Username 
              FROM items
              INNER JOIN categories ON ID = cat_id
              INNER JOIN users ON UserID = member_id");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Made IN</td>
                        <td>Category</td>
                        <td>Member</td>
                        <td>Adding Date</td>
                        <td>Control</td>
                    </tr>

                    <?php
                    foreach ($rows as $row)
                    {
                    ?>
                    <tr>
                        <td><?php echo $row['item_id'] ?></td>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['description'] ?></td>
                        <td><?php echo $row['price'] ?></td>
                        <td><?php echo $row['country_made'] ?></td>
                        <td><?php echo $row['Cat_Name'] ?></td>
                        <td><?php echo $row['Username'] ?></td>
                        <td><?php echo $row['add_date'] ?></td>
                        <td>
                            <a href="?do=Edit&itemid=<?php echo $row['item_id'] ?>" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                            <a href="?do=Delete&itemid=<?php echo $row['item_id'] ?>" class="btn btn-danger confirm"><i class="fa fa-trash"></i> Delete</a>
                            <?php if($row['active'] == 0)
                            {
                                echo '<a href="?do=activate&itemid= '.$row['item_id'].'" class="btn btn-info activate"><i class="fa fa-check"></i> Activate</a>
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
            <a href="?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> New Item</a>
        </div>

        <?php

    }
    elseif ($do == 'Add')//============================== manage page ===========
    {
?>

        <h1 class="text-center">Add New item</h1>
        <div class="container">
            <form class="form-horizontal text-right" action="?do=insert" method="post">
                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >Name</label>
                    <div class="col-md-4 ">
                        <input required="required" type="text" class="form-control" name="name">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >Description</label>
                    <div class="col-md-4 ">
                        <input required="required" type="text" class="form-control" name="desc">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >Price</label>
                    <div class="col-md-4 ">
                        <input required="required" type="text" class="form-control" name="price">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >Made IN</label>
                    <div class="col-md-4 ">
                        <input required="required" type="text" class="form-control" name="made">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >Status</label>
                    <div class="col-md-4 ">
                        <select class="form-control" name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="3">Like New</option>
                            <option value="4">Used</option>
                            <option value="5">Very OLd</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >member</label>
                    <div class="col-md-4 ">
                        <select class="form-control" name="member">
                            <?php
                            $stmt = $con->prepare("SELECT *FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach ($users as $user)
                            {
                                echo '<option value="'.$user['UserID'].'">'.$user['Username'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-md-2 col-md-offset-3" >Category</label>
                    <div class="col-md-4 ">
                        <select class="form-control" name="cat_id">
                            <?php
                            $stmt = $con->prepare("SELECT *FROM categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll();
                            foreach ($cats as $cat)
                            {
                                echo '<option value="'.$cat['ID'].'">'.$cat['Cat_Name'].'</option>';
                            }
                            ?>
                        </select>
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
    elseif ($do == 'insert')//============================== insert page ===========
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $name   = $_POST['name'];
            $desc   = $_POST['desc'];
            $price  = $_POST['price'];
            $made   = $_POST['made'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat    = $_POST['cat_id'];
            //validate inputes
            $form_errors = [];
            if (empty($name))
            {
                $form_errors[] = 'Username can\'t be <strong> empty</strong>';
            }
            if (empty($desc))
            {
                $form_errors[] = 'Password can\'t be <strong> empty</strong>';
            }
            if (empty($price))
            {
                $form_errors[] = 'Full Name can\'t be <strong> empty</strong>';
            }
            if (empty($made))
            {
                $form_errors[] = 'Email can\'t be <strong> empty</strong>';
            }
            if ($status == 0)
            {
                $form_errors[] = 'starus must be <strong>choosen</strong>';
            }
            if ($member == 0)
            {
                $form_errors[] = 'member must be <strong>choosen</strong>';
            }
            if ($cat == 0)
            {
                $form_errors[] = 'category must be <strong>choosen</strong>';
            }
            echo '<div class="container">';
            foreach ($form_errors as $error)
            {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            echo '</div>';
            if (empty($form_errors))
            {
                $check = checkitem('name', 'items', $name);
                if ($check == 0)
                {
                    $stmt = $con->prepare("INSERT INTO items(name, description, price, country_made, status, add_date, member_id, cat_id) 
                                                    VALUES( :zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zmember, :zcat)");
                    $stmt->execute([
                       'zname' =>$name, 'zdesc' => $desc, 'zprice' => $price, 'zcountry' => $made, 'zstatus' => $status, 'zmember' => $member, 'zcat' => $cat
                    ]);
                    $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Saved successfully</div>';
                    redirecthome($themsg, 'back');
                }
                else
                {
                    $themsg = '<div class="alert alert-danger">this item exist try another item</div>';
                    redirecthome($themsg, 'back');
                }
            }
        }
        else
        {
            $themsg = '<div class="alert alert-danger">this can\'t browse this page directly</div>';
            redirecthome($themsg);
        }
    }
    elseif ($do == 'Edit')//============================== edit page ===========
    {
        $itemid =isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']):'';

        $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ? LIMIT 1");
        $stmt->execute([$itemid]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0 )
        {
            ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal text-right" action="?do=Update" method="post">
                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >Name</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="name" value="<?php echo $row['name']?>">
                            <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >Description</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="desc" value="<?php echo $row['description']?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >Price</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="price" value="<?php echo $row['price']?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >Made IN</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="made" value="<?php echo $row['country_made']?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >Status</label>
                        <div class="col-md-4 ">
                            <select class="form-control" name="status">
                                <option value="1" <?php if ($row['status'] == 1){echo 'selected';} ?> >New</option>
                                <option value="2" <?php if ($row['status'] == 2){echo 'selected';} ?> >Like New</option>
                                <option value="3" <?php if ($row['status'] == 3){echo 'selected';} ?> >Used</option>
                                <option value="4" <?php if ($row['status'] == 4){echo 'selected';} ?> >Very OLd</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >member</label>
                        <div class="col-md-4 ">
                            <select class="form-control" name="member">
                                <?php
                                $stmt = $con->prepare("SELECT *FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user)
                                {
                                    echo '<option value="'.$user['UserID'].'"';
                                    if ($row["member_id"] == $user["UserID"]){echo "selected";}
                                    echo '>'.$user["Username"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-md-2 col-md-offset-3" >Category</label>
                        <div class="col-md-4 ">
                            <select class="form-control" name="cat_id">
                                <?php
                                $stmt = $con->prepare("SELECT *FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach ($cats as $cat)
                                {
                                    echo '<option value="'.$cat['ID'].'"';
                                    if ($row['cat_id'] == $cat['ID']){echo 'selected';}
                                    echo '>'.$cat['Cat_Name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class=" col-md-offset-5 col-md-4 ">
                            <input type="submit" class="btn btn-primary" value="save">
                        </div>
                    </div>
                </form>
                <?php
                $stmt = $con->prepare("SELECT comments.*, users.Username FROM comments
                INNER JOIN  users ON UserID = user_id WHERE item = ? ");
                $stmt->execute([$itemid]);
                $rows = $stmt->fetchAll();
                if (!empty($rows))
                {


                ?>
                <h1 class="text-center">Manage [<?php echo $row['name']?>] Comments</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table">
                            <tr>
                                <td>#ID</td>
                                <td>Comment</td>
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
                <?php } ?>
            </div>
            <?php
        }else
        {
            $themsg = '<div class="alert alert-danger">there\'s not such ID</div>';
            redirecthome($themsg);
        }
    }
    elseif ($do == 'Update')//============================== update page ===========
    {
        echo '<h1 class="text-center">Update Item</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $id    = $_POST['itemid'];
            $name   = $_POST['name'];
            $desc   = $_POST['desc'];
            $price  = $_POST['price'];
            $made   = $_POST['made'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat    = $_POST['cat_id'];

            //validate inputes
            $form_errors = [];
            if (empty($name))
            {
                $form_errors[] = 'Username can\'t be <strong> empty</strong>';
            }
            if (empty($desc))
            {
                $form_errors[] = 'Password can\'t be <strong> empty</strong>';
            }
            if (empty($price))
            {
                $form_errors[] = 'Full Name can\'t be <strong> empty</strong>';
            }
            if (empty($made))
            {
                $form_errors[] = 'Email can\'t be <strong> empty</strong>';
            }
            if ($status == 0)
            {
                $form_errors[] = 'starus must be <strong>choosen</strong>';
            }
            if ($member == 0)
            {
                $form_errors[] = 'member must be <strong>choosen</strong>';
            }
            if ($cat == 0)
            {
                $form_errors[] = 'category must be <strong>choosen</strong>';
            }
            echo '<div class="container">';
            foreach ($form_errors as $error)
            {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            echo '</div>';
            if (empty($form_errors))
            {
                $check = checkitem('name', 'items', $name);
                if ($check == 0)
                {
                    $stmt = $con->prepare("UPDATE items SET name = ?, description = ?, price = ?, country_made = ?, status = ?, member_id = ?, cat_id = ?  WHERE item_id = ? ");
                    $stmt->execute([$name, $desc, $price, $made, $status, $member, $cat, $id]);
                    $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record updated successfully</div>';
                    redirecthome($themsg, 'back');
                }
                else
                {
                    $themsg = '<div class="alert alert-danger">this item exist try another item</div>';
                    redirecthome($themsg, 'back');
                }
            }
        }
        echo '</div>';
    }
    elseif ($do == 'Delete')//============================== delete page ===========
    {
        echo '<h1 class="text-center">Delete Item</h1>';
        echo '<div class="container">';
        $item_id = isset($_GET['itemid'])&&is_numeric($_GET['itemid'])? intval($_GET['itemid']):'';
        $check = checkitem('item_id','items',$item_id);
        if ($check > 0)
        {
            $stmt = $con->prepare("DELETE FROM items WHERE item_id = ?");
            $stmt->execute([$item_id]);
            $themsg = '<div class="alert alert-success">'.$stmt->rowCount().' deleted successfully </div>';
            redirecthome($themsg,'back');
        }
        else
        {
            $themsg = '<div class="alert alert-danger">this ID does\'nt exist</div>';
            redirecthome($themsg);
        }
        echo '</div';
    }
    elseif ($do == 'activate')//============================== activate page ===========
    {
        echo '<h1 class="text-center">Activate Item</h1>';
        echo '<div class="container">';
        $itemid = isset($_GET['itemid'])&& is_numeric($_GET['itemid']) ? intval($_GET['itemid']):'';
        $check = checkitem('item_id','items',$itemid);
        if ($check > 0 )
        {
            $stmt = $con->prepare("UPDATE items SET active = 1 WHERE item_id = ?");
            $stmt->execute([$itemid]);
            $themsg = '<div class="alert alert-success">'.$stmt->rowCount().' item activated</div>';
            redirecthome($themsg,'back');
        }
        else
        {
            $themsg = '<div class="alert alert-danger">there is no such id</div>';
            redirecthome($themsg);
        }
        echo '</div>';
    }

    include $tpl . 'footer.php';

}
else
{

    header('Location: index1.php');

    exit();
}


?>