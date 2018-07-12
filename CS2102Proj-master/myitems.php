<?php
    session_start();
    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
      header("location: login.php");
      exit;
    }
    require_once 'DbHandler.php';
    $email = $_SESSION['email'];
    $db = new DbHandler();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        echo "here";
        if(!empty(trim($_POST["item_name"])) && floatval(trim($_POST["fee"]))){
            echo "here";
            $item_name = trim($_POST["item_name"]);
            $fee = floatval(trim($_POST["fee"]));
            $db->add_item($item_name, $email, $fee);
        }
    }
    $my_available_items = $db->get_all_available_items_by_user($email);
    $my_lended_items = $db->get_all_lended_items_by_user($email);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="js/main.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo $_SESSION['email']; ?></b>. Welcome to our site.</h1>
    </div>
    <div>
    <ul class="nav nav-pills nav-justified">
      <li><a href="home.php">Available Items</a></li>
      <li class="active"><a href="#">My Items</a></li>
      <li><a href="mytrans.php">My Transactions</a></li>
    </ul>
    </div>
    <div style="width: 50%; margin-top: 30px;">
        <button class="btn btn-primary" id="additem-btn">Add Item</button>
        <div style="display: none;" id="additem-form">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Item Name:<sup>*</sup></label>
                    <input type="text" name="item_name" class="form-control" value="">
                </div>    
                <div class="form-group">
                    <label>Fee:<sup>*</sup></label>
                    <input type="number" step="0.1" name="fee" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <table class="table" style="margin-top: 30px;">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Item Id</th>
                <th scope="col">Item Name</th>
                <th scope="col">Currently With</th>
                <th scope="col">Fee</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $html_string = "";
            foreach($my_available_items as $item){
                $html_string .= "<tr>";
                $html_string .= "<th scope=\"row\">";
                $html_string .= $item["item_id"];
                $html_string .= "</th>";
                $html_string .= "<td>";
                $html_string .= $item["item_name"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= "Me";
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["fee"];
                $html_string .= "</td>";
                $html_string .= "</tr>";
            }
            echo $html_string;
            $html_string = "";
            foreach($my_lended_items as $item){
                $html_string .= "<tr>";
                $html_string .= "<th scope=\"row\">";
                $html_string .= $item["item_id"];
                $html_string .= "</th>";
                $html_string .= "<td>";
                $html_string .= $item["item_name"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["borrower"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["fee"];
                $html_string .= "</td>";
                $html_string .= "</tr>";
            }
            echo $html_string;
            ?>
        </tbody>
    </table>
    <p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>
</body>
</html>