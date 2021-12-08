<?php
	global $wpdb;
	$table_name = $wpdb->prefix."name_list";
	echo $table_name;    
	$insert_query = " insert into ".$table_name."(user_name) values ('".$_GET['txtUsername']."') "; 
	$insertResult=$wpdb->query($insert_query);
?>