<?php
include_once('classes/Pdo_.php');
include_once('classes/Filter.php');
include_once('classes/Session.php');
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
$pdo = new Pdo_();
logOutIfSessionExpired();

if (isset($_SESSION['manage user activity'])) {
    echo '<table border="1">';
?>
    <tr>
        <h2>Login activity</h2>
    </tr>
    <tr>
        <td>User</td>
        <td>Time</td>
        <td>Session</td>
        <td>Correct</td>
        <td>Log out</td>
    </tr>
    
    <?php
    $logins = $pdo->get_users_login_attempts();
    foreach ($logins as $one_record) {
        $rowColor = "#8de3a4";
        if(!$one_record['correct']){
            $rowColor = "#de506a";
        }
        if($one_record['log_out']){
            $rowColor = "#e3e08d";
        }
        echo "<tr bgcolor=".$rowColor.">";
        echo "<td>" . $one_record['login'] . "</td>";
        echo "<td>" . $one_record['time'] . "</td>";
        echo "<td>" . $one_record['session'] . "</td>";
        echo "<td>" . $one_record['correct'] . "</td>";
        echo "<td>" . $one_record['log_out'] . "</td>";
        echo "</tr>";
    }
    echo '</table>';
} else {
    echo "You have no privilege to preview user activity. Only administrator can see this page";
}

include_once "classes/Page.php";
Page::display_header("Main page");
?>
<H2> User activity</H2>
<?php
Page::display_navigation();
?>