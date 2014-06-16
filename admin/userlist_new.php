<?php
include "../shared/user_check_right.php";

//database connection
include "../shared/dbconnect.php"; 


?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>The list of users</title>
        <link rel="stylesheet" type="text/css" href="../shared/smeInventory.css" />
        <link rel="stylesheet" type="text/css" href="../css/ui-lightness/jquery-ui-1.8.17.custom.css" />
        <style type="text/css">
        div.interval{width: 5px; float: left;}
        div.container{
            white-space: nowrap;
        }
        div#role_container{
            border-style: solid;
            border-width: 1px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            background-color: lightblue;
            float: left;
            width: 30%;
            height: 600px;
        }
        div#user_container{
            border-style: solid;
            border-width: 1px;
            border-radius: 10px;
            background-color: bisque;
            float: left;
            width: 30%;
            height: 600px;
        }
        div.user {
            border-style: dotted;
            border-width: 1px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            width: 80%;
            height: 80px;
            background-color: white;
        }
        div#role {
            border-style: dotted;
            border-width: 1px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            width: 95%;
            height: 80px;
            background-color: yellow;
        }
        div#forDialog {
            display: none;
        }
        </style>
        <script type="text/javascript" src="../js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.8.17.custom.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#add_new_user").click(function(){
                    $("#forDialog").dialog();
                });
                $(".user").draggable();
                $(".user").mouseover(function(){
                    
                });
                $(".role").droppable({
                    drop: function() { $(".role").css("background-color", "red"); }
                });
            });
            
        </script>
    </head>
    <body>
        <div id="container" class="ui-widget">
            <div id="role_container" class="ui-widget">
            <?php
            $sql = "SELECT * FROM role ORDER BY rolename";
            $stmt = $dbh->query($sql);
            $result = $stmt->fetchAll();
            foreach($result as $row){
                echo "<div class='ui-widget ui-corner-all'>";
                echo "<p class='.ui-widget-header'>".$row['rolename']."</p>";
                echo $row['role_desc'];
                echo "</div><br/>";
            }
            ?>
                <div id="add_new_role">test</div>
            </div>
            <div class="interval">&nbsp;</div>
            <div id="user_container">
            <?php
            $sql = "SELECT * FROM sme_access ORDER BY uid";
            $stmt = $dbh->query($sql);
            $result = $stmt->fetchAll();
            foreach($result as $row){
                echo "<div class='user'>";
                echo "<p class='.ui-widget-header'>".$row['uid']."</p>";
                echo $row['username'];
                echo $row['description'];
                echo "</div><br/>";
            }
            ?>
                <div id="add_new_user" class="user">
                    + <div id="forDialog" title="Basic modal dialog" class="ui-dialog"></div>
                </div>
            </div>
            
        </div>
    </body>
</html>
