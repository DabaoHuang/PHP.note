<?php

class DB {
  static $connection = false;
  var $mysql = false;

  function DB() {
    if (self::$connection === false) {
      if ($this->mysql == false) {
        $this->mysql = array(
          'host' => 'localhost',
          'user' => 'root',
          'pass' => '1234',
          'lang' => 'utf8',
          'db'   => 'sql');
      } // end if
      $res = $this->init();
      if ($res === false) exit;
    } // end if
  } // end function DB

  function __destruct() {
    if (self::$connection !== false) self::close();
  } // end function __destruct

  function init($type = '') {
    self::$connection = mysql_connect($this->mysql['host'], $this->mysql['user'], $this->mysql['pass']);
    if (self::$connection == false) return false;
    if (isset($this->mysql['db'])) mysql_select_db($this->mysql['db'], self::$connection);
    if (isset($this->mysql['lang'])) self::query('SET NAMES \''. $this->mysql['lang'] .'\'', self::$connection);

    return true;
  } // end function init

  function close() {
    mysql_close(self::$connection);
  } // end function close

  function quote($string, $quote = false) {
    if ($quote)
      return '\''. mysql_real_escape_string($string) .'\'';
    return mysql_real_escape_string($string);
  } // end function quote

  function query($sql) {
    return mysql_query($sql);
  } // end function query

  function fetchAll($sql, $convert = true) {
      $res = self::query($sql);
      if ($res == false) {
        // TODO: Do Exception
        return array();
      } // end function fetchAll

    if ($convert) {
     // Check field property
     $modify = array();  // Check the field need modify or not
      for ($i = 0, $fd = ''; $i < mysql_num_fields($res); ++$i) {
        $fd = mysql_field_name($res, $i);
        $modify["$fd"] = '';
        if ('int' === mysql_field_type($res, $i)) $modify["$fd"] = 'int';
      } // end for
    } // end if

   $rows = array();
   $i = 0;
   while ($row = mysql_fetch_assoc($res)) {
     if ($convert){
       foreach ($row as $k => $v) {    // Modifying data
         if ('int' == $modify[$k]) $row[$k] = intval($v);   // Convert to INT
       } // end foreach
     } // end if
     $rows["$i"] = $row;
     ++$i;
   } // end while

   mysql_free_result($res);
   return $rows;
  } // end function

  function fetchRow($sql) {
  $rows = self::fetchAll($sql);
  if (count($rows) == 0) return false;
  return $rows[0];
  } // end function

  function fetchOne($sql) {
  $rows = self::fetchAll($sql);
  if (count($rows) == 0) return false;
  foreach ($rows as $row) {
    if (count($row) == 0) return false;
    foreach ($row as $value) {
      return $value;
    } // end foreach
  } // end foreach
  } // end function

  function insert($table, $data) {
    $fields = '';
    $values = '';

    foreach($data as $k => $v) {
      $fields .= '`'. self::quote($k) .'`,';
      $values .= self::quote($v, true) .',';
    } // end foreach

    $fields = substr($fields, 0, -1);
    $values = substr($values, 0, -1);

    $sql = 'INSERT INTO `'. self::quote($table) .'` ('. $fields .') VALUES ('. $values .')';


    $res = self::query($sql);
    
        if ($res == false) return false;
    
        $id = mysql_insert_id();
        return $id;
  } // end function insert

  function update($table, $data, $where) {
    if ($where != '') $where = ' WHERE '. $where .' '; // 前後加空白, 人生不空白~

    $set = '';

    foreach ($data as $field => $value) {
      $set .= '`'. self::quote($field) .'` = '. self::quote($value, true) .',';
    } // end foreach

    $set = substr($set, 0, -1);
    $sql = 'UPDATE `'. $table .'` SET '. $set . $where;

    $res = self::query($sql);
    if ($res == false) return false;

    return mysql_affected_rows();
  } // end function update
 
   function delete($table, $where) {
     if ($where == '') return false;
     else $where = ' WHERE '. $where;
 
     $sql = 'DELETE FROM '. $table . $where;
 
     $res = self::query($sql);
     if ($res == false) return false;
 
     return mysql_affected_rows();
   } // end function delete
 
  function count($table, $where = '', $field = '') {
    if ($where == '' && $field == '') {
      $sql = 'SELECT COUNT(*) as count FROM '. $table;
      $count = self::fetchOne($sql);
      if ($count === false) return 0;
      return $count;
    } // end if

    if ($field == '') $field = '*';
    if ($where != '') $where = ' WHERE '. $where;
    $sql = 'SELECT '. $field .' FROM '. $table . $where;

    $res = self::query($sql);
    if ($res == false) return 0;

    $num_rows = mysql_num_rows($res);
    if ($num_rows == false) return 0;
    return $num_rows;
  } // end function count
  
} // end class DB

/* Initialize */
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require_once(dirname(__FILE__) .'/DB.php');
$db = new DB();

// 每天清掉一個月前的資料
$delete_date = date('Y-m-d H:i:s', time() - 3600*24*30);
$num = $db->delete('jp_host_log', "time < '{$delete_date}'");

// 最佳化 jp_host_log table
$db->query('OPTIMIZE TABLE jp_host_log');
echo date('Y-m-d H:i:s'), ' 刪除 ', $num, ' 個紀錄', PHP_EOL;

?>