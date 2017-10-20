<?php 
header('Content-type:text/html; charset=utf-8');
session_start();
include('../../../includes/common.php');
$user_id = $_SESSION['logic_erp']["user_id"];
if( $_SESSION['logic_erp']['user_id'] == "" ) { header("location:login.php"); die; }
$permission=$_SESSION['page_permission'];
$data=$_REQUEST['data'];
$action=$_REQUEST['action'];
//--------------------------------------------------------------------------------------------
//load drop down company location==============================
if ($action == "load_drop_down_location") {
    echo create_drop_down("cbo_location", 170, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$data' order by location_name", "id,location_name", 1, "-- Select Location --", $selected, "", 0);
    exit();
}

//load drop down Asset Type
if ($action == "load_drop_down_category") 
{
    if ($data == 1) {		//Land
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "21,22,23,24", "", "", "", "4", "", "");
    } elseif ($data == 2) {		//Building
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "41,42,43,44,45,46,47", "", "", "", "4", "", "");
    } elseif ($data == 3) {		//Furniture
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "61,62,63,64,65,66,67,68,69", "", "", "", "4", "", "");
    } elseif ($data == 4) {	//Fixtures
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "71", "", "", "", "4", "", "");
    } elseif ($data == 5) {		//Machinery
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "91,92,93,94,95,96,97,98,99", "", "", "", "4", "", "");
    } elseif ($data == 6) { 	//Equipment
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "101", "", "", "", "4", "", "");
    } elseif ($data == 7) { 	//Power Generation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "111,112,113", "", "", "", "4", "", "");
    } elseif ($data == 8) { 	//Computer
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "81,82,83,84,85,86,87,88", "", "", "", "4", "", "");
    } elseif ($data == 9) { 	//Electric Appliance
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "121,122,123,124,125", "", "", "", "4", "", "");
    } elseif ($data == 10) { 	//Transportation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "131,132,133,134,135,136,137,138,139,140,141", "", "", "", "4", "", "");
    } elseif ($data == 11) { 	//Communication Device
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "151,152,153,154,155,156", "", "", "", "4", "", "");
    } elseif ($data == 11) { 	//Others
        echo create_drop_down("cbo_category", 170, $blank_array, "", 1, "--- Select ---", $selected, "", "", "", "", "", "", "4", "", "");
    }
    exit();
}


if ($action == "search_asset_entry") 
{
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    <script>
        function js_set_value(data) {
            document.getElementById('hidden_system_number').value = data;
            parent.emailwindow.hide();
        }
    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="880" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>               	 
                            <th width="170">Company Name</th>
                            <th width="170">Location</th>
                            <th width="110">Asset Type</th>
                            <th width="170">Category</th>
                            <!--<th width="100">Supplier</th>-->
                            <th width="210" align="center" >Date Range</th>
                            <th width="80"><input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  /></th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                echo create_drop_down("cbo_company_name", 170, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "-- Select Company --", $cbo_company_name, "load_drop_down( 'out_of_order_controller', this.value, 'load_drop_down_location', 'src_location_td');", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td id="src_location_td">
                                <?php
                                echo create_drop_down("cbo_location", 170, $blank_array, "", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td>
                                <?php
                                echo create_drop_down("cbo_aseet_type", 110, $asset_type, "", 1, "--- Select ---", $selected, "load_drop_down( 'out_of_order_controller', this.value, 'load_drop_down_category', 'src_category_td' );", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td id="src_category_td">
                                <?php
                                echo create_drop_down("cbo_category", 170, $blank_array, "", 1, "--- Select ---", $selected, "", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" readonly /> -
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" readonly />
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_location').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_category').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value, 'show_searh_active_listview', 'searh_list_view', 'out_of_order_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr>
                        <tr>                  
                            <td align="center" height="30" valign="middle" colspan="7">
                                <?php echo load_month_buttons(1); ?>
                                <input type="hidden" id="hidden_system_number" value="" />
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
    exit();
}

if ($action == "show_searh_active_listview") 
{
    $ex_data = explode("_", $data);

    if ($ex_data[0] == 0)	$company_id = "";	else	$company_id = " and a.company_id='" . $ex_data[0] . "'";
    if ($ex_data[1] == 0)	$location = "";		else	$location = " and a.location='" . $ex_data[1] . "'";
    if ($ex_data[2] == 0)	$aseet_type = "";	else	$aseet_type = " and a.asset_type='" . $ex_data[2] . "'";
    if ($ex_data[3] == 0)	$category = "";		else	$category = " and a.asset_category='" . $ex_data[3] . "'";

    $txt_date_from = $ex_data[4];
    $txt_date_to = $ex_data[5];

    if ($ex_data[0] == 0) 
	{
        echo "Please Company first";
        die;
    }
	
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0)
		{
			$tran_date = " and a.purchase_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		}
		if($db_type==2 || $db_type==1 )
		{
            $tran_date = " and a.purchase_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
		}
	}
	$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");

	$sql = "SELECT  a.id, a.entry_no, a.location, a.asset_type, a.asset_category, a.store, a.purchase_date, a.qty, c.asset_no, c.id as asset_id, c.serial_no  FROM fam_acquisition_mst a, fam_acquisition_sl_dtls c  WHERE a.id=c.mst_id AND a.status_active=1 AND a.is_deleted=0 $category $aseet_type $location $company_id $asset_number $tran_date";
	
	$arr = array(2 => $company_location, 3 => $asset_type, 4 => $asset_category, 5 => $store_library);
	
	echo create_list_view("list_view", "Entry No,Asset No,Location,Type,Category,Store,Purchase Date,Qty", "90,100,120,90,90,140,90,50", "850", "300", 0, $sql, "js_set_value", "id,asset_id,serial_no,asset_no", "", 1, "0,0,location,asset_type,asset_category,store,0,0", $arr, "entry_no,asset_no,location,asset_type,asset_category,store,purchase_date,qty", "out_of_order_controller", '', '0,0,0,0,0,0,3,1');
	
   exit();
}


if ($action == "populate_asset_details_form_data") 
{
	 $data_arr = explode("_", $data);
	 $data_array = sql_select("select id, company_id, specification, asset_type, asset_category, asset_group, brand, origin, purchase_date from fam_acquisition_mst where id='$data_arr[0]'");
    foreach ($data_array as $row) {
        echo "document.getElementById('cbo_company_name').value 	= '" . $row[csf("company_id")] . "';\n";
        echo "document.getElementById('txt_specification').value 	= '" . $row[csf("specification")] . "';\n";
        echo "document.getElementById('cbo_aseet_type').value 		= '" . $row[csf("asset_type")] . "';\n";
        echo "load_drop_down('requires/out_of_order_controller','" . $row[csf("asset_type")] . "','load_drop_down_category','category_td' );\n";
        echo "document.getElementById('cbo_category').value 		= '" . $row[csf("asset_category")] . "';\n";
		echo "document.getElementById('cbo_category').disabled = true;\n";
        echo "document.getElementById('txt_asset_group').value 		= '" . $row[csf("asset_group")] . "';\n";
        echo "document.getElementById('txt_serial_no').value 		= '" . $row[csf("serial_no")] . "';\n";
        echo "document.getElementById('txt_brand').value 			= '" . $row[csf("brand")] . "';\n";
        echo "document.getElementById('cbo_origin').value 			= '" . $row[csf("origin")] . "';\n";
        echo "document.getElementById('txt_purchase_date').value 	= '" . change_date_format($row[csf("purchase_date")], "dd-mm-yyyy", "-") . "';\n";
    }
	
	$data_array_outoforder = sql_select("select count(asset_sl_id) as sldtlsid   from fam_out_of_order_mst where status_active=1 and is_deleted=0 and asset_sl_id ='$data_arr[1]'");
	foreach ($data_array_outoforder as $row_order) {
        echo "document.getElementById('txt_frequency').value 	= '" . $row_order[csf("sldtlsid")] . "';\n";
    }
	
	$data_array_custody = sql_select("select asset_id ,custody_of  from fam_asset_placement_dtls where status_active=1 and is_deleted=0 and asset_id ='$data_arr[1]'");
	foreach ($data_array_custody as $row_custody) {
        echo "document.getElementById('txt_custody_of').value 	= '" . $row_custody[csf("custody_of")] . "';\n";
    }
	
    exit();	
}


if ($action == "populate_asset_details") 
{
	$custodyof_lib=return_library_array( "select asset_id ,custody_of  from fam_asset_placement_dtls", "asset_id", "custody_of"  );
	$data_array=sql_select("select  a.id as assacq_mst, a.company_id, a.location, a.specification, a.asset_type, a.asset_category, a.asset_group, a.brand, a.origin, a.purchase_date, b.id as asset_sl_id, b.asset_no, b.serial_no from fam_acquisition_mst a, fam_acquisition_sl_dtls  b where b.status_active=1 and b.is_deleted=0 and a.id=b.mst_id and b.asset_no='$data'");
	foreach ($data_array as $row)
	{
		echo "document.getElementById('txt_asset_no').value   		= '".$row[csf("asset_no")]."';\n";
		echo "document.getElementById('txt_asset_id').value 		= '".$row[csf("asset_sl_id")]."';\n";
		echo "document.getElementById('txt_custody_of').value 			= '".$custodyof_lib[$row[csf("asset_sl_id")]]."';\n";  
		echo "document.getElementById('cbo_company_name').value 	= '" . $row[csf("company_id")] . "';\n";
        echo "document.getElementById('txt_specification').value 	= '" . $row[csf("specification")] . "';\n";
        echo "document.getElementById('cbo_aseet_type').value 		= '" . $row[csf("asset_type")] . "';\n";
        echo "load_drop_down('requires/out_of_order_controller','" . $row[csf("asset_type")] . "','load_drop_down_category','category_td' );\n";
        echo "document.getElementById('cbo_category').value 		= '" . $row[csf("asset_category")] . "';\n";
		echo "document.getElementById('cbo_category').disabled 		= true;\n";
        echo "document.getElementById('txt_asset_group').value 		= '" . $row[csf("asset_group")] . "';\n";
        echo "document.getElementById('txt_serial_no').value 		= '" . $row[csf("serial_no")] . "';\n";
        echo "document.getElementById('txt_brand').value 			= '" . $row[csf("brand")] . "';\n";
        echo "document.getElementById('cbo_origin').value 			= '" . $row[csf("origin")] . "';\n";
        echo "document.getElementById('txt_purchase_date').value 	= '" . change_date_format($row[csf("purchase_date")], "dd-mm-yyyy", "-") . "';\n";
	}
    exit();
}


if ($action=="show_asset_active_listview")
{
	?>
    <table width="810" align="center" class="rpt_table" rules="all" id="tbl_list_search">
    	<thead align="center" class="table_header">
        	<th width="39">SL</th>
        	<th width="100">Disorder Date</th>
            <th width="149">Action</th>
            <th width="516">Reason</th>
        </thead>
        <tbody class="table_body">
        	<?php 
			
			if($db_type==0)
			{
				$sql = sql_select("select a.id, a.asset_no, a.asset_sl_id, a.disorder_date, a.action, GROUP_CONCAT(DISTINCT b.reason ORDER BY b.reason DESC SEPARATOR '_')AS reason  from fam_out_of_order_mst a, fam_out_of_order_reason b where a.status_active=1 and a.is_deleted=0 and a.id=b.mst_id and a.asset_sl_id=$data group by a.id, a.asset_no, a.asset_sl_id, a.disorder_date, a.action");
			}
			else
			{
				$sql = sql_select("select a.id, a.asset_no, a.asset_sl_id, a.disorder_date, a.action, LISTAGG(b.reason,'_')within group(order by b.reason) as reason  from fam_out_of_order_mst a, fam_out_of_order_reason b where a.status_active=1 and a.is_deleted=0 and a.id=b.mst_id and a.asset_sl_id=$data group by a.id, a.asset_no, a.asset_sl_id, a.disorder_date, a.action");
			}
			
			$i = 0;
			foreach($sql as $row)
			{
					$i++;
					if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
			?>                 
                <tr style="cursor: pointer; cursor: hand;" align="center" bgcolor="<? echo $bgcolor; ?>"  id="tr_<? echo $i; ?>" height="20" onClick="get_php_form_data(<?php echo $row[csf("id")]; ?>, 'populate_form_data','requires/out_of_order_controller')">
 					<td width="40"><?php echo $i; ?></td>
                    <td width="100"><?php echo change_date_format($row[csf("disorder_date")]); ?></td>
                    <td width="150"><?php echo $order_action_arr[$row[csf("action")]]; ?></td>
                    <td width="500" style="text-align:left;">
					<?php 
					$reason_arr = explode('_',$row[csf("reason")]);
					$q = 0;
					foreach($reason_arr as $value){
						$q++;
						echo $q." : ".$value."<br/>";
					}
					?>
                    </td>
                </tr>
        	<?php 
			}
			?>
        </tbody>
        <tfoot>
        <tr>
            <td align="center" valign="top" id="search_div"></td>
        </tr>
        </tfoot>
    </table> 
    
    <?php
	exit();
	
}


if ($action=="populate_form_data")
{
	$custodyof_lib=return_library_array( "select asset_id ,custody_of  from fam_asset_placement_dtls", "asset_id", "custody_of"  );
	$data_array=sql_select("select a.id, a.asset_no, a.asset_sl_id, a.disorder_date, a.action, b.id as assacq_mst, b.company_id, b.location, b.specification, b.asset_type, b.asset_category, b.asset_group, b.brand, b.origin, b.purchase_date ,c.serial_no from fam_out_of_order_mst a, fam_acquisition_mst b, fam_acquisition_sl_dtls  c where a.status_active=1 and a.is_deleted=0 and b.id=c.mst_id and c.id=a.asset_sl_id and a.id ='$data'");
	foreach ($data_array as $row)
	{
		//echo "reset_form('outOfOrderEntry_1','','');\n";
		echo "document.getElementById('txt_asset_no').value   		= '".$row[csf("asset_no")]."';\n";
		echo "document.getElementById('txt_asset_id').value 		= '".$row[csf("asset_sl_id")]."';\n";
		echo "document.getElementById('txt_custody_of').value 			= '".$custodyof_lib[$row[csf("asset_sl_id")]]."';\n"; 
		echo "document.getElementById('txt_disorder_date').value 	= '".change_date_format($row[csf("disorder_date")])."';\n"; 
		echo "document.getElementById('cbo_action').value 			= '".$row[csf("action")]."';\n";  
		echo "document.getElementById('cbo_company_name').value 	= '" . $row[csf("company_id")] . "';\n";
        echo "document.getElementById('txt_specification').value 	= '" . $row[csf("specification")] . "';\n";
        echo "document.getElementById('cbo_aseet_type').value 		= '" . $row[csf("asset_type")] . "';\n";
        echo "load_drop_down('requires/out_of_order_controller','" . $row[csf("asset_type")] . "','load_drop_down_category','category_td' );\n";
        echo "document.getElementById('cbo_category').value 		= '" . $row[csf("asset_category")] . "';\n";
		echo "document.getElementById('cbo_category').disabled 		= true;\n";
        echo "document.getElementById('txt_asset_group').value 		= '" . $row[csf("asset_group")] . "';\n";
        echo "document.getElementById('txt_serial_no').value 		= '" . $row[csf("serial_no")] . "';\n";
        echo "document.getElementById('txt_brand').value 			= '" . $row[csf("brand")] . "';\n";
        echo "document.getElementById('cbo_origin').value 			= '" . $row[csf("origin")] . "';\n";
        echo "document.getElementById('txt_purchase_date').value 	= '" . change_date_format($row[csf("purchase_date")], "dd-mm-yyyy", "-") . "';\n";
		echo "document.getElementById('update_id').value 			= '".$row[csf("id")]."';\n";
		//echo "get_php_form_data('".$row[csf("asset_sl_id")]."', 'populate_frequency_data', 'requires/out_of_order_controller');\n";  
		echo "set_button_status(1, permission, 'fnc_out_of_order_entry',1);\n";
	}
	
	
	//=======================Start : Reason =======================
		$data_array_reason = sql_select("select id, mst_id, reason from fam_out_of_order_reason   where mst_id='$data'");
		//print_r($data_array_reason);
		$ReasonBreak_down = "";
		foreach ($data_array_reason as $val)
		{
			if ($ReasonBreak_down != "") $ReasonBreak_down.="**";
			$ReasonBreak_down.=$val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("reason")];
		}
		echo "document.getElementById('txt_reason').value = '" . $ReasonBreak_down . "';\n";
		//=======================End : Reason =======================
    exit();
}


if($action=="out_of_order_reason_popup")
{
	echo load_html_head_contents("Order Search","../../../", 1, 1, $unicode);
	extract($_REQUEST);
?>

</head>
<body>
<div align="center" style="width:100%;" >
 <?php  echo load_freeze_divs ("../../../",$permission);  ?>
	<fieldset>
	    <form id="termscondi_1" autocomplete="off">
	   <input type="hidden" id="txt_booking_no" name="txt_booking_no" value="<?php  echo str_replace("'","",$txt_booking_no) ?>"/>
	    <table width="650" cellspacing="0" class="rpt_table" border="0" id="tbl_reason_details" rules="all">
	            <thead>
	                <tr>
	                    <th width="20">Sl</th><th width="530">Reason</th><th ></th>
	                </tr>
	            </thead>
	            <tbody>
	            <?php
	            if ( $reason_break_down != "")
	            {
	                $txt_reason_brackdown_data_row = explode("**",$reason_break_down);
	                $i=0;
	                foreach( $txt_reason_brackdown_data_row as $row_data )
	                {
	                    $row_data_arr = explode("_",$row_data);
	                    $i++;
	                    ?>
	                    <tr id="reasonTr_<?php  echo $i;?>" align="center">
	                        <td>
	                        	<input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> 
	                        	<input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value="<?php  echo $row_data_arr[1] ?>"   class="text_boxes" style="width:20px" />
	                        	<input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value="<?php  echo $row_data_arr[0] ?>"   class="text_boxes" style="width:20px" />
	                        </td>
	                        <td>
	                        	<input type="text" id="reason_<?php  echo $i;?>"   name="reason_<?php  echo $i;?>" style="width:95%"  class="text_boxes"  value="<?php  echo $row_data_arr[2]; ?>"  /> 
	                        </td>
	                        <td> 
	                        	<input type="button" id="increase_<?php  echo $i; ?>" style="width:30px" class="formbutton" value="+" onClick="add_break_down_tr(<?php  echo $i; ?> )" />
	                        	<input type="button" id="decrease_<?php  echo $i; ?>" style="width:30px" class="formbutton" value="-" onClick="javascript:fn_deletebreak_down_tr(<?php  echo $i; ?>);" />
	                        </td>
	                    </tr>
	                    <?php 
	                }
	            }
	            else
	            {
	                $i++;
	            ?>
	            <tr id="reasonTr_1" align="center">
	                <td>
		                <input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> 
		                <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value="<?php  echo $row_data_arr[1] ?>"   class="text_boxes" style="width:20px" />
		                <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value="<?php  echo $row_data_arr[0] ?>"   class="text_boxes" style="width:20px" />
	                </td>
	                <td>
	                	<input type="text" id="reason_<?php  echo $i;?>"   name="reason_<?php  echo $i;?>" style="width:95%"  class="text_boxes"  value="<?php  echo $row[csf('terms')]; ?>"  /> 
	                </td>
	                <td>
		                <input type="button" id="increase_<?php  echo $i; ?>" style="width:30px" class="formbutton" value="+" onClick="add_break_down_tr(<?php  echo $i; ?> )" />
		                <input type="button" id="decrease_<?php  echo $i; ?>" style="width:30px" class="formbutton" value="-" onClick="javascript:fn_deletebreak_down_tr(<?php  echo $i; ?> );" />
	                </td>
	            </tr>
	            <?php 
	            } 
	            ?>
	        </tbody>
	        </table>
	        <table width="650" cellspacing="0" class="" border="0">
	            <tr>
	                <td align="center" height="15" width="100%"> </td>
	            </tr>
	            <tr>
	                <td align="center" width="100%" class="button_container">
	                   <input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="ReasonDtlsBreakdown()" />
	                   <input type="hidden" name="txt_hidden_reason_data" id="txt_hidden_reason_data" class="text_boxes" style="width:170px" />
                       <input type="hidden" id="deleted_id" name="deleted_id" class="text_boxes" style="width:170px" /> 
	                 </td>
	             </tr>
	        </table>
	    </form>
	</fieldset>
</div>
</body>   
<script>
function ReasonDtlsBreakdown() {
	//alert( 'okay');
	//return;
	var numberOfRespons = "";
	var total_row = $("#tbl_reason_details tbody tr").length;
	for (var sl = 1; sl <= total_row; sl++) {
		var reason 				= $("#reason_" + sl).val();
		var txtMstID 			= $("#txtMstID_" + sl).val();
		var hiddenUpdatesetId 	= $("#hiddenUpdatesetId_" + sl).val();
		if (numberOfRespons != '') {
			numberOfRespons += "**" + hiddenUpdatesetId + "_"+ txtMstID + "_" + reason;
		} else {
			numberOfRespons += hiddenUpdatesetId + "_"+ txtMstID + "_" + reason;
		}
	}
	//alert('Ok');
	$('#txt_hidden_reason_data').val(numberOfRespons);
	parent.emailwindow.hide();
}


function add_break_down_tr(i) 
 {
	var row_num=$('#tbl_reason_details tr').length-1;
	if (row_num!=i)
	{
		return false;
	}
	else
	{
		i++;
	 
		 $("#tbl_reason_details tr:last").clone().find("input,select").each(function() {
			$(this).attr({
			  'id': function(_, id) { var id=id.split("_"); return id[0] +"_"+ i },
			  'name': function(_, name) { return name + i },
			  'value': function(_, value) { return value }              
			});  
		  }).end().appendTo("#tbl_reason_details");
		 $('#increase_'+i).removeAttr("onClick").attr("onClick","add_break_down_tr("+i+");");
		  $('#decrease_'+i).removeAttr("onClick").attr("onClick","fn_deletebreak_down_tr("+i+")");
		  
		  $('#txtsl_'+i).val(i);
		  $('#txtMstID_'+i).val("");
		  $('#hiddenUpdatesetId_'+i).val("");
		  $('#reason_'+i).val("");
	}
		  
}

function fn_deletebreak_down_tr(rowNo) 
{
	var deleted_row = $("#deleted_id").val();
    if (deleted_row != "") deleted_row = deleted_row + ",";   
	
	var numRow = $('table#tbl_reason_details tbody tr').length; 
	if(numRow==rowNo && rowNo!=1)
	{
		deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
		$('#tbl_reason_details tbody tr:last').remove();
	}
	$("#deleted_id").val(deleted_row);
}
</script>        
<script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
</html>
<?php
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
		
		$id=return_next_id( "id", "fam_out_of_order_mst", 1 ) ;
		$field_array="id, asset_no, asset_sl_id, disorder_date, action, company_id, inserted_by, insert_date";
		$field_array_reason="id, mst_id, reason";

		$data_array="(".$id.",".$txt_asset_no.",".$txt_asset_id.",".$txt_disorder_date.",".$cbo_action.",".$cbo_company_name.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		
		//Start : Insert Reason Details --------------------------------------------------
		$txt_reason_hidden = explode("**", str_replace("'", "",$txt_reason));
        $id_reason = return_next_id("id", "fam_out_of_order_reason", 1);
        $field_array_reason="id, mst_id, reason";
		
		$data_array_reason="";
        for ($i = 0; $i < count($txt_reason_hidden); $i++) {
            $reason_hidden_popup = explode("_", $txt_reason_hidden[$i]);
            if ($data_array_reason != "")
                $data_array_reason .=",";
           			
            $data_array_reason .="('" . $id_reason . "','" . $id . "','" . $reason_hidden_popup[2]. "')";
            $id_reason = $id_reason + 1;
		}
		//echo "10**insert into fam_out_of_order_reason($field_array_reason) values".$data_array_reason;//die;
		//End : Insert Reason Details --------------------------------------------------
		
		$rID=sql_insert("fam_out_of_order_mst",$field_array,$data_array,0);
		$rID1=sql_insert("fam_out_of_order_reason",$field_array_reason,$data_array_reason,0);
		//echo "10**insert into fam_out_of_order_mst($field_array)values".$data_array;die;
		//echo $rID."&&".$rID1;  die;
		if($db_type==0)
		{
			if($rID && $rID1)
			{
			mysql_query("COMMIT");  
			echo "0**".$new_entry_no[0]."**".$id."**".$txt_asset_id;
			}
			else
			{
			mysql_query("ROLLBACK"); 
			echo "10**".$id;
			}
		}
		
		if($db_type==2 || $db_type==1 )
		{
			if($rID && $rID1)
			{
			oci_commit($con);
			echo "0**".$new_entry_no[0]."**".$id."**".$txt_asset_id;
			}
			else
	  		{
			oci_rollback($con);
			echo "10**" . $new_entry_no[0];
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
		$field_array="asset_no*asset_sl_id*disorder_date*action*updated_by*update_date";
		
		$data_array="".$txt_asset_no."*".$txt_asset_id."*".$txt_disorder_date."*".$cbo_action."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		//Start : Update Reason Details =======================================
		$txt_reason_hidden = explode("**", str_replace("'", "",$txt_reason));
        
		$id_reason = return_next_id("id", "fam_out_of_order_reason", 1);
        $field_array_reason="id, mst_id, reason";
		$field_array_reason_update = "reason";
		
		$data_array_reason = '';
		
        for ($c = 0; $c < count($txt_reason_hidden); $c++)
		{
            $reason_popup = explode("_", $txt_reason_hidden[$c]);
			if ($reason_popup[0] != "")
			{
				$update_reason_arr[] = $reason_popup[0];
				$data_array_reason_update[$reason_popup[0]] = explode(",", ("'" . $reason_popup[2]. "'"));
            }
			else
			{
           	 	if ($data_array_reason != ""){ $data_array_reason .=",";}
				$data_array_reason .="('" . $id_reason . "'," . $update_id . ",'" . $reason_popup[2]. "')";
           		$id_reason = $id_reason + 1;
			}
		}
		
		
		if (str_replace("'", "", $deleted_ids) != "") {
            $hidden_deleted_id = str_replace("'", "", $deleted_ids);
            //echo "delete from fam_out_of_order_reason where id in ($hidden_deleted_id)"; die;
            $rID5 = execute_query("delete from fam_out_of_order_reason where id in ($hidden_deleted_id)");
        }
		
		//End 	: Update Reason Details ============================================
		$rID=sql_update("fam_out_of_order_mst",$field_array,$data_array,"id","".$update_id."",0);
		$rID2=$rID3=1;
		if($data_array_reason != "")
		{  			
			$rID2 = sql_insert("fam_out_of_order_reason", $field_array_reason, $data_array_reason, 1);
		}
		if (count($data_array_reason_update) > 0)
		{ 
            $rID3 = execute_query(bulk_update_sql_statement("fam_out_of_order_reason", "id", $field_array_reason_update, $data_array_reason_update, $update_reason_arr, 0), 1);
        }
		
		if($db_type==0)
		{
			if($rID)
			{
				mysql_query("COMMIT");  
				echo "1**".$new_entry_no[0]."**".$update_id."**".$txt_asset_id;
			}
			else
			{
				mysql_query("ROLLBACK"); 
				echo "10**".$update_id;
			}
		}
		if($db_type==2 || $db_type==1 )
		{
			if ($rID && $rID2 && $rID3) {
                oci_commit($con);
                echo "1**".$new_entry_no[0]."**".$update_id."**".$txt_asset_id;
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
		$field_array="status_active*is_deleted*updated_by*update_date";
		$data_array="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		$update_id=str_replace("'","",$update_id);
		
		$rID=sql_delete("fam_out_of_order_mst",$field_array,$data_array,"id","".$update_id."",1);
		//echo "delete from fam_out_of_order_reason where mst_id=$update_id"; die;
		 $rID1 = execute_query("delete from fam_out_of_order_reason where mst_id=$update_id");
		
		//echo "2**".$rID; die;
		
		 if ($db_type == 0) {
            if ($rID && $rID1) {
                mysql_query("COMMIT");
                echo "2**" .$new_entry_no[0]."**".$update_id."**".$txt_asset_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" .$new_entry_no[0]."**".$update_id."**".$txt_asset_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1) {
                oci_commit($con);
                echo "2**".$new_entry_no[0]."**".$update_id."**".$txt_asset_id;
            } else {
                oci_rollback($con);
                echo "10**" .$new_entry_no[0]."**".$update_id."**".$txt_asset_id;
            }
        }
		disconnect($con);
	}
// Delete Here End ----------------------------------------------------------
}