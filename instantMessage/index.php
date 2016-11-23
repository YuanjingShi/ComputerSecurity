<?php
session_start ();
echo $_POST["targetUser"];
if (isset ( $_GET ['logout'] )) {

    // Simple exit message
    $fp = fopen ( "log.html", 'a' );
    fwrite ( $fp, "<div class='msgln'><i>User " . $_SESSION ['name'] . " has left the chat session.</i><br></div>" );
    fclose ( $fp );

    session_destroy ();
    header ( "Location: login.php" ); // Redirect the user
}

?>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>InstantMessage</title>
</head>
<body>
<div id="wrapper">
        <div id="menu">
            <p class="welcome">
                Welcome, <b><?php echo $_SESSION['name']; ?></b>
            </p>
            <p class="logout">
                <a id="exit" href="#">Exit Chat</a>
            </p>
            <div style="clear: both"></div>
        </div>
        <div id="chatbox"><?php
        if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
            $handle = fopen ( "log.html", "r" );
            $contents = fread ( $handle, filesize ( "log.html" ) );
            fclose ( $handle );

            echo $contents;
        }
        ?></div>

        <form name="message" action="">
            <input name="usermsg" type="text" id="usermsg" size="63" /> <input
                name="submitmsg" type="submit" id="submitmsg" value="Send" />
        </form>
    </div>
    <div id="map" style="width:400px;height:400px;"></div>
    <script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    <script type="text/javascript">
        // jQuery Document
        $(document).ready(function(){
        });

        //jQuery Document
        $(document).ready(function(){
            //If user wants to end session
            $("#exit").click(function(){
                var exit = confirm("Are you sure you want to end the session?");
                if(exit==true){window.location = 'index.php?logout=true';}
            });
        });

        //If user submits the form
        $("#submitmsg").click(function(){
                var clientmsg = $("#usermsg").val();
                $.post("post.php", {text: clientmsg});
                $("#usermsg").attr("value", "");
                loadLog;
                return false;
            });

        function loadLog(){
            var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
            $.ajax({
                url: "log.html",
                cache: false,
                success: function(html){
                    $("#chatbox").html(html); //Insert chat log into the #chatbox div

                //Auto-scroll
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
                    if(newscrollHeight > oldscrollHeight){
                        $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                    }
                },
            });
        }

        setInterval (loadLog, 1000);
    </script>
    <script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    <script type="text/javascript"></script>
</body>
</html>
