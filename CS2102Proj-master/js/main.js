$(document).ready(function(){
    $(".borrow-btn").click(function(){
        console.log("here");
        var item_id = $(this).attr("data-itemid");
        $.post("home.php", {"function": "borrow", "item_id": item_id}, function(data)
            {
                window.location.replace("mytrans.php");
            });
    });
    $(".return-btn").click(function(){
        console.log("here");
        var item_id = $(this).attr("data-transid");
        $.post("mytrans.php", {"function": "return", "trans_id": item_id}, function(data)
            {
                //console.log("here");
                window.location.replace("mytrans.php");
            });
    });
    $("#additem-btn").click(function(){
        $("#additem-form").show();
    });
});