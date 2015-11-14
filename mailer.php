<?php
/**
 * Created by PhpStorm.
 * User: Anuradha Sanjeewa
 * Date: 14/11/2015
 * Time: 13:09
 */
require 'lib/password.php';
define('HOST', 'localhost');
define('UNAME', 'root');
define('PW', '1234');
define('DBNAME', 'mydb');
define('PORT', '3306');
if ($_REQUEST['ty'] == 'check') {
    $username = $_REQUEST['un'];
    $dbc = mysqli_connect(HOST, UNAME, PW, DBNAME);
    $query = "SELECT * FROM mailerAPI_user WHERE `username` = '$username'";
    $result = mysqli_query($dbc, $query) or die(mysqli_error($dbc));
    mysqli_close($dbc);
    if ($result && mysqli_num_rows($result) >= 1) {
        echo json_encode(false);
    } else {
        echo json_encode(true);
    }
} else if ($_REQUEST['ty'] == 'add') {
    $username = $_REQUEST['un'];
    $pass = password_hash($_REQUEST['pw'], PASSWORD_DEFAULT);
    $em = $_REQUEST['em'];
    $dbc = mysqli_connect(HOST, UNAME, PW, DBNAME);
    $query = "INSERT INTO mailerAPI_user (`username`, `email`, `password`) VALUES ('$username','$em','$pass')";
    $result = mysqli_query($dbc, $query);
    if ($result) {
        $id = mysqli_insert_id($dbc);
        // add the email addresses to the user
        $em1 = $_REQUEST['em1'];
        $em2 = $_REQUEST['em2'];
        $query = "INSERT INTO mailerAPI_recepients (`recipient`, `mailerAPI_user_id`) VALUES ('$em1','$id')";
        $result = mysqli_query($dbc, $query);
        if (strlen($em2) > 2) {
            $query = "INSERT INTO mailerAPI_recepients (`recipient`, `mailerAPI_user_id`) VALUES ('$em2','$id')";
            $result = mysqli_query($dbc, $query);
        }
        mysqli_close($dbc);
        echo json_encode(true);
    } else {
        mysqli_close($dbc);
        echo json_encode(false);
    }
} else if ($_REQUEST['ty'] == 'send') {
    $username = $_REQUEST['un'];
    $pass = $_REQUEST['pw'];
    $msg = $_REQUEST['msg'];
    $subject = $_REQUEST['sub'];
    $messenger = $_REQUEST['cli'];
    $name = $_REQUEST['name'];
    $dbc = mysqli_connect(HOST, UNAME, PW, DBNAME);
    $query = "SELECT `id`,`email`,`password` FROM mailerAPI_user WHERE `username` = '$username'";
    $result = mysqli_query($dbc, $query) or die(mysqli_error($dbc));
    $row = mysqli_fetch_array($result);
    if ($result && password_verify($pass, $row['password'])) {
        $id = $row['id'];
        $from = $row['email'];
        $query = "SELECT * FROM mailerAPI_recepients WHERE `mailerapi_user_id` = '$id'";
        $result = mysqli_query($dbc, $query);
        $output = array();
        while ($row = mysqli_fetch_array($result)) {
            $outmsg = array();
            echo '<br>';
            $to = $row['recipient'];
            if (sendEmail($msg, $from, $to, $from, $subject, $messenger, $name)) {
//                echo "<strong>SENT</strong>";
                $outmsg['from'] = $from;
                $outmsg['to'] = $to;
                $outmsg['subject'] = $subject;
                $outmsg['message'] = $msg;
                $outmsg['messenger'] = $messenger;

                array_push($output,$outmsg);
            } else {
//                echo "<strong>FAIL</strong>";
            }

        }
        mysqli_close($dbc);
        echo json_encode($output);
    } else {
        mysqli_close($dbc);
        echo json_encode(false);
    }
}

function sendEmail($msg, $from, $to, $replyto, $subject, $email, $name)
{
    $msgsend = "
<div>
    <div style='margin-top: 40px ;background-color: rgba(245, 245, 245, 0.6); height: 100%'>
        <p style='font-size: 2em'>System Generated Mail</p>
    </div>
    <div style='background-color: rgba(245, 245, 245, 0.6);'>
        <h3>Subject: " . $subject . "</h3>
        <hr>
        <h3>From: " . $name . "</h3>
        <hr>
        <h3>Email: " . $email . "</h3>
        <hr>
        <h3>Message:</h3>
        <h4>" . $msg . "</h4>
        <br>
        <br>
        <br>
        <br>
        <p>&copy; Quark Industrial Solutions</p>
    </div>
</div>

</body>
</html>

</body>
</html>
";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $from . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $mail = mail($to, $subject, $msgsend, $headers);
    if ($mail) {
        return true;
    } else {
        return false;
    }

}



