<?php

require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

$pdo = new Db_product();
class Db_product
{
    private $db;
    private $purifier;
    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=mydb', 'root', "");
        } catch (PDOException $e) {
            echo "Cannot connect to database";
            die();
        }
    }


    public function get_all_products()
    {
        try {
            $sql = "SELECT p.id, p.name, p.count, p.price, c.category_name,  FROM product p"
                . " INNER JOIN category c ON c.id=p.id_category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function get_products_of_category($category)
    {
        $category = $this->purifier->purify($category);
        try {
            $sql = "SELECT p.id, p.name, p.count, p.price, c.category_name FROM product p"
                . " INNER JOIN category c ON c.id=p.id_category"
                . " WHERE c.category_name=:category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["category" => $category]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function get_all_main_categories()
    {
        try {
            $sql = "SELECT c.id, c.category_name FROM category c"
                . " WHERE c.id_parent IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }
    public function get_category_id($category)
    {
        try {
            $sql = "SELECT c.id FROM category c"
                . " WHERE c.category_name=:category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["category" => $category]);
            return $stmt->fetch()['id'];
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }
    public function get_child_categories($parent_category)
    {
        $parent_category = $this->purifier->purify($parent_category);

        try {
            $sql = "SELECT c.id, c.category_name FROM category c"
                . " WHERE c.id_parent=:parent";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["parent" => self::get_category_id($parent_category)]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function save_transaction($cart)
    {
        try {
            $transaction_id = self::create_new_transaction();
            self::save_transation_items($cart, $transaction_id);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function create_new_transaction()
    {
        $sql = "INSERT INTO transaction(datetime, id_cashier)"
            . " VALUES (:date, :id_cashier)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["date" => date("Y-m-d H:i:s"), "id_cashier" => $_SESSION['login_id']]);
        return $this->db->lastInsertId();
    }

    public function save_transation_items($cart, $transaction_id)
    {
        $transaction_id = $this->purifier->purify($transaction_id);
        $sql = "INSERT INTO product_transaction(id_product, id_transaction)"
            . " VALUES (:item, :id_transaction)";
        $stmt = $this->db->prepare($sql);
        foreach ($cart as $item) {
            $stmt->execute(["item" => $item['id'], "id_transaction" => $transaction_id]);
        }
    }

    public function get_all_transactions(){
        try {
            $sql = "SELECT t.id, t.datetime, u.login FROM transaction t"
                . " INNER JOIN user u ON u.id=t.id_cashier";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function get_transation_items($transaction_id)
    {
        try {
            $sql = "SELECT p.name, p.count, p.price FROM product_transaction pt"
                . " INNER JOIN product p ON p.id=pt.id_product"
                . " WHERE pt.id_transaction=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $transaction_id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }
}
