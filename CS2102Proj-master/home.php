<?php
    session_start();
    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
      header("location: login.php");
      exit;
    }
    require_once 'DbHandler.php';
    $email = $_SESSION['email'];
    $db = new DbHandler();
    $borrow_result = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["function"] == "borrow"){
            if(!empty(trim($_POST['item_id']))){
                $item_id = trim($_POST['item_id']);
                $result = $db->borrow_item_for_user($item_id, $email);
                if($result){
                    $borrow_result = "Item borrowed successfully";
                }
                else{
                    $borrow_result = "Could not borrow item";   
                }
            }
        }
    }
    $available_items = $db->get_all_available_items_for_user($email);
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
      <li class="active"><a href="#">Available Items</a></li>
      <li><a href="myitems.php">My Items</a></li>
      <li><a href="mytrans.php">My Transactions</a></li>
    </ul>
    </div>
    <div><span><?<?php echo $borrow_result; ?> </span></div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Item Id</th>
                <th scope="col">Item Name</th>
                <th scope="col">Owner</th>
                <th scope="col">Fee</th>
                <th scope="col">Borrow?</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $html_string = "";
            foreach($available_items as $item){
                $html_string .= "<tr>";
                $html_string .= "<th scope=\"row\">";
                $html_string .= $item["item_id"];
                $html_string .= "</th>";
                $html_string .= "<td>";
                $html_string .= $item["item_name"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["owner"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["fee"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= "<button type=\"button\" class=\"btn btn-success borrow-btn\" data-itemid=\"" . $item["item_id"] . "\">Borrow</button>";
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