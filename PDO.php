<?php

$gb_DBname="db";//資料庫名稱
$gb_DBuser="user";//資料庫使用者名稱
$gb_DBpass="passwd";//資料庫密碼
$gb_DBHOSTname="localhost";//主機的名稱或是IP位址 

class SQL_DB {
    
    var $dbInsert;
 
    function sql_db( $gb_DBHOSTname,$gb_DBname,$gb_DBuser,$gb_DBpass ) {
        
        $this->dbInsert = new PDO('mysql:host='.$gb_DBHOSTname.';port=3306;dbname='.$gb_DBname, $gb_DBuser , $gb_DBpass );
        $sth = $this->dbInsert->prepare("SET NAMES UTF8");
        $sth->execute();

    } // end function sql_db
    
    function num_rows($sthOBJ) {
        return $sthOBJ->rowCount();
    } // end function num_rows
    
    function query($query, $arr) {
        
        if (!is_array($arr)) throw new Exception('need array');
        
        $sth = $this->dbInsert->prepare($query );
        $sth->execute($arr);
        $this->sth = $sth;
        $errorInfo = $this->sth->errorInfo();
        if ((int)$errorInfo[1]!=0) throw new Exception((int)$errorInfo[1]."_".$errorInfo[2]);
        
        return $sth;
    } // end function query
    
    function fetch_record_obj($sthOBJ) {
        return $sthOBJ->fetch(PDO::FETCH_OBJ);
    } // end function fetch_record_obj
    
    function fetch_record($sthOBJ) {
        return $sthOBJ->fetch(PDO::FETCH_ASSOC);
    } // end function fetch_record
    
    function fetch_record_set($sthOBJ) {
        
        while ($setArrayRec = $this->fetch_record($sthOBJ)) {
            $setArray[] =      $setArrayRec;
        } // end while
        return  $setArray;
    } // end function fetch_record_set
    
    function error() {
        $errorInfo = $this->sth->errorInfo();
        return array('code' => (int)$errorInfo[1], 'message' => $errorInfo[2] );              
    } // end function error
    
    function insert_id() {
        return $this->dbInsert->lastInsertId();
    } // end function insert_id
 
}
// 用法

$db = new SQL_DB( $gb_DBHOSTname,$gb_DBname,$gb_DBuser,$gb_DBpass);
 
$sqlRec = $db->query("SELECT * from table Where a = :a AND b = :b", array(":a"=> '參數:a', ":b"=> '參數:b'));

while ($rec = $db->fetch_record($sqlRec)) {
    echo $rec[‘a’];
}


?>