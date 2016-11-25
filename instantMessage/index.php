<?php
session_start ();
$username = $_SESSION["username"];
if (isset ( $_GET ['logout'] )) {

    // Simple exit message
    $fp = fopen ( "log.html", 'a' );
    fwrite ( $fp, "<div class='msgln'><i>User " . $username . " has left the chat session.</i><br></div>" );
    fclose ( $fp );

    session_destroy ();
    header ( "Location: login.php" ); // Redirect the user
}

if (isset($_SESSION["grpid"]))
{
    $grpid=$_SESSION["grpid"];
    $targetGroup = $_POST["targetGroup"];

    $groups = json_decode(file_get_contents("groups.json"), true);
    if (!$groups) die("Internal error"); // server parse error

    if (!array_key_exists($grpid, $groups) || !in_array($username, $groups[$grpid]["users"]))
        header("Location: login.php"); // user not in group or group not exists

}
else {
    session_destroy();
    header("Location: login.php");
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
                Welcome, <b><?php echo $username; ?></b>
            </p>
            <p class="logout">
                <a id="exit" href="#">Exit Chat</a>
            </p>
            <div style="clear: both"></div>
        </div>
        <div id="chatbox"> </div>

        <input name="usermsg" type="text" id="usermsg" size="63" autocomplete="off" /> 
        <input type="hidden" name="type" value="user_say" />
        <button name="submitmsg" id="submitmsg" >Send</button>
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
            return false;
        });

        var msg_no = 0;
        function loadLog(){
            var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
            console.log(msg_no);
            $.ajax({
                method: "POST",
                url: "checkMessage.php",
                data: {"msg_no": msg_no},
                success: function(data)
                {
                    console.log(data);
                    data = JSON.parse(data);
                    msgs = data.msgs;
                    console.log(msgs);

                    var html;
                    for (i = 0; i < msgs.length && msg_no < data.msg_no; ++i, msg_no++)
                    {
                        msg = msgs[i];
                        switch (msg.type)
                        {
                            case "user_join":
                            html = `
                            <div class='msgln'>
                                <span class='msgln'>(`+msg.time+`)</span>
                                <i>User <b>`+msg.user+`</b> has joined the chat session.</i>
                            </div>
                            <br>
                            `;
                            break;

                            case "user_leave":
                            html = `
                            <div class='msgln'> <span class='msgln'>(`+msg.time+`)</span>
                                <i>User <b>`+msg.user+`</b> has left the chat session.</i>
                            </div>
                            <br>
                            `;
                            break;

                            case "user_say":
                            html = `
                            <div>
                                <span class='msgln'>(`+msg.time+`)</span>
                                <b>`+msg.user+`</b>: `+msg.msg+`
                            </div>
                            <br>
                            `;
                            break;
                        }

                        $("#chatbox").append(html);
                    }

                    //Auto-scroll
                    var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; 
                    //Scroll height after the request
                    if(newscrollHeight > oldscrollHeight){
                        $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                    }
                    setTimeout(loadLog, 1000);
                },
            });
        }
        loadLog();

    </script>
    <script type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    <script type="text/javascript"></script>
</body>
</html>
