<?php
session_start();
$pagetitle = 'login';
    if (isset($_SESSION['user']))
    {
        header('location:index1.php');
    }
include 'init.php';
    if (isset($_SERVER['REQUEST_METHOD']) == 'POST')
    {
        if (isset($_POST['user'])&&isset($_POST['pass']))
        {
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $passs = sha1($_POST['pass']);

            $formerror = [];
            if (empty($user))
            {
                $formerror[] = 'UserName is required';
            }
            if (empty($pass))
            {
                $formerror[] = 'Password is required';
            }
            foreach($formerror as $error)
            {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }

            if (empty($formerror))
            {
                $stmt =$con->prepare('SELECT * FROM users WHERE Username = ? AND Password = ?');
                $stmt->execute([$user,$passs]);
                $stmt->fetch();
                $count = $stmt->rowCount();
                if ($count > 0)
                {
                    $_SESSION['user'] = $user;
                    header('location:index1.php');
                    exit();
                }
                else
                {
                    echo '<div class="alert alert-danger">there\'s such user</div>';
                }
            }
        }
    }
?>
<div>
    <div class="container login-page">
        <h1 class="text-center">
            <span class="login">login</span>|<span class="signup"><a href="register.php">signup</a></span>
        </h1>
        <form class="login selected" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input type="text" name="user" class="form-control" placeholder="UserName">
            <input type="password" name="pass" class="form-control" placeholder="Password">
            <input type="submit" class="btn btn-primary btn-block" value="login">
        </form>

    </div>
</div>
<?php include $tpl.'footer.php';
