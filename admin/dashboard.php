<?php

    session_start();


    if (isset($_SESSION['username']))
    {
        $pagetitle = 'Dashboard';
        include 'init.php';
?>
        <div class="container home-stats text-center">
            <h1 class="text-center">Dashboard</h1>
            <div class="col-md-3">
                <div class="info">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                            <div class="info">
                                Total Members
                                <span><a href="members.php"><?php echo countitem('UserID','users'); ?></a> </span>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
                    <div class="info">
                        pending Members
                        <span><a href="members.php?do=manage&page=pending"><?php echo checkitem('RagStatus','users',0); ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">Total Items
                        <span><a href="items.php"><?php echo countitem('item_id','items'); ?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comment-o"></i>
                    <div class="info">
                        Total Comments
                        <span><a href="comments.php"><?php echo countitem('c_id','comments'); ?></a></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container latest">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>
                            <?php
                            $limit = '5';
                            $usersname = getlatest('*','users','UserID',$limit);
                            $limititem = 5;
                            $latestitems = getlatest("*","items","item_id",$limit);
                            ?>
                            Latest <?php echo $limit ?> Registered Users
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                            <?php foreach ($usersname as $user)
                            {
                                echo '<li>'.$user['Username'].'<a href="members.php?do=Edit&userid='.$user['UserID'].'"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span></a> ';
                                 if($user['RagStatus'] == 0)
                              {
                                  echo '<a href="members.php?do=activate&userid= '.$user['UserID'].'" class="btn btn-info pull-right activate"><i class="fa fa-check"></i> Activate</a>
';
                              }
                                 echo '</li>';

                            } ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>
                            Latest <?php echo $limititem ?> Item
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                            <?php foreach ($latestitems as $item)
                            {
                                echo '<li>'.$item['name'].'<a href="items.php?do=Edit&itemid='.$item['item_id'].'"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit</span></a> ';
                                if($item['active'] == 0)
                                {
                                    echo '<a href="items.php?do=activate&itemid= '.$item['item_id'].'" class="btn btn-info pull-right activate"><i class="fa fa-check"></i> Activate</a>
';
                                }
                                echo '</li>';

                            } ?>
                            </ul>
                        </div>
                    </div>
                </div><?php
                                $limitcom = 5;?>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>
                            Latest <?php echo $limitcom; ?> Comments
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                $stmt = $con->prepare("SELECT comments.*, users.Username FROM comments
                                                                INNER JOIN  users ON UserID = user_id ORDER BY c_id DESC LIMIT $limitcom ");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                if (!empty($rows))
                                {
                                    foreach ($rows as $row)
                                    {
                                        echo '<div class="comment-box">';
                                        echo '<span class="member-n">'.$row['Username'].'</span>';
                                        echo '<p class="member-c">'.$row['comment'].'</p>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php

    }else
        {
            header('location:index1.php');
            exit();
        }
?>



<?php include $tpl.'footer.php'; ?>

