<?php
/**
 * Created by PhpStorm.
 * User: Anuradha Sanjeewa
 * Date: 14/11/2015
 * Time: 12:46
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Emailer API</title>
    <script src="js/jquery-1.11.3.js"></script>
</head>
<body>
<script>
    $(document).ready(function () {
        $('#username').on('input', function () {
            if ($('#username').val() == ' ') {
                $('#username').val('');
            } else if ($('#username').val().length >= 3) {
                var un = $('#username').val();
                $.get('mailer.php?ty=check&un=' + un, function (data, success) {
                    if ($.parseJSON(data) == true) {
                        $("#check").text(" Available");
                    } else {
                        $("#check").text(" Not Available");
                    }
                });
            } else {
                $("#check").text(" Must be more than 3 characters");
            }
        });
        var pwcheck = function () {
            if ($('#pwd').val().length < 4) {
                $('#pwcheck').text(' Must be atleast 8 chars long');
                return false;
            } else {
                return true;
            }
        };

        $("#fm").submit(function () {
            if($('#username').val().length < 3){
                return;
            }
            if (pwcheck()) {
                var un = $('#username').val();
                var em = $('#siteem').val();
                var em1 = $('#em1').val();
                var em2 = $('#em2').val();
                var pw = $('#pwd').val();

                $.get('mailer.php?ty=add&un=' + un + '&em=' + em + '&em1=' + em1 + '&em2=' + em2 + '&pw=' + pw,
                    function (data, success) {
                    if ($.parseJSON(data) == true) {
                        alert("Successfully Added");
                    } else {
                        alert("Failed, Please contact the site admin");
                    }
                });
            }
        });
    });

</script>
<form onsubmit="return false;" id="fm">
    Enter the desired username: <input type="text" name="username" id="username" required maxlength="20"><span
        id="check"></span>
    <br>
    Enter the desired password: <input type="password" name="pwd" id="pwd" required maxlength="20"><span
        id="pwcheck"></span>
    <br>
    Site email: <input type="email" name="siteem" id="siteem" required maxlength="45">
    <br>
    Email 1: <input type="email" name="em1" id="em1" required maxlength="45">
    <br>
    Email 2: <input type="email" name="em2" id="em2" maxlength="45">
    <br>
    <button type="submit">Submit</button>
    <br>
    <button type="reset">Reset Fields</button>
</form>
<p>
<p><strong>Documentation</strong><br /><br /><span style="text-decoration: underline;">How to send an email</span></p>
<p><br />Simply send a get request to http://www.quarksis.com/mailerAPI/mailer.php as below<br /><br />http://www.quarksis.com/mailerAPI/mailer.php?ty=send&amp;un=&lt;USERNAME&gt;&amp;pw=&lt;PASSWORD&gt;&amp;msg=&lt;MESSAGE FROM THE CLIENT&gt;&amp;sub=&lt;SUBJECT&gt;&amp;name=&lt;CLIENT NAME&gt;&amp;cli=&lt;CLIENT EMAIL&gt;</p>
<br>
Without spaces
<p>
    Client message, name and email are the data collected from the form
</p>
<p>
    Username and password are the ones you used to register
</p>
<p>
    Subject is just to make the email in your inbox without being in Junk, use something nice eg: Tanker Limited
</p>
<p>
    The output will be a JSON object as below, if only recipient you have message the array will have a single object
    <br>
    {"from":"admin@quarksis.com","to":"anuradhawick@gmail.com","subject":"My Subject","message":"Hi, This is a test","messenger":"tankgame_user@gmail.com"}
</p>
<strong>PLEASE USE SERVER SIDE METHOD TO SEND THE GET</strong>
</p>
</body>
</html>
