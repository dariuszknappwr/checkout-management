<?php

include_once "Pdo_.php";


class Db
{
    private $db; //Database variable
    private $select_result; //result
    private $purifier;

    public function __construct($serwer, $user, $pass, $baza)
    {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
        try {
            $this->db = new PDO('mysql:host=localhost;dbname=mydb', 'root', "");
        } catch (PDOException $e) {
            print "Błąd połączenia z bazą!: " . $e->getMessage() . "<br />";
            die();
        }
    }

    function __destruct()
    {
        $this->db = null;
    }

    public function select($sql)
    {
        $results = array();
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        foreach ($statement->fetchAll() as $k => $v) {
            $results[] = (object)$v;
        }
        $statement->closeCursor();
        $this->select_result = $results;
        self::registerShowMessages($results);
        return $results;
    }

    private function registerShowMessages($messages){
        $row_numbers = "messages  ";
        foreach($messages as $message){
            $row = $message->id;
            $row_numbers = $row_numbers . $row . ',';
        }
        $pdo = new Pdo_();
        $pdo->register_user_activity('show data', null, '', $row_numbers, 'messages');
    }

    public function addMessage($name, $type, $content, $id_user)
    {
        if (isset($_SESSION['add message']) && isset($_SESSION['logged'])) {
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS) === false) {
                echo "Name is not valid<br/>";
                return;
            }
            //whitelist
            switch ($type) {
                case 'public':
                    break;
                case 'private':
                    break;
                default:
                    echo "Type is not valid";
                    return;
            }

            if (str_contains($name, "SELECT"))
                return;
            if (filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS) === false) {
                echo "Content is not valid<br/>";
                return;
            }

            try {
                //add message
                $sql = "INSERT INTO message (`name`,`type`, `message`,`deleted`,`id_user`)
VALUES ('" . $name . "','" . $type . "','" . $content . "',0," . $id_user . ")";

                echo $sql;
                echo "<BR\>";
                $result = $this->db->exec($sql);

                //get data of a new message
                $sql = "SELECT id FROM message WHERE name=:name AND type=:type AND message=:message AND deleted=:deleted AND id_user=:id_user";
                $stmt = $this->db->prepare($sql);
                $data = [
                    'name' => $name,
                    'type' => $type,
                    'message' => $content,
                    'deleted' => "0",
                    'id_user' => $id_user
                ];
                $stmt->execute($data);
                $msg_data = $stmt->fetch();
                $prev_data = $msg_data['id'] . "|" . $name . "|"
                . $type . "|" . $content . "|" . "1" . "|" . $id_user;
                $new_data = $msg_data['id'] . "|" . $name . "|"
                    . $type . "|" . $content . "|" . "0" . "|" . $id_user;

                $pdo = new Pdo_();
                $pdo->register_user_activity('add', $msg_data['id'], $prev_data, $new_data, 'message');
                return $result;
            } catch (Exception $e) {
                print 'Exception' . $e->getMessage();
            }
        } else {
            echo 'You have no privilege to add message';
        }
    }

    public function editMessage($id, $name, $type, $content)
    {
        if (isset($_SESSION['edit message']) && isset($_SESSION['logged'])) {

            if (str_contains($name, "\'"))
                return;
            if (filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT) === false) {
                echo "ID is not valid<br/>";
                return;
            }
            if (filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS) === false) {
                echo "Name is not valid<br/>";
                return;
            }
            //whitelist
            switch ($type) {
                case 'public':
                    break;
                case 'private':
                    break;
                default:
                    echo "Type is not valid";
                    return;
            }
            if (str_contains($name, "SELECT"))
                return;
            if (filter_input(INPUT_POST, "content", FILTER_SANITIZE_SPECIAL_CHARS) === false) {
                echo "Content is not valid<br/>";
                return;
            }


            //getting the previous value of the record
            $prev_data = self::getMessageRowData($id);

            //editing
            $sql = "UPDATE `message` SET `name`=:name,`type`=:type, `message`=:content WHERE `id`=:id";
            $data = [
                'name' => $name,
                'type' => $type,
                'content' => $content,
                'id' => $id
            ];
            echo $sql;
            echo "<BR\>";

            $status = $this->db->prepare($sql)->execute($data);
            $newData = self::getMessageRowData($id);
            $pdo = new Pdo_();
            $pdo->register_user_activity('edit', $id, $prev_data, $newData, 'message');
            return $status;
        } else {

            echo 'You have no privilege to edit message';
        }
    }

    public function deleteMessage($id)
    {
        $id = $this->purifier->purify($id);
        if (isset($_SESSION['delete message']) && isset($_SESSION['logged'])) {
            try {
                $prev_data = self::getMessageRowData($id);

                //set deleted to 1
                $sql = "UPDATE message m "
                    . " SET deleted=1"
                    . " WHERE m.id=:id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['id' => $id]);

                $new_data = self::getMessageRowData($id);

                $pdo = new Pdo_();
                $pdo->register_user_activity('delete', $id, $prev_data, $new_data, 'message');
                return true;
            } catch (Exception $e) {
                print 'Exception' . $e->getMessage();
            }
        } else {
            echo 'YOU HAVE NO PRIVILEGE TO DELETE MESSAGE </br>';
            return false;
        }
    }

    public function getMessage($message_id)
    {
        foreach ($this->select_result as $message) :
            if ($message->id == $message_id)
                return $message->message;
        endforeach;
    }

    private function getMessageRowData($id){
        try {
            //get database before changes
            $sql = "SELECT * FROM message WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $msg_data = $stmt->fetch();
            $result = $id . "|" . $msg_data['name'] . "|" . $msg_data['type'] . "|" . $msg_data['message'] . "|" . $msg_data['deleted'];
            return $result;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }
}
