<?php
    session_start();
    if(!isset($_SESSION['email']) || empty($_SESSION['email'])){
      header("location: login.php");
      exit;
    }
    require_once 'DbHandler.php';
    $email = $_SESSION['email'];
    $db = new DbHandler();
    $return_result = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["function"] == "return"){
            if(!empty(trim($_POST['trans_id']))){
                $trans_id = trim($_POST['trans_id']);
                $result = $db->return_item_for_user($trans_id, $email);
                if($result){
                    $return_result = "Item returned successfully";
                }
                else{
                    $return_result = "Could not return item";   
                }
            }
        }
    }
    $my_borrowed_items = $db->get_all_borrowed_items_by_user($email);
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
      <li><a href="myitems.php">My Items</a></li>
      <li class="active"><a href="#">My Transactions</a></li>
    </ul>
    </div>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Item Id</th>
                <th scope="col">Item Name</th>
                <th scope="col">Owner</th>
                <th scope="col">Borrowed On</th>
                <th scope="col">Returned On</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $html_string = "";
            foreach($my_borrowed_items as $item){
                $html_string .= "<tr>";
                $html_string .= "<th scope=\"row\">";
                $html_string .= $item["trans_id"];
                $html_string .= "</th>";
                $html_string .= "<td>";
                $html_string .= $item["item_name"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["owner"];
                $html_string .= "</td>";
                $html_string .= "<td>";
                $html_string .= $item["borrow_date"];
                $html_string .= "</td>";
                if($item['return_date']){
                    $html_string .= "<td>";
                    $html_string .= $item['return_date'];
                    $html_string .= "</td>";
                }
                else{
                    $html_string .= "<td>";
                    $html_string .= "<button type=\"button\" class=\"btn btn-warning return-btn\" data-transid=\"" . $item["trans_id"] . "\">Return</button>";
                    $html_string .= "</td>";
                }
                $html_string .= "</tr>";
            }
            echo $html_string;
            ?>
        </tbody>
    </table>
    <p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>
</body>
</html>