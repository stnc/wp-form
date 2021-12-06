<?php
	$success_msg = '';
	global $wpdb; 

if(isset($_GET['txtUsername'])){  
	require('./../../../' .'wp-blog-header.php');
	$table_name = $wpdb->prefix."name_list";
 
	if($_GET['txtUsername']==""){
		$full_text_status="show";  
	}
	$insert_query = " insert into ".$table_name."(user_name) values ('".$_GET['txtUsername']."') "; 
	$insertResult=$wpdb->query($insert_query);
	if($insertResult){
		$success_msg = "Insert successfully.";
		//get_permalink();
		header("Location:registeration_form.php");
	}
}

if(isset($_POST['submit'])){
	$table_name = $wpdb->prefix."name_list";
	 
	if($_POST['username']==""){
		$full_text_status="show"; 
	}
	$insert_query = " insert into ".$table_name."(user_name) values ('".$_POST['username']."') "; 
	$insertResult=$wpdb->query($insert_query);
	if($insertResult){
		$success_msg = "Insert successfully.";
		get_permalink();
	}
}

add_action('init','ava_test_init');
 
function ava_test_init() {

wp_enqueue_script( 'ava-test-js',plugin_dir_url(__FILE__). '/jquery-1.8.3.js');
}

function name_plugin_setting(){
	global $wpdb;
	?>   
	<script type="text/javascript">
	
		function fun()  
		{    
			var txtUsername=$('#txtUsername').val(); 
			var query='txtUsername='+txtUsername;
			alert(query);
			$.ajax({             
				url:'../wp-content/plugins/name_plugin/registeration_form.php',           
				data:query,             
				dataType:'text',          
				success:function(jArray)         
				{      
					alert('Insert Successfully');					
				},   
				error:function()   
				{			     
					alert('ERROR:Problem in user registeration'); 
				} 
			});  	
		}
	</script>
	<form name="registration_form" method="post" action="<?php get_permalink();?>">
   		<table border="0" cellspacing="4" cellspadding="4" width="700px">
			<tr>
			<td colspan="2"><b><h3>User Registeration</h3></b></td>
			</tr>
			<tr>
				<td>Username :</td>
				<td><input style="width: 300px;" type="text" id="txtUsername" name="username" /></td>
			</tr>
			<tr>
				<td><input type="button" onclick="fun()" value="click me"></td>
			</tr>
			<tr>
			<td></td>
			<td align="left"><?php submit_button();?></td>
			</tr>			
		</table>
	</form>
	<?php 
	}
?>