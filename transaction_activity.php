<?php
include_once('classes/Pdo_.php');
include_once('classes/Filter.php');
include_once('classes/Session.php');
include_once('classes/Db_product.php');
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
$db_product = new Db_product();
logOutIfSessionExpired();

if (isset($_SESSION['manage user activity'])) {
    echo '<table border="1">';
?>
    <tr>
        <h2>Login activity</h2>
    </tr>
    <tr>
        <td>Kasjer</td>
        <td>Data</td>
    </tr>
    
    <?php
    $transactions = $db_product->get_all_transactions();
    foreach ($transactions as $one_record) {
        echo "<td><b>" . $one_record['login'] . "</b></td>";
        echo "<td><b>" . $one_record['datetime'] . "</b></td>";
        echo "</tr>";
        $products_in_transaction = $db_product->get_transation_items($one_record['id']);
        foreach($products_in_transaction as $item){
            echo "<tr>";
            echo "<td>" . $item['name'] . "</td>";
            echo "<td>" . $item['count'] . "</td>";
            echo "<td>" . $item['price'] . "</td>";
            echo "</tr>";
        }
    }
    echo '</table>';
} else {
    echo "You have no privilege to preview user activity. Only administrator can see this page";
}

include_once "classes/Page.php";
Page::display_navigation();
?>