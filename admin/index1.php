<?php

session_start();
$nonavbar  = '';
$pagetitle = 'login';
    if (isset($_SESSION['username']))
    {
        header('location: dashboard.php');
        exit();
    }
include 'init.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $user = $_POST['user'];
        $pass = sha1($_POST['pass']);

        $stmt = $con->prepare("SELECT * FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
        $stmt->execute([$user,$pass]);
        $row  = $stmt->fetch();
        $count = $stmt->rowCount();

    if ($count > 0)
        {
            $_SESSION['username'] = $row['Username'];
            $_SESSION['ID']       = $row['UserID'];

            header('location: dashboard.php');
            exit();
        }
    }
?>

    <form class="login" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <h4 class="text-center">Admin Login</h4>
        <input type="text" class="form-control" placeholder="username" name="user">
        <input type="password" class="form-control" placeholder="password" name="pass">
        <input type="submit" name="submit" value="login" class="btn btn-primary btn-block">
    </form>


<?php include 'includes/templates/footer.php'; ?>
