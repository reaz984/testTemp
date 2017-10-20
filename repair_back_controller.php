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
//Search Return ID
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
                    <th width="">Return Date Range</th>
                    <th width="">Return Form</th>
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
			                 	echo create_drop_down( "cboServiceNature", 100, $service_nature_arr,"", 1, "--- Select ---", $selected, "","","1,2","","","","4");
			                ?> 
                        </td>
                        <td>
                        	<input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" /> -
                            <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" />
                        </td>
                        <td>
                            <?php
								echo create_drop_down( "cbo_return_form", 100, "select id,supplier_name from lib_supplier where status_active=1 and is_deleted=0 order by supplier_name","id,supplier_name", 1, "Select", $selected, "","");
							?> 
                        </td>
                        <td align="center">
                        	<input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('txt_system_number').value + '_' + document.getElementById('cbo_company_name').value + '_' + document.getElementById('cboServiceNature').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value + '_' + document.getElementById('cbo_return_form').value, 'show_searh_system_id_listview', 'searh_system_id_listview', 'repair_back_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />	
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
	//$system_id = "'".$ex_data[0]."'";
	//echo $system_id; die;
	if ($ex_data[0] == '')	$system_number = "";		else	$system_number = " and a.system_no='" . $ex_data[0] . "'";
	if ($ex_data[1] == 0)	$company_id = "";			else	$company_id = " and a.company_id='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0)	$serviceNature = "";		else	$serviceNature = " and b.service_nature='" . $ex_data[2] . "'";
	if ($ex_data[5] == 0)	$returnForm = "";			else	$returnForm = " and a.return_form='". $ex_data[5] ."'";

	$txt_date_from = $ex_data[3];
	$txt_date_to = $ex_data[4];
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0)
		{
			$tran_date = " and a.return_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		}
		if($db_type==2 || $db_type==1 )
		{
			$tran_date = " and a.return_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
		}
	
	}
	
	$company_location = return_library_array("select id,company_name  from lib_company where status_active =1 and is_deleted=0", "id", "company_name");
	$supplier_array = return_library_array("select id,supplier_name from lib_supplier where status_active=1 and is_deleted=0 order by supplier_name", "id","supplier_name");
	$arr=array (1=>$company_location,2=>$service_nature_arr,4=>$supplier_array);
	
	$sql = "select a.id, a.system_no, a.company_id, a.return_form, a.return_date, b.service_nature  from fam_repair_back_mst a, fam_repair_back_dtls b  where a.id=b.mst_id and b.status_active=1 and b.is_deleted=0  $system_number $company_id $serviceNature $returnForm $tran_date group by a.id, a.system_no, a.company_id, a.return_form, a.return_date, b.service_nature  order by a.id asc";
	//echo $sql; die;
	
	echo  create_list_view("list_view", "System ID,Company Name,Service Nature,Return Date,Return Form", "120,150,100,100,100","700","400",0, $sql , "js_set_value", "id", "", 1, "0,company_id,service_nature,0,return_form", $arr , "system_no,company_id,service_nature,return_date,return_form","requires/repair_back_controller",'','0,0,0,3,0') ;
	
	exit();
}


if($action=="populate_data_from_data")
{
	$sql = "select id, system_no, company_id, return_date, return_form  from fam_repair_back_mst  where status_active=1 and is_deleted=0 and id=$data";
	//echo $sql; die;
	$res = sql_select($sql);	
	foreach($res as $row)
	{		
		echo "$('#txt_system_id').val('".$row[csf("system_no")]."');\n";
		echo "$('#cbo_company_name').val(".$row[csf("company_id")].");\n";
		echo "$('#cbo_company_name').attr('disabled','true')".";\n";
		//echo "$('#cboServiceNature').val(".$row[csf("service_nature")].");\n";
		//echo "$('#cboServiceNature').attr('disabled','true')".";\n";
		echo "$('#cbo_return_form').val(".$row[csf("return_form")].");\n";
		echo "$('#cbo_return_form').attr('disabled','true')".";\n";
		echo "$('#txt_return_date').val('".change_date_format($row[csf("return_date")])."');\n";
		//echo "$('#txt_return_date').attr('disabled','true')".";\n";
		echo "$('#update_id').val(".$row[csf("id")].");\n";
		echo "myFunction(".$row[csf("id")].");\n";
		echo "set_button_status(1, permission,'fnc_repair_back_entry',1);\n";
  	}
	exit();	
}


if($action == "show_return_form_listview")
{
	//echo $data; die;
	$data_row = explode('_',$data);
	
	$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");
	$sql = sql_select("select a.id as rep_back_id, a. mst_id, a.rep_dtls_id, a.acq_serv_outoforder_id, a.asset_id, a.asset_no, a.service_nature, a.service_details, a.qty, a.service_rate, a.amount, b.id  from fam_repair_back_dtls a, fam_send_for_repair_dtls b where a.status_active=1 and a.is_deleted=0 and a.rep_dtls_id=b.id and a.mst_id=$data_row[0]");
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
	  		<td width="90">
	  		'.$row[csf('asset_no')].'
	  		<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_no')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
	  		<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
			<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('acq_serv_outoforder_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtAcqServOutoforderId"/>
			<input type="hidden" name="txtRepierId[]" id="txtRepierId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtRepierId"/>
			<input type="hidden" name="txtupdateIds[]" id="txtupdateIds_'.$i.'" value="'.$row[csf('rep_back_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtupdateIds"/>
			</td>
			<td width="150">
			'.create_drop_down( "cboServiceNature_$i", 162, $service_nature_arr,"", 1, "--- Select ---", $row[csf('service_nature')], "",1,"1,2","","","","4","","cboServiceNature[]").'
	  		</td>
	  		<td id="" style="word-break:break-all;" width="200">
	  		<input type="text" name="txtServiceDtls[]" id="txtServiceDtls_'.$i.'" value="'.$row[csf('service_details')].'" class="text_boxes" style="width:200px"  placeholder="txtServiceDtls"/>
	  		</td>
	  		<td width="50">
	  		<input type="text" name="txtQty[]" id="txtQty_'.$i.'" value="'.$row[csf('qty')].'" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:50px"  placeholder="Qty"/>
	  		</td>
	  		<td width="80">
	  		<input type="text" name="txtRate[]" id="txtRate_'.$i.'" value="'.$row[csf('service_rate')].'" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:80px"  placeholder="Rate"/>
	  		</td>
	  		<td width="80">
	  		<input type="text" name="txtAmount[]" id="txtAmount_'.$i.'" value="'.$row[csf('amount')].'" class="text_boxes_numeric" readonly disabled style="width:80px"  />
	  		</td>
	  		<td id="button_1" align="center">
			<input type="button" id="increase_'.$i.'" name="increase[]" style="width:30px" class="formbuttonplasminus" value="+" onClick="fn_addRow('.$i.')" />
	  		<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
	  		</td>
		</tr>';
	}
	echo $tble_body;die;
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
			
			$('#hidden_repair_back_dtls_id').val( id );
		}
		
		function fnc_close()
		{
			//alert($('#hidden_repair_back_dtls_id').val()); return;
			parent.emailwindow.hide();
		}
		
		function reset_hide_field()
		{
			$('#hidden_repair_back_dtls_id').val( '' );
			selected_id = new Array();
		}
	
    </script>
    </head>
    <body>
  
        <div align="center" style="width:100%;" >
            <form name="searchSendForRep_1"  id="searchSendForRep_1" autocomplete="off">
                <table width="800" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>               	 
                            <th width="120">Asset No</th>
                            <th width="110">Service Nature</th>
                            <th width="200" align="center" >Service Date Range</th>
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
                            <td>
                                <?php
                  					echo create_drop_down( "cboServiceNature", 162, $service_nature_arr,"", 1, "--- Select ---", $selected, "","","1,2","","","","4");
                				?> 
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:80px" placeholder="From" /> -
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:80px" placeholder="To" />
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('hidden_company_id').value + '_' + document.getElementById('asset_number').value + '_' + document.getElementById('cboServiceNature').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value+ '_' + document.getElementById('service_schedule_dtls_id').value+ '_' + document.getElementById('hidden_return_form').value, 'show_searh_sent_for_repiar_listview', 'searh_list_view', 'repair_back_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr> 
                        <tr>                  
                            <td align="center" height="30" valign="middle" colspan="7">
                                <?php echo load_month_buttons(1); ?>
                            </td>
                        </tr> 
                        <tr> 
	                        <input type="hidden" name="hidden_company_id" id="hidden_company_id" style="width:90px;" class="text_boxes" value="<?php echo $cbo_company_id;?>">
                            <input type="hidden" name="service_schedule_dtls_id" id="service_schedule_dtls_id"  value="<?php echo $repair_back_dtls_id;?>" style="width:90px;" class="text_boxes">
                            <input type="hidden" name="hidden_return_form" id="hidden_return_form" value="<?php echo $return_form;?>" class="text_boxes" />
                            
	                        <input type="hidden" name="hidden_repair_back_dtls_id" id="hidden_repair_back_dtls_id" style="width:90px;" class="text_boxes">
	                        <input type="hidden" id="hidden_system_number" value="" class="text_boxes" />
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
if ($action == "show_searh_sent_for_repiar_listview") 
{
	//echo $data; die;
    $ex_data = explode("_", $data);
	?>
	<div style="width:800px; max-height:300px; overflow-y:scroll" id="list_container_batch" align="left">	 
	<table cellspacing="0" cellpadding="0" border="1" rules="all" width="780" class="rpt_table" id="tbl_list_search">
	<thead>
		<th width="35">SL</th>
		<th width="70">Asset No</th>
		<th width="80">Out Date</th>
        <th width="70">Estm. Return Date</th>
<!--         <th width="70">Service No</th> -->
		<th width="70">Service Nature</th>
		<th width="70">Send To</th>
	</thead>
	<tbody>
	<?php	
	//if ($ex_data[0] == 0)	$company_id = "";		else	$company_id = " and a.company_id='" . $ex_data[0] . "'";
	if ($ex_data[1] == 0)	$assetNumber = "";			else	$assetNumber = " and b.asset_number='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0)	$serviceNature = "";		else	$serviceNature = " and a.service_nature='" . $ex_data[2] . "'";
	if ($ex_data[5] == 0)	$sendForeRepId = "";		else	$sendForeRepId = " and  b.id not in(" . $ex_data[5] . ")";
	if ($ex_data[6] == 0)	$returnForm = "";		else	$returnForm = " and a.send_to='" . $ex_data[6] . "'";
	
	$txt_date_from = $ex_data[3];
	$txt_date_to = $ex_data[4];
	
	if ($txt_date_from != "" || $txt_date_to != "") 
	{
		if ($db_type == 0){ 
			$tran_date = " and a.out_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
		}else {
			$tran_date = " and a.out_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
		}
	}
	
	$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
	$supplier_arr=return_library_array( "select id,supplier_name from lib_supplier", "id", "supplier_name");
	
		$sql = "select a.company_id, a.out_date, a.send_to, a.service_nature, b.id, b.estm_returnable_date, b.asset_id, b.asset_number  from fam_send_for_repair_mst a, fam_send_for_repair_dtls b, fam_acquisition_sl_dtls  c where a.status_active=1 and  a.is_deleted=0 and b.status_active=1 and  b.is_deleted=0 and c.status_active=1 and  c.is_deleted=0 and b.is_repair_back !='1' and b.asset_id = c.id and c.asset_type not in(10) and a.id = b.mst_id and a.company_id='$ex_data[0]' $assetNumber $serviceNature $tran_date $sendForeRepId $returnForm";

  	//22-10-2016
	//$sql = "select a.company_id, a.out_date, a.send_to, a.service_nature, b.id, b.estm_returnable_date, b.asset_id, b.asset_number  from fam_send_for_repair_mst a, fam_send_for_repair_dtls b where b.status_active=1 and b.is_repair_back !='1' and b.is_deleted=0 and a.id = b.mst_id and a.company_id='$ex_data[0]' $assetNumber $serviceNature $tran_date $sendForeRepId $returnForm";
	
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
					 <input type="hidden" name="txt_hidden_asset_id<?php echo $i; ?>" id="txt_hidden_asset_id<?php echo $i; ?>" value="<?php echo $row[csf('asset_id')]; ?>" class="text_boxes" style="width:25px;"/>
				</td>
                
				<td width="100" align="center"><?php echo $row[csf('asset_number')]; ?></td>
                
				<td width="80" align="center"><?php echo change_date_format($row[csf('out_date')]); ?></td>
                
				<td width="80" align="center"><?php echo change_date_format($row[csf('estm_returnable_date')]); ?></td>
                
				<td width="100" align="center"><?php echo $service_nature_arr[$row[csf('service_nature')]]; ?></td>
                
				<td width="80" align="center"><?php echo $supplier_arr[$row[csf('send_to')]]; ?></td>
				
			</tr>
		<?
			$i++;
		}
	}
	?>
	</tbody>  
	</table>
	</div>
	<table width="780">
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
	
	$origin_array=return_library_array("select id,country_name from lib_country where status_active=1 and is_deleted=0 order by country_name", "id", "country_name");
	$sql = sql_select("select a.company_id, a.out_date, a.send_to, a.service_nature, b.id, b.estm_returnable_date, b.asset_id, b.acq_serv_outoforder_id, b.asset_number  from fam_send_for_repair_mst a, fam_send_for_repair_dtls b where b.status_active=1 and b.is_deleted=0 and a.id = b.mst_id and b.id in($data_row[0])");
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
						
	  		<td width="90">
		  		'.$row[csf('asset_number')].'
		  		<input type="hidden" name="txtAssetNo[]" id="txtAssetNo_'.$i.'" value="'.$row[csf('asset_number')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_No"/>
		  		<input type="hidden" name="txtAssetId[]" id="txtAssetId_'.$i.'" value="'.$row[csf('asset_id')].'" class="text_boxes" style="width:90px" readonly placeholder="hidden_asset_id"/>
				<input type="hidden" name="txtAcqServOutoforderId[]" id="txtAcqServOutoforderId_'.$i.'" value="'.$row[csf('acq_serv_outoforder_id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtAcqServOutoforderId"/>
		  		<input type="hidden" name="txtRepierId[]" id="txtRepierId_'.$i.'" value="'.$row[csf('id')].'" class="text_boxes" style="width:90px" readonly placeholder="txtRepierId"/>
				<input type="hidden" name="txtupdateIds[]" id="txtupdateIds_'.$i.'" value="" class="text_boxes" style="width:90px" readonly placeholder="txtupdateIds"/>
			</td>
			<td width="150">
				'.create_drop_down( "cboServiceNature_$i", 162, $service_nature_arr,"", 1, "--- Select ---", $row[csf('service_nature')], "",1,"1,2","","","","4","","cboServiceNature[]").'
	  		</td>
	  		<td id="" style="word-break:break-all;" width="200">
	  			<input type="text" name="txtServiceDtls[]" id="txtServiceDtls_'.$i.'" value="" class="text_boxes" style="width:200px"  placeholder="txtServiceDtls"/>
	  		</td>
	  		<td width="50">
	  			<input type="text" name="txtQty[]" id="txtQty_'.$i.'" value="" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:50px"  placeholder="Qty"/>
	  		</td>
	  		<td width="80">
	  			<input type="text" name="txtRate[]" id="txtRate_'.$i.'" value="" onKeyUp="calculate_amount('.$i.')" class="text_boxes_numeric" style="width:80px"  placeholder="write"/>
	  		</td>
	  		<td width="80">
	  			<input type="text" name="txtAmount[]" id="txtAmount_'.$i.'" value="" class="text_boxes_numeric" readonly disabled style="width:80px"  />
	  		</td>
	  		<td id="button_1" align="center">
				<input type="button" id="increase_'.$i.'" name="increase[]" style="width:30px" class="formbuttonplasminus" value="+" onClick="fn_addRow('.$i.')" />
		  		<input type="button" id="decrease_'.$i.'" name="decrease[]" style="width:30px" class="formbuttonplasminus" value="-" onClick="fn_deleteRow('.$i.')" />
	  		</td>
		</tr>';
	}
	echo $tble_body;die;
}


if ($action=="show_send_for_repair_listview")
{
	//echo $data; die;
	$data_row = explode('_',$data);
	$sql = "select a.system_no, a.id, b.asset_no, b.service_nature, b.service_details, b.qty, b.service_rate, b.amount from fam_repair_back_mst a, fam_repair_back_dtls b where a.id=b.mst_id and b.status_active=1 and b.is_deleted=0 and a.id='$data_row[0]'";
	//echo $sql;
	$arr = array(2 => $service_nature_arr);
	echo  create_list_view("list_view", "System No,Asset No,Service Nature,Service Details,Qty,Rate,Amount","120,120,150,200,50,80,100","900","300",0,$sql ,"get_php_form_data", "id","'populate_data_from_data'",1,"0,0,service_nature,0,0,0,0",$arr,"system_no,asset_no,service_nature,service_details,qty,service_rate,amount","requires/repair_back_controller",'','0,0,0,0,1,1,1') ;
	
	exit();
}


//---------------
//==================================================================================
//============================= END Repiar Back ====================================
//==================================================================================

//==================================================================================
//============================= START Repiar Back SAVE-UPDATE-DELETE ===============
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
		
		$id=return_next_id( "id", "fam_repair_back_mst", 1 ) ;
		$dtls_id=return_next_id( "id", "fam_repair_back_dtls", 1 ) ;
		
		
		$field_array="id, system_no_prefix, system_no_prefix_num, system_no, company_id, return_form, return_date, inserted_by, insert_date";
		$field_array_dtls="id, mst_id, rep_dtls_id, acq_serv_outoforder_id, asset_id, asset_no, service_nature, service_details, qty, service_rate, amount, inserted_by, insert_date";
		
		//return_mrr_number( $company, $location, $category, $year, $num_length, $main_query, $str_fld_name, $num_fld_name, $old_mrr_no )
		
		if($db_type==0) $year_cond = " and YEAR(insert_date)=".date('Y',time())." "; 
		else $year_cond = " and to_char(insert_date,'YYYY')=".date('Y',time())." "; 
		
		$new_entry_no=explode("*",return_mrr_number( str_replace("'","",$cbo_company_name),'','RB',date("Y",time()),5,"select system_no_prefix,system_no_prefix_num from fam_repair_back_mst where company_id=$cbo_company_name $year_cond order by id desc ","system_no_prefix","system_no_prefix_num"));
		
		$txtReturnDate=str_replace("'","",$txt_return_date);
		if ($db_type == 0) $return_date = change_date_format($txtReturnDate, 'yyyy-mm-dd');
		if ($db_type == 2) $return_date = change_date_format($txtReturnDate, 'yyyy-mm-dd', '-', 1);
		
		$data_array="(".$id.",'".$new_entry_no[1]."','".$new_entry_no[2]."','".$new_entry_no[0]."',".$cbo_company_name.",".$cbo_return_form.",'".$return_date."',".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
		//echo $data_array; die;
		//echo "insert into fam_repair_back_mst($field_array) values $data_array";die;
		
		//echo "Total=".$tot_row; die;
		
		$sending_form_id='';
		for($j=1;$j<=$tot_row;$j++)
		{ 	
			$txtAssetNo="txtAssetNo_".$j;
			$txtAssetId="txtAssetId_".$j;
			$txtAcqServOutoforderId="txtAcqServOutoforderId_".$j;
			$txtRepierId="txtRepierId_".$j;
			$cboServiceNature="cboServiceNature_".$j;
			$txtServiceDtls="txtServiceDtls_".$j;
			$txtQty="txtQty_".$j;
			$txtRate="txtRate_".$j;
			$txtAmount="txtAmount_".$j;
						
			if($sending_form_id!="") $sending_form_id.=",";
			$sending_form_id.=$$txtRepierId;

			
			if($data_array_dtls!="") $data_array_dtls.=",";
			$data_array_dtls.="(".$dtls_id.",".$id.",".$$txtRepierId.",".$$txtAcqServOutoforderId.",".$$txtAssetId.",".$$txtAssetNo.",".$$cboServiceNature.",".$$txtServiceDtls.",".$$txtQty.",".$$txtRate.",".$$txtAmount.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
			$dtls_id = $dtls_id+1;
		}
		
		//echo "insert into fam_repair_back_dtls($field_array_dtls) values $data_array_dtls";die;
		$rID=sql_insert("fam_repair_back_mst",$field_array,$data_array,0);
		$rID1=sql_insert("fam_repair_back_dtls",$field_array_dtls,$data_array_dtls,0);
		
		$field_update="is_repair_back";
		$rID3=sql_multirow_update("fam_send_for_repair_dtls",$field_update,"1","id","".$sending_form_id."",1);
		
		//echo "10***".$rID."***".$rID1."***".$rID3;die;
		if($db_type==0)
		{
			if($rID && $rID1 && $rID3)
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
			if($rID && $rID1 && $rID3)
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
		$con = connect();
		if($db_type==0)
		{
			mysql_query("BEGIN");
		}
		//$id=return_next_id( "id", "fam_repair_back_mst", 1 ) ;
		$dtls_id=return_next_id( "id", "fam_repair_back_dtls", 1 ) ;
		
		$field_array_dtls="id, mst_id, rep_dtls_id, acq_serv_outoforder_id, asset_id, asset_no, service_nature, service_details, qty, service_rate, amount, inserted_by, insert_date";
		
		//Start : Update : fam_repair_back_mst
		$field_array_update="company_id*return_date*return_form*updated_by*update_date";
		$txtReturnDate=str_replace("'","",$txt_return_date);
		if ($db_type == 0) $ReturnDate = change_date_format($txtReturnDate, 'yyyy-mm-dd');
		if ($db_type == 2) $ReturnDate = change_date_format($txtReturnDate, 'yyyy-mm-dd', '-', 1);
		
		$data_array_update="".$cbo_company_name."*'".$ReturnDate."'*".$cbo_return_form."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		//End : Update : fam_repair_back_mst
		
		//Start : Update : fam_repair_back_dtls
		$field_array_dtls_update="service_nature*service_details*qty*service_rate*amount*updated_by*update_date";
		
		//echo "10**".$tot_row; die;
		
		$update_id_array = array();
		$sending_form_id="";
		for($j=1;$j<=$tot_row;$j++)
		{ 
		  	$txtAssetNo="txtAssetNo_".$j;
			$txtAssetId="txtAssetId_".$j;
			$txtAcqServOutoforderId="txtAcqServOutoforderId_".$j;
			$txtRepierId="txtRepierId_".$j;
			$cboServiceNature="cboServiceNature_".$j;
			$txtServiceDtls="txtServiceDtls_".$j;
			$txtQty="txtQty_".$j;
			$txtRate="txtRate_".$j;
			$txtAmount="txtAmount_".$j;
			$updateIdss="txtupdateIds_".$j;
			
			$update_Ids = str_replace("'","",$$updateIdss);
			
			//echo "10***".$update_Ids; die;
			
			if( $update_Ids != '')
			{
				$update_id_array[]=$update_Ids;
				$data_array_dtls_update[$update_Ids]=explode("*",("".$$cboServiceNature."*".$$txtServiceDtls."*".$$txtQty."*".$$txtRate."*".$$txtAmount."*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'"));
				//print_r($data_array_dtls_update); die;
			}
			else
			{
				if($sending_form_id!="") $sending_form_id.=",";
				$sending_form_id.=$$txtRepierId;

				if($data_array_dtls!="") $data_array_dtls.=",";
				$data_array_dtls.="(".$dtls_id.",".$update_id.",".$$txtRepierId.",".$$txtAcqServOutoforderId.",".$$txtAssetId.",".$$txtAssetNo.",".$$cboServiceNature.",".$$txtServiceDtls.",".$$txtQty.",".$$txtRate.",".$$txtAmount.",".$_SESSION['logic_erp']['user_id'].",'".$pc_date_time."')";
				
				$dtls_id = $dtls_id+1;
			}
			
			
		}
		
		//print_r($data_array_dtls); die;
		
		$rID=sql_update("fam_repair_back_mst",$field_array_update,$data_array_update,"id","".$update_id."",0);
		$rID1=execute_query(bulk_update_sql_statement( "fam_repair_back_dtls", "id", $field_array_dtls_update, $data_array_dtls_update, $update_id_array ));
		
		$field_update="is_repair_back";
		if($data_array_dtls != "")
		{
			$rID2=sql_insert("fam_repair_back_dtls", $field_array_dtls, $data_array_dtls,0);
			//echo "insert into fam_repair_back_dtls($field_array_dtls) values $data_array_dtls"; die;
			$rID5=sql_multirow_update("fam_send_for_repair_dtls",$field_update,"1","id","".$sending_form_id."",1);
		}
		
		$deleted_itmem_id = str_replace("'","",$txt_deleted_itmem_id);
		
		if($deleted_itmem_id != '')
		{
			//echo "10**".$deleted_itmem_id; die;
			$rID3=sql_multirow_update("fam_send_for_repair_dtls",$field_update,"0","id","".$deleted_itmem_id."",1);
		}
		
		$field_array_for_update = "is_repair_back";
		$field_array_delete="status_active*is_deleted*updated_by*update_date";
		$data_array_delete="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		$deleted_id = str_replace("'","",$txt_deleted_id);
		if($deleted_id != '')
		{
			$rID4=sql_multirow_update("fam_repair_back_dtls",$field_array_delete,$data_array_delete,"id","".$deleted_id."",1);
		}
		//echo "10***".$rID."***".$rID1."***".$rID2."***".$rID3."***".$rID4."***".$rID5; die;
		
		$update_id=str_replace("'","",$update_id);
		
		if($db_type==0)
		{
			if($rID && $rID1)
			{
				mysql_query("COMMIT");  
				echo "1**" . $txt_entry_no . "**" . $id . "**" . $update_id;
			}
			else
			{
				mysql_query("ROLLBACK"); 
                echo "10**" . $txt_entry_no . "**" . $id. "**" . $update_id;
			}
		}
		if($db_type==2 || $db_type==1 )
		{
			if($rID && $rID1)
			{
				oci_commit($con);
				echo "1**" . $txt_entry_no . "**" . $id . "**" . $update_id;
			}
			else 
			{
                oci_rollback($con);
                echo "10**" . $txt_entry_no . "**" . $$id. "**" . $update_id;
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
		
		$sending_form_id='';
		for($j=1;$j<=$tot_row;$j++)
		{ 	
			$txtRepierId="txtRepierId_".$j;
			if($sending_form_id!="") $sending_form_id.=",";
			$sending_form_id.=$$txtRepierId;
		}
		$repairIds= str_replace("'","",$sending_form_id);
		
		$rID=sql_delete("fam_repair_back_mst",$field_array,$data_array,"id","".$update_id."",1);
		$rID1=sql_delete("fam_repair_back_dtls",$field_array,$data_array,"mst_id","".$update_id."",1);
		
		$field_update="is_repair_back";
		$rID2=sql_multirow_update("fam_send_for_repair_dtls",$field_update,"0","id","".$repairIds."",1);
		
		$txt_entry_no=str_replace("'","",$txt_entry_no);
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
//============================= END : Repiar Back SAVE-UPDATE-DELETE ===============
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