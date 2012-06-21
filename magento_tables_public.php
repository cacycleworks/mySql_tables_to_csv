<?
$file="magento_db.csv";
$db_ip = "localhost";
$db_user = "username";		// found in app/etc/local.xml
$db_pass = "password";		// found in app/etc/local.xml
$db_name = "database name";	// found in app/etc/local.xml
$db = mysql_connect($db_ip, $db_user, $db_pass) or die(mysql_error());
mysql_select_db($db_name);

$magento_db_description=Array();
$tables=Array();

$sql="SHOW TABLES";
$array_data = multiRow($sql);
foreach( $array_data as $arr) {
	foreach( $arr as $key => $value) {
		$tables[]=$value;
	}
}
// print_r($tables);
foreach( $tables as $table ){
	$sql="DESCRIBE $table";
	$cols_desc=multiRow($sql);
	// print_r($cols);
	//	Array
	//		[0] => Array
	//				[Field] => assert_id
	//				...
	//		[1] => Array
	//				[Field] => assert_type
	//				...
	$cols=Array();
	foreach($cols_desc as $arr){
		$cols[]=$arr['Field'];
	}
	$magento_db_description[$table]="$table,".implode(",",$cols);
}
echo file_put_contents($file,implode("\n",$magento_db_description) )." bytes written to $file\n";
exit;

function isData($sql) {
	if ($sql) {
		if (mysql_num_rows($sql)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function oneRow($sql) {
	$data = mysql_query($sql) or printf("SQL: $sql<br><br>\n\n".mysql_error());
	if (isData($data)) {
		$data = mysql_fetch_assoc($data);
	} else {
		$data = Array();
	}
	return $data;
}

function multiRow ( $sql ) {
	if( $sql == "" ) return FALSE;
	//echo "<hr>\$sql=$sql<br><pre>\n"; // \$row:
	$Q = mysql_query($sql);
	if( isData($Q) )
	while(	$row[]=mysql_fetch_assoc($Q) ) {
		;//print_r( $row );
	}
	if( $row == "" )
		$rtn_stuff=FALSE;
	else
		for($i=0; $i<count($row); $i++){
			if( $row[$i] == "" )
				unset($row[$i]);
		}
	return $row;
}

?>
