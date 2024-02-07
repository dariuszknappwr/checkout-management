<?php
require './htmlpurifier-4.14.0/library/HTMLPurifier.auto.php';
require_once "hash.php";

    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

$pdo = new Pdo_();
class Pdo_
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
    public function add_user($login, $email, $password, $two_factor_authentication)
    {
        //generate salt
        $salt = random_bytes(16);
        //hash password with salt
        $password = hash_using_algorithm($password . $salt);

        $login = $this->purifier->purify($login);
        $email = $this->purifier->purify($email);
        $two_factor_authentication = $this->purifier->purify($two_factor_authentication);
        if ($two_factor_authentication) {
            $two_factor_authentication = 1;
        } else {
            $two_factor_authentication = 0;
        }
        try {
            $sql = "INSERT INTO `user`( `login`, `email`, `hash`, `salt`, `id_status`, `password_form`, `two_factor_authentication`)
VALUES (:login,:email,:hash,:salt,:id_status,:password_form, :two_factor_authentication)";
            $data = [
                'login' => $login,
                'email' => $email,
                'hash' => $password,
                'salt' => $salt,
                'id_status' => '1',
                'password_form' => '1',
                'two_factor_authentication' => $two_factor_authentication
            ];
            $this->db->prepare($sql)->execute($data);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function get_user_privileges($login)
    {
        $login = $this->purifier->purify($login);
        try {
            return self::sql_select_privileges_id_name_idParent_of_user($login);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function set_user_privileges_in_session($login)
    {
        $login = $this->purifier->purify($login);
        try {
            $privileges = self::sql_select_privileges_id_name_idParent_of_user($login);
            //var_dump($privileges);
            foreach ($privileges as $p) {
                $_SESSION[$p['name']] = 'YES';
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    private function sql_select_privileges_id_name_idParent_of_user($login)
    {
        $sql = "SELECT p.id,p.name,p.id_parent_privilege FROM privilege p"
            . " INNER JOIN user_privilege up ON p.id=up.id_privilege"
            . " INNER JOIN user u ON u.id=up.id_user"
            . " WHERE u.login=:login";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['login' => $login]);
        return $stmt->fetchAll();
    }

    public function get_user_privileges_with_childs($login)
    {
        $login = $this->purifier->purify($login);
        try {
            $privileges = self::sql_select_privileges_id_name_idParent_of_user($login);
        $privileges += self::select_all_childs_foreach_parent_privilege($privileges);
        $privileges += self::select_privileges_of_user_roles($login);
        return $privileges;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    private function select_all_childs_foreach_parent_privilege($user_privileges)
    {
        $finalPrivileges = [];
        foreach ($user_privileges as $privilege) {
            // Przy każdym uprawnieniu sprawdzam, czy jest rodzicem
            // Jeżeli jest rodzicem, to zapisuję w zmiennej przywieleje dzieci
            $finalPrivileges +=  self::select_all_childs_if_privilege_if_parent($privilege);
            $finalPrivileges += self::get_all_child_privileges_names($user_privileges);
        }
        return $finalPrivileges;
    }

    private function select_all_childs_if_privilege_if_parent($privilege)
    {
        $sql = "SELECT p.id,p.name FROM privilege p"
            . " WHERE p.id_parent_privilege=:id_parent_privilege";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_parent_privilege' => $privilege['id']]);
        return $stmt->fetchAll();
    }

    private function get_all_child_privileges_names($user_privileges)
    {
        foreach ($user_privileges as $row) {
            $child_privilege = $row['name'];
            $childPrivileges[$child_privilege] = $row;
        }
        return $childPrivileges;
    }

    public function for_each_parent_privilege_save_childs_in_session($login)
    {
        $user_privileges = self::sql_select_privileges_id_name_idParent_of_user($login);
        foreach ($user_privileges as $row) {
            $privilege = $row['name'];
            $_SESSION[$privilege] = 'YES';

            // Przy każdym uprawnieniu sprawdzam, czy jest rodzicem
            // Jeżeli jest rodzicem, to wpisuję do sesji przywieleje dzieci
            $sql = "SELECT p.id,p.name FROM privilege p"
                . " WHERE p.id_parent_privilege=:id_parent_privilege";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_parent_privilege' => $row['id']]);
            $user_privileges = $stmt->fetchAll();

            foreach ($user_privileges as $row) {
                $privilegeChild = $row['name'];
                $_SESSION[$privilegeChild] = 'YES';
            }
        }
    }

    private function select_privileges_of_user_roles($login)
    {
        $sql = "SELECT p.id,p.name FROM privilege p"
            . " INNER JOIN role_privilege rp ON p.id=rp.id_privilege"
            . " INNER JOIN role r ON r.id=rp.id_role"
            . " INNER JOIN user_role ur ON r.id=ur.id_role"
            . " INNER Join user u ON u.id=ur.id_user"
            . " WHERE u.login=:login";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['login' => $login]);
        $user_privileges = $stmt->fetchAll();
        $finalPrivileges = [];
        foreach ($user_privileges as $row) {
            $privilege = $row['name'];
            $_SESSION[$privilege] = 'YES';
            $finalPrivileges[$privilege] = $row;
        }
        return $finalPrivileges;
    }

    public function show_all_privileges()
    {
        try {
            return self::sql_select_all_privileges_id_name();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    private function sql_select_all_privileges_id_name()
    {
        $sql = "SELECT p.id,p.name FROM privilege p";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function show_logged_in_user_privileges()
    {
        try {
            echo "Wszystkie uprawnienia użytkownika: </br>";
            $privilege = '';
            foreach ($_SESSION as $privilege => $value) {
                if ($value == 'YES') {
                    echo "$privilege </br>";
                }
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function add_privilege($login, $privilege_name)
    {
        try {
            $sql = "INSERT INTO user_privilege (id_privilege, id_user)"
                . " SELECT "
                . " (SELECT p.id FROM privilege p"
                . " WHERE p.name=:privilege_name),"
                . "( SELECT u.id FROM user u"
                . " WHERE u.login=:login) ";
            $bindParameters = ['login' => $login, 'privilege_name' => $privilege_name];
            self::sendSqlExecuteQuery($sql, $bindParameters);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }
    private function sendSqlExecuteQuery($sqlQuery, $bindParameters)
    {
        $stmt = $this->db->prepare($sqlQuery);
        $stmt->execute($bindParameters);
    }

    public function remove_privilege($login, $privilege_name)
    {
        $login = $this->purifier->purify($login);
        $privilege_name = $this->purifier->purify($privilege_name);
        try {
            $sql = "DELETE up FROM user_privilege up"
                . " INNER JOIN privilege p ON p.id=up.id_privilege "
                . " INNER JOIN user u ON u.id = up.id_user"
                . " WHERE u.login=:login AND p.name=:privilege_name";
            $bindParameters = ['login' => $login, 'privilege_name' => $privilege_name];
            self::sendSqlExecuteQuery($sql, $bindParameters);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }



    public function get_all_roles()
    {
        try {
            return self::select_all_roles_id_roleName();
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    private function select_all_roles_id_roleName()
    {
        $sql = "SELECT r.id,r.role_name FROM role r";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function add_role($role_name, $description)
    {
        try {
            $inputs = self::purify_variables(['role_name' => $role_name, 'description' => $description]);
            $sql = "INSERT INTO role (role_name, description)"
                . " VALUES(:role_name, :description)";
            $bindParameters = ['role_name' => $inputs['role_name'], 'description' => $inputs['description']];
            self::sendSqlExecuteQuery($sql, $bindParameters);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    function purify_variables($variables)
    {
        foreach ($variables as $v) {
            $v = $this->purifier->purify($v);
        }
        return $variables;
    }

    public function remove_role($role_name)
    {
        try {
            $role_name = $this->purifier->purify($role_name);
            $sql = "DELETE r FROM role r"
                . " WHERE r.role_name=:role_name";
            $bindParameters = ['role_name' => $role_name];
            self::sendSqlExecuteQuery($sql, $bindParameters);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function show_roles_of_user($login)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT r.role_name FROM role r"
                . " INNER JOIN user_role ur ON r.id=ur.id_role"
                . " INNER JOIN user u ON u.id=ur.id_user"
                . " WHERE u.login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $data = $stmt->fetchAll();
            return $data;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }


    public function add_role_to_user($login, $role_name)
    {
        $login = $this->purifier->purify($login);
        $role_name = $this->purifier->purify($role_name);
        try {
            $sql = "INSERT INTO user_role (id_role, id_user, issue_time)"
                . " SELECT "
                . " (SELECT r.id FROM role r"
                . " WHERE r.role_name=:role_name),"
                . " (SELECT u.id FROM user u"
                . " WHERE u.login=:login), "
                . " (SELECT CURRENT_DATE())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login, 'role_name' => $role_name]);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function remove_role_from_user($login, $role_name)
    {
        $login = $this->purifier->purify($login);
        $role_name = $this->purifier->purify($role_name);
        try {
            $sql = "DELETE ur FROM user_role ur"
                . " INNER JOIN role r ON r.id=ur.id_role "
                . " INNER JOIN user u ON u.id = ur.id_user"
                . " WHERE u.login=:login AND r.role_name=:role_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login, 'role_name' => $role_name]);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function show_privileges_of_role($role_name)
    {
        $role_name = $this->purifier->purify($role_name);
        try {
            $sql = "SELECT p.name FROM privilege p"
                . " INNER JOIN role_privilege rp ON p.id=rp.id_privilege"
                . " INNER JOIN role r ON r.id=rp.id_role"
                . " WHERE r.role_name=:role_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['role_name' => $role_name]);
            $data = $stmt->fetchAll();
            return $data;
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function add_privilege_to_role($privilege_name, $role_name)
    {
        $privilege_name = $this->purifier->purify($privilege_name);
        $role_name = $this->purifier->purify($role_name);
        try {
            $sql = "INSERT INTO role_privilege (id_role, id_privilege, issue_time)"
                . " SELECT "
                . " (SELECT r.id FROM role r"
                . " WHERE r.role_name=:role_name),"
                . " (SELECT p.id FROM privilege p"
                . " WHERE p.name=:privilege_name), "
                . " (SELECT CURRENT_DATE())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['privilege_name' => $privilege_name, 'role_name' => $role_name]);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function remove_privilege_from_role($privilege_name, $role_name)
    {
        $privilege_name = $this->purifier->purify($privilege_name);
        $role_name = $this->purifier->purify($role_name);
        try {
            $sql = "DELETE rp FROM role_privilege rp"
                . " INNER JOIN role r ON r.id=rp.id_role "
                . " INNER JOIN privilege p ON p.id = rp.id_privilege"
                . " WHERE p.name=:privilege_name AND r.role_name=:role_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['privilege_name' => $privilege_name, 'role_name' => $role_name]);
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function log_user_in($login, $password)
    {
        $_SESSION['loggedin_time'] = time();
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $password = hash_using_algorithm($password . $user_data['salt']);

            //$password = hash('sha512', $password . $user_data['salt']);
            if ($password == $user_data['hash']) {
                self::get_user_privileges($login, true);
                //self::show_all_privileges();
                echo 'login successfull<BR />';
                echo 'You are logged in as: ' . $user_data['login'] . '<BR />';
                $_SESSION['loggedin_time'] = time();
            } else {
                echo 'login FAILED<BR />';
                self::register_user_login(-1, 'IP', 0, 'PC', 0);
            }
        } catch (Exception $e) {
            //modify the code here
            print 'Exception' . $e->getMessage();
        }
    }

    public function change_password($login, $current_password, $new_password)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $password = hash_using_algorithm($current_password . $user_data['salt']);


            if ($password == $user_data['hash']) {
                echo 'verification successfull, changing password...<BR />';
                //generate salt
                $salt = random_bytes(16);
                //hash password with salt
                $password = hash_using_algorithm($new_password . $salt);

                try {
                    $sql = "UPDATE user SET hash = :password, salt = :salt WHERE login = :login";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindValue(':password', $password);
                    $stmt->bindValue(':salt', $salt);
                    $stmt->bindValue(':login', $login);
                    $stmt->execute();
                    echo "Password changed";
                } catch (Exception $e) {
                    print 'Exception' . $e->getMessage();
                }
            } else {
                echo 'verification FAILED<BR />';
            }
        } catch (Exception $e) {
            //modify the code here
            print 'Exception' . $e->getMessage();
        }
    }

    public function log_2F_step1($login, $password)
    {
        $login = $this->purifier->purify($login);
        try {
            $sql = "SELECT id,hash,login,salt,email FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            $password = hash('sha512', $password . $user_data['salt']);
            if ($password == $user_data['hash']) {
                //generate and send OTP
                $otp = random_int(100000, 999999);
                $code_lifetime = date('Y-m-d H:i:s', time() + 300);
                try {
                    $sql = "UPDATE `user` SET `sms_code`=:code,
        `code_timelife`=:lifetime WHERE login=:login";
                    $data = [
                        'login' => $login,
                        'code' => $otp,
                        'lifetime' => $code_lifetime
                    ];
                    $this->db->prepare($sql)->execute($data);
                    //add the code to send an e-mail with OTP
                    $result = [
                        'result' => 'success'
                    ];
                    $_SESSION['loggedin_time'] = time();
                    return $result;
                } catch (Exception $e) {
                    print 'Exception' . $e->getMessage();
                    //add necessary code here
                }
            } else {
                echo 'login FAILED<BR/>';
                $result = [
                    'result' => 'failed'
                ];
                return $result;
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
            //add necessary code here
        }
    }

    public function log_2F_step2($login, $code)
    {
        $login = $this->purifier->purify($login);
        $code = $this->purifier->purify($code);
        try {
            $sql = "SELECT id,login,sms_code,code_timelife
        FROM user WHERE login=:login";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['login' => $login]);
            $user_data = $stmt->fetch();
            if (
                $code == $user_data['sms_code']
                && time() < strtotime($user_data['code_timelife'])
            ) {
                //login successfull
                echo 'Login successfull<BR/>';
                $_SESSION['loggedin_time'] = time();
                self::register_user_login($user_data['id'], "IP", 1, "PC", 0);
                return true;
            } else {
                echo 'login FAILED<BR/>';
                self::register_user_login($user_data['id'], "IP", 0, "PC", 0);
                return false;
            }
        } catch (Exception $e) {
            print 'Exception' . $e->getMessage();
        }
    }

    public function get_logged_in_user_id()
    {
        if (isset($_SESSION['login'])) {
            try {
                $sql = "SELECT id FROM user WHERE login=:login";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['login' => $_SESSION['login']]);
                $user_data = $stmt->fetch();

                return $user_data['id'];
            } catch (Exception $e) {
                print 'Exception' . $e->getMessage();
            }
        }
    }

    public function show_session_variable()
    {
        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';
    }

    public function register_user_login($id_user, $ip_address, $correct, $computer, $isALougout)
    {
        //id_user=-1 - no such a user
        $id_user = $this->purifier->purify($id_user);
        $ip_address = $this->purifier->purify($ip_address);
        $correct = $this->purifier->purify($correct);
        $computer = $this->purifier->purify($computer);
        if (filter_var($id_user, FILTER_VALIDATE_INT)) {

            //Existing user login
            //check if IP address is registered in DB
            try {
                $sql = "SELECT id FROM ip_address WHERE address_ip=:address_ip";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['address_ip' => $ip_address]);
                $data = $stmt->fetch();
                if (empty($data['id'])) {
                    //IP address not registered. Register in db
                    $sql = "INSERT INTO `ip_address`(`ok_login_num`, `bad_login_num`, `last_bad_login_num`, `permanent_lock`, `address_ip`) "
                        . " VALUES (0,0,0,0,:ip_address)";
                    $this->db->prepare($sql)->execute(['ip_address' => $ip_address]);
                    //check id of inserted record
                    $sql = "SELECT id FROM ip_address WHERE address_ip=:address_ip";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(['address_ip' => $ip_address]);
                    $data = $stmt->fetch();
                }
                $sql = "INSERT INTO `user_login`( `time`, `correct`, `id_user`, `computer`, `session`, `id_address`, `log_out`) VALUES (:time,:correct,:id_user,:computer,:session,:id_address,:log_out)";
                $data = [
                    'time' => date('Y-m-d H:i:s', time()),
                    'correct' => $correct,
                    'id_user' => $id_user,
                    'computer' => $computer,
                    'session' => session_id(),
                    'id_address' => $data['id'],
                    'log_out' => $isALougout
                ];
                $this->db->prepare($sql)->execute($data);
            } catch (Exception $e) {
                print 'Exception' . $e->getMessage();
            }
        }
    }


    public function register_user_activity(
        $action_taken,
        $row_number,
        $previous_data,
        $present_data,
        $table_affected
    ) {
        $action_taken = $this->purifier->purify($action_taken);
        $row_number = $this->purifier->purify($row_number);
        $previous_data = $this->purifier->purify($previous_data);
        $present_data = $this->purifier->purify($present_data);
        $table_affected = $this->purifier->purify($table_affected);
        try {
            $sql = "INSERT INTO `user_activity`( `id_user`, `time`, `action_taken`, `table_affected`,
`row_number`, `previous_data`, `new_data`) VALUES
(:user_id,:time,:action_taken,:table_affected,:row_number,:previous_data,:new_data)";
            $data = [
                'user_id' => $_SESSION['login_id'],
                'time' => date('Y-m-d H:i:s', time()),
                'action_taken' => $action_taken,
                'table_affected' => $table_affected,
                'row_number' => $row_number,
                'previous_data' => $previous_data,
                'new_data' => $present_data
            ];
            $this->db->prepare($sql)->execute($data);
        } catch (Exception $e) {
            var_dump($_SESSION);
            print ' Exception' . $e->getMessage();
        }
    }


    public function get_user_activities()
    {
        try {
            $sql = "SELECT ua.id, u.login, ua.action_taken, ua.time, ua.table_affected, ua.row_number, ua.previous_data, ua.new_data FROM user_activity ua"
                . " INNER JOIN user u ON u.id=ua.id_user";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }
    public function get_user_activity($id)
    {
        try {
            $sql = "SELECT * FROM user_activity WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["id" => $id]);
            $data = $stmt->fetch();
            return $data;
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }

    public function undo_user_activity($activity_id)
    {
        try {
            $activity = self::get_user_activity($activity_id);
            $previous_data = explode("|", $activity['previous_data']);
            $present_data = explode("|", $activity['new_data']);
            $sql = "UPDATE message SET name=:name, type=:type, message=:message, deleted=:deleted WHERE id=:message_id";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":name" => $previous_data[1],
                ":type" => $previous_data[2],
                ":message" => $previous_data[3],
                ":deleted" => $previous_data[4],
                ":message_id" => $activity['row_number']
            ];
            $stmt->execute($data);
            self::register_user_activity('undo changes', $activity['row_number'], $activity['new_data'], $activity['previous_data'], "message");
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }

    public function get_users_login_attempts()
    {
        try {
            $sql = "SELECT u.login, ul.time, ul.session, ul.correct, ul.log_out FROM user_login ul"
                . " INNER JOIN user u ON u.id=ul.id_user";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }

    public function get_all_users()
    {
        try {
            $sql = "SELECT u.id, u.login, u.email  FROM user u"
            . " WHERE u.id > 0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }

    public function save_mqtt_message_to_db($topic, $message, $qos)
    {
        try {
            $sql = "INSERT INTO mqtt (topic, message, qos) VALUES (:topic, :message, :qos)";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":topic" => $topic,
                ":message" => $message,
                ":qos" => $qos,
            ];
            $stmt->execute($data);
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }

    public function get_ordered_products()
    {
        try {
            $sql = "SELECT m.topic, m.message, m.qos FROM mqtt m";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            print ' Exception' . $e->getMessage();
        }
    }


    
}
