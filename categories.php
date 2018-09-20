<?php include 'init.php'; ?>

    <div class="container">
        <h1 class="text-center"><?php echo str_replace('-',' ',$_GET['catname'])?></h1>
        <div class="row">
            <?php
            foreach (getitem('cat_id',$_GET['catid']) as $item)
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

<?php include $tpl.'footer.php';
