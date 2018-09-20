<?php

session_start();
$pagetitle = 'Categories';
if (isset($_SESSION['username'])) {

    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage')//============================== manage page ===========
    {
        $sort = 'ASC';
        $sort_array = ['ASC','DESC'];
        if (isset($_GET['sort'])&& in_array($_GET['sort'],$sort_array))
        {
            $sort = $_GET['sort'];
        }
        $stmt1 = $con->prepare("SELECT * FROM categories order BY Ordering $sort");
        $stmt1->execute();
        $cats = $stmt1->fetchAll();
?>
        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i>Sort BY :[
                        <a class="<?php if ($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a>
                        |<a class="<?php if ($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a>]
                        <i class="fa fa-eye"></i>view :[
                        <span class="active" data-view="full">Full </span>|
                        <span data-view="classic">Classic</span>]
                    </div>
                </div>
                <div class="panel-body">
<?php
                foreach ($cats as $cat)
                {
                    echo '<div class="cat">';
                        echo '<div class="hidden-buttons">';
                            echo '<a class="btn btn-primary btn-xs" href="categories.php?do=Edit&catid='.$cat['ID'].'"><i class="fa fa-edit"></i> Edit</a>';
                            echo '<a class="confirm btn btn-danger btn-xs" href="categories.php?do=Delete&catid='.$cat['ID'].'"><i class="fa fa-close"></i> Delete</a>';
                        echo '</div>';
                        echo '<h3>'.$cat['Cat_Name'].'</h3>';
                        echo '<div class="full-view">';
                            echo '<p>';
                            if ($cat['Description'] == ''){echo 'This Category doesn\'t have description';}
                            else{echo $cat['Description'];}
                            echo '</p>';
                            if ($cat['Visibality'] == 1){echo '<span class="visibilityl"><i class="fa fa-eye"></i> Hidden</span>';}
                            if ($cat['Allow_Comment'] == 1){echo '<span class="commenting"><i class="fa fa-close"></i>Comments Disabled</span>';}
                            if ($cat['Allow_Ads'] == 1){echo '<span class="advertises"><i class="fa fa-close"></i>Ads Disabled</span>';}
                        echo '</div>';
                    echo '</div>';
                    echo '<hr/>';
                }
?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>
<?php
    }
    elseif ($do == 'Add')//============================== Add page ===========
    {
?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="post">
                <div class="form-group">
                    <label class="col-md-2 col-md-offset-3" >Name :</label>
                    <div class="col-md-4 ">
                        <input required="required" type="text" class="form-control" name="name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 col-md-offset-3" >Description :</label>
                    <div class="col-md-4 ">
                        <input type="text" class="form-control" name="description">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 col-md-offset-3" >Ordering :</label>
                    <div class="col-md-4 ">
                        <input type="text" class="form-control" name="ordering">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 col-md-offset-3" >visible :</label>
                    <div class="col-md-4 ">
                       <div>
                            <input type="radio" id="vis-yes" value="0" checked name="visibility">
                            <label for="vis-yes">Yes</label>
                       </div>
                        <div>
                            <input type="radio" id="vis-no" value="1" name="visibility">
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 col-md-offset-3" >Allow Commenting :</label>
                    <div class="col-md-4 ">
                        <div>
                            <input type="radio" id="com-yes" value="0" checked name="commenting">
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" id="com-no" value="1" name="commenting">
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 col-md-offset-3" >Allow Ads :</label>
                    <div class="col-md-4 ">
                        <div>
                            <input type="radio" id="ads-yes" value="0" checked name="ads">
                            <label for="ads-yes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" id="ads-no" value="1" name="ads">
                            <label for="ads-no">No</label>
                        </div>
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
            $name      = $_POST['name'];
            $desc      = $_POST['description'];
            $order     = $_POST['ordering'];
            $visible   = $_POST['visibility'];
            $ads       = $_POST['ads'];
            $comment   = $_POST['commenting'];
            //validate inputes


            $check = checkitem('Cat_Name','categories',$name);
            if ($check == 0)
            {
                $stmt = $con->prepare("INSERT INTO categories(Cat_Name, Description, Ordering, Visibality, Allow_Comment, Allow_Ads) 
                                                VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)");
                $stmt->execute([
                    'zname' => $name, 'zdesc' => $desc, 'zorder' => $order, 'zvisible' => $visible, 'zcomment' => $comment, 'zads' => $ads
                ]);
                $themsg = '<div class="alert alert-success">'. $stmt->rowCount().'Record Saved successfully</div>';
                redirecthome($themsg,'back');
            }else
            {
                $themsg = '<div class="alert alert-danger">this name exist try another name</div>';
                redirecthome($themsg,'back');
            }

        }else
        {
            $themsg= '<div class="alert alert-danger">you can\'t browse this page directly</div>';
            redirecthome($themsg,'back');
        }
    }
    elseif ($do == 'Edit') //============================== edit page ===========
    {
        $catid =isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']):'';

        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
        $stmt->execute([$catid]);
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0 )
        {
            ?>
            <h1 class="text-center">Edit Categories</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update&catid=<?php echo $cat['ID']; ?>" method="post">
                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Name :</label>
                        <div class="col-md-4 ">
                            <input required="required" type="text" class="form-control" name="name" value="<?php echo $cat['Cat_Name'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Description :</label>
                        <div class="col-md-4 ">
                            <input type="text" class="form-control" name="description" value="<?php echo $cat['Description'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Ordering :</label>
                        <div class="col-md-4 ">
                            <input type="text" class="form-control" name="ordering" value="<?php echo $cat['Ordering'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >visible :</label>
                        <div class="col-md-4 ">
                            <div>
                                <input type="radio" id="vis-yes" value="0"  name="visibility" <?php if ($cat['Visibality'] == 0){echo 'checked';} ?> >
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" id="vis-no" value="1" name="visibility" <?php if ($cat['Visibality'] == 1){echo 'checked';} ?> >
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Allow Commenting :</label>
                        <div class="col-md-4 ">
                            <div>
                                <input type="radio" id="com-yes" value="0"  name="commenting" <?php if ($cat['Allow_Comment'] == 0){echo 'checked';} ?> >
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" id="com-no" value="1" name="commenting" <?php if ($cat['Allow_Comment'] == 1){echo 'checked';} ?> >
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 col-md-offset-3" >Allow Ads :</label>
                        <div class="col-md-4 ">
                            <div>
                                <input type="radio" id="ads-yes" value="0"  name="ads" <?php if ($cat['Allow_Ads'] == 0){echo 'checked';} ?> >
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" id="ads-no" value="1" name="ads" <?php if ($cat['Allow_Ads'] == 1){echo 'checked';} ?> >
                                <label for="ads-no">No</label>
                            </div>
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

    }
    elseif ($do == 'update')//============================== update page ===========
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $catid = isset($_GET['catid'])&& is_numeric($_GET['catid']) ? intval($_GET['catid']):'';
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $ads = $_POST['ads'];
            $comment = $_POST['commenting'];
            $check = checkitem('Cat_name','categories',$name);
            if (!empty($name)&&$check == 0)
            {
             $stmt2 = $con->prepare("UPDATE categories SET Cat_Name = ?, Description = ?, Ordering = ?, Visibality = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");
             $stmt2->execute([$name, $desc, $order, $visible, $comment, $ads, $catid]);
             $count = $stmt2->rowCount();

                $themsg = '<div class="alert alert-success">'.$count.'Record updated successfully</div>';
                redirecthome($themsg,'back');
            }
            else
            {
                echo '<div class="alert alert-info">Category Name can\'t empty or there\'s the same name</div>';
                redirecthome($themsg,'back');
            }
        }
        else
        {
            $themsg =  "<div class='alert alert-danger'>you can not browse this page directly</div>";
            redirecthome($themsg);
        }

    }
    elseif ($do == 'Delete')//============================== delete page ===========
    {
        $catid =isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']):'';
        $check = checkitem('ID','categories',$catid);

        if ($check > 0 )
        {
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = ? ");
            $stmt->execute([$catid]);
            $themsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Deleted successfully</div>';
            redirecthome($themsg,'back');
        }

    }

    include $tpl . 'footer.php';

} else {

    header('Location: index1.php');

    exit();
}


?>