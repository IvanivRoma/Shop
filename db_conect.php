<?php
class Db_conect extends PDO
{

    function __construct($server="localhost",$dbname="shop",$username="root",$password=""){
            parent::__construct("mysql:host=$server;dbname=$dbname", $username, $password,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false));
            $this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}



?>
