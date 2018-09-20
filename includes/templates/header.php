<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php getTitle(); ?></title>

         <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
		<link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href="<?php echo $css ?>frontend.css" />
		<link rel="stylesheet" href="<?php echo $css ?>mystyle.css" />
</head>
<body>
<div class="upper-bar">
    <div class="container">
        <?php
        if (isset($_SESSION['user']))
        {
            echo 'Welcome '.$_SESSION['user'];
            echo ' <a href="profile.php">Profile</a>';
            echo ' <a href="newad.php">New Ad</a>';
            echo ' <a href="logout.php">logout</a>';
            $userstatus = useractive($_SESSION['user']);
            echo $userstatus;
        }
        else
            {?>
        <a href="login.php"><span class="pull-right">login/signup</span></a>
        <?php } ?>
    </div>
</div>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index1.php"><?php echo lang('HOME_ADMIN') ?></a>
        </div>
        <div class="collapse navbar-collapse navbar-right" id="app-nav">
            <ul class="nav navbar-nav">
                <?php
                foreach (getcategories() as $cat)
                {
                    echo'<li><a href="categories.php?catid='.$cat['ID'].'&catname='.str_replace(' ','-',$cat['Cat_Name']).'">'.$cat['Cat_Name'].'</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>