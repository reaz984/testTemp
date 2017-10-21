<?php 
header('Content-type:text/html; charset=utf-8');
session_start();
include('../../../includes/common.php');
$user_id = $_SESSION['logic_erp']["user_id"];
if( $_SESSION['logic_erp']['user_id'] == "" ) { header("location:login.php"); die; }
$permission=$_SESSION['page_permission'];
$data=$_REQUEST['data'];
$action=$_REQUEST['action'];

$company_library=return_library_array( "select id,company_short_name from lib_company", "id", "company_short_name"  );
$location_library=return_library_array( "select id,location_name from lib_location", "id", "location_name"  );
$division_library=return_library_array( "select id,division_name from lib_division", "id", "division_name"  );
$department_library=return_library_array( "select id,department_name from lib_department", "id", "department_name"  );
$section_library=return_library_array( "select id,section_name from lib_section", "id", "section_name"  );
$store_library=return_library_array( "select id,store_name from lib_store_location", "id", "store_name"  );


//--------------------------------------------------------------------------------------------
//load drop down company location
if ($action=="load_drop_down_division")
{
	echo create_drop_down( "cbo_division", 170, "select id,division_name from lib_division where status_active =1 and is_deleted=0 and company_id='$data' order by division_name","id,division_name", 1, "-- Select Division --", $selected, "load_drop_down( 'requires/asset_placement_controller', this.value, 'load_drop_down_department', 'department_td'); show_list_view(document.getElementById('cbo_company_name').value +'_'+ document.getElementById('cbo_location').value +'_'+ document.getElementById('cbo_division').value +'_'+ document.getElementById('cbo_department').value +'_'+ document.getElementById('cbo_section').value +'_'+ document.getElementById('cbo_subsec').value +'_'+ document.getElementById('cbo_floor').value +'_'+ document.getElementById('txt_room_no').value +'_'+ document.getElementById('txt_placing_date').value,'show_asset_active_listview','asset_list_view','requires/asset_placement_controller','');",0, "", "", "", "", "3", "", "" );     	 
	exit();
}

if ($action=="load_drop_down_department")
{
	echo create_drop_down( "cbo_department", 170, "select id,department_name from lib_department where status_active =1 and is_deleted=0 and division_id='$data' order by department_name","id,department_name", 1, "-- Select Department --", $selected, "load_drop_down( 'requires/asset_placement_controller', this.value, 'load_drop_down_section', 'section_td' ); 	show_list_view(document.getElementById('cbo_company_name').value +'_'+ document.getElementById('cbo_location').value +'_'+ document.getElementById('cbo_division').value +'_'+ document.getElementById('cbo_department').value +'_'+ document.getElementById('cbo_section').value +'_'+ document.getElementById('cbo_subsec').value +'_'+ document.getElementById('cbo_floor').value +'_'+ document.getElementById('txt_room_no').value +'_'+ document.getElementById('txt_placing_date').value,'show_asset_active_listview','asset_list_view','requires/asset_placement_controller','');",0 );     	 
	exit();
}

if ($action=="load_drop_down_section")
{
	echo create_drop_down( "cbo_section", 170, "select id,section_name from lib_section where status_active =1 and is_deleted=0 and department_id='$data' order by section_name","id,section_name", 1, "-- Select Section --", $selected, "show_list_view(document.getElementById('cbo_company_name').value +'_'+ document.getElementById('cbo_location').value +'_'+ document.getElementById('cbo_division').value +'_'+ document.getElementById('cbo_department').value +'_'+ document.getElementById('cbo_section').value +'_'+ document.getElementById('cbo_subsec').value +'_'+ document.getElementById('cbo_floor').value +'_'+ document.getElementById('txt_room_no').value +'_'+ document.getElementById('txt_placing_date').value,'show_asset_active_listview','asset_list_view','requires/asset_placement_controller','');",0 );     	 
	exit();
}

if ($action=="load_drop_down_floor")
{
	$data=explode("_",$data);
	echo create_drop_down( "cbo_floor", 170, "select id,floor_name from lib_prod_floor where status_active =1 and is_deleted=0 and company_id='$data[0]' and location_id='$data[1]' order by floor_name","id,floor_name", 1, "-- Select Floor --", $selected, "show_list_view(document.getElementById('cbo_company_name').value +'_'+ document.getElementById('cbo_location').value +'_'+ document.getElementById('cbo_division').value +'_'+ document.getElementById('cbo_department').value +'_'+ document.getElementById('cbo_section').value +'_'+ document.getElementById('cbo_subsec').value +'_'+ document.getElementById('cbo_floor').value +'_'+ document.getElementById('txt_room_no').value +'_'+ document.getElementById('txt_placing_date').value,'show_asset_active_listview','asset_list_view','requires/asset_placement_controller','');",0 );     	 
	exit();
}

//load drop down company Store location
if ($action=="load_drop_down_store")
{
	echo create_drop_down( "cbo_store_name", 170, "select id,store_name from lib_store_location where status_active=1 and is_deleted=0 and company_id='$data' order by store_location","id,store_name", 1, "Display", $selected, "show_list_view(document.getElementById('cbo_company_name').value +'_'+ document.getElementById('cbo_location').value +'_'+ document.getElementById('cbo_division').value +'_'+ document.getElementById('cbo_department').value +'_'+ document.getElementById('cbo_section').value +'_'+ document.getElementById('cbo_subsec').value +'_'+ document.getElementById('cbo_floor').value +'_'+ document.getElementById('txt_room_no').value +'_'+ document.getElementById('txt_placing_date').value,'show_asset_active_listview','asset_list_view','requires/asset_placement_controller','');",1 );   	 
	exit();
}

if ($action=="show_asset_active_listview")
{
	//echo $data; die;
	$ex_data = explode("_", $data);
	
    if ($ex_data[0] == 0)$company_name = "";  	else $company_name 	= " and b.company_name='" . $ex_data[0] . "'";
    if ($ex_data[1] == 0)$location = "";		else $location 		= " and b.location='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0)$division = "";		else $division 		= " and b.division='" . $ex_data[2] . "'";
	if ($ex_data[3] == 0)$department = "";		else $department 	= " and b.department='" . $ex_data[3] . "'";
	if ($ex_data[4] == 0)$section = ""; 		else $section 		= " and b.section='" . $ex_data[4] . "'";
	if ($ex_data[5] == 0)$sub_section = "";		else $sub_section 	= " and b.sub_section='" . $ex_data[5] . "'";
	if ($ex_data[6] == 0)$room_no = "";			else $room_no 		= " and b.room_no='" . $ex_data[6] . "'";
	if ($ex_data[7] == 0)$floor = "";			else $floor 		= " and b.floor='" . $ex_data[7] . "'";
	if ($ex_data[8] == 0)$place_date = "";		else $place_date 	= " and b.place_date='" . $ex_data[8] . "'";
	 
	 //$arr=array (1=>$company_library,2=>$location_library,3=>$division_library,4=>$department_library,5=>$section_library);
	 $arr=array (3=>$asset_type,4=>$asset_category,6=>$store_library);
 	 $sql= "select a.asset_no, a.custody_of, a.specification, a.asset_type, a.asset_category, a.asset_group, a.store, a.id, b.company_name, b.id as asset_place_id from fam_asset_placement_dtls a, fam_asset_placement_mst b where a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and b.id=a.mst_id $company_name $location $division $department $section $sub_section $room_no $floor $place_date order by b.id desc";
	 //echo $sql; die;	
	echo  create_list_view("list_view", "Asset No,Custody of,Specification,Asset Type,Category,Group,Store","120,120,130,120,120,120","950","220",0,$sql, "get_details_form_data","asset_place_id","'populate_asset_placement_details_form_data'", 1, "0,0,0,asset_type,asset_category,0,store", $arr , "asset_no,custody_of,specification,asset_type,asset_category,asset_group,store", "requires/asset_placement_controller",'','0,0,0,0,0,0,0');
	
}

if ($action=="asset_popup")
{
  	echo load_html_head_contents("Popup Info","../../../", 1, 1, $unicode);
	extract($_REQUEST);

?>
	<script>
		function js_set_value( id )
		{
			//alert(id); return;
			document.getElementById('asset_id').value=id;
			parent.emailwindow.hide();
		}
    </script>
</head>

<body>
	<div align="center" style="width:100%;" >
	<form name="searchorderfrm_1"  id="searchorderfrm_1" autocomplete="off">
	 <input type="hidden" id="asset_id">
	<?php  
		$previous_placement_sql=sql_select("select asset_id from fam_asset_placement_dtls  where status_active=1 and is_deleted=0");
		foreach($previous_placement_sql as $p_val)
		{
			$previous_placement_asset[]=$p_val[csf("asset_id")];
		}
		
		$land_bulding_not_sql=sql_select("select asset_type from fam_acquisition_mst where status_active=1 and is_deleted=0 and asset_type=2 or asset_type = 1");
		foreach($land_bulding_not_sql as $no_val)
		{
			$assetLandBulding[]=$no_val[csf("asset_type")];
		}
		//print_r($previous_placement_asset);
		?>
		<table width="655" align="center" class="rpt_table" rules="all" id="tbl_list_search">
	    	<thead align="center" class="table_header">
	        	<th width="40">SL</th>
	        	<th width="100">Asset No</th>
	            <th width="80">Asset Type</th>
	            <th width="80">Asset Category</th>
	            <th width="110">Asset Group</th>
                <th width="100">Specification</th>
	            <th width="135">Store</th>
	        </thead>
        </table>
        <table width="655" align="center" class="rpt_table" rules="all" id="list_view_placement">
	        <tbody id="" class="table_body" style="height: 269px;">
	        	<?php  
				$arr=array (1=>$company_library,3=>$asset_type,4=>$asset_category,6=>$store_library);
				$sql= sql_select("select b.asset_no, b.id as asset_sl_id, a.company_id, a.specification,  a.asset_type, a.asset_category, a.asset_group, a.store, a.id from fam_acquisition_mst a, fam_acquisition_sl_dtls b where a.id=b.mst_id and a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and a.company_id='$cbo_company_name'");
				//print_r($sql);die;
				$i = 0;
				foreach($sql as $row)
				{
					if(!in_array($row[csf("asset_type")],$assetLandBulding))
					{
						if(!in_array($row[csf("asset_sl_id")],$previous_placement_asset))
						{
							
						$i++;
						if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
				?>
	                <tr style="cursor: pointer; cursor: hand;" align="center" bgcolor="<?php  echo $bgcolor; ?>"  id="tr_<?php  echo $i; ?>" height="20" onClick="js_set_value(<?php  echo $row[csf("asset_sl_id")]; ?>)">
	                	<td width="40"><?php  echo $i; ?></td>
	                    <td width="100"><?php  echo $row[csf("asset_no")]; ?></td>
	                    <td width="80"><?php  echo $arr[3][$row[csf("asset_type")]]; ?></td>
	                    <td width="80"><?php  echo $arr[4][$row[csf("asset_category")]]; ?></td>
	                    <td width="110"><?php  echo $row[csf("asset_group")]; ?></td>
                        <td width="100"><?php  echo $row[csf("specification")]; ?></td>
	                    <td width="113"><?php  echo $arr[6][$row[csf("store")]]; ?></td>
	                </tr>
	        	<?php  
						}
					}
				}
				?>
	        </tbody>
	        <tfoot>
		        <tr>
		            <td align="center" valign="top" id="search_div"></td>
		        </tr>
	        </tfoot>
	    </table>    
	    </form>
	   </div>
</body>           
<script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
<script>
	setFilterGrid("list_view_placement",-1);
</script>
</html>
<?php 
	exit();
}


if ($action=="populate_asset_data")
{
	$data_array=sql_select("select b.asset_no, b.id as asset_id, a.company_id, a.specification, a.asset_type, a.asset_category, a.asset_group, a.store, a.id from fam_acquisition_mst a, fam_acquisition_sl_dtls b where a.id=b.mst_id and a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and b.id='$data'");
	//print_r($data_array); die;
	foreach ($data_array as $row)
	{
		echo "document.getElementById('txt_asset_id').value = '".$row[csf("asset_id")]."';\n"; 
		echo "document.getElementById('txt_asset_no').value = '".$row[csf("asset_no")]."';\n";  
		echo "document.getElementById('txt_specification').value = '".$row[csf("specification")]."';\n";  
		echo "document.getElementById('cbo_aseet_type').value = '".$row[csf("asset_type")]."';\n";  
		echo "document.getElementById('cbo_category').value = '".$row[csf("asset_category")]."';\n";  
		echo "document.getElementById('txt_group').value = '".$row[csf("asset_group")]."';\n";  
		echo "document.getElementById('cbo_store_name').value = '".$row[csf("store")]."';\n";  
	}
	exit();
}


if ($action=="populate_asset_placement_details_form_data")
{

	$data_array=sql_select("select a.id, a.asset_no, a.asset_id, a.custody_of, a.specification, a.asset_type, a.asset_category, a.asset_group, a.store, b.id as asset_place_id, b.company_name, b.location, b.division, b.department, b.section, b.sub_section, b.room_no, b.floor, b.place_date from fam_asset_placement_dtls a, fam_asset_placement_mst b where a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and b.id=a.mst_id and b.id='$data'");
	//print_r($data_array); die;
	foreach ($data_array as $row)
	{ 
		echo "
		load_drop_down( 'requires/asset_placement_controller', '".$row[csf("company_name")]."', 'load_drop_down_location', 'location_td' );
		load_drop_down( 'requires/asset_placement_controller', '".$row[csf("company_name")]."', 'load_drop_down_division', 'division_td' );
		load_drop_down( 'requires/asset_placement_controller', '".$row[csf("company_name")]."'+'_'+'".$row[csf("location")]."', 'load_drop_down_floor', 'floor_td' );
		load_drop_down( 'requires/asset_placement_controller', '".$row[csf("company_name")]."', 'load_drop_down_store', 'store_td' );
		load_drop_down( 'requires/asset_placement_controller', '".$row[csf("division")]."', 'load_drop_down_department', 'department_td' );
		load_drop_down( 'requires/asset_placement_controller', '".$row[csf("department")]."', 'load_drop_down_section', 'section_td' );\n";
	
		echo "document.getElementById('update_id_mst').value 	= '".$row[csf("asset_place_id")]."';\n";
		echo "document.getElementById('cbo_company_name').value 	= '".$row[csf("company_name")]."';\n";  
		echo "document.getElementById('cbo_location').value 		= '".$row[csf("location")]."';\n";  
		echo "document.getElementById('cbo_division').value 		= '".$row[csf("division")]."';\n";  
		echo "document.getElementById('cbo_department').value 		= '".$row[csf("department")]."';\n";  
		echo "document.getElementById('cbo_section').value 			= '".$row[csf("section")]."';\n";  
		echo "document.getElementById('cbo_subsec').value 			= '".$row[csf("sub_section")]."';\n"; 
		echo "document.getElementById('txt_room_no').value 			= '".$row[csf("room_no")]."';\n";  
		echo "document.getElementById('cbo_floor').value 			= '".$row[csf("floor")]."';\n";  
		echo "document.getElementById('txt_placing_date').value 	= '".change_date_format($row[csf("place_date")], "dd-mm-yyyy", "-")."';\n";
		 
		echo "document.getElementById('txt_asset_no').value = '".$row[csf("asset_no")]."';\n"; 
		echo "document.getElementById('txt_asset_id').value = '".$row[csf("asset_id")]."';\n"; 
		echo "document.getElementById('txt_custody_of').value = '".$row[csf("custody_of")]."';\n";
		echo "document.getElementById('txt_specification').value = '".$row[csf("specification")]."';\n";
		echo "document.getElementById('cbo_aseet_type').value = '".$row[csf("asset_type")]."';\n";
		echo "document.getElementById('cbo_category').value = '".$row[csf("asset_category")]."';\n";
		echo "document.getElementById('txt_group').value = '".$row[csf("asset_group")]."';\n";
		echo "document.getElementById('cbo_store_name').value = '".$row[csf("store")]."';\n";
		
		echo "document.getElementById('update_id').value = '".$row[csf("id")]."';\n"; 
		//echo "get_php_form_data('".$row[csf("asset_id")]."', 'populate_asset_data', 'requires/asset_placement_controller');\n";
	}
	exit();
}


if ($action=="save_update_delete")
{
	$process = array( &$_POST );
	extract(check_magic_quote_gpc( $process )); 
	
// Insert Here----------------------------------------------------------
	if ($operation==0) 
	{
		$con = connect();
		if($db_type==0)
		{
			mysql_query("BEGIN");
		}
		$id=return_next_id( "id", "fam_asset_placement_mst", 1 ) ;	
			
		$field_array="id, company_name, location, division, department, section, sub_section, room_no, floor,  place_date, inserted_by, insert_date";
		$data_array="(".$id.",".$cbo_company_name.",".$cbo_location.",".$cbo_division.",".$cbo_department.",".$cbo_section.",".$cbo_subsec.",".$txt_room_no.",".$cbo_floor.",".$txt_placing_date.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo $data_array;
		//echo "insert into fam_asset_placement_mst($field_array) values $data_array";//die;
		
		//=================fam_asset_placement_dtls================
		$id1=return_next_id( "id", "fam_asset_placement_dtls", 1 ) ;
		$field_array_dtls="id, mst_id, asset_id, asset_no, custody_of, specification, asset_type, asset_category, asset_group, store, inserted_by, insert_date";
		$data_array_dtls="(".$id1.",".$id.",".$txt_asset_id.",".$txt_asset_no.",".$txt_custody_of.",".$txt_specification.",".$cbo_aseet_type.",".$cbo_category.",".$txt_group.",".$cbo_store_name.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo "insert into fam_asset_placement_dtls($field_array_dtls) values $data_array_dtls";die;

		$rID =	sql_insert("fam_asset_placement_mst",$field_array,$data_array,1);
		$rID1=	sql_insert("fam_asset_placement_dtls",$field_array_dtls,$data_array_dtls,1);
		
		//echo "10**".$rID."**".$rID1."**".$id; die;
		
		
		if($db_type==0)
		{
			if($rID & $rID1)
			{
				mysql_query("COMMIT");  
				echo "0**".$id;
			}
			else
			{
				mysql_query("ROLLBACK"); 
				echo "10**".$id;
			}
		}
		
		if($db_type==2 || $db_type==1 )
		{
			if ($rID & $rID1) {
                oci_commit($con);
                echo "0**" . $id;
            } else {
                oci_rollback($con);
                echo "10**" . $id;
            }
		}
		disconnect($con);
		die;
	}
// Insert Here End------------------------------------------------------
// Update Here----------------------------------------------------------
	else if ($operation==1) 
	{
		$con = connect();
		if($db_type==0)
		{
			mysql_query("BEGIN");
		}
		/*$field_array="quotation_id*company_name*buyer_name*location_name*style_ref_no*style_description*product_dept*product_code*pro_sub_dep*currency_id*agent_name*order_repeat_no*region*product_category*team_leader*dealing_marchant*packing*remarks*ship_mode*order_uom*gmts_item_id*set_break_down*total_set_qnty*set_smv*season*is_deleted*status_active*updated_by*update_date";
		$data_array="".$txt_quotation_id."*".$cbo_company_name."*".$cbo_buyer_name."*".$cbo_location_name."*".$txt_style_ref."*".$txt_style_description."*".$cbo_product_department."*".$txt_product_code."*".$cbo_sub_dept."*".$cbo_currercy."*".$cbo_agent."*".$txt_repeat_no."*".$cbo_region."*".$txt_item_catgory."*".$cbo_team_leader."*".$cbo_dealing_merchant."*".$cbo_packing."*".$txt_remarks."*".$cbo_ship_mode."*".$cbo_order_uom."*".$item_id."*".$set_breck_down."*".$tot_set_qnty."*".$tot_smv_qnty."*".$txt_season."*0*1*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";*/
		
		/*
		$field_array="asset_id*asset_no*company_name*location*division*department*section*sub_section*floor*place_date*custody_of*updated_by*update_date";
		$data_array="".$txt_asset_id."*".$txt_asset_no."*".$cbo_company_name."*".$cbo_location."*".$cbo_division."*".$cbo_department."*".$cbo_section."*".$cbo_subsec."*".$cbo_floor."*".$txt_placing_date."*".$txt_custody_of_id."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";

		*/
		
		$field_array="company_name*location*division*department*section*sub_section*room_no*floor*place_date*updated_by*update_date";
		$data_array="".$cbo_company_name."*".$cbo_location."*".$cbo_division."*".$cbo_department."*".$cbo_section."*".$cbo_subsec."*".$txt_room_no."*".$cbo_floor."*".$txt_placing_date."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";

		$field_array_dtls="mst_id*asset_id*asset_no*custody_of*specification*asset_type*asset_category*asset_group*store*updated_by*update_date";
		$data_array_dtls="".$update_id_mst."*".$txt_asset_id."*".$txt_asset_no."*".$txt_custody_of."*".$txt_specification."*".$cbo_aseet_type."*".$cbo_category."*".$txt_group."*".$cbo_store_name."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		//For testing Update Query
	 	 /*function sql_update_a($strTable,$arrUpdateFields,$arrUpdateValues,$arrRefFields,$arrRefValues,$commit)
	  		{
	  	$strQuery = "UPDATE ".$strTable." SET ";
	  	$arrUpdateFields=explode("*",$arrUpdateFields);
	  	$arrUpdateValues=explode("*",$arrUpdateValues);	
	  	if(is_array($arrUpdateFields))
	  	{
	  		$arrayUpdate = array_combine($arrUpdateFields,$arrUpdateValues);
	  		$Arraysize = count($arrayUpdate);
	  		$i = 1;
	  		foreach($arrayUpdate as $key=>$value):
	  			$strQuery .= ($i != $Arraysize)? $key."=".$value.", ":$key."=".$value." WHERE ";
	  			$i++;
	  		endforeach;
	  	}
	  	else
	  	{
	  		$strQuery .= $arrUpdateFields."=".$arrUpdateValues." WHERE ";
	  	}
	  	$arrRefFields=explode("*",$arrRefFields);
	  	$arrRefValues=explode("*",$arrRefValues);	
	  	if(is_array($arrRefFields))
	  	{
	  		$arrayRef = array_combine($arrRefFields,$arrRefValues);
	  		$Arraysize = count($arrayRef);
	  		$i = 1;
	  		foreach($arrayRef as $key=>$value):
	  			$strQuery .= ($i != $Arraysize)? $key."=".$value." AND ":$key."=".$value."";
	  			$i++;
	  		endforeach;
	  	}
	  	else
	  	{
	  		$strQuery .= $arrRefFields."=".$arrRefValues."";
	  	}
	  	
	  	global $con;
	  	//echo $strQuery; die;
	  	 //return $strQuery; die;
	  	$stid =  oci_parse($con, $strQuery);
	  	$exestd=oci_execute($stid,OCI_NO_AUTO_COMMIT);
	  	if ($exestd) 
	  		return "1";
	  	else 
	  		return "0";
	  	
	  	die;
	  	if ( $commit==1 )
	  	{
	  		if (!oci_error($stid))
	  		{
	  			oci_commit($con); 
	  			return "1";
	  		}
	  		else
	  		{
	  			oci_rollback($con);
	  			return "10";
	  		}
	  	}
	  	else
	  		return 1;
	  	die;
	  }*/
	  
		//echo $update_id_mst; die;
		$rID 	= sql_update("fam_asset_placement_mst",$field_array,$data_array,"id","".$update_id_mst."",0);
		$rID1 	= sql_update("fam_asset_placement_dtls",$field_array_dtls,$data_array_dtls,"id","".$update_id."",0);
		
		$update_id_mst = str_replace("'","",$update_id_mst);
		//echo "10**".$rID."**".$rID1."**".$update_id_mst; die;
		if($db_type==0)
		{
			if($rID && $rID1)
			{
				mysql_query("COMMIT");  
				echo "1**".$update_id_mst;
			}
			else
			{
				mysql_query("ROLLBACK"); 
				echo "10**".$update_id_mst;
			}
		}
		if($db_type==2 || $db_type==1 )
		{
			if ($rID && $rID1) {
                oci_commit($con);
                echo "1**". $update_id_mst;
            } else {
                oci_rollback($con);
                echo "10**". $update_id_mst;
            }
		}
		disconnect($con);
		die;
	}
// Update Here End -----------------------------------------------------
// Delete Here----------------------------------------------------------
	else if ($operation==2)   
	{
		$con = connect();
		
		$field_array="status_active*is_deleted*updated_by*update_date";
		$data_array="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		$update_id_mst=str_replace("'","",$update_id_mst);
		$update_id=str_replace("'","",$update_id);
		
		$rID=sql_delete("fam_asset_placement_mst",$field_array,$data_array,"id","".$update_id_mst."",1);
		$rID1=sql_delete("fam_asset_placement_dtls",$field_array,$data_array,"id","".$update_id."",1);
		
		
		if ($db_type == 0) {
            if ($rID && $rID1) {
                mysql_query("COMMIT");
                echo "2**" . $update_id_mst;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $update_id_mst;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1) {
                oci_commit($con);
                echo "2**" . $update_id_mst;
            } else {
                oci_rollback($con);
                echo "10**" . $update_id_mst;
            }
        }
		disconnect($con);
		die;
	}
// Delete Here End -----------------------------------------------------
}
