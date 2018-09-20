<?php

session_start();
$pagetitle = 'Regiter Page';
    if (isset($_SESSION['user']))
    {
        header('location:index1.php');
    }
include 'init.php';
    if (isset($_SERVER['REQUEST_METHOD']) == 'POST')
    {
        if (isset($_POST['user'])&&isset($_POST['pass']))
        {
            $user = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
            $pass = $_POST['pass'];
            $pass2 = $_POST['pass2'];
            $email = filter_var(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL),FILTER_VALIDATE_EMAIL);
            $full = filter_var($_POST['full'],FILTER_SANITIZE_STRING);
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
            if (empty($pass2))
            {
                $formerror[] = 'Confirm Password is required';
            }
            if (isset($_POST['pass'])&&isset($_POST['pass2']))
            {
                if($_POST['pass'] !== $_POST['pass2'])
                {
                    $formerror[] = 'Password not match';
                }
            }
            if (empty($email))
            {
                $formerror[] = 'Email is required';
            }
            if ($email != true)
            {
                $formerror[] = 'Email not valid';
            }
            if (empty($full))
            {
                $formerror[] = 'Full Name is required';
            }
            if (strlen($pass) < 6)
            {
                $formerror[] = 'Password must be more than 6 letters or numbers';
            }
            if (strlen($user) < 4)
            {
                $formerror[] = 'User Name must be more than 4 letters or numbers';
            }
            if (strlen($full) < 4)
            {
                $formerror[] = 'Full Name must be more than 4 letters or numbers';
            }
            foreach($formerror as $error)
            {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }

            if (empty($formerror))
            {
                $check = checkitem('Username','users',$user);
                if ($check == 0)
                {
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, regdate)VALUES( :zuser, :zpass, :zemail, :zfull, now())");
                    $stmt->execute([
                        'zuser' => $user,
                        'zpass' => $passs,
                        'zemail' => $email,
                        'zfull' => $full
                    ]);
                    header('location:profile.php');
                    exit();
                }
                else
                {
                    echo '<div class="alert alert-danger">this User Name already token choose anthor one</div>';
                }
            }
        }
    }
?>
    <div>
        <div class="container login-page">
            <h1 class="text-center">
                <span class="signup">signup</span>
            </h1>
             <form class="register" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <input type="text" name="user" class="form-control" placeholder="UserName">
                <input type="password" name="pass" class="form-control" placeholder="Password">
                <input type="password" name="pass2" class="form-control" placeholder="Confirm Password">
                <input type="text" name="full" class="form-control" placeholder="Full Name">
                <input type="email" name="email" class="form-control" placeholder="Email">
                <input type="submit" class="btn btn-info btn-block" value="signup">
            </form>
        </div>
    </div>
<?php include $tpl.'footer.php';
