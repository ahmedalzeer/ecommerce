<?php

    session_start();
    $pagetitle = 'Profile';
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
<h1 class="text-center"><?php echo $user['Username']?> Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">hi</div>
        </div>
        </div>
    </div>

    <div class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
            <div class="panel-heading">My Ads</div>
            <div class="panel-body">
                <div class="row">
                    <?php
                    foreach (getitem('member_id',$user['UserID']) as $item)
                    {
                        echo '<div class="col-md-4">';
                        echo '<div class="thumbnail item-box">';
                        echo '<span class="price-tag">'.$item['price'].'</span>';
                        echo '<img class="img-responsive" src="layout/img/1.jpg">';
                        echo '<div class="caption">';
                        echo '<h3>'.$item['name'].'</h3>';
                        echo '<p>'.$item['description'].'</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="my-comments block">
        <div class="container">
            <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>Date</td>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                $user_id = $user['UserID'];
                $stmt = $con->prepare("SELECT comments.*,items.name FROM comments 
                                                INNER JOIN items ON item_id = comments.item
                                                WHERE user_id = ?");
                $stmt->execute([$user_id]);
                $comments = $stmt->fetchAll();
                foreach ($comments as $comment)
                {
                    echo '<tr>';
                    echo '<td>'.$comment['comment'].'</td>';
                    echo '<td>'.$comment['item'].'</td>';
                    echo '<td>'.$comment['comment_date'].'</td>';
                    echo '</tr>';
                }
                ?>
                    </tbody>
                </table>
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
include 'includes/templates/footer.php'; ?>
