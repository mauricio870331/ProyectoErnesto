<?php

include_once '../Utils/SimbolyLink.php';
if (strpos($config['base_url'], 'localhost') !== false) {
    require 'define_local.php';
//    echo "local";
} else {
    require 'define_server.php';
//    echo "server";
}

class BD {

    private $con;
    private $stm;
    private $rs;

    public function __construct() {
        try {
            $this->con = new PDO('mysql:host=' . HOST . ';dbname=' . BD . ';charset=utf8', USER, PASS, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function desconectar() {
        $this->stm = null;
        $this->rs = null;
        $this->con = null;
    }

    public function query($query, $opc = false) {
        $this->stm = $this->con->prepare($query);
        $this->stm->execute();
        if ($opc) {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ);
        } else {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_ASSOC);
        }
        return $this->rs;
    }

    public function executeSentence($sql) {
        $this->stm = $this->con->prepare($sql);
        $this->stm->execute();
        $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ); //FETCH_ASSOC
        return $this->rs;
    }

    public function findAll($tabla, $cond = "", $single = false) {
        $query = "select * from " . $tabla . " " . $cond;
        $this->stm = $this->con->prepare($query);
        $this->stm->execute();
        $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ);
        if ($single) {
            return $this->rs[0];
        } else {
            return $this->rs;
        }
    }

    public function login($query, $fields = array(), $opc = false) {
        $this->stm = $this->con->prepare($query);
        $this->stm->execute([$fields[0], $fields[1]]);
        if ($opc) {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ);
        } else {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_ASSOC);
        }
        return $this->rs;
    }

    public function findAll2($query, $opc = false) {
        $this->stm = $this->con->prepare($query);
        $this->stm->execute();
        if ($opc) {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ);
        } else {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_ASSOC);
        }
        return $this->rs;
    }

    public function findById($tabla, $fieldId, $id, $mode = "All") {
//        echo "select * from " . $tabla . " where " . $fieldId . " = " . $id;
        $this->stm = $this->con->prepare("select * from " . $tabla . " where " . $fieldId . " = ?");
        $this->stm->execute(array($id));
        if ($mode == "All") {
            $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ);
        } else {
            $this->rs = $this->stm->fetch(PDO::FETCH_OBJ);
        }
        return $this->rs;
    }

    public function exec($query, $operacion = "") {

        try {
            $this->con->beginTransaction();
            $this->stm = $this->con->prepare($query);
            $this->stm->execute();
            $this->con->commit();
            return array(
                "code" => 1,
                "message_code" => "success",
                "msn" => "Operacion " . $operacion . " realizada con exito..!",
                "code_mysql" => "1000",
                "row_count" => $this->stm->rowCount(),
                "operacion" => $operacion,
                "trace" => base64_encode(json_encode($query))
            );
        } catch (Exception $ex) {
            $this->con->rollBack();
            return array(
                "code" => 0,
                "message_code" => "error",
                "msn" => str_replace("'", "\"", $ex->getMessage()),
                "code_mysql" => $ex->getCode(),
                "row_count" => 0,
                "operacion" => $operacion,
                "trace" => base64_encode(json_encode($ex->getTrace()))
            );
        }
    }

    public function checkID($tabla, $fieldId, $id) {
        $this->stm = $this->con->prepare("select * from " . $tabla . " where " . $fieldId . " = ?");
        $this->stm->execute(array($id));
        if ($this->stm->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function executLogin($sql, $user, $pass) {
        $this->stm = $this->con->prepare($sql);
        $this->stm->execute(array($user, $pass));
        $this->rs = $this->stm->fetchAll(PDO::FETCH_OBJ); //FETCH_ASSOC
        return $this->rs;
    }

}

?>
