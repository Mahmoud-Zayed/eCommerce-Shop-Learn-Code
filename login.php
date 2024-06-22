<?php
    ob_start(); 
    session_start();
    $pageTitle = 'Login';
    if (isset($_SESSION['user'])) {
        header('Location: index.php'); // Redirect To Dashboard Page 
    } 

    include 'init.php';

    // Check If User Coming Form Http Post
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);

            // Check If The User Exist In Database 
            $stmt = $con->prepare(" SELECT 
                                        UserID, Username, Password 
                                    FROM 
                                        users 
                                    WHERE 
                                        Username = ? 
                                    AND 
                                        password = ? ");
            $stmt->execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowcount();
            // echo $count;
            // If Count > 0 This Mean The Database Contain Record About This Username 
            if ($count > 0) {
                $_SESSION['user'] = $user; // Register Session Name 
                $_SESSION['uid'] = $get["UserID"]; // Register Session User ID 
                header('Location: index.php'); // Redirect To Dashboard Page 
                exit(); 
            }
        } else {
            $formErrors = array();

            $user = $_POST["username"];
            $pass__1 = $_POST["password"];
            $pass__2 = $_POST["password_2"];
            $email = $_POST["email"];

            if (isset($user)) {
                $filterUser = filter_var($user, FILTER_SANITIZE_STRING);
                if (strlen($filterUser) < 4) {
                    $formErrors[] = 'Username Must Be Larger Than 4 Characters';
                }
            }
            if (isset($pass__1) && isset($pass__2)) {
                if (empty($pass__1)) {
                    $formErrors[] = "Sorry Password Cant't Be Empty";
                }
                if (sha1($pass__1) !== sha1($pass__2)) {
                    $formErrors[] = 'Sorry Password Is Not Match';
                }
            }
            if (isset($email)) {
                $filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true) {
                    $formErrors[] = 'This Email Is Not Valid';
                }
            }



            
            // Check If There's No Error Proceed The User Add
            if (empty($formErrors)) {
                // Check If User Exist In Database  
                $check = checkItem("Username", "users", $user); 
                if ($check == 1) { 
                    $formErrors[] = 'Sorry This User Is Exist';
                } else {
                    // Insert User Info In Database
                    $stmt = $con->prepare(" INSERT INTO users(Username, Password, Email, RegStatus, Date) VALUES(:zuser, :zpass, :zmail, 0, now())");
                    $stmt->execute(array(
                        "zuser" => $user,
                        "zpass" => sha1($pass__1),
                        "zmail" => $email
                    ));
                    // Echo Success Massage
                    $successMsg = "Congrats You Are Now Regesterd User";
                }
            }





        }
    }
    
?>

    <div class="login_background">
        <div class="container">
            <h1 class="text-center"> <span class="selected x" data-class='login'>Login</span> | <span data-class="singup" class="y">Singup</span> </h1>
            <form class="form login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="input_container">
                    <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required>
                </div>
                <div class="input_container">
                    <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required>
                </div>
                <input class="btn btn-primary btn-block" type="submit" value="Login" name="login">
            </form>
            <form class="form singup"  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="input_container">
                    <div class="input_container">
                        <input pattern=".{4,}" title="Username Must Be 4 Chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required>
                    </div>
                    <div class="input_container">
                        <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type A Complex Password" required>
                    </div>
                    <div class="input_container">
                        <input minlength="4" class="form-control" type="password" name="password_2" autocomplete="new-password" placeholder="Type A Password Again" required>
                    </div>
                    <div class="input_container">
                        <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Type A Valid Email" required>
                    </div>
                    <input class="btn btn-success btn-block" type="submit" value="Singup" name="signup">
                </div>
            </form>
        </div>
        <div class="the_errors text-center">
            <?php
                if (!empty($formErrors)) {
                    echo "<p class='error_singup'>";
                        foreach($formErrors as $error) {
                            echo $error . "<br>";
                        }
                    echo "</p>";
                }
                if (isset($successMsg)) {
                    echo "<div class='succ_singup'>" . $successMsg . "</div>";
                }
            ?>
        </div>
    </div>

<?php
    include $tpl . 'footer.php';
    ob_end_flush();
?>