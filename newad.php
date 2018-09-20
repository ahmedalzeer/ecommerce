<?php

session_start();
$pagetitle = 'Create New Ad';
include 'init.php';
if (isset($_SESSION['user']))
{
    $stmt = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->execute([$sessionuser]);
    $user = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count >0)
    {



        ?>
        <h1 class="text-center">Create New Item</h1>
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">Create New Item</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form class="form-horizontal" action="?do=insert" method="post">
                                    <div class="form-group form-group-lg">
                                        <label class="col-md-2 " >Name</label>
                                        <div class="col-md-9">
                                            <input required="required" type="text" class="form-control live" data-class=".live-name" name="name">
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-md-2 " >Description</label>
                                        <div class="col-md-9 ">
                                            <input required="required" type="text" class="form-control live" data-class=".live-desc" name="desc">
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-md-2 " >Price</label>
                                        <div class="col-md-9 ">
                                            <input required="required" type="text" class="form-control live" data-class=".live-price" name="price">
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-md-2 " >Made IN</label>
                                        <div class="col-md-9 ">
                                            <input required="required" type="text" class="form-control" name="made">
                                        </div>
                                    </div>

                                    <div class="form-group form-group-lg">
                                        <label class="col-md-2 " >Status</label>
                                        <div class="col-md-9 ">
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
                                        <label class="col-md-2 " >Category</label>
                                        <div class="col-md-9 ">
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
                                        <div class="col-md-offset-2 col-md-9 ">
                                            <input type="submit" class="btn btn-primary btn-block" value="save">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">$
                                        <span class="live-price">0</span>
                                    </span>
                                    <img class="img-responsive" src="layout/img/1.jpg">
                                    <div class="caption">
                                        <h3 class="live-name">title</h3>
                                        <p class="live-desc">description</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}
else
{
    header('location:login.php');
    exit();
}
?>

<?php include 'includes/templates/footer.php'; ?>
