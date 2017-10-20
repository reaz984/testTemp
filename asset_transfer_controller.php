<?php 
header('Content-type:text/html; charset=utf-8');
session_start();
include('../../../includes/common.php');
$user_id = $_SESSION['logic_erp']["user_id"];
if( $_SESSION['logic_erp']['user_id'] == "" ) { header("location:login.php"); die; }
$permission=$_SESSION['page_permission'];
$data=$_REQUEST['data'];
$action=$_REQUEST['action'];
//$store_library=return_library_array( "select id,store_name from lib_store_location", "id", "store_name"  );


//--------------------------------------------------------------------------------------------
//load drop down company location
if ($action=="load_drop_down_location")
{
	echo create_drop_down( "cbo_location", 158, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$data' order by location_name","id,location_name", 1, "-- Select Location --", $selected, "load_drop_down( 'requires/asset_transfer_controller', document.getElementById('cbo_company_id').value+'_'+this.value, 'load_drop_down_floor', 'floor_td' );",0 );     	 
	exit();
}

if ($action=="load_drop_down_division")
{
	echo create_drop_down( "cbo_division", 158, "select id,division_name from lib_division where status_active =1 and is_deleted=0 and company_id='$data' order by division_name","id,division_name", 1, "-- Select Division --", $selected, "load_drop_down( 'requires/asset_transfer_controller', this.value, 'load_drop_down_department', 'department_td');",0 );     	 
	exit();
}

if ($action=="load_drop_down_department")
{
	echo create_drop_down( "cbo_department", 158, "select id,department_name from lib_department where status_active =1 and is_deleted=0 and division_id='$data' order by department_name","id,department_name", 1, "-- Select Department --", $selected, "load_drop_down( 'requires/asset_transfer_controller', this.value, 'load_drop_down_section', 'section_td' );",0 );     	 
	exit();
}

if ($action=="load_drop_down_section")
{
	echo create_drop_down( "cbo_section", 158, "select id,section_name from lib_section where status_active =1 and is_deleted=0 and department_id='$data' order by section_name","id,section_name", 1, "-- Select Section --", $selected, "",0 );     	 
	exit();
}


if ($action=="load_drop_down_floor")
{
	$data=explode("_",$data);
	echo create_drop_down( "cbo_floor", 158, "select id,floor_name from lib_prod_floor where status_active =1 and is_deleted=0 and company_id='$data[0]' and location_id='$data[1]' order by floor_name","id,floor_name", 1, "-- Select Floor --", $selected, "",0 );     	 
	exit();
}

if ($action=="load_drop_down_location_a")
{
	echo create_drop_down( "cbo_com_location", 150, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$data' order by location_name","id,location_name", 1, "-- Select Location --", $selected, "load_drop_down('asset_transfer_controller', document.getElementById('cbo_company_name').value+'_'+this.value,'load_drop_down_floor_a','src_floor_td');",0 );     	 
	exit();
}

if ($action=="load_drop_down_division_a")
{
	echo create_drop_down( "cbo_com_division", 150, "select id,division_name from lib_division where status_active =1 and is_deleted=0 and company_id='$data' order by division_name","id,division_name", 1, "-- Select Division --", $selected, "load_drop_down( 'asset_transfer_controller', this.value, 'load_drop_down_department_a', 'src_department_td');",0 );     	 
	exit();
}

if ($action=="load_drop_down_floor_a")
{
	//echo $data; die;
	$data=explode("_",$data);
	echo create_drop_down( "cbo_com_floor", 100, "select id,floor_name from lib_prod_floor where status_active =1 and is_deleted=0 and company_id='$data[0]' and location_id='$data[1]' order by floor_name","id,floor_name", 1, "-- Select Floor --", $selected, "",0 );     	 
	exit();
}

if ($action=="load_drop_down_department_a")
{
	echo create_drop_down( "cbo_com_department", 150, "select id,department_name from lib_department where status_active =1 and is_deleted=0 and division_id='$data' order by department_name","id,department_name", 1, "-- Select Department --", $selected, "",0 );     	 
	exit();
}

if($action == "search_asset_no_entry")
{
	echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	
    ?>
    <script>
        function js_set_value(id) {
            //alert(id); return;
            document.getElementById('hidden_asset_placement_id').value = id;
            parent.emailwindow.hide();
        }

    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="1065" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                                           	 
                            <th width="">Company Name</th>
                            <th width="">Location</th>
                            <th width="">Division</th>
                            <th width="">Department</th>
                            <th width="">Asset No</th> 
                            <th width="">Floor</th> 
                            
                            <!--<th width="100">Supplier</th>-->
                            <th width="210" align="center" >Date Range</th>
                            <th width="80"><input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  /></th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <td>
                            <?php 
							echo create_drop_down( "cbo_company_name", 150, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name","id,company_name", 1, "-- Select Company --", $selected, "load_drop_down( 'asset_transfer_controller', this.value, 'load_drop_down_location_a', 'src_location_td');load_drop_down( 'asset_transfer_controller', this.value, 'load_drop_down_division_a', 'src_division_td' );load_drop_down( 'asset_transfer_controller', this.value+'_'+document.getElementById('cbo_com_location').value, 'load_drop_down_floor_a', 'src_floor_td' );" );
                             ?>
                            </td>
                            <td id="src_location_td">
                            <?php 
                                echo create_drop_down("cbo_com_location", 150, $blank_array, "", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "", "", "");
                             ?>
                            </td>
                            <td id="src_division_td">
                                <?php 
                                	echo create_drop_down( "cbo_com_division", 150, $blank_array,"", 1, "-- Select Division --", $selected, "", "", "", "", "", "", "3", "", "" );
                                ?>  
                            </td>
                            <td id="src_department_td">
							  <?php 
								echo create_drop_down( "cbo_com_department", 150, $blank_array,"", 1, "-- Select Department --", $selected, "", "", "", "", "", "", "4", "", "" );
                                ?> 
                            </td>
                           <td >
                                <input type="text" name="asset_number" id="asset_number" style="width:90px;" class="text_boxes">
                            </td>
                            <td id="src_floor_td">
                                 <?php 
                                	echo create_drop_down("cbo_com_floor", 100, $blank_array, "", 1, "-- Select Floor --", $selected, "", "", "", "", "", "", "7", "", "");
                                ?> 
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" readonly/>-
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" readonly/>
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_com_location').value + '_' + document.getElementById('cbo_com_division').value + '_' + document.getElementById('cbo_com_department').value + '_' +document.getElementById('asset_number').value + '_' + document.getElementById('cbo_com_floor').value+ '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value, 'show_searh_active_listview', 'searh_list_view', 'asset_transfer_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr>
                        <tr>                  
                            <td align="center" height="70" valign="middle" colspan="8">
                                <?php  echo load_month_buttons(1); ?>
                                <!-- Hidden field here-------->
                                <input type="hidden" id="hidden_asset_placement_id" value="" />
                                <!-- ---------END------------->
                            </td>
                        </tr>  
                    </tbody>
                </table> 
                 
            </form>
            <div align="center" valign="top" id="searh_list_view"> </div>
        </div>
        
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php 
}

if ($action == "show_searh_active_listview") 
{
	//echo $data; die;
    $ex_data = explode("_", $data);
	
	if ($ex_data[0] == 0) $company_id = ""; 	else $company_id 	= " and a.company_name='" . $ex_data[0] . "'";
	if ($ex_data[1] == 0) $location = ""; 		else $location 		= " and a.location='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0) $divition = ""; 		else $divition 	= " and a.division='" . $ex_data[2] . "'";
    if ($ex_data[3] == 0) $department = ""; 	else $department 		= " and a.department='" . $ex_data[3] . "'";
    if ($ex_data[4] == 0) $asset_number = ""; 	else $asset_number 	= " and b.asset_no='" . $ex_data[4] . "'";
	if ($ex_data[5] == 0) $floor = ""; 			else $floor 	= " and a.floor='" . $ex_data[5] . "'";
    
    
    

    $txt_date_from = $ex_data[6];
    $txt_date_to = $ex_data[7];

    if ($ex_data[0] == 0) 
	{
        echo "Please Company first";
        die;
    }

	
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0) 
			$tran_date = " and a.place_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		else 
			$tran_date = " and a.place_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
	}
	
	$sql = "select a.id, a.company_name, a.location, a.division, a.department, b.asset_no, a.floor, a.place_date   from fam_asset_placement_mst  a, fam_asset_placement_dtls  b where a.id=b.mst_id and a.status_active=1 and a.is_deleted=0 $company_id $location $divition $department $asset_number $floor $tran_date";
    //echo $sql; die;
	$company_library=return_library_array( "select id,company_name from lib_company", "id", "company_name"  );
	$location_library=return_library_array( "select id,location_name from lib_location", "id", "location_name"  );
	$division_library=return_library_array( "select id,division_name from lib_division", "id", "division_name"  );
	$department_library=return_library_array( "select id,department_name from lib_department", "id", "department_name"  );
	
	$arr = array( 1 => $company_library, 2 => $location_library, 3 => $division_library, 4 => $department_library);
	
	echo create_list_view("list_view", "Asset No,Company,Location,Division,Department,Floor,Placing Date", "150,150,150,150,90,100,150", "1065", "300", 0, $sql, "js_set_value", "id", "", 1, "0,company_name,location,division,department,0,0", $arr, "asset_no,company_name,location,division,department,floor,place_date", "asset_acquisition_controller", '', '0,0,0,0,0,0,3');
}

if($action == "search_transfer_id_entry")
{
	echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	
    ?>
    <script>
        function js_set_value(id) {
            //alert(id); return;
            document.getElementById('hidden_update_id').value = id;
            parent.emailwindow.hide();
        }

    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="1065" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                                           	 
                            <th width="">Company Name</th>
                            <th width="">Location</th>
                            <th width="">Division</th>
                            <th width="">Department</th>
                            <th width="">Transfer ID</th> 
                            <th width="">Floor</th> 
                            
                            <!--<th width="100">Supplier</th>-->
                            <th width="210" align="center" >Date Range</th>
                            <th width="80"><input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  /></th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <td>
                            <?php 
							echo create_drop_down( "cbo_company_name", 150, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name","id,company_name", 1, "-- Select Company --", $selected, "load_drop_down( 'asset_transfer_controller', this.value, 'load_drop_down_location_a', 'src_location_td');load_drop_down( 'asset_transfer_controller', this.value, 'load_drop_down_division_a', 'src_division_td' );load_drop_down( 'asset_transfer_controller', this.value+'_'+document.getElementById('cbo_com_location').value, 'load_drop_down_floor_a', 'src_floor_td' );" );
                             ?>
                            </td>
                            <td id="src_location_td">
                            <?php 
                                echo create_drop_down("cbo_com_location", 150, $blank_array, "", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "", "", "");
                             ?>
                            </td>
                            <td id="src_division_td">
                                <?php 
                                	echo create_drop_down( "cbo_com_division", 150, $blank_array,"", 1, "-- Select Division --", $selected, "", "", "", "", "", "", "3", "", "" );
                                ?>  
                            </td>
                            <td id="src_department_td">
							  <?php 
								echo create_drop_down( "cbo_com_department", 150, $blank_array,"", 1, "-- Select Department --", $selected, "", "", "", "", "", "", "4", "", "" );
                                ?> 
                            </td>
                           <td >
                                <input type="text" name="transfer_id" id="transfer_id" style="width:90px;" class="text_boxes">
                            </td>
                            <td id="src_floor_td">
                                 <?php 
                                	echo create_drop_down("cbo_com_floor", 100, $blank_array, "", 1, "-- Select Floor --", $selected, "", "", "", "", "", "", "7", "", "");
                                ?> 
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" readonly/>-
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" readonly/>
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_com_location').value + '_' + document.getElementById('cbo_com_division').value + '_' + document.getElementById('cbo_com_department').value + '_' +document.getElementById('transfer_id').value + '_' + document.getElementById('cbo_com_floor').value+ '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value, 'show_searh_transfer_listview', 'searh_list_view', 'asset_transfer_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr>
                        <tr>                  
                            <td align="center" height="70" valign="middle" colspan="8">
                                <?php  echo load_month_buttons(1); ?>
                                <!-- Hidden field here-------->
                                <input type="hidden" id="hidden_update_id" value="" />
                                <!-- ---------END------------->
                            </td>
                        </tr>  
                    </tbody>
                </table> 
                 
            </form>
            <div align="center" valign="top" id="searh_list_view"> </div>
        </div>
        
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php 
}

if ($action == "show_searh_transfer_listview") 
{
	
	//echo $data; die;
    $ex_data = explode("_", $data);
	
	if ($ex_data[0] == 0) $company_id = ""; 	else $company_id 	= " and company_id='" . $ex_data[0] . "'";
	if ($ex_data[1] == 0) $location = ""; 		else $location 		= " and location='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0) $divition = ""; 		else $divition 	= " and division='" . $ex_data[2] . "'";
    if ($ex_data[3] == 0) $department = ""; 	else $department 		= " and department='" . $ex_data[3] . "'";
    if ($ex_data[4] == '') $system_no = ""; 	else $system_no 	= " and system_no='" . $ex_data[4] . "'";
	if ($ex_data[5] == 0) $floor = ""; 			else $floor 	= " and floor='" . $ex_data[5] . "'";
    //echo $system_no; die;
    

    $txt_date_from = $ex_data[6];
    $txt_date_to = $ex_data[7];
	
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0) 
			$tran_date = " and transfer_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		else 
			$tran_date = " and transfer_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
	}
	
	$sql = "select id, system_no, asset_no, company_id, location, division, department, section, sub_section, floor, transfer_date from fam_asset_transfer_mst where status_active=1 and is_deleted=0 $company_id $location $divition $department $system_no $floor $tran_date";
    //echo $sql; die;
	$company_library=return_library_array( "select id,company_name from lib_company", "id", "company_name"  );
	$location_library=return_library_array( "select id,location_name from lib_location", "id", "location_name"  );
	$division_library=return_library_array( "select id,division_name from lib_division", "id", "division_name"  );
	$department_library=return_library_array( "select id,department_name from lib_department", "id", "department_name"  );
	
	$arr = array( 2 => $company_library, 3 => $location_library, 4 => $division_library, 5 => $department_library);
	
	echo create_list_view("list_view", "Transfer ID,Asset No,Company,Location,Division,Department,Floor,Placing Date", "150,150,150,150,150,90,100,150", "1065", "300", 0, $sql, "js_set_value", "id", "", 1, "0,0,company_id,location,division,department,0,0", $arr, "system_no,asset_no,company_id,location,division,department,floor,place_date", "asset_acquisition_controller", '', '0,0,0,0,0,0,0,3');
}

if($action=="populate_data_from_data")
{
	$company_library=return_library_array( "select id,company_name from lib_company", "id", "company_name"  );
	$location_library=return_library_array( "select id,location_name from lib_location", "id", "location_name"  );
	$division_library=return_library_array( "select id,division_name from lib_division", "id", "division_name"  );
	$department_library=return_library_array( "select id,department_name from lib_department", "id", "department_name"  );
	
	$sql = "select a.id, a.company_name, a.location, a.division, a.department, a.section, a.sub_section, b.asset_no, a.floor, a.room_no, a.place_date from fam_asset_placement_mst  a, fam_asset_placement_dtls  b where a.id=b.mst_id and a.status_active=1 and a.is_deleted=0 and a.id='$data'";
	//echo $sql; die;
	$res = sql_select($sql);	
	foreach($res as $row)
	{		
		echo "$('#txt_asset_no').val('".$row[csf("asset_no")]."');\n";
		echo "$('#hidden_placement_id').val('".$row[csf("id")]."');\n";
		echo "$('#txt_company_id').val('".$company_library[$row[csf("company_name")]]."');\n";
		echo "$('#hidden_company_id').val('".$row[csf("company_name")]."');\n";
		echo "$('#txt_location').val('".$location_library[$row[csf("location")]]."');\n";
		echo "$('#hidden_location').val('".$row[csf("location")]."');\n";
		echo "$('#txt_division').val('".$division_library[$row[csf("division")]]."');\n";
		echo "$('#hidden_division').val('".$row[csf("division")]."');\n";
		echo "$('#txt_department').val('".$department_library[$row[csf("department")]]."');\n";
		echo "$('#hidden_department').val('".$row[csf("department")]."');\n";
		echo "$('#txt_section').val(".$row[csf("section")].");\n";
		echo "$('#hidden_section').val(".$row[csf("section")].");\n";
		echo "$('#txt_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#hidden_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#txt_floor').val(".$row[csf("floor")].");\n";
		echo "$('#hidden_floor').val(".$row[csf("floor")].");\n";
		echo "$('#txt_room_no').val(".$row[csf("room_no")].");\n";
		echo "$('#txt_placing_date').val('".change_date_format($row[csf("place_date")])."');\n";
		
		echo "$('#txt_asset_no_copy').val('".$row[csf("asset_no")]."');\n";
		echo "$('#cbo_company_id').val(".$row[csf("company_name")].");\n";
		echo "load_drop_down('requires/asset_transfer_controller','" . $row[csf("company_name")] . "','load_drop_down_location','location_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("company_name")] . "','load_drop_down_division','division_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("location")] . "','load_drop_down_floor','floor_td' );\n";
		
		
		/*echo "$('#cbo_location').val(".$row[csf("location")].");\n";
		echo "$('#cbo_division').val(".$row[csf("division")].");\n";
		echo "$('#cbo_department').val(".$row[csf("department")].");\n";
		echo "$('#cbo_section').val(".$row[csf("section")].");\n";
		echo "$('#cbo_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#cbo_floor').val(".$row[csf("floor")].");\n";*/
		
		
		
		
		/*
		echo "$('#cbo_company_name').val(".$row[csf("company_id")].");\n";
		echo "$('#cbo_company_name').attr('disabled','true')".";\n";
		//echo "$('#cbo_serviceNature').val(".$row[csf("service_nature")].");\n";
		//echo "$('#cbo_serviceNature').attr('disabled','true')".";\n";
		echo "$('#cbo_return_form').val(".$row[csf("return_form")].");\n";
		echo "$('#cbo_return_form').attr('disabled','true')".";\n";
		echo "$('#txt_return_date').val('".change_date_format($row[csf("return_date")])."');\n";
		//echo "$('#txt_return_date').attr('disabled','true')".";\n";
		echo "$('#update_id').val(".$row[csf("id")].");\n";
		echo "myFunction(".$row[csf("id")].");\n";
		echo "set_button_status(1, permission,'fnc_repair_back_entry',1);\n";*/
  	}
	exit();	
}

if($action=="populate_data_transfer_data")
{
	$company_library=return_library_array( "select id,company_name from lib_company", "id", "company_name"  );
	$location_library=return_library_array( "select id,location_name from lib_location", "id", "location_name"  );
	$division_library=return_library_array( "select id,division_name from lib_division", "id", "division_name"  );
	$department_library=return_library_array( "select id,department_name from lib_department", "id", "department_name"  );
	
	//$sql = "select id, system_no, asset_no, company_id, location, division, department, section, sub_section, floor, transfer_date from fam_asset_transfer_mst where status_active=1 and is_deleted=0 and id='$data'";
	$sql = "select b.mst_id, a.id, a.system_no, a.asset_no, a.company_id, a.location, a.division, a.department, a.section, a.sub_section, a.floor, a.room_no, a.transfer_date from fam_asset_transfer_mst a, fam_asset_placement_dtls b where a.asset_no=b.asset_no and a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and a.id='$data'";
	//echo $sql; die;
	$res = sql_select($sql);	
	foreach($res as $row)
	{
		echo "$('#txt_system_id').val('".$row[csf("system_no")]."');\n";		
		echo "$('#txt_asset_no').val('".$row[csf("asset_no")]."');\n";
		echo "$('#hidden_placement_id').val('".$row[csf("mst_id")]."');\n";
		echo "$('#txt_company_id').val('".$company_library[$row[csf("company_id")]]."');\n";
		echo "$('#hidden_company_id').val('".$row[csf("company_id")]."');\n";
		echo "$('#txt_location').val('".$location_library[$row[csf("location")]]."');\n";
		echo "$('#hidden_location').val('".$row[csf("location")]."');\n";
		echo "$('#txt_division').val('".$division_library[$row[csf("division")]]."');\n";
		echo "$('#hidden_division').val('".$row[csf("division")]."');\n";
		echo "$('#txt_department').val('".$department_library[$row[csf("department")]]."');\n";
		echo "$('#hidden_department').val('".$row[csf("department")]."');\n";
		echo "$('#txt_section').val(".$row[csf("section")].");\n";
		echo "$('#hidden_section').val(".$row[csf("section")].");\n";
		echo "$('#txt_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#hidden_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#txt_floor').val(".$row[csf("floor")].");\n";
		echo "$('#hidden_floor').val(".$row[csf("floor")].");\n";
		echo "$('#txt_room_no').val(".$row[csf("room_no")].");\n";
		echo "$('#txt_placing_date').val('".change_date_format($row[csf("transfer_date")])."');\n";
		echo "$('#txt_transfer_date').val('".change_date_format($row[csf("transfer_date")])."');\n";
		
		echo "$('#txt_asset_no_copy').val('".$row[csf("asset_no")]."');\n";
		echo "$('#cbo_company_id').val(".$row[csf("company_id")].");\n";
		echo "load_drop_down('requires/asset_transfer_controller','" . $row[csf("company_id")] . "','load_drop_down_location','location_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("company_id")] . "','load_drop_down_division','division_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("division")] . "','load_drop_down_department','department_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("department")] . "','load_drop_down_section','section_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("location")] . "','load_drop_down_floor','floor_td' );\n";
		
		
		echo "$('#cbo_location').val(".$row[csf("location")].");\n";
		echo "$('#cbo_division').val(".$row[csf("division")].");\n";
		echo "$('#cbo_department').val(".$row[csf("department")].");\n";
		echo "$('#cbo_section').val(".$row[csf("section")].");\n";
		echo "$('#cbo_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#cbo_floor').val(".$row[csf("floor")].");\n";
		echo "$('#txt_room_no_to').val(".$row[csf("room_no")].");\n";
		echo "$('#update_id').val('".$row[csf("id")]."');\n";
		echo "set_button_status(1, permission,'fnc_repair_back_entry',1);\n";
		
		/*
		
		/*
		echo "$('#cbo_company_name').val(".$row[csf("company_id")].");\n";
		echo "$('#cbo_company_name').attr('disabled','true')".";\n";
		//echo "$('#cbo_serviceNature').val(".$row[csf("service_nature")].");\n";
		//echo "$('#cbo_serviceNature').attr('disabled','true')".";\n";
		echo "$('#cbo_return_form').val(".$row[csf("return_form")].");\n";
		echo "$('#cbo_return_form').attr('disabled','true')".";\n";
		echo "$('#txt_return_date').val('".change_date_format($row[csf("return_date")])."');\n";
		//echo "$('#txt_return_date').attr('disabled','true')".";\n";
		echo "$('#update_id').val(".$row[csf("id")].");\n";
		echo "myFunction(".$row[csf("id")].");\n";
		echo "set_button_status(1, permission,'fnc_repair_back_entry',1);\n";*/
  	}
	exit();	
}

if ($action == "populate_asset_details") 
{
	$company_library=return_library_array( "select id,company_name from lib_company", "id", "company_name"  );
	$location_library=return_library_array( "select id,location_name from lib_location", "id", "location_name"  );
	$division_library=return_library_array( "select id,division_name from lib_division", "id", "division_name"  );
	$department_library=return_library_array( "select id,department_name from lib_department", "id", "department_name"  );
	
	$sql = "select a.id, a.company_name, a.location, a.division, a.department, a.section, a.sub_section, b.asset_no, a.floor, a.place_date from fam_asset_placement_mst  a, fam_asset_placement_dtls  b where a.id=b.mst_id and a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and b.asset_no='$data'";
	//echo $sql; die;
	$res = sql_select($sql);	
	foreach($res as $row)
	{		
		echo "$('#txt_asset_no').val('".$row[csf("asset_no")]."');\n";
		echo "$('#hidden_placement_id').val('".$row[csf("id")]."');\n";
		echo "$('#txt_company_id').val('".$company_library[$row[csf("company_name")]]."');\n";
		echo "$('#hidden_company_id').val('".$row[csf("company_name")]."');\n";
		echo "$('#txt_location').val('".$location_library[$row[csf("location")]]."');\n";
		echo "$('#hidden_location').val('".$row[csf("location")]."');\n";
		echo "$('#txt_division').val('".$division_library[$row[csf("division")]]."');\n";
		echo "$('#hidden_division').val('".$row[csf("division")]."');\n";
		echo "$('#txt_department').val('".$department_library[$row[csf("department")]]."');\n";
		echo "$('#hidden_department').val('".$row[csf("department")]."');\n";
		echo "$('#txt_section').val(".$row[csf("section")].");\n";
		echo "$('#hidden_section').val(".$row[csf("section")].");\n";
		echo "$('#txt_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#hidden_subsec').val(".$row[csf("sub_section")].");\n";
		echo "$('#txt_floor').val(".$row[csf("floor")].");\n";
		echo "$('#hidden_floor').val(".$row[csf("floor")].");\n";
		echo "$('#txt_placing_date').val('".change_date_format($row[csf("place_date")])."');\n";
		
		echo "$('#txt_asset_no_copy').val('".$row[csf("asset_no")]."');\n";
		echo "$('#cbo_company_id').val(".$row[csf("company_name")].");\n";
		echo "load_drop_down('requires/asset_transfer_controller','" . $row[csf("company_name")] . "','load_drop_down_location','location_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("company_name")] . "','load_drop_down_division','division_td' );load_drop_down('requires/asset_transfer_controller','" . $row[csf("location")] . "','load_drop_down_floor','floor_td' );\n";
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
		
		
		$id=return_next_id( "id", "fam_asset_transfer_mst", 1 ) ;
		$id_history=return_next_id( "id", "fam_asset_transfer_history_mst", 1 ) ;	
			
			
		if($db_type==0) $year_cond = " and YEAR(insert_date)=".date('Y',time())." "; 
		else $year_cond = " and to_char(insert_date,'YYYY')=".date('Y',time())." "; 
			
		
		$new_entry_no=explode("*",return_mrr_number( str_replace("'","",$cbo_company_id),'','ATR',date("Y",time()),5,"select system_no_prefix,system_no_prefix_num from fam_asset_transfer_mst where company_id=$cbo_company_id $year_cond order by id desc ","system_no_prefix","system_no_prefix_num"));
		
		//echo $txt_transfer_date; die;
		$txtTransferDate=str_replace("'","",$txt_transfer_date);
		if ($db_type == 0) $transfer_date = change_date_format($txtTransferDate, 'yyyy-mm-dd');
		else $transfer_date = change_date_format($txtTransferDate, 'yyyy-mm-dd', '-', 1);
		
		$txtPlacingDate=str_replace("'","",$txt_placing_date);
		if ($db_type == 0) $placing_date = change_date_format($txtPlacingDate, 'yyyy-mm-dd');
		else $placing_date = change_date_format($txtPlacingDate, 'yyyy-mm-dd', '-', 1);
		
		
		$field_array="id, system_no, system_no_prefix, system_no_prefix_num, asset_no, company_id, location, division, department, section, sub_section, floor, room_no,  transfer_date, inserted_by, insert_date";	
		$data_array="(".$id.",'".$new_entry_no[0] . "','" . $new_entry_no[1] . "','" . $new_entry_no[2]."',".$txt_asset_no.",".$cbo_company_id.",".$cbo_location.",".$cbo_division.",".$cbo_department.",".$cbo_section.",".$cbo_subsec.",".$cbo_floor.",".$txt_room_no_to.",'".$transfer_date."',".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo "insert into fam_asset_transfer_mst($field_array) values $data_array";die;


		$field_array_history="id,placement_id,asset_no,company_id,location,division,department,section,sub_section,floor,room_no,placing_date,new_insert,inserted_by,insert_date";	
		$data_array_history="(".$id_history.",".$hidden_placement_id.",".$txt_asset_no.",".$hidden_company_id.",".$hidden_location.",".$hidden_division.",".$hidden_department.",".$hidden_section.",".$hidden_subsec.",".$hidden_floor.",".$txt_room_no.",'".$placing_date."','1',".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo "insert into fam_asset_transfer_history_mst($field_array_history) values $data_array_history";die;

		
		
		$field_array_placement="company_name*location*division*department*section*sub_section*floor*room_no*place_date*inserted_by*insert_date";
		$data_array_placement="".$cbo_company_id."*".$cbo_location."*".$cbo_division."*".$cbo_department."*".$cbo_section."*".$cbo_subsec."*".$cbo_floor."*".$txt_room_no_to."*'".$transfer_date."'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		

		$rID 	=	sql_insert("fam_asset_transfer_mst",$field_array,$data_array,1);
		$rID1 	=	sql_insert("fam_asset_transfer_history_mst",$field_array_history,$data_array_history,1);
		$rID2 	=	sql_update("fam_asset_placement_mst",$field_array_placement,$data_array_placement,"id","".$hidden_placement_id."",0);
		
		//echo "10**".$rID."**".$rID1."**".$rID2; die;
		
		if($db_type==0)
		{
			if($rID && $rID1 && $rID2)
			{
				mysql_query("COMMIT");  
				echo "0**".$id."**".$new_entry_no[0];
			}
			else
			{
				mysql_query("ROLLBACK"); 
				echo "10**".$id;
			}
		}
		
		if($db_type==2 || $db_type==1 )
		{
			if ($rID && $rID1 && $rID2) {
                oci_commit($con);
                echo "0**".$id."**".$new_entry_no[0];
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
		
		//echo $txt_transfer_date; die;
		$txtTransferDate=str_replace("'","",$txt_transfer_date);
		if ($db_type == 0) $transfer_date = change_date_format($txtTransferDate, 'yyyy-mm-dd');
		else $transfer_date = change_date_format($txtTransferDate, 'yyyy-mm-dd', '-', 1);
		
		$txtPlacingDate=str_replace("'","",$txt_placing_date);
		if ($db_type == 0) $placing_date = change_date_format($txtPlacingDate, 'yyyy-mm-dd');
		else $placing_date = change_date_format($txtPlacingDate, 'yyyy-mm-dd', '-', 1);
		
		
		$id_history=return_next_id( "id", "fam_asset_transfer_history_mst", 1 ) ;	 
		
		$field_array="location*division*department*section*sub_section*floor*room_no*transfer_date*updated_by*update_date";//txt_room_no
		$data_array="".$cbo_location."*".$cbo_division."*".$cbo_department."*".$cbo_section."*".$cbo_subsec."*".$cbo_floor."*".$txt_room_no_to."*".$txt_transfer_date."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		$field_array_placement="company_name*location*division*department*section*sub_section*floor*room_no*place_date*updated_by*update_date";
		$data_array_placement="".$cbo_company_id."*".$cbo_location."*".$cbo_division."*".$cbo_department."*".$cbo_section."*".$cbo_subsec."*".$cbo_floor."*".$txt_room_no_to."*'".$transfer_date."'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		
		$field_array_history="id, placement_id, asset_no, company_id, location, division, department, section, sub_section, floor, room_no, placing_date, update_insert, inserted_by, insert_date";	
		$data_array_history="(".$id_history.",".$hidden_placement_id.",".$txt_asset_no.",".$hidden_company_id.",".$hidden_location.",".$hidden_division.",".$hidden_department.",".$hidden_section.",".$hidden_subsec.",".$hidden_floor.",".$txt_room_no.",'".$placing_date."','2',".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo "insert into fam_asset_transfer_history_mst($field_array_history) values $data_array_history";die;
		
	  	$rID 	=	sql_update("fam_asset_transfer_mst",$field_array,$data_array,"id","".$update_id."",0);
		$rID1 	=	sql_insert("fam_asset_transfer_history_mst",$field_array_history,$data_array_history,1);
		$rID2 	=	sql_update("fam_asset_placement_mst",$field_array_placement,$data_array_placement,"id","".$hidden_placement_id."",0);
		
		//echo "10**".$rID."**".$rID1."**".$rID2; die;
		
		if($db_type==0)
		{
			if($rID && $rID1 && $rID2)
			{
				mysql_query("COMMIT");  
				echo "1**".$id."**".$new_entry_no[0]."**".$update_id;
			}
			else
			{
				mysql_query("ROLLBACK"); 
				echo "10**".$update_id;
			}
		}
		if($db_type==2 || $db_type==1 )
		{
			if ($rID && $rID1 && $rID2) {
                oci_commit($con);
                echo "1**".$id."**".$new_entry_no[0]."**".$update_id;
            } else {
                oci_rollback($con);
                echo "10**". $update_id;
            }
		}
		disconnect($con);
		die;
	}
// Update Here End ----------------------------------------------------------

// Delete Here----------------------------------------------------------
	else if ($operation==2)   
	{
		$con = connect();
		echo "13**";
		/*
		$field_array="status_active*is_deleted*updated_by*update_date";
		$data_array="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		$txt_asset_placement_id=str_replace("'","",$txt_asset_placement_id);
		$update_id=str_replace("'","",$update_id);
		
		$rID=sql_delete("fam_asset_placement_mst",$field_array,$data_array,"id","".$txt_asset_placement_id."",1);
		$rID1=sql_delete("fam_asset_placement_dtls",$field_array,$data_array,"id","".$update_id."",1);
		
		
		if ($db_type == 0) {
            if ($rID && $rID1) {
                mysql_query("COMMIT");
                echo "2**" . $txt_asset_placement_id . "**" . $update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $txt_asset_placement_id . "**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1) {
                oci_commit($con);
                echo "2**" . $txt_asset_placement_id . "**" . $update_id;
            } else {
                oci_rollback($con);
                echo "10**" . $txt_asset_placement_id . "**" . $update_id;
            }
        }*/
		disconnect($con);
		die;
	}
// Delete Here End ----------------------------------------------------------
}



//================= Update Query Test============================================================================
function sql_update_a($strTable,$arrUpdateFields,$arrUpdateValues,$arrRefFields,$arrRefValues,$commit)
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
	echo $strQuery; die;
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
}
