<?php
require_once 'DbHandler.php';
$email = $password = $confirm_password = $name = "";
$email_err = $password_err = $confirm_password_err = $name_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $db = new DbHandler();
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
    } else{
        if($db->user_exists(trim($_POST["email"]))){
            $email_err = "This email is already taken.";
        }
        else{
            $email = trim($_POST["email"]);
        }
    }
    
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";     
    } else{
        $password = trim($_POST['password']);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }

    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    }
    else{
        $name = trim($_POST["name"]);
    }
    
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($name_err)){

        if($db->add_user($email, $password, $name, 0)){
            header("location: login.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>E-mail:<sup>*</sup></label>
                <input type="text" name="email"class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password:<sup>*</sup></label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password:<sup>*</sup></label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name:<sup>*</sup></label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>