<?php
header('Content-type:text/html; charset=utf-8');
session_start();
include('../../../includes/common.php');
$user_id = $_SESSION['logic_erp']["user_id"];
if( $_SESSION['logic_erp']['user_id'] == "" ) { header("location:login.php"); die; }
$permission=$_SESSION['page_permission'];
$data=$_REQUEST['data'];
$action=$_REQUEST['action'];

$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");
//--------------------------------------------------------------------------------------------
//load drop down company location
if ($action == "load_drop_down_location") 
{
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
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "81,82,83,84,85,86,87,88,89,90,191", "", "", "", "4", "", "");
    } elseif ($data == 9) { 	//Electric Appliance
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "121,122,123,124,125,126,127,128,129,130", "", "", "", "4", "", "");
    } elseif ($data == 10) { 	//Transportation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "131,132,133,134,135,136,137,138,139,140,141", "", "", "", "4", "", "");
    } elseif ($data == 11) { 	//Communication Device
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "151,152,153,154,155,156,157", "", "", "", "4", "", "");
    } elseif ($data == 12) { 	//Security System
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "161,162,163,164,165,166,167", "", "", "", "4", "", "");
    } elseif ($data == 13) { 	//Kitchen Appliance
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "171,172,173,174,175,176,177", "", "", "", "4", "", "");
    }
    exit();
}


if($action == "search_send_for_repiar_entry")
{
	echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	?>
    <script>
	    function js_set_value(id)
		{
			$('#hidden_system_id').val(id);
			//alert(id); return;
			parent.emailwindow.hide();
		}
    </script>
    </head>
    <body>
      <div align="center" style="width:100%;">
      	<form id="searchSendForRepiar_1" name="searchSendForRepiar_1" autocomplete="off">
        	<table width="710" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
            	<thead>
                	<th width="">System Number</th>
                    <th width="">Company</th>
                    <th width="">Service Nature</th>
                    <th width="">Out Date Range</th>
                    <th width="">Send To</th>
                    <th width="" >
                    	<input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"/>
                     </th> 
                </thead>
                <tbody>
                	<tr>
                    	<td>
                            <input type="text" id="txt_system_number" name="txt_system_number" class="text_boxes" style="width:90px;" >
                        </td>
                        <td>
                            <?php
								echo create_drop_down("cbo_company_name", 150, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "--- Select ---", $cbo_company_id, "", "0", "", "", "", "", "1", "", "");
							?>  
                        </td>
                        <td>
                            <?php
			                 	echo create_drop_down( "cbo_serviceNature", 100, $service_nature_arr,"", 1, "--- Select ---", $selected, "","","","","","","4");
			                ?> 
                        </td>
                        <td>
                        	<input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" /> -
                            <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" />
                        </td>
                        <td>
                            <?php
								echo create_drop_down( "cbo_sent_to", 100, "select id,supplier_name from lib_supplier where status_active=1 and is_deleted=0 order by supplier_name","id,supplier_name", 1, "Select", $selected, "","");
							?> 
                        </td>
                        <td align="center">
                        	<input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('txt_system_number').value + '_' + document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_serviceNature').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value + '_' + document.getElementById('cbo_sent_to').value + '_' + document.getElementById('hidden_system_id').value, 'show_searh_system_id_listview', 'searh_system_id_listview', 'sent_for_repiar_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />	
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                	<input type="hidden" id="hidden_system_id" name="hidden_system_id" class="text_boxes" value="" style="width:90px;">
                </tfoot>	
            </table>	
        </form>
        <div align="center" valign="top" id="searh_system_id_listview"> </div>
      </div>
    </body>
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php
	exit();
}


if($action == "show_searh_system_id_listview")
{
	//echo $data; //die;
	$ex_data = explode("_", $data);
	//$system_id = "'".$ex_data[0]."'";
	//echo $system_id; die;
	
	if ($ex_data[0] == '')	$system_number = "";		else	$system_number = " and system_no='" . $ex_data[0] . "'";
	if ($ex_data[1] == 0)	$company_id = "";		else	$company_id = " and company_id='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0)	$serviceNature = "";	else	$serviceNature = " and service_nature='" . $ex_data[2] . "'";
	if ($ex_data[5] == 0)	$sent_to = "";			else	$sent_to = " and send_to='". $ex_data[5] ."'";
	//if ($ex_data[6] == 0)	$id = "";			else	$id = " and id not in=('". $ex_data[6] ."')";
	//select id, system_no, company_id, out_date send_to, service_nature  from fam_send_for_repair_mst  where status_active=1 and is_deleted=0
	
	$txt_date_from = $ex_data[3];
	$txt_date_to = $ex_data[4];
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0)
		{
			$tran_date = " and out_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		}
		if($db_type==2 || $db_type==1 )
		{
			$tran_date = " and out_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
		}
	
	}
	
	$company_location = return_library_array("select id,company_name  from lib_company where status_active =1 and is_deleted=0", "id", "company_name");
	$supplier_array = return_library_array("select id,supplier_name from lib_supplier where status_active=1 and is_deleted=0 order by supplier_name", "id","supplier_name");
	$arr=array (1=>$company_location,2=>$service_nature_arr,4=>$supplier_array);
	
	$sql = "select id, system_no, company_id, out_date, send_to, service_nature  from fam_send_for_repair_mst  where status_active=1 and is_deleted=0 $system_number $company_id $serviceNature $sent_to $tran_date  order by id asc";
	//echo $sql;
	echo  create_list_view("list_view", "System ID,Company Name,Service Nature,Out Date,Send To", "120,150,100,100,100","700","300",0, $sql , "js_set_value", "id", "", 1, "0,company_id,service_nature,0,send_to", $arr , "system_no,company_id,service_nature,out_date,send_to", "requires/sent_for_repiar_controller",'','0,0,0,3,0') ;
	
	exit();
}


if($action=="populate_data_from_data")
{
	$sql = "select id, system_no, company_id, out_date, send_to, service_nature  from fam_send_for_repair_mst  where status_active=1 and is_deleted=0 and id=$data";
	//echo $sql; die;
	$res = sql_select($sql);	
	foreach($res as $row)
	{		
		echo "$('#txt_system_id').val('".$row[csf("system_no")]."');\n";
		echo "$('#cbo_company_name').val(".$row[csf("company_id")].");\n";
		echo "$('#cbo_company_name').attr('disabled','true')".";\n";
		echo "$('#cbo_serviceNature').val(".$row[csf("service_nature")].");\n";
		echo "$('#cbo_serviceNature').attr('disabled','true')".";\n";
		echo "$('#cbo_sent_to').val(".$row[csf("send_to")].");\n";
		echo "$('#txt_out_date').val('".change_date_format($row[csf("out_date")])."');\n";
		echo "$('#update_id').val(".$row[csf("id")].");\n";
		echo "set_button_status(1, permission, 'fnc_sent_for_repair_entry',1);\n";
  	}
	exit();	
}



if($action == "show_send_for_repiar_listview")
{
	$data_row = explode('_',$data);

	if($data_row[1]==1)
	{
		
		$sql = sql_select("select f.id as dtls_id, b.id, b.asset_id, b.service_no, b.service_date, c.asset_no, c.serial_no, d.company_id, d.asset_category, d.asset_type, d.specification, d.asset_group , d.brand, d.purchase_date, d.origin,f.duration, f.estm_returnable_date from fam_service_schedule_mst a, fam_service_schedule_dtls b, fam_acquisition_sl_dtls c, fam_acquisition_mst d,  fam_send_for_repair_mst e, fam_send_for_repair_dtls f where a.id=b.mst_id and b.asset_id=c.id and c.mst_id=d.id and b.asset_id=f.asset_id and f.mst_id = e.id and b.id=f.acq_serv_outoforder_id and f.status_active=1 and f.is_deleted = 0 and f.mst_id=$data_row[0]");
		
		if($data_row[2] != ''){ 
			$i=$data_row[2]; 
		}else{
			$i = 0;
		}
		
		foreach($sql as $row)
		{
			$i++;
			if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
			
			$tble_body .='<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle"><td width="30">'.$i.'</td><td id="" width="50">
			<input type="text" name="txtDuration[]" id="txtDuration_'.$i.'" onchange="calculateDate('.$i.')" value="'.$row[csf('duration')].'" class="text_boxes_numeric" style="width:50px"/></td><td id="" style="word-break:break-all;" width="85">
			<input type="text" name="returnableDate[]" id="returnableDate_'.$i.'"  value="'.change_date_format($row[csf('estm_returnable_date')]).'"  class="datepicker" style="width:85px"></td><td id="assetNo_'.$i.'" name="assetNo[]" width="90">
			'.$row[csf('asset_no')].'
			<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
			<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
			<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtAcqServOutoforderId"/>
			<input type="hidden" name="txtDeletedId[]" id="txtDeletedId_'.$i.'" value="'.$row[csf('dtls_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtDeletedId"/>
			</td><td  name="txtSpecification[]" id="txtSpecification_'.$i.'" style="word-break:break-all;" width="100">'.$row[csf('specification')].'
			</td><td id="" style="word-break:break-all;" width="100">
			'.$asset_type[$row[csf('asset_type')]].'
			</td><td id="" class="image_uploader" width="50"><a href="#">View</a>
			</td><td width="90" id="category_td">
			'.$asset_category[$row[csf('asset_category')]].'
			</td><td name="txt_asset_group[]" id="txt_asset_group_'.$i.'" style="word-break:break-all;" width="90">
			'.$row[csf('asset_group')].'
			</td><td name="txt_brand[]" id="txt_brand_'.$i.'" width="60" align="center">
			'. $row[csf('brand')].'
			</td><td id="" style="word-break:break-all;" width="100">
			'.$order_action_arr[$row[csf('action')]].'
			</td><td name="txt_purchase_date[]" id="txt_purchase_date_'.$i.'" style="word-break:break-all;" width="80">
			'.change_date_format($row[csf('purchase_date')]).'
			</td><td name="txt_serial_no[]" id="txt_serial_no_'.$i.'" style="word-break:break-all;" width="70" align="center">
			'.$row[csf('serial_no')].'
			</td><td id="" style="word-break:break-all;" width="80">
			'.$origin_array[$row[csf('origin')]].'
			</td><td id="button_1" align="center">
			<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
			</td></tr>';
	    }
		echo $tble_body;
		die;
		
	}
	else if ($data_row[1]==2)
	{
		$sql = sql_select("select a.service_nature, b.id, b.mst_id, b.duration, b.estm_returnable_date, b.asset_number, b.asset_id, b.acq_serv_outoforder_id,  
 c.asset_category, c.asset_type, c.specification, c.asset_group , c.brand, c.purchase_date, c.origin, d.serial_no,e.action from fam_send_for_repair_mst a, fam_send_for_repair_dtls b, fam_acquisition_mst c, fam_acquisition_sl_dtls d, fam_out_of_order_mst e where a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0  and c.status_active=1 and c.is_deleted=0 and d.status_active=1 and d.is_deleted=0 and e.status_active=1 and e.is_deleted=0 and a.id=b.mst_id and c.id=d.mst_id and b.asset_id=d.id and e.asset_sl_id=d.id and a.service_nature=2 and b.mst_id=$data_row[0]");
		
		if($data_row[2] != ''){ 
			$i=$data_row[2]; 
		}else{
			$i = 0;
		}
		
		foreach($sql as $row)
		{
			$i++;
			if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
			
			$tble_body .='<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle"><td width="30">'.$i.'</td><td id="" width="50">
			<input type="text" name="txtDuration[]" id="txtDuration_'.$i.'" onchange="calculateDate('.$i.')" value="'.$row[csf('duration')].'" class="text_boxes_numeric" style="width:50px"/></td><td id="" style="word-break:break-all;" width="85">
			<input type="text" name="returnableDate[]" id="returnableDate_'.$i.'"  value="'.change_date_format($row[csf('estm_returnable_date')]).'"  class="datepicker" style="width:85px"></td><td id="assetNo_'.$i.'" name="assetNo[]" width="90">
			'.$row[csf('asset_number')].'
			<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_number')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
			<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
			<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('acq_serv_outoforder_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtAcqServOutoforderId"/>
			<input type="hidden" name="txtDeletedId[]" id="txtDeletedId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtDeletedId"/>
			</td><td  name="txtSpecification[]" id="txtSpecification_'.$i.'" style="word-break:break-all;" width="100">'.$row[csf('specification')].'
			</td><td id="" style="word-break:break-all;" width="100">
			'.$asset_type[$row[csf('asset_type')]].'
			</td><td id="" class="image_uploader" width="50" onClick="shwo_reason('.$row[csf('reasonMstId')].')"><a href="#">View</a>
			</td><td width="90" id="category_td">
			'.$asset_category[$row[csf('asset_category')]].'
			</td><td name="txt_asset_group[]" id="txt_asset_group_'.$i.'" style="word-break:break-all;" width="90">
			'.$row[csf('asset_group')].'
			</td><td name="txt_brand[]" id="txt_brand_'.$i.'" width="60" align="center">
			'. $row[csf('brand')].'
			</td><td id="" style="word-break:break-all;" width="100">
			'.$order_action_arr[$row[csf('action')]].'
			</td><td name="txt_purchase_date[]" id="txt_purchase_date_'.$i.'" style="word-break:break-all;" width="80">
			'.change_date_format($row[csf('purchase_date')]).'
			</td><td name="txt_serial_no[]" id="txt_serial_no_'.$i.'" style="word-break:break-all;" width="70" align="center">
			'.$row[csf('serial_no')].'
			</td><td id="" style="word-break:break-all;" width="80">
			'.$origin_array[$row[csf('origin')]].'
			</td><td id="button_1" align="center">
			<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
			</td></tr>';
	    }
		echo $tble_body;
		die;
	}
	else if ($data_row[1]==3)
	{
		//Sir Stop this option 20/08/2016
		die;
	}
	
}


//====Asset No Search 
if ($action == "search_asset_entry") 
{
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	//echo $cbo_company_id; die;
    ?>
    <script>
		var selected_id = new Array();
		function toggle( x, origColor ) {
			var newColor = 'yellow';
			if ( x.style ) {
				x.style.backgroundColor = ( newColor == x.style.backgroundColor )? origColor : newColor;
			}
		}
		
		function js_set_value( str) 
		{
			
			toggle( document.getElementById( 'search' + str ), '#FFFFCC' );
			//alert(str); return;
			if( jQuery.inArray( $('#txt_individual_id' + str).val(), selected_id ) == -1 ) {
				selected_id.push( $('#txt_individual_id' + str).val() );
				
			}
			else {
				for( var i = 0; i < selected_id.length; i++ ) {
					if( selected_id[i] == $('#txt_individual_id' + str).val() ) break;
				}
				selected_id.splice( i, 1 );
			}
			var id = '';
			for( var i = 0; i < selected_id.length; i++ ) {
				id += selected_id[i] + ',';
			}
			id = id.substr( 0, id.length - 1 );
			
			$('#hidden_service_schedule_dtls_id').val( id );
		}
		
		function fnc_close()
		{
			//alert($('#hidden_service_schedule_dtls_id').val()); return;
			parent.emailwindow.hide();
		}
		
		function reset_hide_field()
		{
			$('#hidden_service_schedule_dtls_id').val( '' );
			selected_id = new Array();
		}
	
    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
        	<?php 
			if($cbo_serviceNature == 1)
			{ 	
			?>
            <form name="searchorderfrm_1"  id="searchorderfrm_1" autocomplete="off">
                <table width="645" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>               	 
                            <th width="90">Asset No</th>
                            <th width="110">Asset Type</th>
                            <th width="170">Category</th>
                            <!--<th width="100">Supplier</th>-->
                            <th width="210" align="center" >Service Date Range</th>
                            <th width="80">
                            <input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  />
                            </th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="asset_number" id="asset_number" style="width:90px;" class="text_boxes">
                            </td>
                            <td>
                                <?php
                                echo create_drop_down("cbo_aseet_type", 110, $asset_type, "", 1, "--- Select ---", $selected, "load_drop_down( 'sent_for_repiar_controller', this.value, 'load_drop_down_category', 'src_category_td' );", "", "5,6,7,8,9,10", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td id="src_category_td">
                                <?php
                                echo create_drop_down("cbo_category", 170, $blank_array, "", 1, "--- Select ---", $selected, "", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" /> -
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" />
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('hidden_company_id').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_category').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value + '_' + document.getElementById('asset_number').value + '_' + document.getElementById('hidden_service_nature').value + '_' + document.getElementById('service_schedule_dtls_id').value, 'show_searh_service_schedule_listview', 'searh_list_view', 'sent_for_repiar_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr> 
                        <tr>                  
                            <td align="center" height="30" valign="middle" colspan="7">
                                <?php echo load_month_buttons(1); ?>
                            </td>
                        </tr> 
                        <tr> 
	                        <input type="hidden" name="hidden_company_id" id="hidden_company_id" style="width:90px;" class="text_boxes" value="<?php echo $cbo_company_id;?>">
	                        <input type="hidden" name="hidden_service_nature" id="hidden_service_nature" style="width:90px;" class="text_boxes" value="<?php echo $cbo_serviceNature;?>">
	                        <input type="hidden" name="hidden_service_schedule_dtls_id" id="hidden_service_schedule_dtls_id"     style="width:90px;" class="text_boxes">
	                        <input type="hidden" name="service_schedule_dtls_id" id="service_schedule_dtls_id"  value="<?php echo $service_schedule_dtls_id;?>" style="width:90px;" class="text_boxes">
	                        <input type="hidden" id="hidden_system_number" value="" />
                        </tr> 
                    </tbody>
                </table> 
            </form>
            
            <?php 
			}
			
			else if($cbo_serviceNature==2)
			{
				?>
            
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="645" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>               	 
                            <th width="90">Asset No</th>
                            <th width="110">Asset Type</th>
                            <th width="170">Category</th>
                            <!--<th width="100">Supplier</th>-->
                            <th width="210" align="center" >Disorder Date Range</th>
                            <th width="80">
                            <input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  />
                            </th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="asset_number" id="asset_number" style="width:90px;" class="text_boxes">
                            </td>
                            <td>
							<?php
                            	echo create_drop_down("cbo_aseet_type",110,$asset_type,"",1,"--- Select ---",$selected,"load_drop_down('sent_for_repiar_controller', this.value,'load_drop_down_category','src_category_td' );","","5,6,7,8,9,10","","","","","","");
                            ?>
                            </td>
                            <td id="src_category_td">
							<?php
                            	echo create_drop_down("cbo_category",170,$blank_array,"",1,"--- Select ---",$selected,"","","","","","","","","");
                            ?>
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" /> -
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" />
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('hidden_company_id').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_category').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value + '_' + document.getElementById('asset_number').value + '_' + document.getElementById('hidden_service_nature').value + '_' + document.getElementById('service_schedule_dtls_id').value, 'show_searh_out_of_order_listview', 'searh_list_view', 'sent_for_repiar_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr> 
                        <tr>                  
                            <td align="center" height="30" valign="middle" colspan="7">
                                <?php echo load_month_buttons(1); ?>
                            </td>
                        </tr> 
                        <tr> 
                            <input type="hidden" name="hidden_company_id" id="hidden_company_id" style="width:90px;" class="text_boxes" value="<?php echo $cbo_company_id;?>">
                            <input type="hidden" name="hidden_service_nature" id="hidden_service_nature" style="width:90px;" class="text_boxes" value="<?php echo $cbo_serviceNature;?>">
                            <input type="hidden" name="hidden_service_schedule_dtls_id" id="hidden_service_schedule_dtls_id"     style="width:90px;" class="text_boxes">
                            <input type="hidden" name="service_schedule_dtls_id" id="service_schedule_dtls_id"  value="<?php echo $service_schedule_dtls_id;?>" style="width:90px;" class="text_boxes">
                            <input type="hidden" id="hidden_system_number" value="" />
                        </tr> 
                    </tbody>
                </table> 
            </form>
            
            <?php 
			}
			
			else if($cbo_serviceNature==3)
			{
				?>
            
             <form name="searchorderfrm_3"  id="searchorderfrm_3" autocomplete="off">
                <table width="645" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>               	 
                            <th width="90">Asset No</th>
                            <th width="170">Location</th>
                            <th width="110">Asset Type</th>
                            <th width="170">Category</th>
                            <th width="210" align="center" >Purchase Date Range</th>
                            <th width="80">
                              <input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  />
                            </th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="asset_number" id="asset_number" style="width:90px;" class="text_boxes">
                            </td>
                            <td>
                                <?php
								echo create_drop_down("cbo_location", 170, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$cbo_company_id' order by location_name", "id,location_name", 1, "-- Select Location --", $selected, "", 0);
                                ?>
                            </td>
                            <td>
                                <?php
                                echo create_drop_down("cbo_aseet_type", 110, $asset_type, "", 1, "--- Select ---", $selected, "load_drop_down( 'sent_for_repiar_controller', this.value, 'load_drop_down_category', 'src_category_td' );", "", "5,6,7,8,9,10", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td id="src_category_td">
                                <?php
                                echo create_drop_down("cbo_category", 170, $blank_array, "", 1, "--- Select ---", $selected, "", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" /> -
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" />
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('hidden_company_id').value + '_' + document.getElementById('cbo_location').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_category').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value + '_' + document.getElementById('asset_number').value + '_' + document.getElementById('hidden_service_nature').value + '_' + document.getElementById('service_schedule_dtls_id').value, 'show_check_up_listview', 'searh_list_view', 'sent_for_repiar_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr> 
                        <tr>                  
                            <td align="center" height="30" valign="middle" colspan="7">
                                <?php echo load_month_buttons(1); ?>
                            </td>
                        </tr> 
                        <tr> 
                        <input type="hidden" name="hidden_company_id" id="hidden_company_id" style="width:90px;" class="text_boxes" value="<? echo $cbo_company_id;?>">
                        <input type="hidden" name="hidden_service_nature" id="hidden_service_nature" style="width:90px;" class="text_boxes" value="<? echo $cbo_serviceNature;?>">
                        <input type="hidden" name="hidden_service_schedule_dtls_id" id="hidden_service_schedule_dtls_id"     style="width:90px;" class="text_boxes">
                        <input type="hidden" name="service_schedule_dtls_id" id="service_schedule_dtls_id"  value="<?php echo $service_schedule_dtls_id;?>" style="width:90px;" class="text_boxes">
                        <input type="hidden" id="hidden_system_number" value="" />
                        </tr> 
                    </tbody>
                </table> 
            </form>
            
            <?php 
			}
			?>
            <div align="center" valign="top" id="searh_list_view"> </div>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php
    exit();
}


if ($action == "show_searh_service_schedule_listview") 
{
    $ex_data = explode("_", $data);
	$service_nature=$ex_data[7];
	if($service_nature==1)
	{
	?>
	<div style="width:645px; max-height:280px; overflow-y:scroll" id="list_container_batch" align="left">	 
	<table cellspacing="0" cellpadding="0" border="1" rules="all" width="627" class="rpt_table" id="tbl_list_search">
	<thead>
		<th width="35">SL</th>
		<th width="70">Asset No</th>
		<th width="80">Type</th>
		<th width="70">Category</th>
		<th width="70">Service No</th>
		<th width="70">Service Date</th>
	</thead>
	<tbody>
	<?php
	//if ($ex_data[0] == 0)	$company_id = "";		else	$company_id = " and a.company_id='" . $ex_data[0] . "'";
	if ($ex_data[2] == 0)	$aseet_type = "";		else	$aseet_type = " and a.asset_type='" . $ex_data[2] . "'";
	if ($ex_data[3] == 0)	$category = "";			else	$category = " and a.asset_category='" . $ex_data[3] . "'";
	if ($ex_data[6] == 0)	$asset_number = "";		else	$asset_number = " and c.asset_no='" . $ex_data[6] . "'";
	if ($ex_data[8] == 0)	$service_schedule_ids = "";		else	$service_schedule_ids = " and  b.id not in(" . $ex_data[8] . ")";
	
	
	$txt_date_from = $ex_data[4];
	$txt_date_to = $ex_data[5];
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0){ 
			$tran_date = " and b.service_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		}else {
			$tran_date = " and b.service_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
		}
	}
	
	$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
	
	$result = sql_select("select a.company_id, a.asset_type, a.asset_category, b.id, b.asset_id, b.service_no, b.service_date, c.asset_no from fam_service_schedule_mst a, fam_service_schedule_dtls b, fam_acquisition_sl_dtls c  where a.status_active=1 and a.is_deleted = 0 and b.status_active=1 and b.is_deleted = 0 and c.status_active=1 and c.is_deleted = 0 and b.is_send_for_repair != '1' and a.id=b.mst_id and c.id=b.asset_id  and a.company_id='$ex_data[0]' $aseet_type $category $asset_number $tran_date $service_schedule_ids");
	
	$i=1;
	foreach ($result as $row)
	{  
		if($row[csf('asset_no')] !== "")
		{
			if($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";	
		?>
			<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" id="search<?php echo $i;?>" onClick="js_set_value(<?php echo $i; ?>)"> 
				<td width="35" align="center">
					<?php echo $i; ?>
					 <input type="hidden" name="txt_individual_id<?php echo $i; ?>" id="txt_individual_id<?php echo $i; ?>" value="<?php echo $row[csf('id')]; ?>" class="text_boxes" style="width:25px;"/>
					 <input type="hidden" name="txt_hidden_asset_id<?php echo $i; ?>" id="txt_hidden_asset_id<?php echo $i; ?>" value="<?php echo $row[csf('asset_id')]; ?>" class="text_boxes" style="width:25px;"/>
				</td>
				<td width="70"><?php echo $row[csf('asset_no')]; ?></td>
				<td width="80"><?php echo $asset_type[$row[csf('asset_type')]]; ?></td>
				<td width="70"><?php echo $asset_category[$row[csf('asset_category')]]; ?></td>
				<td width="70"><?php echo $row[csf('service_no')]; ?></td>
				<td width="70" align="center"><?php echo change_date_format($row[csf('service_date')]); ?>
				</td>
			</tr>
		<?
			$i++;
		}
	}
	?>
	</tbody>  
	</table>
	</div>
	<table width="632">
	<tr>
	<td align="center" >
		<input type="button" name="close" class="formbutton" value="Close" id="main_close" onClick="fnc_close();" style="width:100px" />
	</td>
	</tr>
	</table>
	<?php
	exit();
	}
	
	else if($service_nature==2)
	{
	?>
    <div style="width:645px; max-height:280px; overflow-y:scroll" id="list_container_batch" align="left">	 
        <table cellspacing="0" cellpadding="0" border="1" rules="all" width="627" class="rpt_table" id="tbl_list_search">
        <thead>
	            <th width="35">SL</th>
	            <th width="70">Asset No</th>
	            <th width="80">Type</th>
	            <th width="70">Category</th>
                <th width="70">Service No</th>
	            <th width="70">Service Date</th>
	        </thead>
            <tbody>
        <?php
		if ($ex_data[2] == 0)	$aseet_type = "";		else	$aseet_type = " and a.asset_type='" . $ex_data[2] . "'";
	    if ($ex_data[3] == 0)	$category = "";			else	$category = " and a.asset_category='" . $ex_data[3] . "'";
		if ($ex_data[6] == 0)	$asset_number = "";		else	$asset_number = " and c.asset_no='" . $ex_data[6] . "'";
		if ($ex_data[8] == 0)	$service_schedule_ids = "";		else	$service_schedule_ids = " and  b.id not in(" . $ex_data[8] . ")";

	
	    $txt_date_from = $ex_data[4];
	    $txt_date_to = $ex_data[5];
		if ($txt_date_from != "" || $txt_date_to != ""){
			if ($db_type == 0)
			{
				$tran_date = " and b.service_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
			}
			if($db_type==2 || $db_type==1 )
			{
	            $tran_date = " and b.service_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
			}
		}
		
		$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
		$result = sql_select( "select a.id, a.company_id, a.asset_type, a.asset_category, b.id as scheduleDtlsId, b.asset_id, b.service_no, b.service_date, c.asset_no from fam_service_schedule_mst a,  fam_service_schedule_dtls b, fam_acquisition_sl_dtls c  where a.status_active=1 and a.is_deleted = 0 and a.id=b.mst_id and c.id=b.asset_id  and a.company_id='$ex_data[0]' $aseet_type $category $asset_number $tran_date $service_schedule_ids and b.is_send_for_repair!='1'");

            $i=1;
            foreach ($result as $row)
            {  
				if($row[csf('asset_no')] !== "")
				{
					if($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";	
				?>
					<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" id="search<?php echo $i;?>" onClick="js_set_value(<?php echo $i; ?>)"> 
						<td width="35" align="center">
							<?php echo $i; ?>
							 <input type="hidden" name="txt_individual_id" id="txt_individual_id<?php echo $i; ?>" value="<?php echo $row[csf('scheduleDtlsId')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
                             <input type="hidden" name="txt_hidden_asset_id" id="txt_hidden_asset_id<?php echo $i; ?>" value="<?php echo $row[csf('asset_id')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
						</td>
						<td width="70"><?php echo $row[csf('asset_no')]; ?></td>
                        <td width="80"><?php echo $asset_type[$row[csf('asset_type')]]; ?></td>
                        <td width="70"><?php echo $asset_category[$row[csf('asset_category')]]; ?></td>
                        <td width="70"><?php echo $row[csf('service_no')]; ?></td>
						<td width="70" align="center"><?php echo change_date_format($row[csf('service_date')]); ?>
                        </td>
					</tr>
				<?
					$i++;
				}
			}
        	?>
            </tbody>  
        </table>
    </div>
    <table width="627">
        <tr>
            <td align="center" >
                <input type="button" name="close" class="formbutton" value="Close" id="main_close" onClick="fnc_close();" style="width:100px" />
            </td>
        </tr>
    </table>
    
    <?php
	}
	
	else if($service_nature==3)
	{
	?>
    <div style="width:645px; max-height:280px; overflow-y:scroll" id="list_container_batch" align="left">	 
        <table cellspacing="0" cellpadding="0" border="1" rules="all" width="627" class="rpt_table" id="tbl_list_search">
        <thead>
	            <th width="35">SL</th>
	            <th width="70">Asset No</th>
	            <th width="80">Type</th>
	            <th width="70">Category</th>
                <th width="70">aaaaa</th>
	            <th width="70">ccccccc</th>
	        </thead>
            <tbody>
        <?php
		if ($ex_data[2] == 0)	$aseet_type = "";		else	$aseet_type = " and a.asset_type='" . $ex_data[2] . "'";
	    if ($ex_data[3] == 0)	$category = "";			else	$category = " and a.asset_category='" . $ex_data[3] . "'";
		if ($ex_data[6] == 0)	$asset_number = "";		else	$asset_number = " and c.asset_no='" . $ex_data[6] . "'";
		if ($ex_data[8] == 0)	$service_schedule_ids = "";		else	$service_schedule_ids = " and  b.id not in(" . $ex_data[8] . ")";

	
	    $txt_date_from = $ex_data[4];
	    $txt_date_to = $ex_data[5];
		if ($txt_date_from != "" || $txt_date_to != ""){
			if ($db_type == 0){
				$tran_date = " and b.service_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
			}else{
				$tran_date = " and b.service_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
			}
		}
		
		$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
		$result = sql_select( "select a.id, a.company_id, a.asset_type, a.asset_category, b.id as scheduleDtlsId, b.asset_id, b.service_no, b.service_date, c.asset_no from fam_service_schedule_mst a,  fam_service_schedule_dtls b, fam_acquisition_sl_dtls c  where a.status_active=1 and a.is_deleted = 0 and a.id=b.mst_id and c.id=b.asset_id and b.is_send_for_repair != 1 $company_id $aseet_type $category $asset_number $tran_date $service_schedule_ids");

            $i=1;
            foreach ($result as $row)
            {  
				if($row[csf('asset_no')] !== "")
				{
					if($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";	
				?>
					<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" id="search<?php echo $i;?>" onClick="js_set_value(<?php echo $i; ?>)"> 
						<td width="35" align="center">
							<?php echo $i; ?>
							<input type="hidden" name="txt_individual_id" id="txt_individual_id<?php echo $i; ?>" value="<?php echo $row[csf('scheduleDtlsId')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
							<input type="hidden" name="txt_hidden_asset_id" id="txt_hidden_asset_id<?php echo $i; ?>" value="<?php echo $row[csf('asset_id')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
						</td>
						<td width="70"><?php echo $row[csf('asset_no')]; ?></td>
                        <td width="80"><?php echo $asset_type[$row[csf('asset_type')]]; ?></td>
                        <td width="70"><?php echo $asset_category[$row[csf('asset_category')]]; ?></td>
                        <td width="70"><?php echo $row[csf('service_no')]; ?></td>
						<td width="70" align="center"><?php echo change_date_format($row[csf('service_date')]); ?>
                        </td>
					</tr>
				<?
					$i++;
				}
			}
        	?>
            </tbody>  
        </table>
    </div>
    <table width="627">
        <tr>
            <td align="center" >
                <input type="button" name="close" class="formbutton" value="Close" id="main_close" onClick="fnc_close();" style="width:100px" />
            </td>
        </tr>
    </table>
    
    <?php
	}  
}


if ($action == "show_searh_out_of_order_listview") 
{
	//echo $data; //die;
    $ex_data = explode("_", $data);
	?>
    <div style="width:645px; max-height:280px; overflow-y:scroll" id="list_container_batch" align="left">	 
        <table cellspacing="0" cellpadding="0" border="1" rules="all" width="627" class="rpt_table" id="tbl_list_search">
        <thead>
	            <th width="35">SL</th>
	            <th width="70">Asset No</th>
	            <th width="80">Type</th>
	            <th width="70">Category</th>
	            <th width="70">Disorder Date</th>
        </thead>
        <tbody>
        <?php
		if ($ex_data[2] == 0)	$aseet_type = "";		else	$aseet_type = " and c.asset_type='" . $ex_data[2] . "'";
	    if ($ex_data[3] == 0)	$category = "";			else	$category = " and c.asset_category='" . $ex_data[3] . "'";
		if ($ex_data[6] == 0)	$asset_number = "";		else	$asset_number = " and a.asset_no='" . $ex_data[6] . "'";
		if ($ex_data[8] == 0)	$outOfOrder_ids = "";		else	$outOfOrder_ids = " and  a.id not in(" . $ex_data[8] . ")";
	    $txt_date_from = $ex_data[4];
	    $txt_date_to = $ex_data[5];
		if ($txt_date_from != "" || $txt_date_to != "") 
		{
			if ($db_type == 0)
			{
				$tran_date = " and a.disorder_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
			}
			if($db_type==2 || $db_type==1 )
			{
	            $tran_date = " and a.disorder_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
			}
		}
		
	
		$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
		$result = sql_select( "select a.id, a.asset_no , a.asset_sl_id , a.disorder_date, c.asset_type, c.asset_category from fam_out_of_order_mst  a, fam_out_of_order_reason  b, fam_acquisition_sl_dtls c  where a.is_send_for_repair!=1 and a.status_active=1 and a.is_deleted = 0 and a.id=b.mst_id and a.asset_sl_id=c.id and a.company_id='$ex_data[0]' and c.asset_type not in(1,2,3,4) and a.is_send_for_repair!=1  $aseet_type $category $asset_number $tran_date  $outOfOrder_ids group by a.id, a.asset_no , a.asset_sl_id , a.disorder_date, c.asset_type, c.asset_category");

            $i=1;
            foreach ($result as $row)
            {  
				if($row[csf('asset_no')] !== "")
				{
					if($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";	
				?>
					<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" id="search<?php echo $i;?>" onClick="js_set_value(<?php echo $i; ?>)"> 
						<td width="35" align="center">
							<?php echo $i; ?>
							 <input type="hidden" name="txt_individual_id" id="txt_individual_id<?php echo $i; ?>" value="<?php echo $row[csf('id')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
                             <input type="hidden" name="txt_hidden_asset_id" id="txt_hidden_asset_id<?php echo $i; ?>" value="<?php echo $row[csf('asset_sl_id')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
						</td>
						<!-- <td width="80"><?php //echo $row[csf('entry_no')]; ?></td> -->
						<td width="70"><?php echo $row[csf('asset_no')]; ?></td>
                        <td width="80"><?php echo $asset_type[$row[csf('asset_type')]]; ?></td>
                        <td width="70"><?php echo $asset_category[$row[csf('asset_category')]]; ?></td>
						<td width="70" align="center"><?php echo change_date_format($row[csf('disorder_date')]); ?>
                        </td>
					</tr>
				<?
					$i++;
				}
			}
        	?>
            </tbody>  
        </table>
    </div>
    <table width="627">
        <tr>
            <td align="center">
                <input type="button" name="close" class="formbutton" value="Close" id="main_close" onClick="fnc_close();" style="width:100px" />
            </td>
        </tr>
    </table>
    <?php
   exit();
}


if ($action=="show_asset_active_listview")
{
	//echo $data; die;
	$data_row = explode('_',$data);
	
	if($data_row[2]==1)
	{
		
		$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");
		
		$sql = sql_select("select a.company_id, a.asset_type, a.asset_category, b.id, b.asset_id, b.service_no, b.service_date, c.asset_no, c.serial_no, d.specification, d.brand,  d.asset_group, d.purchase_date, d.origin from fam_service_schedule_mst a, fam_service_schedule_dtls b, fam_acquisition_sl_dtls c, fam_acquisition_mst d where   a.id=b.mst_id and b.asset_id=c.id and c.mst_id=d.id and b.id in($data_row[0]) and a.status_active=1 and a.is_deleted = 0 and b.status_active=1 and b.is_deleted = 0 and c.status_active=1 and c.is_deleted = 0 and d.status_active=1 and d.is_deleted = 0");
		//print_r($sql);die;
		
		if($data_row[1] != '') 
		$i=$data_row[1]; 
		else 
		$i = 0;
		
		foreach($sql as $row)
		{
			$i++;
			if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
			
			$tble_body .='<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle"><td width="30">'.$i.'</td><td id="" width="50">
			<input type="text" name="txtDuration[]" id="txtDuration_'.$i.'" onchange="calculateDate('.$i.')" class="text_boxes_numeric" style="width:50px"/></td><td id="" style="word-break:break-all;" width="85">
			<input type="text" name="returnableDate[]" id="returnableDate_'.$i.'" class="datepicker" style="width:85px"></td><td id="assetNo_'.$i.'" name="assetNo[]" width="90">
			'.$row[csf('asset_no')].'
			<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
			<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
			<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
			<input type="hidden" name="txtDeletedId[]" id="txtDeletedId_'.$i.'" value="'.$row[csf('dtls_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtDeletedId"/>
			</td><td  name="txtSpecification[]" id="txtSpecification_'.$i.'" style="word-break:break-all;" width="100">'.$row[csf('specification')].'
			</td><td id="" style="word-break:break-all;" width="100">
			'.$asset_type[$row[csf('asset_type')]].'
			</td><td id="" class="image_uploader" width="50"><a href="#">View</a>
			</td><td width="90" id="category_td">
			'.$asset_category[$row[csf('asset_category')]].'
			</td><td name="txt_asset_group[]" id="txt_asset_group_'.$i.'" style="word-break:break-all;" width="90">
			'.$row[csf('asset_group')].'
			</td><td name="txt_brand[]" id="txt_brand_'.$i.'" width="60" align="center">
			'. $row[csf('brand')].'
			</td><td id="" style="word-break:break-all;" width="100">
			'.$order_action_arr[$row[csf('action')]].'
			</td><td name="txt_purchase_date[]" id="txt_purchase_date_'.$i.'" style="word-break:break-all;" width="80">
			'.change_date_format($row[csf('purchase_date')]).'
			</td><td name="txt_serial_no[]" id="txt_serial_no_'.$i.'" style="word-break:break-all;" width="70" align="center">
			'.$row[csf('serial_no')].'
			</td><td id="" style="word-break:break-all;" width="80">
			'.$origin_array[$row[csf('origin')]].'
			</td><td id="button_1" align="center">
			<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
			</td></tr>';
	    }
		echo $tble_body;
		die;
	}
	
	else if($data_row[2]==2)
	{
		
	  $sql = sql_select("select a.id, a.asset_no , a.asset_sl_id , a.disorder_date , a.action , a.company_id, c.asset_type, c.asset_category, d.specification, d.brand, d.asset_group, d.purchase_date, d.origin, c.serial_no from fam_out_of_order_mst a,  fam_acquisition_sl_dtls c, fam_acquisition_mst d where a.asset_sl_id=c.id and c.mst_id=d.id and a.id in($data_row[0]) and  a.status_active=1 and a.is_deleted = 0  and c.status_active=1 and c.is_deleted = 0  and d.status_active=1 and d.is_deleted = 0 group by a.id, a.asset_no , a.asset_sl_id , a.disorder_date , a.action , a.company_id,  c.asset_type, c.asset_category, d.specification, d.brand, d.asset_group, d.purchase_date, d.origin, c.serial_no");

	$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");	
	
	if($data_row[1] != '') $i=$data_row[1]; else $i = 0;
	
	foreach($sql as $row)
	{
		$i++;
		if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
		
		$tble_body .='<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle"><td width="30">'.$i.'</td><td id="" width="50">
		<input type="text" name="txtDuration[]" id="txtDuration_'.$i.'" onchange="calculateDate('.$i.')" class="text_boxes_numeric" style="width:50px"/></td><td id="" style="word-break:break-all;" width="85">
		<input type="text" name="returnableDate[]" id="returnableDate_'.$i.'" class="datepicker" style="width:85px"></td><td id="assetNo_'.$i.'" name="assetNo[]" width="90">
		'.$row[csf('asset_no')].'
		<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
		<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_sl_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
		<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
		<input type="hidden" name="txtDeletedId[]" id="txtDeletedId_'.$i.'" value="'.$row[csf('dtls_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtDeletedId"/>
		</td><td  name="txtSpecification[]" id="txtSpecification_'.$i.'" style="word-break:break-all;" width="100">'.$row[csf('specification')].'
		</td><td id="" style="word-break:break-all;" width="100">
		'.$asset_type[$row[csf('asset_type')]].'
		</td><td id="" class="image_uploader" width="50" onClick="shwo_reason('.$row[csf('id')].')"><a href="#">View</a>
		</td><td width="90" id="category_td">
		'.$asset_category[$row[csf('asset_category')]].'
		</td><td name="txt_asset_group[]" id="txt_asset_group_'.$i.'" style="word-break:break-all;" width="90">
		'.$row[csf('asset_group')].'
		</td><td name="txt_brand[]" id="txt_brand_'.$i.'" width="60" align="center">
		'. $row[csf('brand')].'
		</td><td id="" style="word-break:break-all;" width="100">
		'.$order_action_arr[$row[csf('action')]].'
		</td><td name="txt_purchase_date[]" id="txt_purchase_date_'.$i.'" style="word-break:break-all;" width="80">
		'.change_date_format($row[csf('purchase_date')]).'
		</td><td name="txt_serial_no[]" id="txt_serial_no_'.$i.'" style="word-break:break-all;" width="70" align="center">
		'.$row[csf('serial_no')].'
		</td><td id="" style="word-break:break-all;" width="80">
		'.$origin_array[$row[csf('origin')]].'
		</td><td id="button_1" align="center">
		<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$row[csf('id')].')" />
		</td></tr>';
		}
		echo $tble_body;
		die;
	}
	
	else if($data_row[2]==3)
	{
	$sql = sql_select("select a.location, a.specification, a.asset_type, a.asset_category, a.asset_group, a.brand, a.origin, a.purchase_date, b.id as asset_id, b.asset_no, b.serial_no from fam_acquisition_mst a, fam_acquisition_sl_dtls b where a.id=b.mst_id and b.id in($data_row[0]) and a.status_active=1 and a.is_deleted = 0 and b.status_active=1 and b.is_deleted = 0");
	$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");	
	if($data_row[1] != '') 
		$i=$data_row[1]; 
	else 
		$i = 0;
	
		foreach($sql as $row)
		{
		$i++;
		if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
		
		$tble_body .='
		<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle"><td width="30">'.$i.'
	  		</td><td id="" width="50">
			<input type="text" name="txtDuration[]" id="txtDuration_'.$i.'" onchange="calculateDate('.$i.')" class="text_boxes_numeric" style="width:50px"/>
	  		</td><td id="" style="word-break:break-all;" width="85">
			<input type="text" name="returnableDate[]" id="returnableDate_'.$i.'" class="datepicker" style="width:85px">
	  		</td><td id="assetNo_'.$i.'" name="assetNo[]" width="90">
			'.$row[csf('asset_no')].'
			<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
	  		<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
	  		<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
			<input type="hidden" name="txtDeletedId[]" id="txtDeletedId_'.$i.'" value="'.$row[csf('dtls_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtDeletedId"/>
	  		</td><td  name="txtSpecification[]" id="txtSpecification_'.$i.'" style="word-break:break-all;" width="100">'.$row[csf('specification')].'
	  		</td><td id="" style="word-break:break-all;" width="100">'.$asset_type[$row[csf('asset_type')]].'
	  		</td><td id="" class="image_uploader" width="50"><a href="#">View</a>
	  		</td><td width="90" id="category_td">'.$asset_category[$row[csf('asset_category')]].'
	  		</td><td name="txt_asset_group[]" id="txt_asset_group_'.$i.'" style="word-break:break-all;" width="90">'.$row[csf('asset_group')].'
	  		</td><td name="txt_brand[]" id="txt_brand_'.$i.'" width="60" align="center">'. $row[csf('brand')].'
	  		</td><td id="" style="word-break:break-all;" width="100">'.$order_action_arr[$row[csf('')]].'
	  		</td><td name="txt_purchase_date[]" id="txt_purchase_date_'.$i.'" style="word-break:break-all;" width="80">'.change_date_format($row[csf('purchase_date')]).'
	  		</td><td name="txt_serial_no[]" id="txt_serial_no_'.$i.'" style="word-break:break-all;" width="70" align="center">'.$row[csf('serial_no')].'
	  		</td><td id="" style="word-break:break-all;" width="80">'.$origin_array[$row[csf('origin')]].'
	  		</td><td id="button_1" align="center"><input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
	  		</td>
		</tr>';
	    }
		echo $tble_body;
		die;
	}
	
}

if ($action == "show_reason_popup") 
{
	extract($_REQUEST);
	?>
        <table cellspacing="0" cellpadding="0" border="1" rules="all" width="480" class="rpt_table" id="tbl_reason">
        <thead>
	            <th width="35">SL</th>
	            <th width="445">Reason</th>
	        </thead>
            <tbody>
	        <?php
			$result = sql_select("select id, mst_id, reason from fam_out_of_order_reason  where mst_id='$mst_id'");
            $i=1;
            foreach ($result as $row)
            {  
				if($row[csf('mst_id')] !== "")
				{
					if($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";	
				?>
					<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" id="search<?php echo $i;?>"> 
						<td width="35" align="center"><?php echo $i; ?></td>
						<td width="445"><?php echo $row[csf('reason')]; ?></td>
					</tr>
				<?php
					$i++;
				}
			}
        	?>
            </tbody>  
        </table>
    <?php
	
   exit();
}

if ($action == "show_check_up_listview") 
{
    $ex_data = explode("_", $data);
	?>
    <div style="width:880px; max-height:210px; overflow-y:scroll" id="list_container_batch" align="left">	 
        <table cellspacing="0" cellpadding="0" border="1" rules="all" width="863" class="rpt_table" id="tbl_list_search">
        <thead>
	            <th width="35">SL</th>
	            <th width="70">Asset No</th>
	            <th width="80">Type</th>
	            <th width="70">Category</th>
	            <th width="70">Purchase Date</th>
	        </thead>
            <tbody>
        <?php
		//if ($ex_data[0] == 0)	$company_id = "";		else	$company_id = " and a.company_id='" . $ex_data[0] . "'";
		if ($ex_data[1] == 0)	$location = "";			else	$location = " and  a.location='" . $ex_data[1] . "'";
		if ($ex_data[2] == 0)	$aseet_type = "";		else	$aseet_type = " and a.asset_type='" . $ex_data[2] . "'";
	    if ($ex_data[3] == 0)	$category = "";			else	$category = " and a.asset_category='" . $ex_data[3] . "'";
		if ($ex_data[6] == 0)	$asset_number = "";		else	$asset_number = " and b.asset_no='" . $ex_data[6] . "'";
		if ($ex_data[8] == 0)	$asset_ids = "";		else	$asset_ids = " and  b.id not in(" . $ex_data[8] . ")";
	
	    $txt_date_from = $ex_data[4];
	    $txt_date_to = $ex_data[5];
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
		$result = sql_select( "select a.id, a.asset_type, a.asset_category, a.purchase_date, b.id as asset_id, b.asset_no, b.serial_no from fam_acquisition_mst a, fam_acquisition_sl_dtls b where a.status_active=1 and a.is_deleted = 0 and a.id=b.mst_id and a.company_id='$ex_data[0]' and a.asset_type not in(1,2,3,4) $location $aseet_type $category $asset_number $asset_ids $tran_date group by a.id, a.asset_type, a.asset_category, a.purchase_date, b.id, b.asset_no, b.serial_no");

            $i=1;
            foreach ($result as $row)
            {  
				if($row[csf('asset_no')] !== "")
				{
					if($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";	
				?>
					<tr bgcolor="<?php echo $bgcolor; ?>" style="text-decoration:none; cursor:pointer" id="search<?php echo $i;?>" onClick="js_set_value(<?php echo $i; ?>)"> 
						<td width="35" align="center">
							<?php echo $i; ?>
							 <input type="hidden" name="txt_individual_id" id="txt_individual_id<?php echo $i; ?>" value="<?php echo $row[csf('asset_id')]; ?>" class="text_boxes_numeric" style="width:25px;"/>
						</td>
						<td width="70"><?php echo $row[csf('asset_no')]; ?></td>
                        <td width="80"><?php echo $asset_type[$row[csf('asset_type')]]; ?></td>
                        <td width="70"><?php echo $asset_category[$row[csf('asset_category')]]; ?></td>
						<td width="70" align="center"><?php echo change_date_format($row[csf('purchase_date')]); ?>
                        </td>
					</tr>
				<?
					$i++;
				}
			}
        	?>
            </tbody>  
        </table>
    </div>
    <table width="880">
        <tr>
            <td align="center">
                <input type="button" name="close" class="formbutton" value="Close" id="main_close" onClick="fnc_close();" style="width:100px" />
            </td>
        </tr>
    </table>
    
    <?php
	
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
		
		$id=return_next_id( "id", "fam_send_for_repair_mst", 1 ) ;
		$dtls_id=return_next_id( "id", "fam_send_for_repair_dtls", 1 ) ;
		
		//return_mrr_number( $company, $location, $category, $year, $num_length, $main_query, $str_fld_name, $num_fld_name, $old_mrr_no )
		
		if($db_type==0) 
			$year_cond = " and YEAR(insert_date)=".date('Y',time())." "; 
		else 
			$year_cond = " and to_char(insert_date,'YYYY')=".date('Y',time())." "; 
		
		$new_entry_no=explode("*",return_mrr_number( str_replace("'","",$cbo_company_name),'','SFR',date("Y",time()),5,"select system_no_prefix,system_no_prefix_num from fam_send_for_repair_mst where company_id=$cbo_company_name $year_cond order by id desc ","system_no_prefix","system_no_prefix_num"));
		
		$field_array="id,system_no_prefix,system_no_prefix_num,system_no,company_id,out_date,send_to,service_nature,inserted_by,insert_date";
		$field_array_dtls="id, mst_id, duration, estm_returnable_date, asset_id, asset_number, acq_serv_outoforder_id, inserted_by, insert_date";
		
		$txtOutDate=str_replace("'","",$txt_out_date);
		if ($db_type == 0) $out_date = change_date_format($txtOutDate, 'yyyy-mm-dd');
		if ($db_type == 2) $out_date = change_date_format($txtOutDate, 'yyyy-mm-dd', '-', 1);
		
		
		$data_array="(".$id.",'".$new_entry_no[1]."','".$new_entry_no[2]."','".$new_entry_no[0]."',".$cbo_company_name.",'".$out_date."',".$cbo_sent_to.",".$cbo_serviceNature.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo "insert into fam_send_for_repair_mst($field_array) values $data_array";die;
		$sending_form_id='';
		for($j=1;$j<=$tot_row;$j++)
		{ 	
			$txtDuration="txtDuration_".$j;
			$returnableDate="returnableDate_".$j;
			$txtAssetNo="txtAssetNo_".$j;
			$txtAssetId="txtAssetId_".$j;
			$txtAcqServOutoforderId="txtAcqServOutoforderId_".$j;
			//$txtAcqServOutoforderId="txtAcqServOutoforderId_".$j;
			
			if($sending_form_id!="") $sending_form_id.=",";
			$sending_form_id.=$$txtAcqServOutoforderId;
			
			$returnDate=str_replace("'","",$$returnableDate);
			if ($db_type == 0) $returnDate = change_date_format($returnDate, 'yyyy-mm-dd');
		    if ($db_type == 2) $returnDate = change_date_format($returnDate, 'yyyy-mm-dd', '-', 1);
		
			if($data_array_dtls!="") $data_array_dtls.=",";
			$data_array_dtls.="(".$dtls_id.",".$id.",".$$txtDuration.",'".$returnDate."',".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqServOutoforderId.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
			$dtls_id = $dtls_id+1;
		}
		//echo "insert into fam_send_for_repair_dtls($field_array_dtls) values $data_array_dtls";die;
		$rID=sql_insert("fam_send_for_repair_mst",$field_array,$data_array,0);
		$rID1=sql_insert("fam_send_for_repair_dtls",$field_array_dtls,$data_array_dtls,0);
		$field_array_update="is_send_for_repair";
		
		$field_array_for_repair = "is_send_for_repair";
		if(str_replace("'","",$cbo_serviceNature)==1)
		{
			$rID3=sql_multirow_update("fam_service_schedule_dtls",$field_array_for_repair,"1","id","".$sending_form_id."",1);
		}
		else
		{
			$rID3=sql_multirow_update("fam_out_of_order_mst",$field_array_for_repair,"1","id","".$sending_form_id."",1);
		}
		
		//echo "10***".$rID."***".$rID1;die;
		if($db_type==0)
		{
			if($rID && $rID1)
			{
				mysql_query("COMMIT");  
				echo "0**".$new_entry_no[0]."**".$id;
			}
			else
			{
				mysql_query("ROLLBACK"); 
				echo "10**" . $new_entry_no[0];
			}
		}
		
		if($db_type==2 || $db_type==1 )
		{
			if($rID && $rID1)
			{
				oci_commit($con);
				echo "0**".$new_entry_no[0]."**".$id;
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
		
		//echo $txt_deleted_itmem_id; die;
		$con = connect();
		if($db_type==0)
		{
			mysql_query("BEGIN");
		}
		$id=return_next_id( "id", "fam_send_for_repair_mst", 1 ) ;
		$dtls_id=return_next_id( "id", "fam_send_for_repair_dtls", 1 ) ;
		
		$field_array_dtls="id, mst_id, duration, estm_returnable_date, asset_id, asset_number, acq_serv_outoforder_id, inserted_by, insert_date";
		//fam_send_for_repair_mst
		$field_array_update="company_id*out_date*send_to*service_nature*updated_by*update_date";
		$txtOutDate=str_replace("'","",$txt_out_date);
		if ($db_type == 0) $out_date = change_date_format($txtOutDate, 'yyyy-mm-dd');
		if ($db_type == 2) $out_date = change_date_format($txtOutDate, 'yyyy-mm-dd', '-', 1);
		$data_array_update="".$cbo_company_name."*'".$out_date."'*".$cbo_sent_to."*".$cbo_serviceNature."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		//fam_send_for_repair_dtls
		$field_array_dtls_update="duration*estm_returnable_date*updated_by*update_date";
		//$data_array_dtls_update="".$cbo_company_name."*".$txt_out_date."*".$cbo_sent_to."*".$cbo_serviceNature."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		//$update_id_array = array();
		$sending_form_id="";
		for($j=1;$j<=$tot_row;$j++)
		{ 	
			$txtDuration="txtDuration_".$j;
			$returnableDate="returnableDate_".$j;
			$txtAssetNo="txtAssetNo_".$j;
			$txtAssetId="txtAssetId_".$j;
			$txtAcqServOutoforderId="txtAcqServOutoforderId_".$j;
			$txtDeletedId="txtDeletedId_".$j;
			
			$returnDateArr=str_replace("'","",$$returnableDate);
			if ($db_type == 0) $returnDate = change_date_format($returnDateArr, 'yyyy-mm-dd');
		    if ($db_type == 2) $returnDate = change_date_format($returnDateArr, 'yyyy-mm-dd', '-', 1);
			
			$updateIds = str_replace("'","",$$txtDeletedId);
			
			if( $updateIds != '')
			{
				$update_id_array[]=$updateIds;
				$data_array_dtls_update[$updateIds]=explode("*",("".$$txtDuration."*'".$returnDate."'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'"));
				//print_r($data_array_dtls_update); die;
			}
			else
			{
				if($data_array_dtls!="") $data_array_dtls.=",";
				$data_array_dtls.="(".$dtls_id.",".$update_id.",".$$txtDuration.",'".$returnDate."',".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqServOutoforderId.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
				
				if($sending_form_id!="") $sending_form_id.=",";
				$sending_form_id.=$$txtAcqServOutoforderId;
				
				$dtls_id = $dtls_id+1;
			}
			
		}
		
		$field_array_for_update = "is_send_for_repair";
		$sendingFromId = str_replace("'","",$sending_form_id);
		if($sendingFromId != '')
		{
			if(str_replace("'","",$cbo_serviceNature)==1)
			{
				$rID8=sql_multirow_update("fam_service_schedule_dtls",$field_array_for_update,"1","id","".$sendingFromId."",1);
			}
			else
			{
				$rID8=sql_multirow_update("fam_out_of_order_mst",$field_array_for_update,"1","id","".$sendingFromId."",1);
			}
		
		}
		//print_r($data_array_dtls_update);die;
		
		//echo bulk_update_sql_statement( "fam_send_for_repair_dtls", "id", $field_array_dtls_update, $data_array_dtls_update, $update_id_array ); die;
		
		$rID=sql_update("fam_send_for_repair_mst",$field_array_update,$data_array_update,"id","".$update_id."",0);
		$rID1=execute_query(bulk_update_sql_statement( "fam_send_for_repair_dtls", "id", $field_array_dtls_update, $data_array_dtls_update, $update_id_array ));
		
		if($data_array_dtls != "")
		{
			$rID2=sql_insert("fam_send_for_repair_dtls", $field_array_dtls, $data_array_dtls,0);
			//echo "insert into fam_send_for_repair_dtls($field_array_dtls) values $data_array_dtls";
			
		
		}
		
		//echo "insert into fam_send_for_repair_mst($field_array_update) values $data_array_update"; //die;
		//echo bulk_update_sql_statement("fam_send_for_repair_dtls", "id", $field_array_dtls_update, $data_array_dtls_update, $update_id_array);//die;
		//$field_array_for_update = "is_send_for_repair";
		$deleted_itmem_id = str_replace("'","",$txt_deleted_itmem_id);
		if($deleted_itmem_id != '')
		{
			if(str_replace("'","",$cbo_serviceNature)==1)
			{
				$rID4=sql_multirow_update("fam_service_schedule_dtls",$field_array_for_update,"0","id","".$deleted_itmem_id."",1);
			}
			else
			{
				$rID4=sql_multirow_update("fam_out_of_order_mst",$field_array_for_update,"0","id","".$deleted_itmem_id."",1);
			}
		}
		 
		
		$field_array_delete="status_active*is_deleted*updated_by*update_date";
		$data_array_delete="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		$deleted_id = str_replace("'","",$txt_deleted_id);
		//echo $deleted_itmem_id; die;
		if($deleted_id != '')
		{
	  		$rID6=sql_multirow_update("fam_send_for_repair_dtls",$field_array_delete,$data_array_delete,"id","".$deleted_id."",1);
		}
		
		
		
		
		
		
		
		
		//echo $deleted_itmem_id; die;
		
		//$rID1=sql_update("fam_send_for_repair_dtls",$field_array_dtls_update,$data_array_dtls_update,"id","".$update_id."",0);
		
		
		
		//echo "insert into fam_send_for_repair_dtls $field_array_dtls_update value($data_array_dtls_update)"; die;
		
		//$field_array="id,system_no_prefix,system_no_prefix_num,system_no,company_id,out_date,send_to,service_nature,inserted_by,insert_date";
		//$field_array_dtls="id, mst_id, duration, estm_returnable_date, asset_id, asset_number, acq_serv_outoforder_id, inserted_by, insert_date";
		
		//$txt_entry_no=str_replace("'","",$txt_entry_no);
		/*$field_array_for_repair = "is_send_for_repair";
		if(str_replace("'","",$cbo_serviceNature)==1)
		{
			if($deleted_ids != ""){
			$rID5=sql_multirow_update("fam_service_schedule_dtls",$field_array_for_repair,"0","id","".$deleted_ids."",'');
			}
		}
		else
		{
			if($deleted_ids != ""){
			$rID5=sql_multirow_update("fam_out_of_order_mst",$field_array_for_repair,"0","id","".$deleted_ids."",'');
			}
		}
		
		*/
		
		//echo "10***".$rID."***".$rID1."***".$rID2."***".$rID3."***50***".$$rID5."***10".$rID4;die;
		
		$update_id=str_replace("'","",$update_id);
		
		if($db_type==0)
		{
			if($rID)
			{
				mysql_query("COMMIT");  
				echo "1**" . $txt_entry_no . "**" . $update_id . "**" . $id;
			}
			else
			{
				mysql_query("ROLLBACK"); 
                echo "10**" . $txt_entry_no . "**" . $update_id;
			}
		}
		if($db_type==2 || $db_type==1 )
		{
			if($rID)
			{
				oci_commit($con);
				echo "1**" . $txt_entry_no . "**" . $update_id . "**" . $id;
			}
			else 
			{
                oci_rollback($con);
                echo "10**" . $txt_entry_no . "**" . $update_id;
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
		$rID=sql_delete("fam_send_for_repair_mst",$field_array,$data_array,"id","".$update_id."",1);
		$rID1=sql_delete("fam_send_for_repair_dtls",$field_array,$data_array,"mst_id","".$update_id."",1);
		
		$txt_entry_no=str_replace("'","",$txt_entry_no);
		$update_id=str_replace("'","",$update_id);
		
		
		if ($db_type == 0) {
            if ($rID && $rID1) {
                mysql_query("COMMIT");
                echo "2**".$txt_entry_no."**".$update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" .$txt_entry_no."**".$update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1) {
                oci_commit($con);
                echo "2**".$txt_entry_no."**".$update_id;
            } else {
                oci_rollback($con);
                echo "10**" .$txt_entry_no."**".$update_id;
            }
        }
		disconnect($con);
	}
// Delete Here End ----------------------------------------------------------
}


//Print
if ($action=="print_sent_for_repier_dtls")
{
	//echo "$data";die;
	$data = explode("*",$data);
	$company = $data[0];
	$update_id = $data[1];
	$report_title = $data[2];
	$systemNo = $data[3];
	
	$country_arr=return_library_array( "select id, country_name from  lib_country", "id", "country_name");
	$companyID_arr=return_library_array( "select master_tble_id , image_location  from common_photo_library  where   is_deleted = 0", "master_tble_id", "image_location"  );
	$supplier_arr=return_library_array( "select id,supplier_name from lib_supplier", "id", "supplier_name");
	
	$company_array=array();
	$company_name=sql_select("select id, company_name from lib_company");
	
	foreach($company_name as $row){
	$company_array[$row[csf('id')]]=$row[csf('company_name')];
	}
	
	
	//print_r($companyID_arr); die;
	
	?>
	<div style="width:700;">
		<table width="700" cellspacing="0" align="" >
			<tr>
				<td colspan="8" align="center" style="font-size:20px"><strong><? echo $company_array[$data[0]]; ?></strong></td>
			</tr>
			<tr class="form_caption">
				<td  colspan="8" align="center" style="font-size:14px">  
				<?php 
				//Report Header Address
				$nameArray=sql_select( "select plot_no,level_no,road_no,block_no,country_id,province,city,zip_code,email,website from lib_company where id=$data[0]"); 
				foreach ($nameArray as $result)
				{ 
				?>
					<?php echo $result[csf('plot_no')]; ?> 
					,<?php echo $result[csf('level_no')]?>
					,<?php echo $result[csf('road_no')]; ?> 
					,<?php echo $result[csf('block_no')];?> 
					,<?php echo $result[csf('city')];?> 
					,<?php echo $result[csf('zip_code')]; ?> 
					,<?php echo $result[csf('province')];?> 
					,<?php echo $country_arr[$result[csf('country_id')]]; ?><br> 
					Email Address : <?php echo $result[csf('email')];?>, 
					Website No: <?php echo $result[csf('website')];
				}
				?> 
				</td>  
			</tr>
			
			<tr>
				<td colspan="8" align="center" style="font-size:16px;"><strong style="text-decoration:underline;"><?php echo $report_title; ?></strong></td>
			</tr>
			<?php
			//echo "select id, system_no, company_id, out_date, send_to, service_nature from fam_send_for_repair_mst where status_active=1 and  is_deleted = 0 and id='$update_id'"; die;
			$company_data_array=sql_select("select id, system_no, company_id, out_date, send_to, service_nature from fam_send_for_repair_mst where status_active=1 and  is_deleted = 0 and id='$update_id'");
			foreach($company_data_array as $com_row)
			{
			//echo $com_row[csf('asset_type')]; die;	
			?>
			<tr>
				<td width="115"><strong>Service Nature :</strong></td> 	<td width="120px"><?php echo $asset_type[$com_row[csf('service_nature')]]; ?></td>
				<td width="90"><strong>Out Date :</strong></td> 		<td width="120px"><?php echo change_date_format($com_row[csf('out_date')]); ?></td>
				<td width="90"><strong>Send To :</strong></td> 	<td width="120px"><?php echo $supplier_arr[$com_row[csf('send_to')]]; ?></td>
			</tr>
			
            <tr>
                <td  align="left" style="font-size:16px;"><strong>System No :</strong></td><td colspan="3" align="left"  id="barcode_img_id"></td> 
			</tr>
			<?php 
			}
			?>
		</table>
		<br>
		<div style="width:100%;">
			<table align="" cellspacing="0" width="700"  border="1" rules="all" class="rpt_table" >
			<thead bgcolor="#dddddd" align="center">
				<th width="30">SL</th>
				<th width="150" >Asset No</th>
                <th width="300" >Asset Description </th>
				<th width="150" >Service No</th>
				<th width="150" >Service Date</th>
			</thead>
			<tbody> 
			<?php
			$asset_array=return_library_array( "select id,asset_no from fam_acquisition_sl_dtls", "id", "asset_no"  );
			//$service_schedule_arr=sql_select("select id, mst_id, duration, estm_returnable_date, asset_id, asset_number, acq_serv_outoforder_id from fam_send_for_repair_dtls where status_active=1 and is_deleted=0 and mst_id ='$update_id'");
			
			echo "select a.id, a.mst_id, a.duration, a.estm_returnable_date, a.asset_id, a.asset_number, a.acq_serv_outoforder_id,  c.asset_category, c.asset_group, c.asset_type, c.specification from fam_send_for_repair_dtls a, fam_acquisition_sl_dtls b, fam_acquisition_mst  c where a.status_active=1 and a.is_deleted=0 and a.acq_serv_outoforder_id=b.id and b.mst_id=c.id and a.mst_id ='$update_id'"; die;
			
			$service_schedule_arr=sql_select("select a.id, a.mst_id, a.duration, a.estm_returnable_date, a.asset_id, a.asset_number, a.acq_serv_outoforder_id,  c.asset_category, c.asset_group, c.asset_type, c.specification from fam_send_for_repair_dtls a, fam_acquisition_sl_dtls b, fam_acquisition_mst  c where a.status_active=1 and a.is_deleted=0 and a.acq_serv_outoforder_id=b.id and b.mst_id=c.id and a.mst_id ='$update_id'");
			
			$i=0;
			foreach($service_schedule_arr as $ssval)
			{
			if ($i%2==0)  $bgcolor="#E9F3FF";	else $bgcolor="#FFFFFF";
			
			$i++;
			
			?>
			<tr bgcolor="<?php echo $bgcolor; ?>">
				<td align="center"><?php echo $i; ?></td>
				<td  align="center"><?php echo $ssval[csf("asset_number")]; ?></td>
				<td  align="left" style="word-break:break-all;"><?php echo $asset_type[$ssval[csf("asset_type")]].", ". $asset_category[$ssval[csf("asset_category")]].", ". $ssval[csf("asset_group")].", ". $ssval[csf("specification")]; ?></td>
				<td align="center"><?php echo $ssval[csf("duration")]; ?></td>
				<td  align="center"><?php echo change_date_format($ssval[csf("estm_returnable_date")]); ?></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			</table>
		<br>
		</div>
	</div> 
	<script type="text/javascript" src="../../js/jquery.js"></script>
	<script type="text/javascript" src="../../js/jquerybarcode.js"></script>
	<script>
	function generateBarcode( valuess ){
	   
		var value = valuess;//$("#barcodeValue").val();
	 	//alert(value)
		var btype = 'code39';//$("input[name=btype]:checked").val();
		var renderer ='bmp';// $("input[name=renderer]:checked").val();
		 
		var settings = {
		  output:renderer,
		  bgColor: '#FFFFFF',
		  color: '#000000',
		  barWidth: 1,
		  barHeight: 30,
		  moduleSize:5,
		  posX: 10,
		  posY: 20,
		  addQuietZone: 1
		};
		//$("#barcode_img_id").html('Reaz666666666'); return;
		 value = {code:value, rect: false};
		 //alert(value);
		$("#barcode_img_id").show().barcode(value, btype, settings);
	} 
	generateBarcode('<? echo $data[3]; ?>');
	</script>
	<?
	exit();
}




































//======Test=====================

//For testing Update Query
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

function sql_multirow_update_a($strTable,$arrUpdateFields,$arrUpdateValues,$arrRefFields,$arrRefValues, $commit)
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
	
	//$arrRefFields=explode("*",$arrRefFields);
	//$arrRefValues=explode("*",$arrRefValues);	
	$strQuery .= $arrRefFields." in (".$arrRefValues.")";
	 
   return $strQuery; die;
	$result=mysql_query($strQuery); 
	$_SESSION['last_query']=$_SESSION['last_query'].";;".$strQuery;
	if ($commit==1)
	{
		$pc_time= add_time(date("H:i:s",time()),360);  
		$pc_date = date("Y-m-d",strtotime(add_time(date("H:i:s",time()),360)));
		
		$strQuery= "INSERT INTO activities_history ( session_id,user_id,ip_address,entry_time,entry_date,module_name,form_name,query_details,query_type) VALUES ('".$_SESSION['logic_erp']["history_id"]."','".$_SESSION['logic_erp']["user_id"]."','".$_SESSION['logic_erp']["pc_local_ip"]."','".$pc_time."','".$pc_date."','".$_SESSION["module_id"]."','".$_SESSION['menu_id']."','".encrypt($_SESSION['last_query'])."','1')"; 

		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		 
		$result111=mysql_query($strQuery); 
		$_SESSION['last_query']="";
	}
	//return $strQuery; die;
		return $result;
	die;
}