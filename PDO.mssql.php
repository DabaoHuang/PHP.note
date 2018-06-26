<?php
// For SQL server 2016
try {
    $dbhost = "";
    $dbname = "";
    $dbuser = "";
    $dbpass = "";
    $dbh = new PDO('odbc:Driver=FreeTDS; Server='.$dbhost.'; Prot=1433; Database='.$dbname.'; TDS_Version=8.0; ClientCharset=UTF-8', $dbuser, $dbpass);
} catch ( PDOException $e ) {
    echo $e->getMessage() . "\n";
    exit;
}

// Function One ASSOC
$stmt  = $dbh->query($queryString);
$DataAss  = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Function Two One
$stmt = $dbh->prepare("SELECT * FROM table WHERE id=1 LIMIT 1");
$stmt->execute();
$row = $stmt->fetch();

?>