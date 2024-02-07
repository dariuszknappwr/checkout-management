<?php
//http://zdrowyszop.pl/produkty
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting( E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED );
include_once "classes/Db_product.php";
include_once "classes/Page.php";
$db_product = new Db_product();

if (isset($_REQUEST['add_product_to_cart'])) {
    $product['id'] = $_REQUEST['id'];
    $product['name'] = $_REQUEST['name'];
    $product['count'] = $_REQUEST['count'];
    $product['category_name'] = $_REQUEST['category_name'];
    $product['price'] = $_REQUEST['price'];

    $_SESSION['cart'][$product['id']] = $product;
    if(isset($_SESSION['total_cart_value'])){
        $_SESSION['total_cart_value'] = floatval($_SESSION['total_cart_value']) + floatval($product['price']);
    }
    else
    {
        $_SESSION['total_cart_value'] = floatval($product['price']);
    }
    
    echo "Dodano " . $product['name'] . " do koszyka";
}
if(isset($_REQUEST['category'])){
    $_SESSION['category'] = $_REQUEST['category_name'];
}
if(isset($_REQUEST['show_category'])){
    unset($_SESSION['category']);
}

if(!isset($_SESSION['category'])){
    echo "Wybierz kategorię";
        $categories = $db_product->get_all_main_categories();
        create_table($categories, "add_product.php", [], ["id"],["category_name"],"Wybierz", "category");
}else{
    $categories = $db_product->get_child_categories($_SESSION['category']);
            create_table($categories, "add_product.php", ["Wybierz kategorię"], ["id"],["category_name"],"Wybierz", "category");
}

?>
    <?php
    if(isset($_SESSION['category'])){
        $all_products_of_category = $db_product->get_products_of_category($_SESSION['category']);
        if($all_products_of_category != null){
            create_table($all_products_of_category, "add_product.php", ["nazwa", "ilość"], ["id", "category_name"],  ["name", "count", "price"],"Dodaj produkt", "add_product_to_cart");
        }
        ?>
        <form method="post" action="add_product.php">
        <input type="submit" id="submit" value="Zmień kategorię" name="show_category">
    </form>
            <?php
    }
    

    Page::display_navigation();




function create_table($items, $form_action, $column_labels, $hiddenProperties, $visibleProperties, $button_value, $button_name){
    ?>
    <table>
    <tr>
    <?php foreach($column_labels as $label){ ?>
        <td><?php echo $label ?></td>
    <?php } ?>
    </tr>
    <?php
    foreach ($items as $item) {
        ?>
        <form method="post" action="<?php echo $form_action ?>">
        <tr>
            <?php
            foreach($visibleProperties as $property){
                ?>
                <td>
                    <input type="hidden" name="<?php echo $property?>" id="<?php echo $property?>" value="<?php echo $item[$property] ?>" />
                    <?php echo $item[$property] ?>
                </td>
                <?php
            }
            foreach($hiddenProperties as $property){
                ?>
                <td>
                    <input type="hidden" name="<?php echo $property?>" id="<?php echo $property?>" value="<?php echo $item[$property] ?>" />
                </td>
                <?php
            }
            ?>
            <td>
                <input type="submit" id="submit" value="<?php echo $button_value?>" name="<?php echo $button_name?>">
            </td>    
        </tr>
        </form>
        <?php
    }
    ?>
    </table>
    <?php
}
