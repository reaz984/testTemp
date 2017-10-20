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

if ($action == "load_drop_down_location") {
    echo create_drop_down("cbo_location", 170, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$data' order by location_name", "id,location_name", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "2", "", "");
    exit();
}

//==========Search Asset No=================
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
			
			$('#hidden_fuel_ids').val( id );
		}
		
		function fnc_close()
		{
			//alert($('#hidden_fuel_ids').val()); return;
			parent.emailwindow.hide();
		}
		
		function reset_hide_field()
		{
			$('#hidden_fuel_ids').val( '' );
			selected_id = new Array();
		}
	
    </script>
    </head>
    <body>
  
        <div align="center" style="width:100%;" >
            <form name="searchSendForRep_1"  id="searchSendForRep_1" autocomplete="off">
                <table width="705" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>               	 
                            <th width="120">Asset No</th>
                            <th width="150" align="center">Company</th>
                            <th width="110" align="center">Location</th>
                            <th width="110" align="center">Category</th>
                            <th width="80">
                            <input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  />
                            </th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="asset_number" id="asset_number" style="width:120px;" class="text_boxes">
                            </td>
                             <td width="148">
                                <?php
                                echo create_drop_down("cbo_company_name", 148, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "--- Select ---", $cbo_company_id, "load_drop_down( 'fuel_and_lubricant_consumption_controller', this.value, 'load_drop_down_location', 'location_td' );", "0", "", "", "", "", "1", "", "");
                                ?>  
                            </td>
                            <td width="170" id="location_td">
                                <?php
                                echo create_drop_down("cbo_location", 170, $blank_array, "", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "2", "", "");
                                ?>
                            </td>
                            
                            <td width="170">
                                <?php
									echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "131,132,133,134,135,136,137,138,139,140", "", "", "", "4", "", "");
                                ?>	
                            </td>
                            

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('asset_number').value + '_' + document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_location').value + '_' + document.getElementById('cbo_category').value, 'show_searh_fuel_listview', 'searh_list_view', 'fuel_and_lubricant_consumption_controller', 'setFilterGrid(\'tbl_list_search\',-1)')" style="width:70px;" />		
                            </td>
                        </tr> 
                        <tr> 
	                        <input type="hidden" name="hidden_fuel_ids" id="hidden_fuel_ids" style="width:90px;" class="text_boxes">
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


//=======show_searh_sent_for_repiar_listview
if ($action == "show_searh_fuel_listview") 
{
	//echo $data; die;
    $ex_data = explode("_", $data);
	?>
	<div style="width:705px; max-height:300px; overflow-y:scroll" id="list_container_batch" align="left">	 
	<table cellspacing="0" cellpadding="0" border="1" rules="all" width="688" class="rpt_table" id="">
	<thead>
		<th width="35">SL</th>
        <th width="120">Vehicle Name</th>
		<th width="70">Asset No</th>
		<th width="70">Category</th>
	</thead>
    </table>
    <table cellspacing="0" cellpadding="0" border="1" rules="all" width="685" class="rpt_table" id="tbl_list_search">
	<tbody id="">
	<?php
	$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
	$supplier_arr=return_library_array( "select id,supplier_name from lib_supplier", "id", "supplier_name");
		
	if ($ex_data[0] == 0)	$assetNumber = "";	else	$assetNumber = " and b.asset_no LIKE '%" . $ex_data[0] . "'";
	if ($ex_data[1] == 0)	$companyId = "";	else	$companyId = " and a.company_id='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0)	$location = "";		else	$location = " and  a.location='" . $ex_data[2] . "'";
	if ($ex_data[3] == 0)	$category = "";		else	$category = " and a.asset_category='" . $ex_data[3] . "'";
	
	$sql = "select a.company_id, a.location , a.asset_category , b.id, b.mst_id, b.asset_no, b.reg_no from fam_acquisition_mst  a, fam_acquisition_sl_dtls  b where a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and a.id = b.mst_id and a.asset_type='10' $assetNumber $companyId $location $category";
	
	//$sql = "select a.company_id, a.out_date, a.send_to, a.service_nature, b.id, b.estm_returnable_date, b.asset_id, b.asset_number  from fam_send_for_repair_mst a, fam_send_for_repair_dtls b, fam_acquisition_sl_dtls  c where a.status_active=1 and  a.is_deleted=0 and b.status_active=1 and  b.is_deleted=0 and c.status_active=1 and  c.is_deleted=0 and b.is_repair_back !='1' and b.asset_id = c.id and c.asset_type not in(10) and a.id = b.mst_id and a.company_id='$ex_data[0]' $assetNumber $serviceNature $tran_date $sendForeRepId $returnForm";
	
	
	//echo $sql; die;
	
	$result = sql_select($sql);
	
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
				</td>
                <td width="120" align="center"><?php echo $row[csf('reg_no')]; ?></td>
				<td width="100" align="center"><?php echo $row[csf('asset_no')]; ?></td>
				<td width="80" align="center"><?php echo $asset_category[$row[csf('asset_category')]]; ?></td>
			</tr>
		<?
			$i++;
		}
	}
	?>
	</tbody>  
	</table>
	</div>
	<table width="685">
	<tr>
	<td align="center" >
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
	
	//$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");
	$query = "select a.company_id, a.location , a.asset_category, a.asset_group, a.specification, b.id, b.mst_id, b.asset_no from fam_acquisition_mst  a, fam_acquisition_sl_dtls  b where a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and a.id = b.mst_id and a.asset_type='10'and b.id in($data_row[0])";
	//print_r($sql);die;
	
	//$fuel_type = array(1=>'CNG',2=>'Octane',3=>'Petrol',4=>'Diesel',5=>'Engine Oil',6=>'Gear Oil',7=>'Brake Oil');
	
	$sql = sql_select($query);
	
	if($data_row[1] != '') $i=$data_row[1]; else $i = 0;
	
	foreach($sql as $row)
	{
		$i++;
		if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
		
		$tble_body .='
		<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle">
			
			<td align="center" width="30">
				<input type="text" name="txtsl[]" id="txtsl_'.$i.'" value="'.$i.'" class="text_boxes" style="width:30px" readonly  disabled/>
				<input type="hidden" id="hiddenExtraTr_'.$i.'" name="hiddenExtraTr[]"  value="'.$i.'"style="width:30px;" />
			</td>
						
	  		<td width="100">
		  		'.$row[csf('asset_no')].'
				
		  		<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px"/>
		  		<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtAcqMstId[]" id="txtAcqMstId_'.$i.'" value="'.$row[csf('mst_id')].'" class="text_boxes" style="width:90px"/>
				
				<input type="hidden" name="txtLocation[]" id="txtLocation_'.$i.'" value="'.$row[csf('location')].'" class="text_boxes_numeric" style="width:90px"/>
				
				<input type="hidden" name="txtAssetCategory[]" id="txtAssetCategory_'.$i.'" value="'.$row[csf('asset_category')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtAssetGroup[]" id="txtAssetGroup_'.$i.'" value="'.$row[csf('asset_group')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtSpecification[]" id="txtSpecification_'.$i.'" value="'.$row[csf('specification')].'" class="text_boxes" style="width:90px" />
				
				<input type="hidden" name="txtRepierId[]" id="txtRepierId_'.$i.'" value="" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtupdateIds[]" id="txtupdateIds_'.$i.'" value="" class="text_boxes" style="width:90px"/>
			</td>
			<td width="100">
				'.create_drop_down( "cboFuelType_$i", 100, $fuel_type,"", 1, "--- Select ---", $selected, "",'',"","","","","4","","cboFuelType[]").'
	  		</td>
	  		<td id="" width="100" style="word-break:break-all;" >
	  			<input type="text" name="txtBrand[]" id="txtBrand_'.$i.'" value="" class="text_boxes" style="width:100px"  placeholder="Write"/>
	  		</td>
	  		<td width="50">
	  			<input type="text" name="txtQty[]" id="txtQty_'.$i.'" value="" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:50px"  placeholder="Qty"/>
	  		</td>
	  		<td width="50">
	  			<input type="text" name="txtRate[]" id="txtRate_'.$i.'" value="" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:50px"  placeholder="write"/>
	  		</td>
	  		<td width="80">
	  			<input type="text" name="txtAmount[]" id="txtAmount_'.$i.'" value="" class="text_boxes_numeric" readonly disabled style="width:80px"  />
	  		</td>
			<td id="" width="150" style="word-break:break-all;" >
	  			<input type="text" name="txtFillingStation[]" id="txtFillingStation_'.$i.'" value="" class="text_boxes" style="width:150px"  placeholder="Write"/>
	  		</td>
			<td id="" width="80" style="word-break:break-all;" >
	  			<input type="text" name="txtMemoNo[]" id="txtMemoNo_'.$i.'" value="" class="text_boxes" style="width:80px"  placeholder="Write"/>
	  		</td>
			<td id="" width="80" style="word-break:break-all;" >
	  			<input type="text" name="txtKmRun[]" id="txtKmRun_'.$i.'" value="" class="text_boxes" style="width:80px"  placeholder="Write"/>
				<input type="hidden" name="txtKmRunId[]" id="txtKmRunId_'.$i.'" value="" class="text_boxes" style="width:80px"  placeholder="Write"/>
	  		</td>
	  		<td id="button_1" align="center">
				<input type="button" id="increase_'.$i.'" name="increase[]" style="width:30px" class="formbuttonplasminus" value="+" onClick="fn_addRow('.$i.')" />
		  		<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
	  		</td>
		</tr>';
	}
	echo $tble_body;die;
}


if($action=="populate_data_from_data")
{
	$sql = "select id, system_no, company_id, filling_date from fam_vm_fuel_consumption_mst where status_active=1 and is_deleted=0 and entry_form='1' and id=$data";
	//echo $sql; die;
	$res = sql_select($sql);	
	if($data != '') $i=$data; else $i = 0;
	
	
	foreach($res as $row)
	{		
		echo "$('#txt_system_id').val('".$row[csf("system_no")]."');\n";
		echo "$('#cbo_company_name').val(".$row[csf("company_id")].");\n";
		echo "$('#cbo_company_name').attr('disabled','true')".";\n";
		//echo "$('#cboServiceNature').val(".$row[csf("service_nature")].");\n";
		//echo "$('#cboServiceNature').attr('disabled','true')".";\n";
		//echo "$('#cbo_return_form').val(".$row[csf("return_form")].");\n";
		//echo "$('#cbo_return_form').attr('disabled','true')".";\n";
		echo "$('#txt_consumption_date').val('".change_date_format($row[csf("filling_date")])."');\n";
		//echo "$('#txt_return_date').attr('disabled','true')".";\n";
		echo "$('#update_id').val(".$row[csf("id")].");\n";
		echo "myFunction(".$row[csf("id")].");\n";
		echo "set_button_status(1, permission,'fnc_fuel_consumption_entry',1);\n";
  	}
	exit();	
}

if($action == "show_update_form_listview")
{
	//echo $data; die;
	$data_row = explode('_',$data);
	
	$k_m_run_array=return_library_array("select fuel_dtls_id, k_m_run from fam_vm_kilometer_log_dtls where status_active=1 and is_deleted=0 and mst_id ='$data_row[0]'", "fuel_dtls_id", "k_m_run");
	$k_m_run_id_array=return_library_array("select fuel_dtls_id, id from fam_vm_kilometer_log_dtls where status_active=1 and is_deleted=0 and mst_id ='$data_row[0]'", "fuel_dtls_id", "id");
	
	$sql_q = "select b.id, b.asset_id, b.asset_no, b.acq_mst_id, b.location, b.asset_category, b.asset_group, b.specification, b.fuel_type, b.brand, b.qty, b.rate, b.amount,  b.filling_station, b.memo_no from fam_vm_fuel_consumption_mst a, fam_vm_fuel_consumption_dtls b  where a.id=b.mst_id and a.entry_form='1' and a.status_active=1 and a.is_deleted=0 and b.status_active=1 and b.is_deleted=0 and a.id=$data_row[0]";
	//echo $sql_q; die;
	$sql = sql_select($sql_q);
	//print_r($sql);die;
	if($data_row[1] != '') $i=$data_row[1]; else $i = 0;
	
	foreach($sql as $row)
	{
		$i++;
		if ($i%2==0) $bgcolor="#E9F3FF"; else $bgcolor="#FFFFFF";
		
		$tble_body .='
		<tr bgcolor="'.$bgcolor.'" id="tr_'.$i.'" align="center" valign="middle">
			
			<td align="center" width="30">
				<input type="text" name="txtsl[]" id="txtsl_'.$i.'" value="'.$i.'" class="text_boxes" style="width:30px" readonly />
				<input type="hidden" id="hiddenExtraTr_'.$i.'" name="hiddenExtraTr[]"  value="'.$i.'"style="width:30px;" />
			</td>
						
	  		<td width="100">
		  		'.$row[csf('asset_no')].'
		  		<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px"/>
		  		<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtAcqMstId[]" id="txtAcqMstId_'.$i.'" value="'.$row[csf('acq_mst_id')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtLocation[]" id="txtLocation_'.$i.'" value="'.$row[csf('location')].'" class="text_boxes_numeric" style="width:90px"/>
				<input type="hidden" name="txtAssetCategory[]" id="txtAssetCategory_'.$i.'" value="'.$row[csf('asset_category')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtAssetGroup[]" id="txtAssetGroup_'.$i.'" value="'.$row[csf('asset_group')].'" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtSpecification[]" id="txtSpecification_'.$i.'" value="'.$row[csf('specification')].'" class="text_boxes" style="width:90px" />
				<input type="hidden" name="txtRepierId[]" id="txtRepierId_'.$i.'" value="" class="text_boxes" style="width:90px"/>
				<input type="hidden" name="txtupdateIds[]" id="txtupdateIds_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px"/>
			</td>
			<td width="100">
				'.create_drop_down( "cboFuelType_$i", 100, $fuel_type,"", 1, "--- Select ---", $row[csf('fuel_type')], "",'',"","","","","4","","cboFuelType[]").'
	  		</td>
	  		<td id="" width="100" style="word-break:break-all;" >
	  			<input type="text" name="txtBrand[]" id="txtBrand_'.$i.'" value="'.$row[csf('brand')].'" class="text_boxes" style="width:100px"  placeholder="Write"/>
	  		</td>
	  		<td width="50">
	  			<input type="text" name="txtQty[]" id="txtQty_'.$i.'" value="'.$row[csf('qty')].'" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:50px"  placeholder="Qty"/>
	  		</td>
	  		<td width="50">
	  			<input type="text" name="txtRate[]" id="txtRate_'.$i.'" value="'.$row[csf('rate')].'" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:50px"  placeholder="write"/>
	  		</td>
	  		<td width="80">
	  			<input type="text" name="txtAmount[]" id="txtAmount_'.$i.'" value="'.$row[csf('amount')].'" class="text_boxes_numeric" readonly disabled style="width:80px"  />
	  		</td>
			<td id="" width="150" style="word-break:break-all;" >
	  			<input type="text" name="txtFillingStation[]" id="txtFillingStation_'.$i.'" value="'.$row[csf('filling_station')].'" class="text_boxes" style="width:150px"  placeholder="Write"/>
	  		</td>
			<td id="" width="80" style="word-break:break-all;" >
	  			<input type="text" name="txtMemoNo[]" id="txtMemoNo_'.$i.'" value="'.$row[csf('memo_no')].'" class="text_boxes" style="width:80px"  placeholder="Write"/>
	  		</td>
			<td id="" width="80" style="word-break:break-all;" >
	  			<input type="text" name="txtKmRun[]" id="txtKmRun_'.$i.'" value="'.$k_m_run_array[$row[csf('id')]].'" class="text_boxes" style="width:80px"  placeholder="Write"/>
				<input type="hidden" name="txtKmRunId[]" id="txtKmRunId_'.$i.'" value="'.$k_m_run_id_array[$row[csf('id')]].'" class="text_boxes" style="width:80px"  placeholder="Write"/>
	  		</td>
	  		<td id="button_1" align="center">
				<input type="button" id="increase_'.$i.'" name="increase[]" style="width:30px" class="formbuttonplasminus" value="+" onClick="fn_addRow('.$i.')" />
		  		<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
	  		</td>
		</tr>';
	}
	echo $tble_body;die;
}

//Search Vehicle 
if($action == "search_saved_data")
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
        	<table width="600" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
            	<thead>
                	<th width="150">System Number</th>
                    <th width="150">Company</th>
                    <th width="170">Felling Date Range</th>
                    <th width="" >
                    	<input type="reset" name="re_button" id="re_button" value="Reset" style="width:100px" class="formbutton"/>
                     </th> 
                </thead>
                <tbody>
                	<tr>
                    	<td>
                            <input type="text" id="txt_system_number" name="txt_system_number" class="text_boxes" style="width:150px;" >
                        </td>
                        <td>
                            <?php
								echo create_drop_down("cbo_company_name", 150, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "--- Select ---", $cbo_company_id, "", "0", "", "", "", "", "1", "", "");
							?>  
                        </td>
                        <td>
                        	<input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" /> -
                            <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" />
                        </td>
                        <td align="center">
                        	<input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('txt_system_number').value + '_' + document.getElementById('cbo_company_name').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value, 'show_searh_system_id_listview', 'searh_system_id_listview', 'fuel_and_lubricant_consumption_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:100px;" />	
                        </td>
                    </tr>
                    <tr>                  
                        <td align="center" height="30" valign="middle" colspan="4">
                            <?php echo load_month_buttons(1); ?>
                        </td>
                    </tr> 
                </tbody>
                <tfoot>
                	<input type="hidden" id="hidden_system_id" name="hidden_system_id" class="text_boxes" value="<?php //echo $updateID; ?>" style="width:90px;">
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


//Search Return ID  --  List View
if($action == "show_searh_system_id_listview")
{
	//echo $data; die;
	$ex_data = explode("_", $data);
	if ($ex_data[0] == '')	$system_number = "";		else	$system_number = " and system_no LIKE '%" . $ex_data[0] . "'";
	if ($ex_data[1] == 0)	$company_id = "";			else	$company_id = " and company_id='" . $ex_data[1] . "'";
	
	$txt_date_from = $ex_data[2];
	$txt_date_to = $ex_data[3];
	
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0) $tran_date = " and filling_date '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		else $tran_date = " and filling_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
	}
	
	$company_location = return_library_array("select id,company_name  from lib_company where status_active =1 and is_deleted=0", "id", "company_name");
	$supplier_array = return_library_array("select id,supplier_name from lib_supplier where status_active=1 and is_deleted=0 order by supplier_name", "id","supplier_name");
	$arr=array (1=>$company_location,2=>$service_nature_arr,4=>$supplier_array);
	
	$sql = "select id, system_no, company_id, filling_date from fam_vm_fuel_consumption_mst where  status_active=1 and is_deleted=0 and entry_form='1' $system_number $company_id $tran_date order by id asc";
	//echo $sql; die;
	echo  create_list_view("list_view", "System ID,Company Name, Filling Date", "120,150,100","600","350",0, $sql , "js_set_value", "id", "", 1, "0,company_id,0", $arr , "system_no,company_id,filling_date","requires/fuel_and_lubricant_consumption_controller",'','0,0,3') ;
	
	exit();
}


//==================================================================================
//=== START : SAVE-UPDATE-DELETE Fuel and Lubricant Consumption  ===============
//==================================================================================

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
		
		if($db_type==0) $year_cond = " and YEAR(insert_date)=".date('Y',time())." "; 
		else $year_cond = " and to_char(insert_date,'YYYY')=".date('Y',time())." "; 
		//===============Fuel and Lubricant Consumption======================
		$new_entry_no = explode("*",return_mrr_number( str_replace("'","",$cbo_company_name),'','FLC',date("Y",time()),5,"select system_no_prefix,system_no_prefix_num from fam_vm_fuel_consumption_mst where company_id=$cbo_company_name and entry_form='1' $year_cond order by id desc ","system_no_prefix","system_no_prefix_num"));
		//print_r($new_entry_no); die;
		//==========Kilometer Log=======================================
		//$new_entry_no_km = explode("*",return_mrr_number( str_replace("'","",$cbo_company_name),'','KML',date("Y",time()),5,"select system_no_prefix,system_no_prefix_num from fam_kilometer_log_mst where company_id=$cbo_company_name $year_cond order by id desc ","system_no_prefix","system_no_prefix_num"));
		
		$txtConsumptionDate=str_replace("'","",$txt_consumption_date);
		if ($db_type == 0) $consumption_date = change_date_format($txtConsumptionDate, 'yyyy-mm-dd');
		if ($db_type == 2) $consumption_date = change_date_format($txtConsumptionDate, 'yyyy-mm-dd', '-', 1);
		   
		$ful_id_mst=return_next_id( "id", "fam_vm_fuel_consumption_mst", 1 ) ;
		$ful_id_dtls=return_next_id( "id", "fam_vm_fuel_consumption_dtls", 1 ) ;
		$km_id_dtls=return_next_id( "id", "fam_vm_kilometer_log_dtls", 1 ) ;
		
		//$km_id_mst=return_next_id( "id", "fam_kilometer_log_mst", 1 ) ;
		
		$ful_mst_field_array="id, system_no_prefix, system_no_prefix_num, system_no, company_id, filling_date, entry_form, inserted_by, insert_date";
		$ful_mst_data_array="(".$ful_id_mst.",'".$new_entry_no[1]."','".$new_entry_no[2]."','".$new_entry_no[0]."',".$cbo_company_name.",'".$consumption_date."',".'1'.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		
		//echo "insert into fam_vm_fuel_consumption_mst($ful_mst_field_array) values $ful_mst_data_array";die;
		
		$ful_dtls_field_array="id, mst_id, asset_id, asset_no, acq_mst_id, asset_category, asset_group, specification, fuel_type, brand, qty, rate, amount, filling_station, memo_no, inserted_by, insert_date,location";
		
		$km_dtls_field_array="id, mst_id, fuel_dtls_id, asset_id, asset_no, acq_mst_id, asset_category, asset_group, specification, current_odometer, previous_odometer, k_m_run, run_form, run_to, inserted_by, insert_date";
		
		
		//$km_mst_field_array="id, system_no_prefix, system_no_prefix_num, system_no, company_id, movement_date, fuel_mst_id, inserted_by, insert_date";
		//$km_mst_data_array="(".$km_id_mst.",'".$new_entry_no_km[1]."','".$new_entry_no_km[2]."','".$new_entry_no_km[0]."',".$cbo_company_name.",'".$consumption_date."',".$ful_id_mst.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo "insert into fam_kilometer_log_mst($km_mst_field_array) values $km_mst_data_array";die;
		//echo "Total=".$tot_row; die;
		
		$sending_form_id='';
		for($j=1;$j<=$tot_row;$j++)
		{ 	
			$txtAssetNo="txtAssetNo_".$j;
			$txtAssetId="txtAssetId_".$j;
			$txtAcqMstId="txtAcqMstId_".$j;
			$txtLocation="txtLocation_".$j;
			$txtAssetCategory="txtAssetCategory_".$j;
			$txtAssetGroup="txtAssetGroup_".$j;
			$txtSpecification="txtSpecification_".$j;
			$txtRepierId="txtRepierId_".$j;
			$cboFuelType="cboFuelType_".$j;
			$txtBrand="txtBrand_".$j;
			$txtQty="txtQty_".$j;
			$txtRate="txtRate_".$j;
			$txtAmount="txtAmount_".$j;
			$txtFillingStation="txtFillingStation_".$j;
			$txtMemoNo="txtMemoNo_".$j;
			$txtKmRun="txtKmRun_".$j;
			$txtKmRunId="txtKmRunId_".$j;
			
			//echo "10************".$$txtKmRun; die;
						
			//if($sending_form_id!="") $sending_form_id.=",";
			//$sending_form_id.=$$txtRepierId;

			//ful_dtls_field_array
			if($ful_dtls_data_array!="") $ful_dtls_data_array.=",";
			$ful_dtls_data_array.="(".$ful_id_dtls.",".$ful_id_mst.",".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqMstId.",".$$txtAssetCategory.",".$$txtAssetGroup.",".$$txtSpecification.",".$$cboFuelType.",".$$txtBrand.",".$$txtQty.",".$$txtRate.",".$$txtAmount.",".$$txtFillingStation.",".$$txtMemoNo.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."',".$$txtLocation.")";
			
			if( str_replace("'","",$$txtKmRun) != '' )
			{
				if($km_dtls_data_array!="") $km_dtls_data_array.=",";
			  	$km_dtls_data_array.="(".$km_id_dtls.",".$ful_id_mst.",".$ful_id_dtls.",".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqMstId.",".$$txtAssetCategory.",".$$txtAssetGroup.",".$$txtSpecification.",".'0'.",".'0'.",".$$txtKmRun.",".'0'.",".'0'.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
				
				$km_id_dtls = $km_id_dtls+1;
			}
			$ful_id_dtls = $ful_id_dtls+1;
		}
		//echo "insert into fam_vm_fuel_consumption_dtls($ful_dtls_field_array) values $ful_dtls_data_array";die;
		//echo "insert into fam_vm_kilometer_log_dtls($km_dtls_field_array) values $km_dtls_data_array";die;
		//echo "insert into fam_vm_fuel_consumption_mst($ful_mst_field_array) values $ful_mst_data_array";die;
		//echo "insert into fam_kilometer_log_mst($km_mst_field_array) values $km_mst_data_array";die;
		
		$rID=sql_insert("fam_vm_fuel_consumption_mst",$ful_mst_field_array,$ful_mst_data_array,0);
		$rID1=sql_insert("fam_vm_fuel_consumption_dtls",$ful_dtls_field_array,$ful_dtls_data_array,0);
		$rID2=sql_insert("fam_vm_kilometer_log_dtls",$km_dtls_field_array,$km_dtls_data_array,0);
		
		//$rID2=sql_insert("fam_kilometer_log_mst",$km_mst_field_array,$km_mst_data_array,0);
		//$field_update="is_repair_back";
		//$rID3=sql_multirow_update("fam_send_for_repair_dtls",$field_update,"1","id","".$sending_form_id."",1);
		
		//echo "10***".$rID."***".$rID1."***".$rID2;die;
		if($db_type==0)
		{
			if($rID && $rID1)
			{
				mysql_query("COMMIT");  
				echo "0**".$new_entry_no[0]."**".$ful_id_mst;
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
				echo "0**".$new_entry_no[0]."**".$ful_id_mst;
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
		//$id=return_next_id( "id", "fam_repair_back_mst", 1 ) ;
		//======= Start : For New Insert ==========================
		$ful_id_mst=return_next_id( "id", "fam_vm_fuel_consumption_mst", 1 ) ;
		$ful_id_dtls=return_next_id( "id", "fam_vm_fuel_consumption_dtls", 1 ) ;
		$km_id_dtls=return_next_id( "id", "fam_vm_kilometer_log_dtls", 1 ) ;
		
		//$km_id_mst=return_next_id( "id", "fam_kilometer_log_mst", 1 ) ;
		
		$ful_mst_field_array="id, system_no_prefix, system_no_prefix_num, system_no, company_id, filling_date, inserted_by, insert_date";
		$ful_dtls_field_array="id, mst_id, asset_id, asset_no, acq_mst_id, asset_category, asset_group, specification, fuel_type, brand, qty, rate, amount, filling_station, memo_no, inserted_by, insert_date";
		$km_dtls_field_array="id, mst_id, fuel_dtls_id, asset_id, asset_no, acq_mst_id, asset_category, asset_group, specification, current_odometer, previous_odometer, k_m_run, run_form, run_to, inserted_by, insert_date";
		
		//$km_mst_field_array="id, system_no_prefix, system_no_prefix_num, system_no, company_id, movement_date, inserted_by, insert_date";
		
		//======= End : For New Insert ==========================
		
		
		//======= Start : For Update ==========================
		
		//------Start : Update : fam_vm_fuel_consumption_mst & fam_vm_fuel_consumption_dtls
		$txtConsumptionDate=str_replace("'","",$txt_consumption_date);
		if ($db_type == 0) $consumption_date = change_date_format($txtConsumptionDate, 'yyyy-mm-dd');
		if ($db_type == 2) $consumption_date = change_date_format($txtConsumptionDate, 'yyyy-mm-dd', '-', 1);
		
		$ful_mst_field_array_update="company_id*filling_date*updated_by*update_date";
		$ful_mst_data_array_update="".$cbo_company_name."*'".$consumption_date."'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		
		//$km_mst_field_array_update="company_id*movement_date*updated_by*update_date";
		//$km_mst_data_array_update="".$cbo_company_name."*'".$consumption_date."'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		//-------End : Update : fam_vm_fuel_consumption_mst & fam_vm_fuel_consumption_dtls
				
		
		
		$ful_dtls_field_array_update="fuel_type*brand*qty*rate*amount*filling_station*memo_no*updated_by*update_date";
		$km_dtls_field_array_update="k_m_run*updated_by*update_date";
		//======= End : For Update==========================
		//echo "10**".$tot_row; die;
		$update_id_array = array();
		$km_dtls_update_id_array = array();
		
		$sending_form_id="";
		for($j=1;$j<=$tot_row;$j++)
		{ 
		
		  	$txtAssetNo="txtAssetNo_".$j;
			$txtAssetId="txtAssetId_".$j;
			$txtAcqMstId="txtAcqMstId_".$j;
			$txtAssetCategory="txtAssetCategory_".$j;
			$txtAssetGroup="txtAssetGroup_".$j;
			$txtSpecification="txtSpecification_".$j;
			$txtRepierId="txtRepierId_".$j;
			$cboFuelType="cboFuelType_".$j;
			$txtBrand="txtBrand_".$j;
			$txtQty="txtQty_".$j;
			$txtRate="txtRate_".$j;
			$txtAmount="txtAmount_".$j;
			$txtFillingStation="txtFillingStation_".$j;
			$txtMemoNo="txtMemoNo_".$j;
			$txtKmRun="txtKmRun_".$j;
			$txtKmRunId="txtKmRunId_".$j;
			$updateIdss="txtupdateIds_".$j;
			
			$txtKm_Run = str_replace("'","",$$txtKmRun);
			$txtKm_Run_Id = str_replace("'","",$$txtKmRunId);
			$update_Ids = str_replace("'","",$$updateIdss);
			
			//echo "10***".$txtKm_Run_Id; die;
			if( $update_Ids != '')
			{
				$update_id_array[]=$update_Ids;
				$ful_dtls_data_array_update[$update_Ids]=explode("*",("".$$cboFuelType."*".$$txtBrand."*".$$txtQty."*".$$txtRate."*".$$txtAmount."*".$$txtFillingStation."*".$$txtMemoNo."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'"));
				
				if( $txtKm_Run_Id != '' )
				{
					//echo "10***".$txtKm_Run_Id."***". $txtKm_Run."\n;"; //die;
					
					if( $txtKm_Run_Id != '' &&   $txtKm_Run != '')
					{
						$km_dtls_update_id_array[]=$txtKm_Run_Id;
						$km_dtls_data_array_update[$txtKm_Run_Id]=explode("*",("'".$txtKm_Run."'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'"));
					}
					
					//echo "10**".$txtKm_Run."**".$txtKm_Run_Id; die;
					if( $txtKm_Run == '' && $txtKm_Run_Id != '')
					{
						$field_array_delete="status_active*is_deleted*updated_by*update_date";
						$data_array_delete="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
						$rID11=sql_update("fam_vm_kilometer_log_dtls",$field_array_delete,$data_array_delete,"id","".$txtKm_Run_Id."",0);
					}
				}
				
				//echo "10**".$txtKm_Run_Id."11**".$txtKm_Run; die;
				if( $txtKm_Run_Id =='' && $txtKm_Run !='' )
				{
					//echo "10*****kabir wwwwwwwwww";die;
					if($km_dtls_data_array_update_insert!="") $km_dtls_data_array_update_insert.=",";
					$km_dtls_data_array_update_insert.="(".$km_id_dtls.",".$update_id.",".$update_Ids.",".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqMstId.",".$$txtAssetCategory.",".$$txtAssetGroup.",".$$txtSpecification.",".'0'.",".'0'.", '".$txtKm_Run."',".'0'.",".'0'.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
					
					//print_r($km_dtls_data_array_update_insert);die;
				}
			}
			else
			{
				if($ful_dtls_data_array!="") $ful_dtls_data_array.=",";
				$ful_dtls_data_array.="(".$ful_id_dtls.",".$update_id.",".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqMstId.",".$$txtAssetCategory.",".$$txtAssetGroup.",".$$txtSpecification.",".$$cboFuelType.",".$$txtBrand.",".$$txtQty.",".$$txtRate.",".$$txtAmount.",".$$txtFillingStation.",".$$txtMemoNo.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
				//echo $ful_dtls_data_array; die;
				if( $txtKm_Run != '' )
				{
					if($km_dtls_data_array!="") $km_dtls_data_array.=",";
					$km_dtls_data_array.="(".$km_id_dtls.",".$ful_id_mst.",".$ful_id_dtls.",".$$txtAssetId.",".$$txtAssetNo.",".$$txtAcqMstId.",".$$txtAssetCategory.",".$$txtAssetGroup.",".$$txtSpecification.",".'0'.",".'0'.", '".$txtKm_Run."',".'0'.",".'0'.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
					//echo $km_dtls_data_array;
					$km_id_dtls = $km_id_dtls+1;
				}
				$ful_id_dtls = $ful_id_dtls+1;
			}
		}
		
		$rID=sql_update("fam_vm_fuel_consumption_mst",$ful_mst_field_array_update,$ful_mst_data_array_update,"id","".$update_id."",0);
		//$rID1=sql_update("fam_kilometer_log_mst",$km_mst_field_array_update,$km_mst_data_array_update,"fuel_mst_id","".$update_id."",0);
		
		
  		//print_r($update_id_array);die;
		if( $update_id_array != '')
		{
			$rID1=execute_query(bulk_update_sql_statement( "fam_vm_fuel_consumption_dtls", "id", $ful_dtls_field_array_update, $ful_dtls_data_array_update, $update_id_array ));
			//echo bulk_update_sql_statement("fam_vm_fuel_consumption_dtls","id", $ful_dtls_field_array_update, $ful_dtls_data_array_update, $update_id_array, 0); die;
			//print_r($km_dtls_update_id_array); die;
			
			if( $km_dtls_update_id_array != '')
			{
				$rID2=execute_query(bulk_update_sql_statement( "fam_vm_kilometer_log_dtls", "id", $km_dtls_field_array_update, $km_dtls_data_array_update, $km_dtls_update_id_array ));
				//echo bulk_update_sql_statement("fam_vm_kilometer_log_dtls","id", $km_dtls_field_array_update, $km_dtls_data_array_update, $km_dtls_update_id_array, 0); die;
			}
			
			if( $km_dtls_data_array_update_insert != '')
			{
				$rID12=sql_insert("fam_vm_kilometer_log_dtls",$km_dtls_field_array,$km_dtls_data_array_update_insert,0);
				//echo "insert into fam_vm_kilometer_log_dtls($km_dtls_field_array) values $km_dtls_data_array_update_insert"; die;
			}
			
		}
		
		
		if($ful_dtls_data_array != '')
		{
			$rID3=sql_insert("fam_vm_fuel_consumption_dtls",$ful_dtls_field_array,$ful_dtls_data_array,0);
			//echo "insert into fam_vm_fuel_consumption_dtls($ful_dtls_field_array) values $ful_dtls_data_array"; die;
			
			if($km_dtls_data_array != '')
			{
				//echo "100**********";die;
				$rID4=sql_insert("fam_vm_kilometer_log_dtls",$km_dtls_field_array,$km_dtls_data_array,0);
				//echo "insert into fam_vm_kilometer_log_dtls($km_dtls_field_array) values $km_dtls_data_array"; die;
			}
		}
		
		
		/*
		$update_id=str_replace("'","",$update_id);
		if( $update_id != '')
		{
			//echo "10***".$update_id; die;
			$rID=sql_update("fam_vm_fuel_consumption_mst",$ful_mst_field_array_update,$ful_mst_data_array_update,"id","'".$update_id."'",0);
			//$rID1=sql_update("fam_kilometer_log_mst",$km_mst_field_array_update,$km_mst_data_array_update,"fuel_mst_id","".$update_id."",0);

			if( $update_id_array != '')
			{
				$rID1=execute_query(bulk_update_sql_statement( "fam_vm_fuel_consumption_dtls", "id", $ful_dtls_field_array_update, $ful_dtls_data_array_update, $update_id_array ));
				//echo bulk_update_sql_statement("fam_vm_fuel_consumption_dtls","id", $ful_dtls_field_array_update, $ful_dtls_data_array_update, $update_id_array, 0); die;
				if( $km_dtls_update_id_array != '')
	  			{
	  				$rID2=execute_query(bulk_update_sql_statement( "fam_vm_kilometer_log_dtls", "fuel_dtls_id", $km_dtls_field_array_update, $km_dtls_data_array_update, $km_dtls_update_id_array ));
					//echo bulk_update_sql_statement("fam_vm_kilometer_log_dtls","fuel_dtls_id", $km_dtls_field_array_update, $km_dtls_data_array_update, $km_dtls_update_id_array, 0); die;
	  				
	  			}
			}
		}	
		else
		{
			if($ful_dtls_data_array != '')
			{
				$rID3=sql_insert("fam_vm_fuel_consumption_dtls",$ful_dtls_field_array,$ful_dtls_data_array,0);
				echo "insert into fam_vm_fuel_consumption_dtls($ful_dtls_field_array) values $ful_dtls_data_array"; die;
				
				if($km_dtls_data_array != '')
				{
					//echo "100**********";die;
					$rID4=sql_insert("fam_vm_kilometer_log_dtls",$km_dtls_field_array,$km_dtls_data_array,0);
					echo "insert into fam_vm_kilometer_log_dtls($km_dtls_field_array) values $km_dtls_data_array"; die;
				}
			}
		}
		*/
		
		
		

		$field_array_delete="status_active*is_deleted*updated_by*update_date";
		$data_array_delete="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		$deleted_id = str_replace("'","",$txt_deleted_id);
		if($deleted_id != '')
		{
			$rID6=sql_multirow_update("fam_vm_fuel_consumption_dtls",$field_array_delete,$data_array_delete,"id","".$deleted_id."",1);
			$rID7=sql_multirow_update("fam_vm_kilometer_log_dtls",$field_array_delete,$data_array_delete,"fuel_dtls_id","".$deleted_id."",1);
		}
		
		//echo "10***".$rID."***".$rID1."***".$rID2."***".$rID3."***".$rID4."***".$rID6."***".$rID7."***".$rID11."***".$rID12; die;
		
		$txt_system_id=str_replace("'","",$txt_system_id);
		$update_id=str_replace("'","",$update_id);
		
		if($db_type==0)
		{
			if($rID && $rID1)
			{
				mysql_query("COMMIT");  
				echo "1**" . $txt_system_id . "**" . $update_id ;
			}
			else
			{
				mysql_query("ROLLBACK"); 
                echo "10**" . $txt_system_id . "**" . $update_id;
			}
		}
		
		if($db_type==2 || $db_type==1 )
		{
			if($rID && $rID1)
			{
				oci_commit($con);
				echo "1**" . $txt_system_id . "**" . $update_id;
			}
			else 
			{
                oci_rollback($con);
                echo "10**" . $txt_system_id . "**" . $update_id;
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
		
		
		
		$rID=sql_delete("fam_vm_fuel_consumption_mst",$field_array,$data_array,"id","".$update_id."",1);
		$rID1=sql_delete("fam_vm_fuel_consumption_dtls",$field_array,$data_array,"mst_id","".$update_id."",1);
		$rID2=sql_delete("fam_vm_kilometer_log_dtls",$field_array,$data_array,"mst_id","".$update_id."",1);
		
		
		//echo "10***".$rID."***".$rID1."***".$rID2; die;
		
		//$txt_entry_no=str_replace("'","",$txt_entry_no);
		$update_id=str_replace("'","",$update_id);

		if ($db_type == 0) {
            if ($rID && $rID1 && $rID2) {
                mysql_query("COMMIT");
                echo "2**".$txt_entry_no."**".$update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" .$txt_entry_no."**".$update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1 && $rID2) {
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
//==================================================================================
//===END : Fuel and Lubricant Consumption SAVE-UPDATE-DELETE ===============
//==================================================================================







































































































































































if ($action=="show_send_for_repair_listview")
{
	//echo $data; die;
	$data_row = explode('_',$data);
	$sql = "select a.system_no, a.id, b.asset_no, b.service_nature, b.service_details, b.qty, b.service_rate, b.amount from fam_repair_back_mst a, fam_repair_back_dtls b where a.id=b.mst_id and b.status_active=1 and b.is_deleted=0 and a.id='$data_row[0]'";
	//echo $sql;
	$arr = array(2 => $service_nature_arr);
	echo  create_list_view("list_view", "System No,Asset No,Service Nature,Service Details,Qty,Rate,Amount","120,120,150,200,50,80,100","900","300",0,$sql ,"get_php_form_data", "id","'populate_data_from_data'",1,"0,0,service_nature,0,0,0,0",$arr,"system_no,asset_no,service_nature,service_details,qty,service_rate,amount","requires/fuel_and_lubricant_consumption_controller",'','0,0,0,0,1,1,1') ;
	
	exit();
}


//---------------
//==================================================================================
//============================= END Repiar Back ====================================
//==================================================================================


//==================================================================================


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