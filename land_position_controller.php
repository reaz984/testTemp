
<?php
header('Content-type:text/html; charset=utf-8');
session_start();
include('../../../includes/common.php');
$user_id = $_SESSION['logic_erp']["user_id"];
if ($_SESSION['logic_erp']['user_id'] == "") {
    header("location:login.php");
    die;
}
$permission = $_SESSION['page_permission'];
$data = $_REQUEST['data'];
$action = $_REQUEST['action'];
$company_library = return_library_array("select id,company_short_name from lib_company", "id", "company_short_name");
//$store_library = return_library_array("select id,store_name from lib_store_location", "id", "store_name");
//--------------------------------------------------------------------------------------------
//load drop down company location=============================
if ($action == "load_drop_down_location") {
    echo create_drop_down("cbo_location", 170, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$data' order by location_name", "id,location_name", 1, "-- Select Location --", $selected, "", 0);
    exit();
}
//--------------------------------------------------------------------------------------------

if ($action == "check_asset_no") {
    $data = explode("**", $data);
    //print_r($data); die;
    //
	//$sql="select id,asset_id,asset_no from fam_land_position_mst  where asset_id=$data[1] and company_id=$data[0]";
    $sql = "select a.id,a.asset_id,a.asset_no, c.cost_per_unit from fam_land_position_mst  a, fam_acquisition_sl_dtls b, fam_acquisition_mst c  where a.status_active=1 and a.is_deleted=0 and a.asset_id=b.id and b.mst_id=c.id and  a.asset_id=$data[1] and a.company_id=$data[0]";
    //echo $sql; die;
    $data_array = sql_select($sql, 1);
    if (count($data_array) > 0) {
        //echo "1"."_".$data_array[0][csf('id')]."_".$data_array[0][csf('asset_id')];
        echo "1" . "_" . $data_array[0][csf('id')] . "_" . $data_array[0][csf('asset_id')] . "_" . $data_array[0][csf('cost_per_unit')];
        die;
    } else {
        echo "0_";
    }
    exit();
}

if($action=="load_land_location")
{
	$sql="select land_location from fam_land_position_mst where status_active=1 and is_deleted=0 and company_id = $data group by land_location";
	echo "[".substr(return_library_autocomplete( $sql, "land_location" ), 0, -1)."]";
	exit();	
}



//----------------Land Positon Search PopUp ----------------
if ($action == "search_asset_no") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    <script>
        function js_asset_value(data) {
            //alert(data); return;
            document.getElementById('hidden_system_number').value = data;
            parent.emailwindow.hide();
        }

    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="535" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                            <th width="100">Asset No</th>                	 
                            <th width="170">Company Name</th>
                            <th width="170">Location</th>
                            <th width="100"><input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  /></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="asset_number" id="asset_number" style="width:100px;" class="text_boxes"></td>
                            <td>
                                <?php
                                echo create_drop_down("cbo_company_name", 170, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "-- Select Company --", $selected, "load_drop_down( 'asset_acquisition_controller', this.value, 'load_drop_down_location', 'src_location_td');", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td id="src_location_td">
                                <?php
                                echo create_drop_down("cbo_location", 170, $blank_array, "", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show"  style="width:70px;"  onClick="show_list_view(document.getElementById('asset_number').value + '_' + document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_location').value, 'show_asset_no_searh_listview', 'searh_list_view', 'land_position_controller', 'setFilterGrid(\'list_view\',-1)')"/>
                            </td>
                        </tr>
                    <input type="hidden" id="hidden_system_number" value="" />  
                    </tbody>
                </table> 
                <div align="center" valign="top" id="searh_list_view"> </div> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php
}


//--------------Asset No Search List View--------------------
if ($action == "show_asset_no_searh_listview") {
    $ex_data = explode("_", $data);
    if ($ex_data[0] == 0) {
        $asset_number = "";
    } else {
        //and c.asset_no LIKE '%" . $ex_data[0] ."'";
        $asset_number = " and c.asset_no LIKE '%" . $ex_data[0] . "'";
    }
    if ($ex_data[1] == 0)
        $company_name = "";
    else
        $company_name = " and a.company_id='" . $ex_data[1] . "'";
    if ($ex_data[2] == 0)
        $location = "";
    else
        $location = " and a.location='" . $ex_data[2] . "'";



    if ($db_type == 0) {  //for MySql
        $sql = "SELECT  a.company_id, a.cost_per_unit, c.id, c.serial_no, c.asset_no FROM fam_acquisition_mst a, fam_acquisition_sl_dtls c  WHERE a.id=c.mst_id AND c.asset_type=1 AND  a.status_active=1 AND a.is_deleted=0 AND c.status_active=1 AND c.is_deleted=0 $asset_number $company_name $location";
    }
    if ($db_type == 2 || $db_type == 1) { // for Oracale and MsSql
        $sql = "SELECT a.company_id, a.cost_per_unit, c.id, c.serial_no, c.asset_no FROM fam_acquisition_mst a, fam_acquisition_sl_dtls c WHERE a.id=c.mst_id AND c.asset_type=1 AND a.status_active=1 AND a.is_deleted=0 AND c.status_active=1 AND c.is_deleted=0 $asset_number $company_name $location";
    }
	$land_fileNO_array = return_library_array("select asset_id,file_number from fam_land_position_mst where status_active =1 and is_deleted=0", "asset_id", "file_number");
	$land_deed_no_array = return_library_array("select asset_id,deed_number from fam_land_position_mst where status_active =1 and is_deleted=0", "asset_id", "deed_number");
    $arr = array(2 => $land_fileNO_array, 3=> $land_deed_no_array );

    echo create_list_view("list_view", "Asset No,Serial No,File Number,Deed Number", "112,112,112,112", "535", "330", 0, $sql, "js_asset_value", "asset_no,id,company_id,cost_per_unit", "", 1, "0,0,id,id", $arr, "asset_no,serial_no,id,id", "requires/land_position_controller", 'setFilterGrid("list_view",-1);', '0,0,0,0');
}


if ($action == "show_asset_active_listview") {
    $ex_data = explode("_", $data);
    if ($ex_data[0] == 0) {
        $asset_number = "";
    } else {
        $asset_number = " and c.asset_no='" . $ex_data[0] . "'";
    }
    if ($ex_data[1] == 0)
        $company_name = "";
    else
        $company_name = " and a.company_id='" . $ex_data[1] . "'";
    if ($ex_data[2] == 0)
        $location = "";
    else
        $location = " and a.location='" . $ex_data[2] . "'";
    if ($ex_data[3] == 0)
        $aseet_type = "";
    else
        $aseet_type = " and a.asset_type='" . $ex_data[3] . "'";
    if ($ex_data[4] == 0)
        $category = "";
    else
        $category = " and a.asset_category='" . $ex_data[4] . "'";

    $txt_date_from = $ex_data[5];
    $txt_date_to = $ex_data[6];

    if ($ex_data[1] == 0) {
        echo "Please Company first";
        //die;
    }

    if ($db_type == 0) {//for mysql
        if ($txt_date_from != "" || $txt_date_to != "") {
            $tran_date = " and a.purchase_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
        }
        $sql = "SELECT  a.id, a.entry_no, c.asset_no, a.location, a.asset_type, a.asset_category, a.store, a.purchase_date, a.qty  FROM fam_land_position_mst a, fam_acquisition_sl_dtls c  WHERE a.id=c.mst_id AND a.status_active=1 AND a.is_deleted=0 $category $aseet_type $location $company_name $asset_number";
    }

    if ($db_type == 2) {//for oracal
        if ($txt_date_from != "" && $txt_date_to != "") {
            $tran_date = " and a.purchase_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
        }
        $sql = "SELECT  a.id, a.entry_no, c.asset_no, a.location, a.asset_type, a.asset_category, a.store, a.purchase_date, a.qty  FROM fam_land_position_mst a, fam_acquisition_sl_dtls c  WHERE a.id=c.mst_id AND a.status_active=1 AND a.is_deleted=0 $category $aseet_type $location $company_name $asset_number";
        //echo $sql;
    }
    //echo $sql; die;
    $company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
    //print_r($company_location );
    $arr = array(2 => $company_location, 3 => $asset_type, 4 => $asset_category, 5 => $store_library);

    echo create_list_view("list_view", "Entry No,Asset No,Location,Type,Category,Store,Purchase Date,Qty", "90,100,100,120,90,140,120", "980", "400", 0, $sql, "js_set_value", "id", "", 1, "0,0,location,asset_type,asset_category,store,0,0", $arr, "entry_no,asset_no,location,asset_type,asset_category,store,purchase_date,qty", "asset_acquisition_controller", '', '0,0,0,0,0,0,0,1');
}

//---------------Sellers Details PopUp -----------------------------
if ($action == "land_sellers_popup") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    //echo $seller_name_break_down;
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >

            <form name="LandSellers_1"  id="LandSellers_1" autocomplete="off">
               <!-- <input type="hidden" id="owner_id">-->
                <table class="rpt_table" id="tbl_sellers" width="900" cellspacing="0" cellpadding="0" border="0" align="center">
                    <thead>
                    <th width="20">SL</th>
                    <th width="150">Seller Name</th>
                    <th width="130">Father's Name</th>
                    <th width="130">Mother's Name</th>
                    <th width="150">Address</th>
                    <th width="90">Phon No.</th>
                    <th width="45">Qty (in Decimal)</th>
                    <th width="80">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        if ($seller_name_break_down != "") {
                            //echo $seller_name_break_down;
                            $seller_name_break_down_data = explode("*", $seller_name_break_down);
                            $i = 1;
                            $totalQty = 0;
                            foreach ($seller_name_break_down_data as $row_data) {
                                $row_data_arr = explode("_", $row_data);
                                ?>

                                <tr id="seller_value="<?php echo $i; ?>" ">
                                    <td><input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> </td>
                                    <td><input type="text" name="SellerName_<?php echo $i; ?>" id="SellerName_<?php echo $i; ?>" value="<?php echo $row_data_arr[2]; ?>"  class="text_boxes" style="width:150px" /> 
                                    <input type="hidden" name="seller_<?php echo $i; ?>" id="seller_<?php echo $i; ?>" value="<?php echo $row_data_arr[2]; ?>"  class="text_boxes" style="width:150px" /> 
                                    </td>
                                    
                                    <td><input type="text" name="SellerFName_<?php echo $i; ?>" id="SellerFName_<?php echo $i; ?>" value="<?php echo $row_data_arr[3]; ?>"  class="text_boxes" style="width:130px"/> </td>
                                    <td><input type="text" name="SellerMName_<?php echo $i; ?>" id="SellerMName_<?php echo $i; ?>" value="<?php echo $row_data_arr[4]; ?>" class="text_boxes" style="width:130px" /> </td>
                                    <td><input type="text" name="address_<?php echo $i; ?>" id="address_<?php echo $i; ?>" value="<?php echo $row_data_arr[5]; ?>" class="text_boxes" style="width:150px" /> </td>
                                    <td><input type="text" name="SellerPhoneNo_<?php echo $i; ?>" id="SellerPhoneNo_<?php echo $i; ?>" value="<?php echo $row_data_arr[6]; ?>" class="text_boxes" style="width:90px" /> </td>
                                    <td><input type="text" name="txtQty_<?php echo $i; ?>" id="txtQty_<?php echo $i; ?>" value="<?php
                                        $totalQty+=$row_data_arr[7];
                                        echo $row_data_arr[7];
                                        ?>" class="text_boxes_numeric" onKeyup="calculate_total_qty();" style="width:45px" /> </td>
                                    <td>
                                        <input type="button" name="btnadd_<?php echo $i; ?>" id="btnadd_<?php echo $i; ?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/>       
                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value="<?php echo $row_data_arr[0] ?>"   class="text_boxes" style="width:200px" />
                                        <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value="<?php echo $row_data_arr[1]; ?>"   class="text_boxes" style="width:200px" />
                                    </td>

                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr id="seller_1">
                                <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                                <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                <td>
                                <input type="text" name="SellerName_1" id="SellerName_1" class="text_boxes" style="width:150px" /> 
                                <input type="hidden" name="seller_1" id="seller_1" class="text_boxes" style="width:150px" /> 
                                </td>

                                <td><input type="text" name="SellerFName_1" id="SellerFName_1" class="text_boxes" style="width:130px" value=""/> </td>
                                <td><input type="text" name="SellerMName_1" id="SellerMName_1" class="text_boxes" style="width:130px" /> </td>
                                <td><input type="text" name="address_1" id="address_1" class="text_boxes" style="width:150px" /> </td>
                                <td><input type="text" name="SellerPhoneNo_1" id="SellerPhoneNo_1" class="text_boxes" style="width:90px" /> </td>
                                <td><input type="text" name="txtQty_1" id="txtQty_1" class="text_boxes_numeric" onKeyup="calculate_total_qty();" style="width:45px" /> </td>
                                <td>
                                    <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                    <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       

                                    <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" value=""   class="text_boxes" style="width:200px" />
                                    <input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"></td>
                            <td style="text-align:right; font-weight:bold">Total:</td>
                            <td><input type="text" name="txtTotalSellQty" id="txtTotalSellQty" class="text_boxes_numeric" style="width:45px" readonly disabled="disabled" value="<?php echo $totalQty; ?>" /></td>
                        </tr>
                        <tr>
                            <td colspan="7" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="SellersDtlsBreakdown()" /></td>
                        </tr>

                    </tfoot>

                </table>  
                <input type="hidden" name="txt_hidden_sellers_data" id="txt_hidden_sellers_data" class="text_boxes" style="width:170px" />
                <input type="hidden" name="txt_hidden_seller" id="txt_hidden_seller" class="text_boxes" style="width:170px" />
                <input type="hidden" name="hidden_total_sell_qty" id="hidden_total_sell_qty" value="" placeholder="hidden_total_sell_qty"/>

                <input type="hidden" name="deleted_set_id" id="deleted_set_id" /> 
                <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" class="text_boxes_numeric"  value=""/> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <script>
	function SellersDtlsBreakdown() {
		var numberOfSeller = "";
		var total_row = $("#tbl_sellers tbody tr").length;
		var SName='';
		
		for (var sl = 1; sl <= total_row; sl++) {
			var hiddenUpdatesetId = $("#hiddenUpdatesetId_" + sl).val();
			var txtMstID = $("#txtMstID_" + sl).val();
			var SellerName = $("#SellerName_" + sl).val();
			SName = $("#SellerName_1").val();
			var SellerFName = $("#SellerFName_" + sl).val();
			var SellerMName = $("#SellerMName_" + sl).val();
			var address = $("#address_" + sl).val();
			var SellerPhoneNo = $("#SellerPhoneNo_" + sl).val();
			var txtQty = $("#txtQty_" + sl).val();

			if (numberOfSeller != '') {
				numberOfSeller += "*" + hiddenUpdatesetId + "_" + txtMstID + "_" + SellerName + "_" + SellerFName + "_" + SellerMName + "_" + address + "_" + SellerPhoneNo + "_" + txtQty;
			} else {
				numberOfSeller += hiddenUpdatesetId + "_" + txtMstID + "_" + SellerName + "_" + SellerFName + "_" + SellerMName + "_" + address + "_" + SellerPhoneNo + "_" + txtQty;
			}
		}
		$('#txt_hidden_sellers_data').val(numberOfSeller);
		$('#txt_hidden_seller').val(SName);		
		//var total_qty = $('#txtTotalQty').val();
		//$('#hidden_total_qty').val( total_qty );

		//alert($('#txt_hidden_owners_data').val()); die;
		//return;

		parent.emailwindow.hide();
	}
	//function math_operation( target_fld, value_fld, operator, fld_range, dec_point)
	function calculate_total_qty() {
		var tot_row = $('#tbl_sellers tbody tr').length;
		var total_Sellerqty = 0;
		for (var sl = 1; sl <= tot_row; sl++)
		{
			total_Sellerqty += $("#txtQty_" + sl).val() * 1;
		}
		$("#txtTotalSellQty").val(number_format(total_Sellerqty, 2, '.', ""));
		//math_operation( "txtTotalSellQty", "txtQty_", "+", tot_row, "2");

	}


	function add_break_down_tr(i) {
		//alert( i);
		var row_num = $('#tbl_sellers tbody tr').length;
		if (row_num != i) {
			return false;
		} else {
			i++;
			//$('#samplepic_' + i).removeAttr("src,value");
			if (row_num < row_num + 1) {
				$("#tbl_sellers tbody tr:last").clone().find("input,select").each(function () {
					$(this).attr({
						'id': function (_, id) {
							var id = id.split("_");
							//alert(id);
							return id[0] + "_" + i;
						},
						'name': function (_, name) {
							var name = name.split("_");
							return name[0] + "_" + i;
						},
						'value': function (_, value) {
							return value
						},
						'src': function (_, src) {
							return src
						}
					});
				}).end().appendTo("#tbl_sellers tbody");
				$('#txtQty_' + i).removeAttr("onKeyup").attr("onKeyup", "calculate_total_qty()");
				$("#tbl_sellers tbody tr:last ").removeAttr('id').attr('id', 'seller_' + i);
				//$("#txtqtyset_"+i).removeAttr('class','text_boxes_numeric').attr('class', 'text_boxes_numeric');
				//$('#decrease_'+i).removeAttr("value").attr("value","-");
				$('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
				$('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");

				$('#txtsl_' + i).val(i);
				$('#SellerName_' + i).val('');
				$('#SellerFName_' + i).val('');
				$('#SellerMName_' + i).val('');
				$('#address_' + i).val('');
				$('#SellerPhoneNo_' + i).val('');
				$('#txtQty_' + i).val('');
				$("#hiddenUpdatesetId_" + i).val('');
				$("#txtMstID_" + i).val('');


				$('#txtQty_' + i).attr('class', 'text_boxes_numeric');
				//var result = parseInt(num1) + parseInt(num2);
				set_all_onclick();
			}
		}
	}

	function fn_deleteRow(rowNo) {
		var deleted_row = $("#deleted_set_id").val();

		if (deleted_row != "")
			deleted_row = deleted_row + ",";
		var numRow = $('#tbl_sellers tbody tr').length;
		if (numRow != rowNo && numRow != 1) {
			return false;
		} else {
			deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
			$("#seller_" + rowNo).remove();
		}
		$("#deleted_set_id").val(deleted_row);
		calculate_total_qty();
	}

    </script>
    </html>
    <?php
}

//--------------Owners Details PopUp -----------------------------
if ($action == "land_owner_popup") {
    //load_html_head_contents($title, $path, $filter, $popup, $unicode, $multi_select, $am_chart)
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    // echo $sell_qty_hidden;
    //echo "<pre>";
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="LandOwners_1"  id="LandOwners_1" autocomplete="off">
                <input type="hidden" id="owner_id">
                <table class="rpt_table" id="tbl_owners" width="800" cellspacing="0" cellpadding="0" border="0" align="center">
                    <thead>
                    <th width="20">SL</th>
                    <th width="150">Owner Name</th>
                    <th width="150">Father's Name</th>
                    <th width="150">Mother's Name</th>
                    <th width="100">Phon No.</th>
                    <th width="45">Qty (in Decimal)</th>
                    <th width="80">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        if ($owner_name_break_down != "") {
                            //echo $owner_name_break_down;
                            $owner_name_break_down_data = explode("*", $owner_name_break_down);
                            $i = 1;
                            $totalQty = 0;
                            foreach ($owner_name_break_down_data as $row_data) {
                                $row_data_arr = explode("_", $row_data);
                                ?>
                                <tr id="Owners_<?php echo $i; ?>">
                                    <td><input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> </td>
                                    <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                    <td>
                                    <input type="text" name="OwnerName_<?php echo $i; ?>" id="OwnerName_<?php echo $i; ?>"  value="<?php echo $row_data_arr[2]; ?>"  class="text_boxes" style="width:150px" /> 
                                    <input type="hidden" name="owner_<?php echo $i; ?>" id="owner_<?php echo $i; ?>"  value="<?php echo $row_data_arr[2]; ?>"  class="text_boxes" style="width:150px" /> 
                                    </td>

                                    <td><input type="text" name="OwnerFName_<?php echo $i; ?>" id="OwnerFName_<?php echo $i; ?>"  value="<?php echo $row_data_arr[3]; ?>"  class="text_boxes" style="width:150px"/> </td>
                                    <td><input type="text" name="OwnerMName_<?php echo $i; ?>" id="OwnerMName_<?php echo $i; ?>"  value="<?php echo $row_data_arr[4]; ?>"  class="text_boxes" style="width:150px" /> </td>
                                    <td><input type="text" name="OwnerPhoneNo_<?php echo $i; ?>" id="OwnerPhoneNo_<?php echo $i; ?>" value="<?php echo $row_data_arr[5]; ?>"  class="text_boxes" style="width:100px" /> </td>
                                    <td><input type="text" name="txtQty_<?php echo $i; ?>" id="txtQty_<?php echo $i; ?>" value="<?php
                                        $totalQty+=$row_data_arr[6];
                                        echo $row_data_arr[6];
                                        ?>"  class="text_boxes_numeric" onKeyup="calculate_total_qty();" style="width:45px" /> </td>
                                    <td>
                                        <input type="button" name="btnadd_<?php echo $i; ?>" id="btnadd_<?php echo $i; ?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/> 

                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value="<?php echo $row_data_arr[0] ?>"   class="text_boxes" style="width:200px" />
                                        <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value="<?php echo $row_data_arr[1] ?>"   class="text_boxes" style="width:200px" />

                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr id="Owners_1">
                                <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                                <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                <td>
                                <input type="text" name="OwnerName_1" id="OwnerName_1" class="text_boxes" style="width:150px" /> 
                                <input type="hidden" name="owner_1" id="owner_1" class="text_boxes" style="width:150px" />
                                </td>

                                <td><input type="text" name="OwnerFName_1" id="OwnerFName_1" class="text_boxes" style="width:150px" value=""/> </td>
                                <td><input type="text" name="OwnerMName_1" id="OwnerMName_1" class="text_boxes" style="width:150px" /> </td>
                                <td><input type="text" name="OwnerPhoneNo_1" id="OwnerPhoneNo_1" class="text_boxes" style="width:100px" /> </td>
                                <td><input type="text" name="txtQty_1" id="txtQty_1" class="text_boxes_numeric" onKeyup="calculate_total_qty();" style="width:45px" /> </td>
                                <td>
                                    <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                    <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/> 


                                    <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" value=""   class="text_boxes" style="width:200px" />
                                    <input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />      
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td style="text-align:right; font-weight:bold">Total:</td>
                            <td>
                                <input type="text" name="txtTotalQty" id="txtTotalQty" value="<?php echo $totalQty; ?>" class="text_boxes_numeric" style="width:45px" readonly disabled="disabled"/>
                                <input type="hidden" name="txtSellQty" id="txtSellQty" value="<?php echo $sell_qty_hidden; ?>" class="text_boxes_numeric" style="width:45px" readonly disabled="disabled"/>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="6" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="asset_set_breakdown()" /></td>
                        </tr>
                    </tfoot>
                </table> 
                <input type="hidden" name="hidden_total_qty" id="hidden_total_qty" value="" /> 
                <input type="hidden" name="txt_hidden_owners_data" id="txt_hidden_owners_data" class="text_boxes" style="width:170px" /> 
                <input type="hidden" name="txt_hidden_owner" id="txt_hidden_owner" class="text_boxes" style="width:170px" /> 
                <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" class="text_boxes_numeric"  value=""/> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <!-- For incrimate field -->
    <script>

	function asset_set_breakdown() {
		//alert( 'okay');
		//return;
		var numberOfOwner = "";
		var Owner = "";
		var total_row = $("#tbl_owners tbody tr").length;
		for (var sl = 1; sl <= total_row; sl++) {

			//alert (cbo_partname);
			var hiddenUpdatesetId = $("#hiddenUpdatesetId_" + sl).val();
			var txtMstID = $("#txtMstID_" + sl).val();
			var OwnerName = $("#OwnerName_" + sl).val();
			Owner = $("#OwnerName_1").val();
			var OwnerFName = $("#OwnerFName_" + sl).val();
			var OwnerMName = $("#OwnerMName_" + sl).val();
			var OwnerPhoneNo = $("#OwnerPhoneNo_" + sl).val();
			var txtQty = $("#txtQty_" + sl).val();

			if (numberOfOwner != '') {
				numberOfOwner += "*" + hiddenUpdatesetId + "_" + txtMstID + "_" + OwnerName + "_" + OwnerFName + "_" + OwnerMName + "_" + OwnerPhoneNo + "_" + txtQty;
			} else {
				numberOfOwner += hiddenUpdatesetId + "_" + txtMstID + "_" + OwnerName + "_" + OwnerFName + "_" + OwnerMName + "_" + OwnerPhoneNo + "_" + txtQty;
			}
		}
		$('#txt_hidden_owners_data').val(numberOfOwner);
  		$('#txt_hidden_owner').val(Owner);
		
		var total_qty = $('#txtTotalQty').val();
		$('#hidden_total_qty').val(total_qty);

		//alert($('#txt_hidden_owners_data').val()); die;
		//return;
		parent.emailwindow.hide();
	}

	//function math_operation( target_fld, value_fld, operator, fld_range, dec_point)
	function calculate_total_qty()
	{
		var tot_row = $('#tbl_owners tbody tr').length;
		//math_operation( "txtTotalQty", "txtQty_", "+", tot_row, "2");
		var total_sellerqty =<?php echo $sell_qty_hidden * 1; ?>;
		var total_wonerqty = 0;
		var error = 0;
		for (var sl = 1; sl <= tot_row; sl++)
		{
			if (error == 0)
			{
				total_wonerqty += $("#txtQty_" + sl).val() * 1;
				if (total_wonerqty > total_sellerqty)
				{
					error = 1;
					alert("Owner Qty Must be same Seller Qty(<?php echo $sell_qty_hidden * 1; ?>)");
					$("#txtQty_" + sl).val('');
					break;
				}
			}
		}

		if (error == 0)
		{
			$("#txtTotalQty").val(number_format(total_wonerqty, 2, '.', ""));
		}

	}

	function add_break_down_tr(i)
	{
		var row_num = $('#tbl_owners tbody tr').length;
		if (row_num != i) {
			return false;
		} else {
			i++;
			//$('#samplepic_' + i).removeAttr("src,value");
			if (row_num < row_num + 1) {
				$("#tbl_owners tbody tr:last").clone().find("input,select").each(function () {
					$(this).attr({
						'id': function (_, id) {
							var id = id.split("_");
							//alert(id);
							return id[0] + "_" + i;
						},
						'name': function (_, name) {
							var name = name.split("_");
							return name[0] + "_" + i;
						},
						'value': function (_, value) {
							return value
						},
						'src': function (_, src) {
							return src
						}
					});
				}).end().appendTo("#tbl_owners tbody");
				$('#txtQty_' + i).removeAttr("onKeyup").attr("onKeyup", "calculate_total_qty()");
				$("#tbl_owners tbody tr:last ").removeAttr('id').attr('id', 'Owners_' + i);
				//$("#txtqtyset_"+i).removeAttr('class','text_boxes_numeric').attr('class', 'text_boxes_numeric');
				//$('#decrease_'+i).removeAttr("value").attr("value","-");
				$('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
				$('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");

				$('#txtsl_' + i).val(i);
				$('#OwnerName_' + i).val('');
				$('#OwnerFName_' + i).val('');
				$('#OwnerMName_' + i).val('');
				$('#OwnerPhoneNo_' + i).val('');
				$('#txtQty_' + i).val('');

				$('#hiddenUpdatesetId_' + i).val('');
				$('#txtMstID_' + i).val('');


				$('#txtQty_' + i).attr('class', 'text_boxes_numeric');
				//var result = parseInt(num1) + parseInt(num2);
				set_all_onclick();
			}
		}
	}

	function fn_deleteRow(rowNo) {
		var deleted_row = $("#deleted_set_id").val();

		if (deleted_row != "")
			deleted_row = deleted_row + ",";
		var numRow = $('#tbl_owners tbody tr').length;
		if (numRow != rowNo && numRow != 1) {
			return false;
		} else {
			deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
			$("#Owners_" + rowNo).remove();
		}
		$("#deleted_set_id").val(deleted_row);
		calculate_total_qty();
	}


    </script>
    </html>
    <?php
}

//--------------Dag & Ledger & Porcha PopUp -----------------------------
if ($action == "dag_and_ledger_popup") {
    //load_html_head_contents($title, $path, $filter, $popup, $unicode, $multi_select, $am_chart)
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    //echo $dag_ledg_porcha_break_down;
    //echo "<pre>";
    ?>
    <script>
	
	function fn_addRow(actual_id) 
	{
		 //return;
		 var serveyValue = $('#cboLandSurveyType_' + actual_id).val();
		var row_num=$('#tbl_DagAndLedger tbody tr').length; //tbody id
		//alert(actual_id);
		row_num++;
		var clone= $("#tr_"+actual_id).clone(); // tr id
		clone.attr({
			id: "tr_"+ row_num,
		});
		
		clone.find("input,select").each(function(){
			  
		$(this).attr({ 
		  'id': function(_, id) { var id=id.split("_"); return id[0] +"_"+ row_num },
		  'name': function(_, name) { var name=name.split("_"); return name[0] +"_"+ row_num  },
		  'value': function(_, value) { return value }              
		});
		 
		}).end();
		
		$("#tr_"+actual_id).after(clone);
		
		$('#increase_'+row_num).removeAttr("onclick").attr("onclick","fn_addRow("+row_num+");");
		//=================================================================================================================
		$('#decrease_'+row_num).removeAttr("onclick").attr("onclick","fn_deleteRow("+row_num+");");
		//===================================================================================================================
		//$("#hiddenExtraTr_"+actual_id).val($("#hiddenExtraTr_"+actual_id).val()+"**"+row_num);
		//alert(serveyValue);
		$('#cboLandSurveyType_' + row_num).val(serveyValue);
		$('#cboLandSurveyType_' + row_num).attr("disabled","disabled");
		$('#sYear_' + row_num).val();
		$('#Dag_' + row_num).val();
		$('#Ledger_' + row_num).val();
		$('#Porcha_' + row_num).val();
		$('#hiddenUpdatesetId_' + row_num).val('');
		$('#txtMstID_' + row_num).val();
		
		$('#txtQty_' + row_num).val('');
		$('#txtQty_' + row_num).removeAttr("onkeyup").attr("onkeyup", "calculate_total_qty();");
		
		$('#txtAmount_' + row_num).val();
		//serial_rearrange();
	}
	
    function fn_deleteRow(rowNo)
	{
		//alert("Dhaka Cotton Correction"); return;
		var deleted_row = $("#deleted_ids").val();

		if (deleted_row != "")
			deleted_row = deleted_row + ",";
			
		var numRow = $('#tbl_DagAndLedger tbody tr').length;
		if ( numRow == 1) {
			return false;
		} else {
			deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
			$("#tr_" + rowNo).remove();
		}
		$("#deleted_ids").val(deleted_row);
	}
    </script>
    
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="DagAndLedger_1"  id="DagAndLedger_1" autocomplete="off">
                <table class="rpt_table" id="tbl_DagAndLedger" width="730" cellspacing="0" cellpadding="0" border="0" align="center">
                    <thead>
                    <th width="70"></th>
                    <th width="80">Survey Year</th>
                    <th width="120">Dag Number</th>
                    <th width="120">Ledger Number</th>
                    <th width="120">Porcha Number</th>
                    <th width="80">Qty (in Decimal)</th>
                   <th width="100">Action</th> 
                    </thead>
                    <tbody>
                        <?php
                        $landsurveymethod = array(1 => 'CS', 2 => 'RS', 3 => 'SA', 4 => 'BS');
                        $surveYear = array(1 => '1940', 2 => '2012', 3 => '1962', 4 => '2016');
                        //echo "50000000********************".count($landsurveymethod); die;
                        /* ?>

                          <?php */

                        if ($dag_ledg_porcha_break_down != "") 
						{
                            $dag_ledg_porcha_break_down_data = explode("*", $dag_ledg_porcha_break_down);
                            $i = 1;
							$totalQty = 0;
                            foreach ($dag_ledg_porcha_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                                //print_r($row_data_arr);
                                ?>

                                <tr  id="tr_<?php echo $i; ?>">
                                    <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                    <td style="font-weight:bolder"> 
                                        <?php
                                        echo create_drop_down("cboLandSurveyType_$i", 70, $landsurveymethod, "", "", "", $selected, "", 1, $row_data_arr[2], "", "", "", "3", "", "");
                                        ?>	
                                    </td>
                                    <td><input type="text" name="sYear_<?php echo $i; ?>" id="sYear_<?php echo $i; ?>" value="<?php echo $row_data_arr[6]; ?>" class="text_boxes" style="width:80px; text-align:center;" /> </td>
                                    <td><input type="text" name="Dag_<?php echo $i; ?>" id="Dag_<?php echo $i; ?>" class="text_boxes_numeric" value="<?php echo $row_data_arr[3]; ?>" style="width:120px;text-align:left;" /> </td>
                                    <td><input type="text" name="Ledger_<?php echo $i; ?>" id="Ledger_<?php echo $i; ?>" class="text_boxes_numeric"  value="<?php echo $row_data_arr[4]; ?>" style="width:120px;text-align:left;" /> </td>
                                    <td>
                                        <input type="text" name="Porcha_<?php echo $i; ?>" id="Porcha_<?php echo $i; ?>" class="text_boxes_numeric" value="<?php echo $row_data_arr[5]; ?>" style="width:120px;text-align:left;" /> 
                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value="<?php echo $row_data_arr[0] ?>"   class="text_boxes" style="width:200px" />
                                        <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value="<?php echo $row_data_arr[1] ?>"   class="text_boxes" style="width:200px" /> 
                                    </td>
                                    <td>
                                    <input type="text" name="txtQty_<?php echo $i; ?>" id="txtQty_<?php echo $i; ?>"  value="<?php 
										$totalQty+=$row_data_arr[7];
                                        echo $row_data_arr[7];
									?>" class="text_boxes_numeric" onKeyup="calculate_total_qty();" style="width:45px" /> 
                                    </td>
                                    <td>
                                   		<input type="button" name="increase_<?php echo $i; ?>" id="increase_<?php echo $i; ?>" value="+" class="formbutton" onClick="fn_addRow(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/> 
                                    </td>
                                </tr>

                                <?php
                                $i++;
                            }
                        } 
						else 
						{
                            for ($i = 1; $i <= 1; $i++) 
							{
                                ?>
                                <tr  id="tr_<?php echo $i; ?>">
                                    <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                    <td style="font-weight:bolder"> 
                                        <?php
                                        //create_drop_down( $field_id, $field_width, $query, $field_list, $show_select, $select_text_msg, $selected_index, $onchange_func, $is_disabled, $array_index, $fixed_options, $fixed_values, $not_show_array_index, $tab_index, $new_conn, $field_name )
                                        echo create_drop_down("cboLandSurveyType_$i", 70,$landsurveymethod, "", "", "",$i, "", '', '', "", "", "", "3", "", "");
                                        ?>	

                                    </td>
                                    <td><input type="text" name="sYear_<?php echo $i; ?>" id="sYear_<?php echo $i; ?>" class="text_boxes" value="<?php echo $surveYear[$i]; ?>" style="width:80px; text-align:center;" /> </td>
                                    <td><input type="text" name="Dag_<?php echo $i; ?>" id="Dag_<?php echo $i; ?>" class="text_boxes_numeric" style="width:120px;text-align:left;" /> </td>
                                    <td><input type="text" name="Ledger_<?php echo $i; ?>" id="Ledger_<?php echo $i; ?>" class="text_boxes_numeric" style="width:120px;text-align:left;" /> </td>
                                    <td>
                                        <input type="text" name="Porcha_<?php echo $i; ?>" id="Porcha_<?php echo $i; ?>" class="text_boxes_numeric" style="width:120px;text-align:left;" /> 

                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value=""   class="text_boxes" style="width:200px" />
                                        <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value=""   class="text_boxes" style="width:200px" />     
                                    </td> 
                                    <td>
                                    	<input type="text" name="txtQty_<?php echo $i; ?>" id="txtQty_<?php echo $i; ?>" value="" class="text_boxes_numeric" onKeyup="calculate_total_qty();" style="width:45px" /> 
                                    </td>
                                    <td>
                                   		<input type="button" name="increase_<?php echo $i; ?>" id="increase_<?php echo $i; ?>" value="+" class="formbutton" onClick="fn_addRow(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/> 
                                    </td>
                                </tr>

                                <?php
                            }
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td style="text-align:right; font-weight:bold">Total:</td>
                            <td>
                                <input type="text" name="txtTotalQty" id="txtTotalQty" value="<?php echo $totalQty; ?>" class="text_boxes_numeric" style="width:45px" readonly disabled="disabled"/>
                                <input type="hidden" name="txtLandQty" id="txtLandQty" value="<?php echo $land_qty_hidden; ?>" class="text_boxes_numeric" style="width:45px" readonly disabled="disabled"/>
                            </td>
                        </tr>
                    </tfoot>
                </table> 
                <br/> 
                <input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="dag_ledger_breakdown()" style="width:100px;height:30px;" />
                <input type="hidden" name="txt_hidden_dag_ledger_porcha_data" id="txt_hidden_dag_ledger_porcha_data" class="text_boxes" style="width:170px" /> 
                <input type="hidden" name="deleted_ids" id="deleted_ids" class="text_boxes" style="width:170px" /> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <!-- For incrimate field -->
    <script>
		//function math_operation( target_fld, value_fld, operator, fld_range, dec_point)
		function dag_ledger_breakdown() {
			//alert( 'okay');
			//return;
			var DagLedgerPorcha = "";
			var total_row = $("#tbl_DagAndLedger tbody tr").length;
			for (var sl = 1; sl <= total_row; sl++) {
	
				//alert (cbo_partname);
				var hiddenUpdatesetId = $("#hiddenUpdatesetId_" + sl).val();
				var txtMstID = $("#txtMstID_" + sl).val();
				var cbo_landSurveyType = $("#cboLandSurveyType_" + sl).val();
				//alert(cbo_landSurveyType);
				var DagNo = $("#Dag_" + sl).val();
				var LedgerNo = $("#Ledger_" + sl).val();
				var PorchaNo = $("#Porcha_" + sl).val();
				var SurveyYear = $("#sYear_" + sl).val();
				var txtQty = $("#txtQty_" + sl).val();
	
				if (DagLedgerPorcha != '') {
					DagLedgerPorcha += "*" + hiddenUpdatesetId + "_" + txtMstID + "_" + cbo_landSurveyType + "_" + DagNo + "_" + LedgerNo + "_" + PorchaNo + "_" + SurveyYear + "_" + txtQty;
				} else {
					DagLedgerPorcha += hiddenUpdatesetId + "_" + txtMstID + "_" + cbo_landSurveyType + "_" + DagNo + "_" + LedgerNo + "_" + PorchaNo + "_" + SurveyYear + "_" + txtQty;
				}
			}
			
			$('#txt_hidden_dag_ledger_porcha_data').val(DagLedgerPorcha);
			parent.emailwindow.hide();
		}
		
		function calculate_total_qty()
		{
			//alert('under developing');return;
			var tot_row = $('#tbl_DagAndLedger tbody tr').length;
			//alert(tot_row);
			//math_operation( "txtTotalQty", "txtQty_", "+", tot_row, "2");
			var total_amt_of_land =<?php echo $amt_of_land_hidden * 1; ?>;
			var grand_total_qty = 0;
			var error = 0;
			for (var sl = 1; sl <= tot_row; sl++)
			{
				if (error == 0)
				{
					grand_total_qty += $("#txtQty_" + sl).val() * 1;
					if (grand_total_qty > total_amt_of_land)
					{
						error = 1;
						alert("Not over then Seller Qty(<?php echo $amt_of_land_hidden * 1; ?>)");
						$("#txtQty_" + sl).val('');
						break;
					}
				}
			}
	
			if (error == 0)
			{
				$("#txtTotalQty").val(number_format(grand_total_qty, 2, '.', ""));
			}
	
		}
    </script>
    </html>
    <?php
}


//---------------VIA Document Details PopUp -----------------------------
if ($action == "via_document_popup") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="frmViaDocDtls_1"  id="frmViaDocDtls_1" autocomplete="off">
               <!-- <input type="hidden" id="owner_id">-->
                <table class="rpt_table" id="tbl_via_doc" width="735" cellspacing="0" cellpadding="0" border="0" align="center">
                    <thead>
                    <th width="20">SL</th>

                    <th width="130">Seller Name</th>
                    <th width="130">Father's Name</th>
                    <th width="130">Mother's Name</th>
                    <th width="80">Selling Date</th>
                    <th width="80">Deed No</th>

                    <th width="80">Action</th>
                    </thead>
                    <tbody>

                        <?php
                        if ($via_document_break_down != "") {
                            $via_document_break_down_data = explode("*", $via_document_break_down);
                            $i = 1;
                            foreach ($via_document_break_down_data as $row_data) {
                                $row_data_arr = explode("_", $row_data);
                                ?>
                                <tr id="ViaDoc_<?php echo $i; ?>">
                                    <td><input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> </td>
                                    <td><input type="text" id="SellerName_<?php echo $i; ?>" name="SellerName_<?php echo $i; ?>" value="<?php echo $row_data_arr[2] ?>"   class="text_boxes" style="width:130px" /> </td>
                                    <td><input type="text" id="FatherName_<?php echo $i; ?>" name="FatherName_<?php echo $i; ?>" value="<?php echo $row_data_arr[3] ?>"  class="text_boxes" style="width:130px" /> </td>
                                    <td><input type="text" id="MotherName_<?php echo $i; ?>" name="MotherName_<?php echo $i; ?>" value="<?php echo $row_data_arr[4] ?>"  class="text_boxes" style="width:130px" /> </td>
                                    <td><input type="text" id="SellingDate_<?php echo $i; ?>" name="SellingDate_<?php echo $i; ?>" value="<?php echo change_date_format($row_data_arr[5], 'dd-mm-yyyy') ?>"  class="datepicker" style="width:80px" readonly /> </td>
                                    <td><input type="text" id="deedNo_<?php echo $i; ?>" name="deedNo_<?php echo $i; ?>" value="<?php echo $row_data_arr[6] ?>"  class="text_boxes" style="width:80px" /> </td>

                                    <td>
                                        <input type="button" name="btnadd_<?php echo $i; ?>" id="btnadd_<?php echo $i; ?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/>       

                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" value="<?php echo $row_data_arr[0] ?>"   class="text_boxes" style="width:200px" />
                                        <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>" value="<?php echo $row_data_arr[1] ?>"   class="text_boxes" style="width:200px" />  
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr id="ViaDoc_1">
                                <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                                <td><input type="text" id="SellerName_1" name="SellerName_1"  class="text_boxes" style="width:130px" /> </td>
                                <td><input type="text" id="FatherName_1" name="FatherName_1" class="text_boxes" style="width:130px" /> </td>
                                <td><input type="text" id="MotherName_1" name="MotherName_1" class="text_boxes" style="width:130px" /> </td>
                                <td><input type="text" id="SellingDate_1" name="SellingDate_1" class="datepicker" style="width:80px" readonly/> </td>
                                <td><input type="text" id="deedNo_1" name="deedNo_1" class="text_boxes" style="width:80px" /> </td>

                                <td>
                                    <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                    <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       

                                    <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" value=""   class="text_boxes" style="width:200px" />
                                    <input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />  
                                </td>
                            </tr>
                        <?php } ?>


                    </tbody>
                    <tfoot>
                        <tr>
                            <td  colspan="7" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="viaDocBreakdown()" /></td>
                        </tr>
                    </tfoot>
                </table>  
                <input type="hidden" name="txt_hidden_via_doc_data" id="txt_hidden_via_doc_data" class="text_boxes" style="width:170px" readonly placeholder="txt_hidden_via_doc_data"/> 
                <input type="hidden" name="deleted_id" id="deleted_id" /> 

                <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" class="text_boxes_numeric"  value=""/> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <!-- For incrimate field -->
    <script>

		function viaDocBreakdown() 
		{
			//alert( 'okay');
			//return;
			var numberOfBuySell = "";
			var total_row = $("#tbl_via_doc tbody tr").length;
			for (var sl = 1; sl <= total_row; sl++) {

				//alert ("DKJFALKJ");
				//var txtsl 		= $("#txtsl_" + sl).val();
				var hiddenUpdatesetId = $("#hiddenUpdatesetId_" + sl).val();
				var txtMstID = $("#txtMstID_" + sl).val();
				var SellerName = $("#SellerName_" + sl).val();
				var FatherName = $("#FatherName_" + sl).val();
				var MotherName = $("#MotherName_" + sl).val();
				var SellingDate = $("#SellingDate_" + sl).val();
				var deedNo = $("#deedNo_" + sl).val();
				//alert (SellingDate);

				if (numberOfBuySell != '') {
					numberOfBuySell += "*" + hiddenUpdatesetId + "_" + txtMstID + "_" + SellerName + "_" + FatherName + "_" + MotherName + "_" + SellingDate + "_" + deedNo;
				} else {
					numberOfBuySell += hiddenUpdatesetId + "_" + txtMstID + "_" + SellerName + "_" + FatherName + "_" + MotherName + "_" + SellingDate + "_" + deedNo;
				}
			}

			$('#txt_hidden_via_doc_data').val(numberOfBuySell);
			//alert($('#txt_hidden_via_doc_data').val(numberOfBuySell)); die;
			//return;
			parent.emailwindow.hide();
		}

		function add_break_down_tr(i) 
		{
			var row_num = $('#tbl_via_doc tbody tr').length;
			if (row_num != i) {
				return false;
			} else {
				i++;
				//$('#samplepic_' + i).removeAttr("src,value");
				if (row_num < row_num + 1) {
					$("#tbl_via_doc tbody tr:last").clone().find("input,select").each(function () {
						$(this).attr({
							'id': function (_, id) {
								var id = id.split("_");
								//alert(id);
								return id[0] + "_" + i
							},
							'name': function (_, name) {
								var name = name.split("_");
								return name[0] + "_" + i;
							},
							'value': function (_, value) {
								return value
							},
							'src': function (_, src) {
								return src
							}
						});
					}).end().appendTo("#tbl_via_doc tbody");
					$("#tbl_via_doc tbody tr:last ").removeAttr('id').attr('id', 'ViaDoc_' + i);
					//$("#txtqtyset_"+i).removeAttr('class','text_boxes_numeric').attr('class', 'text_boxes_numeric');
					//$('#decrease_'+i).removeAttr("value").attr("value","-");
					$('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
					$('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");

					$('#txtsl_' + i).val(i);

					$('#hiddenUpdatesetId_' + i).val('');
					$('#txtMstID_' + i).val('');
					$('#SellerName_' + i).val('');
					$("#FatherName_" + i).val('');
					$("#MotherName_" + i).val('');
					$('#SellingDate_' + i).val('');
					$('#deedNo_' + i).val('');


					$('#SellingDate_' + i).attr('class', 'datepicker');
					//var result = parseInt(num1) + parseInt(num2);
					set_all_onclick();
				}
			}
		}

		function fn_deleteRow(rowNo)
		{
			var deleted_row = $("#deleted_set_id").val();

			if (deleted_row != "")
				deleted_row = deleted_row + ",";
			var numRow = $('#tbl_via_doc tbody tr').length;
			if (numRow != rowNo && numRow != 1) {
				return false;
			} else {
				deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
				$("#ViaDoc_" + rowNo).remove();
			}
			$("#deleted_set_id").val(deleted_row);
		}



    </script>
    </html>
    <?php
}

//---------------Tax Outstanding Details PopUp -----------------------------
if ($action == "tax_outstanding_amt_popup") {
    //load_html_head_contents($title, $path, $filter, $popup, $unicode, $multi_select, $am_chart)
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="frmTaxOutstandingDtls_1"  id="frmTaxOutstandingDtls_1" autocomplete="off">
               <!-- <input type="hidden" id="owner_id">-->
                <table class="rpt_table" id="tbl_Tax_outstanding" width="500" cellspacing="0" cellpadding="0" border="0" align="center">
                    <thead>
                    <th width="20">SL</th>

                    <th width="80">Year</th>
                    <th width="80">Tax Amount</th>
                    <th width="80">Paid Amount</th>
                    <th width="80">Due Amount</th>

                    <th width="80">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        if ($tax_outstanding_amount_break_down != "") {
                            $tax_outstanding_amount_break_down_data = explode("*", $tax_outstanding_amount_break_down);
                            $i = 1;
                            foreach ($tax_outstanding_amount_break_down_data as $row_data) {
                                $row_data_arr = explode("_", $row_data);

                                //print_r($row_data_arr); die;
                                ?>
                                <tr id="TaxOutstanding_<?php echo $i; ?>">

                                    <td><input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> </td>
                                    <td><input type="text" id="Year_<?php echo $i; ?>" name="Year_<?php echo $i; ?>"  class="text_boxes" value="<?php echo $row_data_arr[2] ?>" style="width:80px" /> </td>
                                    <td><input type="text" id="TaxAmount_<?php echo $i; ?>" name="TaxAmount_<?php echo $i; ?>" class="text_boxes_numeric" value="<?php echo $row_data_arr[3] ?>"  onKeyUp="calculate_TaxOutstanding()" style="width:80px" value=""/> </td>
                                    <td><input type="text" id="PaidAmount_<?php echo $i; ?>" name="PaidAmount_<?php echo $i; ?>" class="text_boxes_numeric" value="<?php echo $row_data_arr[4] ?>"  onKeyUp="calculate_TaxOutstanding();" style="width:80px" /> </td>
                                    <td><input type="text" id="DueAmount_<?php echo $i; ?>" name="DueAmount_<?php echo $i; ?>" class="text_boxes_numeric" value="<?php echo $row_data_arr[5] ?>"  style="width:80px"  readonly disabled="disabled"/> </td>

                                    <td>
                                        <input type="button" name="btnadd_<?php echo $i; ?>" id="btnadd_<?php echo $i; ?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/>             

                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>"  value="<?php echo $row_data_arr[0] ?>"   class="text_boxes" style="width:200px" />
                                        <input type="hidden" name="txtMstID_<?php echo $i; ?>" id="txtMstID_<?php echo $i; ?>"  value="<?php echo $row_data_arr[1] ?>"   class="text_boxes" style="width:200px" />  
                                    </td>

                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr id="TaxOutstanding_1">

                                <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                                <td><input type="text" id="Year_1" name="Year_1"  class="text_boxes" style="width:80px" /> </td>
                                <td><input type="text" id="TaxAmount_1" name="TaxAmount_1"class="text_boxes_numeric" onKeyUp="calculate_TaxOutstanding()" style="width:80px" value=""/> </td>
                                <td><input type="text" id="PaidAmount_1" name="PaidAmount_1" class="text_boxes_numeric" onKeyUp="calculate_TaxOutstanding();" style="width:80px" /> </td>
                                <td><input type="text" id="DueAmount_1" name="DueAmount_1" class="text_boxes_numeric" style="width:80px"  readonly disabled="disabled"/> </td>

                                <td>
                                    <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                    <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>             

                                    <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" value=""   class="text_boxes" style="width:200px" />
                                    <input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />  
                                </td>

                            </tr>
                        <?php } ?>

                    </tbody>
                    <tfoot >
                        <tr style="border:none">
                            <td colspan="2" style="text-align:right; font-weight:bold; border:none;">Total:</td>
                            <td  style="border:none"><input type="text" name="TotalTaxAmount" id="TotalTaxAmount" class="text_boxes_numeric" style="width:80px" readonly disabled="disabled"/></td>
                            <td  style="border:none"><input type="text" name="TotalTaxPaidAmount" id="TotalTaxPaidAmount" class="text_boxes_numeric" style="width:80px" readonly disabled="disabled"/></td>
                            <td  style="border:none"><input type="text" name="TotalDueAmount" id="TotalDueAmount" class="text_boxes_numeric" style="width:80px" readonly disabled="disabled"/></td>
                        </tr>
                        <tr>
                            <td colspan="6" align="center" style="border:none"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="taxOutstandingAmount()" /></td>
                        </tr>
                    </tfoot>
                </table>  
                <input type="hidden" name="txt_hidden_tax_outstanding_data" id="txt_hidden_tax_outstanding_data" class="text_boxes" style="width:170px" /> 

                <input type="hidden" name="deleted_id" id="deleted_id" /> 

            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <!-- For incrimate field -->
    <script>
	function taxOutstandingAmount() {
		//alert( 'okay');
		//return;
		var taxOutstandingamt = "";
		var total_row = $("#tbl_Tax_outstanding tbody tr").length;
		for (var sl = 1; sl <= total_row; sl++) {

			//alert (cbo_partname);
			var hiddenUpdatesetId = $("#hiddenUpdatesetId_" + sl).val();
			var txtMstID = $("#txtMstID_" + sl).val();
			var Year = $("#Year_" + sl).val();
			var TaxAmount = $("#TaxAmount_" + sl).val();
			var PaidAmount = $("#PaidAmount_" + sl).val();
			var DueAmount = $("#DueAmount_" + sl).val();

			if (taxOutstandingamt != '') {
				taxOutstandingamt += "*" + hiddenUpdatesetId + "_" + txtMstID + "_" + Year + "_" + TaxAmount + "_" + PaidAmount + "_" + DueAmount;
			} else {
				taxOutstandingamt += hiddenUpdatesetId + "_" + txtMstID + "_" + Year + "_" + TaxAmount + "_" + PaidAmount + "_" + DueAmount;
			}
		}
		$('#txt_hidden_tax_outstanding_data').val(taxOutstandingamt);
		parent.emailwindow.hide();
	}

	function calculate_TaxOutstanding() {
		var total_row = $('#tbl_Tax_outstanding tbody tr').length;

		var taxTot = 0;
		var paidTot = 0;
		var dueTot = 0;

		for (i = 1; i <= total_row; i++) {
			var tax = $('#TaxAmount_' + i).val() * 1;
			var paid = $('#PaidAmount_' + i).val() * 1;
			var due = tax - paid;
			$('#DueAmount_' + i).val(due);
			taxTot += tax;
			paidTot += paid;
			dueTot += due;
		}

		$('#TotalTaxAmount').val(taxTot);
		$('#TotalTaxPaidAmount').val(paidTot);
		$('#TotalDueAmount').val(dueTot);
	}


	function add_break_down_tr(i) {
		var row_num = $('#tbl_Tax_outstanding tbody tr').length;
		if (row_num != i) {
			return false;
		} else {
			i++;
			//$('#samplepic_' + i).removeAttr("src,value");
			if (row_num < row_num + 1) {
				$("#tbl_Tax_outstanding tbody tr:last").clone().find("input,select").each(function () {
					$(this).attr({
						'id': function (_, id) {
							var id = id.split("_");
							//alert(id);
							return id[0] + "_" + i
						},
						'name': function (_, name) {
							var name = name.split("_");
							return name[0] + "_" + i;
						},
						'value': function (_, value) {
							return value
						},
						'src': function (_, src) {
							return src
						}
					});
				}).end().appendTo("#tbl_Tax_outstanding tbody");
				$("#tbl_Tax_outstanding tbody tr:last ").removeAttr('id').attr('id', 'TaxOutstanding_' + i);
				//$("#txtqtyset_"+i).removeAttr('class','text_boxes_numeric').attr('class', 'text_boxes_numeric');
				//$('#decrease_'+i).removeAttr("value").attr("value","-");
				$('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
				$('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");

				$('#txtsl_' + i).val(i);

				$('#hiddenUpdatesetId_' + i).val('');
				$('#txtMstID_' + i).val('');
				$('#Year_' + i).val('');
				$('#TaxAmount_' + i).val('');
				$('#PaidAmount_' + i).val('');
				$('#DueAmount_' + i).val('');

				//$('#Year_' + i).attr('class', 'datepicker');
				//$('#SellingDate_' + i).attr('class','datepicker');
				//var result = parseInt(num1) + parseInt(num2);
				set_all_onclick();
			}
		}
	}

	function fn_deleteRow(rowNo) {
		var deleted_row = $("#deleted_set_id").val();

		if (deleted_row != "")
			deleted_row = deleted_row + ",";
		var numRow = $('#tbl_Tax_outstanding tbody tr').length;
		if (numRow != rowNo && numRow != 1) {
			return false;
		} else {
			deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
			$("#TaxOutstanding_" + rowNo).remove();
		}
		$("#deleted_set_id").val(deleted_row);
		calculate_TaxOutstanding();
	}

    </script>
    </html>
    <?php
}


if ($action == "save_update_delete_mst") 
{
    $process = array(&$_POST);
    extract(check_magic_quote_gpc($process));
	
	$txt_deed_value = str_replace(",", "", $txt_deed_value);
	$txt_purchase_cost = str_replace(",", "", $txt_purchase_cost);
	$txt_pre_market_value = str_replace(",", "", $txt_pre_market_value);
	$txt_booking_amount = str_replace(",", "", $txt_booking_amount);
	$txt_court_fee = str_replace(",", "", $txt_court_fee);

// Start: Insert Here----------------------------------------------------------
    if ($operation == 0) {
        $con = connect();
        if ($db_type == 0) {
            mysql_query("BEGIN");
        }

        $id_mst = return_next_id("id", "fam_land_position_mst", 1);

        if ($db_type == 2) {
            $year_id = " extract(year from insert_date)=";
        }

        if ($db_type == 0) {
            $year_id = "YEAR(insert_date)=";
        }



        $field_array = "id,asset_id,asset_no,company_id,land_location,address,mouja_name,file_number,amt_of_land,deed_number,east_owner,west_owner,south_owner,north_owner,deed_value,purchase_cost,pre_market_value,booking_date,booking_amount,selling_date,reg_office,court_fee,orig_deed_recpt_no,registry_property,copy_doc_rev_date,original_deed_rev_date,orig_deed_poss_coll_date,land_possession,possession_under,tax_paid_up_to,tax_payment_date,tax_maturity_date,havingCase,mortgage_given,mortgage_no,mortgage_date,mortgage_to,branch_name,which_company,inserted_by,insert_date,is_mutation,entry_form_name";

        $data_array = "(" . $id_mst . "," . $hidden_AssetId . "," . $txt_asset_no . "," . $cbo_company_name . "," . $txt_land_location . "," . $txt_address . "," . $txt_mouja_name . "," . $txt_file_number . "," . $txt_amt_of_land . "," . $txt_deed_number . "," . $txt_east_owner . "," . $txt_west_owner . "," . $txt_south_owner . "," . $txt_north_owner . "," . $txt_deed_value . "," . $txt_purchase_cost . "," . $txt_pre_market_value . "," . $txt_booking_date . "," . $txt_booking_amount . "," . $txt_selling_date . "," . $txt_reg_office . "," . $txt_court_fee . "," . $txt_orig_deed_recpt_no . "," . $cbo_registry_property . "," . $txt_copy_doc_rev_date . "," . $txt_original_deed_rev_date . "," . $txt_orig_deed_poss_coll_date . "," . $cbo_land_possession . "," . $txt_possession_under . "," . $txt_tax_paid_up_to . "," . $txt_tax_payment_date . "," . $txt_tax_maturity_date . "," . $cbo_havingCase . "," . $cbo_mortgage_given . "," . $txt_mortgage_no . "," . $txt_mortgage_date . "," . $txt_mortgage_to . "," . $txt_branch_name . "," . $txt_which_company . "," . $_SESSION['logic_erp']['user_id'] . ",'" . $pc_date_time . "',". $cbo_is_mutation. "," . "1" . ")";

        //Start : Insert Sellers Details --------------------------------------------------
        $txt_seller_name_hidden = explode("*", str_replace("'", "", $txt_seller_name));
        //echo $txt_seller_name_hidden; die;
        $id_seller = return_next_id("id", "fam_land_sellers_dtls", 1);
        $field_array_sellers_dtls = "id,mst_id,seller_name,father_name,mother_name,address,phone_no,qty,inserted_by,insert_date";

        for ($c = 0; $c < count($txt_seller_name_hidden); $c++) {
            $seller_dtls_popup = explode("_", $txt_seller_name_hidden[$c]);
            if ($data_array_seller_dtls != "")
                $data_array_seller_dtls .=",";
            //print_r($seller_dtls_popup); die;

            $data_array_seller_dtls .="('" . $id_seller . "','" . $id_mst . "','" . $seller_dtls_popup[2] . "','" . $seller_dtls_popup[3] . "','" . $seller_dtls_popup[4] . "','" . $seller_dtls_popup[5] . "','" . $seller_dtls_popup[6] . "','" . $seller_dtls_popup[7] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
            $id_seller = $id_seller + 1;
        }
        //echo "10**insert into fam_land_sellers_dtls($field_array_sellers_dtls)values".$data_array_seller_dtls;		die;
        //End : Insert Sellers Details --------------------------------------------------
        //Start : Insert Owner Details --------------------------------------------------
        $txt_owner_name_hidden = explode("*", str_replace("'", "", $txt_owner_name));
        $id_owner = return_next_id("id", "fam_land_owner_dtls", 1);
        $field_array_owners_dtls = "id,mst_id,owner_name,father_name,mother_name,phone_no,qty,inserted_by,insert_date";

        for ($a = 0; $a < count($txt_owner_name_hidden); $a++) {
            $owner_name_popup = explode("_", $txt_owner_name_hidden[$a]);
            if ($data_array_owner_dtls != "") {
                $data_array_owner_dtls .=",";
            }
            //print_r($owner_name_popup); die;
            //print_r($id_mst); die;

            $data_array_owner_dtls .="('" . $id_owner . "','" . $id_mst . "','" . $owner_name_popup[2] . "','" . $owner_name_popup[3] . "','" . $owner_name_popup[4] . "','" . $owner_name_popup[5] . "','" . $owner_name_popup[6] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

            $id_owner = $id_owner + 1;
        }
        //echo "10**insert into fam_land_owner_dtls($field_array_owners_dtls)values".$data_array_owner_dtls;     die;
        //End  : Insert Owner Details --------------------------------------------------
       
	    //Start : Insert Dag, Ledger AND Porcha Details --------------------------------------------------
        $txt_dagLedgerPorcha_hidden = explode("*", str_replace("'", "", $txt_dag_ledg_porcha_number));
        $idServey = return_next_id("id", "fam_land_surveytype_dtls", 1);
        $field_array_SurveyType_dtls = "id,mst_id,landSurveyType,dagNo,ledgerNo,porchaNo,s_year,qty,inserted_by,insert_date";

        for ($a = 0; $a < count($txt_dagLedgerPorcha_hidden); $a++) {
			//print_r($txt_dagLedgerPorcha_hidden[$a]); die;
		
            $dagLedgerPorcha_popup = explode("_", $txt_dagLedgerPorcha_hidden[$a]);
            if ($data_array_SurveyType_dtls != "") {
                $data_array_SurveyType_dtls .=",";
            }
            //print_r($dagLedgerPorcha_popup); die;
            //print_r($id_mst); die;
			if(trim($dagLedgerPorcha_popup[3]) != '' && trim($dagLedgerPorcha_popup[4]) != '' && trim($dagLedgerPorcha_popup[5]) != '' && trim($dagLedgerPorcha_popup[6]) != '' && trim($dagLedgerPorcha_popup[7]) != '')
			{
            $data_array_SurveyType_dtls .="('" . $idServey . "','" . $id_mst . "','" . $dagLedgerPorcha_popup[2] . "','" . $dagLedgerPorcha_popup[3] . "','" . $dagLedgerPorcha_popup[4] . "','" . $dagLedgerPorcha_popup[5] . "','" . $dagLedgerPorcha_popup[6] . "','" . $dagLedgerPorcha_popup[7] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			}

            $idServey = $idServey + 1;
        }
        //echo "10**insert into fam_land_surveytype_dtls($field_array_SurveyType_dtls)values".$data_array_SurveyType_dtls; die;
        //End  : Insert Dag, Ledger AND Porcha Details --------------------------------------------------
        
		//Start : Insert VIA Documents Details --------------------------------------------------
        $txt_via_document_hidden = explode("*", str_replace("'", "", $txt_via_document));
        $id_viaDoc = return_next_id("id", "fam_land_via_dtls", 1);
        $field_array_viaDoc_dtls = "id,mst_id,seller_name,father_name,mother_name,selling_date,deed_no,inserted_by,insert_date";

        for ($b = 0; $b < count($txt_via_document_hidden); $b++) {
            $via_document_popup = explode("_", $txt_via_document_hidden[$b]);
            if ($data_array_viaDoc_dtls != "")
                $data_array_viaDoc_dtls .=",";
            //print_r($via_document_popup); die;
            //print_r($id_mst);die;
            $txt_selling_date = $via_document_popup[5];
            if ($db_type == 0)
                $selling_date = change_date_format($txt_selling_date, 'yyyy-mm-dd');
            if ($db_type == 2)
                $selling_date = change_date_format(str_replace("'", "", $txt_selling_date), "yyyy-mm-dd", "-", 1);


            $data_array_viaDoc_dtls .="('" . $id_viaDoc . "','" . $id_mst . "','" . $via_document_popup[2] . "','" . $via_document_popup[3] . "','" . $via_document_popup[4] . "','" . $selling_date . "','" . $via_document_popup[6] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

            $id_viaDoc = $id_viaDoc + 1;
        }
        //echo "10**insert into fam_land_via_dtls($field_array_viaDoc_dtls)values".$data_array_viaDoc_dtls; die;
        //End : Insert VIA Documents Details --------------------------------------------------
        //Start : Insert Tax Outstanding Amount Details --------------------------------------------------
        $txt_tax_outstanding_amount_hidden = explode("*", str_replace("'", "", $txt_tax_outstanding_amount));
        $id_tax = return_next_id("id", "fam_land_tax_dtls", 1);
        $field_array_tax_dtls = "id,mst_id,payment_year,tax_amount,paid_amount,due_amount,inserted_by,insert_date";

        for ($d = 0; $d < count($txt_tax_outstanding_amount_hidden); $d++) {
            $tax_amount_hidden_popup = explode("_", $txt_tax_outstanding_amount_hidden[$d]);
            //print_r($tax_amount_hidden_popup);die;
            //print_r($tax_amount_hiddenpopup[2]); die;
            if ($data_array_tax_dtls != "")
                $data_array_tax_dtls .=",";

            $data_array_tax_dtls .="('" . $id_tax . "','" . $id_mst . "','" . $tax_amount_hidden_popup[2] . "','" . $tax_amount_hidden_popup[3] . "','" . $tax_amount_hidden_popup[4] . "','" . $tax_amount_hidden_popup[5] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
            $id_tax = $id_tax + 1;
        }
        //echo "10**insert into fam_land_tax_dtls($field_array_tax_dtls)values".$data_array_tax_dtls;die;
        //End : Insert Tax Outstanding Amount Details --------------------------------------------------
        //Start : Set Qty PopUp data ---------------------------------------
        //End : Set Serial No PopUp data ---------------------------------------
		
		$rID=1; $rID1=1; $rID2=1; $rID3=1; $rID4=1; $rID5=1;
		
        //echo "10**insert into fam_land_position_mst($field_array)values".$data_array;die;
        $rID = sql_insert("fam_land_position_mst", $field_array, $data_array, 0);


        if ($data_array_seller_dtls != "") {
            $rID1 = sql_insert("fam_land_sellers_dtls", $field_array_sellers_dtls, $data_array_seller_dtls, 1);
        }

        if ($data_array_owner_dtls != "") {
            $rID2 = sql_insert("fam_land_owner_dtls", $field_array_owners_dtls, $data_array_owner_dtls, 1);
        }

        if ($data_array_SurveyType_dtls != "") {
            //echo "10**insert into fam_land_surveytype_dtls($field_array_SurveyType_dtls)values".$data_array_SurveyType_dtls; die;
            $rID3 = sql_insert("fam_land_surveytype_dtls", $field_array_SurveyType_dtls, $data_array_SurveyType_dtls, 1);
        }

        if ($data_array_viaDoc_dtls != "") {
            $rID4 = sql_insert("fam_land_via_dtls", $field_array_viaDoc_dtls, $data_array_viaDoc_dtls, 1);
        }

        if ($data_array_tax_dtls != "") {
            $rID5 = sql_insert("fam_land_tax_dtls", $field_array_tax_dtls, $data_array_tax_dtls, 1);
        }

        //echo "10**".$rID ."**". $rID1  ."**". $rID2 ."**". $rID3  ."**". $rID4  ."**". $rID5;   die;
        $purchase_cost = str_replace("'", "", $txt_purchase_cost);

        if ($db_type == 0) {
            if ($rID && $rID1 && $rID2 && $rID3 && $rID4 && $rID5) {

                mysql_query("COMMIT");
                echo "0**" . $id_mst . "**" . $purchase_cost;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $id_mst;
            }
        }

        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1 && $rID2 && $rID3 && $rID4 && $rID5) {
                oci_commit($con);
                echo "0**" . $id_mst . "**" . $purchase_cost;
            } else {
                oci_rollback($con);
                echo "10**" . $id_mst;
            }
        }
        disconnect($con);
        die;
    }
// End : Insert ------------------------------------------------------
// Start : Update Here----------------------------------------------------------
    else if ($operation == 1) {
        $con = connect();

        if ($db_type == 0) {
            mysql_query("BEGIN");
        }

        //echo $update_id;die;
        //Start : Update_FAM_LAND_POSITION_MST PopUp  ---------------------------------------
        $field_array = "asset_id*asset_no*company_id*land_location*address*mouja_name*file_number*amt_of_land*deed_number*east_owner*west_owner*south_owner*north_owner*deed_value*purchase_cost*pre_market_value*booking_date*booking_amount*selling_date*reg_office*court_fee*orig_deed_recpt_no*registry_property*copy_doc_rev_date*original_deed_rev_date*orig_deed_poss_coll_date*land_possession*possession_under*tax_paid_up_to*tax_payment_date*tax_maturity_date*havingCase*mortgage_given*mortgage_no*mortgage_date*mortgage_to*branch_name*which_company*updated_by*update_date*is_mutation";

        $data_array = "" . $hidden_AssetId . "*" . $txt_asset_no . "*" . $cbo_company_name . "*" . $txt_land_location . "*" . $txt_address . "*" . $txt_mouja_name . "*" . $txt_file_number . "*" . $txt_amt_of_land . "*" . $txt_deed_number . "*" . $txt_east_owner . "*" . $txt_west_owner . "*" . $txt_south_owner . "*" . $txt_north_owner . "*" . $txt_deed_value . "*" . $txt_purchase_cost . "*" . $txt_pre_market_value . "*" . $txt_booking_date . "*" . $txt_booking_amount . "*" . $txt_selling_date . "*" . $txt_reg_office . "*" . $txt_court_fee . "*" . $txt_orig_deed_recpt_no . "*" . $cbo_registry_property . "*" . $txt_copy_doc_rev_date . "*" . $txt_original_deed_rev_date . "*" . $txt_orig_deed_poss_coll_date . "*" . $cbo_land_possession . "*" . $txt_possession_under . "*" . $txt_tax_paid_up_to . "*" . $txt_tax_payment_date . "*" . $txt_tax_maturity_date . "*" . $cbo_havingCase . "*" . $cbo_mortgage_given . "*" . $txt_mortgage_no . "*" . $txt_mortgage_date . "*" . $txt_mortgage_to . "*" . $txt_branch_name . "*" . $txt_which_company . "*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'*".$cbo_is_mutation."";
        //End : Update_FAM_LAND_POSITION_MST PopUp  ---------------------------------------
        
		//Start : Update Sellers Details --------------------------------------------------
        $txt_seller_name_hidden = explode("*", str_replace("'", "", $txt_seller_name));

        $id_seller = return_next_id("id", "fam_land_sellers_dtls", 1);
        $field_array_sellers_dtls = "id,mst_id,seller_name,father_name,mother_name,address,phone_no,qty,inserted_by,insert_date";
        $field_array_seller_update = "mst_id*seller_name*father_name*mother_name*address*phone_no*qty*updated_by*update_date";

        $data_array_seller_dtls = '';
        for ($c = 0; $c < count($txt_seller_name_hidden); $c++) {
            $seller_dtls_popup = explode("_", $txt_seller_name_hidden[$c]);

            if ($seller_dtls_popup[0] != "") {
                $update_seller_arr[] = $seller_dtls_popup[0];
                $data_array_sellers_arr[$seller_dtls_popup[0]] = explode("*", ("'" . $seller_dtls_popup[1] . "'*'" . $seller_dtls_popup[2] . "'*'" . $seller_dtls_popup[3] . "'*'" . $seller_dtls_popup[4] . "'*'" . $seller_dtls_popup[5] . "'*'" . $seller_dtls_popup[6] . "'*'" . $seller_dtls_popup[7] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
            } else {
                if ($data_array_seller_dtls != "") {
                    $data_array_seller_dtls .=",";
                }
                $data_array_seller_dtls .="('" . $id_seller . "'," . $update_id . ",'" . $seller_dtls_popup[2] . "','" . $seller_dtls_popup[3] . "','" . $seller_dtls_popup[4] . "','" . $seller_dtls_popup[5] . "','" . $seller_dtls_popup[6] . "','" . $seller_dtls_popup[7] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

                $id_seller = $id_seller + 1;
            }
        }
        //End 	: Update Sellers Details --------------------------------------------------
        
		//Start : Update Owner Details =======================================
        $txt_owner_name_hidden = explode("*", str_replace("'", "", $txt_owner_name));

        $id_owner = return_next_id("id", "fam_land_owner_dtls", 1);
        $field_array_owner_dtls = "id,mst_id,owner_name,father_name,mother_name,phone_no,qty,inserted_by,insert_date";
        $field_array_owner_update = "mst_id*owner_name*father_name*mother_name*phone_no*qty*updated_by*update_date";

        $data_array_owner_dtls = '';

        for ($c = 0; $c < count($txt_owner_name_hidden); $c++) {
            $owner_dtls_popup = explode("_", $txt_owner_name_hidden[$c]);
            //print_r($owner_dtls_popup);

            if ($owner_dtls_popup[0] != "") {

                $update_owner_arr[] = $owner_dtls_popup[0];

                $data_array_owners_arr[$owner_dtls_popup[0]] = explode("*", ("'" . $owner_dtls_popup[1] . "'*'" . $owner_dtls_popup[2] . "'*'" . $owner_dtls_popup[3] . "'*'" . $owner_dtls_popup[4] . "'*'" . $owner_dtls_popup[5] . "'*'" . $owner_dtls_popup[6] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
            } else {
                if ($data_array_owner_dtls != "") {
                    $data_array_owner_dtls .=",";
                }
                $data_array_owner_dtls .="('" . $id_owner . "'," . $update_id . ",'" . $owner_dtls_popup[2] . "','" . $owner_dtls_popup[3] . "','" . $owner_dtls_popup[4] . "','" . $owner_dtls_popup[5] . "','" . $owner_dtls_popup[6] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

                $id_owner = $id_owner + 1;
            }
        }
        //End 	: Update Owner Details ============================================
        
		//Start : Update Dag/Ledger/Porcha Number =======================================
        $txt_dag_ledg_porcha_number_hidden = explode("*", str_replace("'", "", $txt_dag_ledg_porcha_number));

        $id_land_surveytype = return_next_id("id", "fam_land_surveytype_dtls", 1);
        $field_array_land_surveytype_dtls = "id,mst_id,landsurveytype,dagno,ledgerno,porchano,s_year,qty,inserted_by,insert_date";

        $field_array_land_surveytype_update = "mst_id*landsurveytype*dagno*ledgerno*porchano*s_year*qty*updated_by*update_date";

        $data_array_land_surveytype_dtls = '';
        for ($d = 0; $d < count($txt_dag_ledg_porcha_number_hidden); $d++) {
            $dag_ledg_porcha_dtls_popup = explode("_", $txt_dag_ledg_porcha_number_hidden[$d]);
            //print_r($dag_ledg_porcha_dtls_popup); //die;
            if ($dag_ledg_porcha_dtls_popup[0] != "") {

                $update_dag_ledg_porcha_arr[] = $dag_ledg_porcha_dtls_popup[0];

                $data_array_dag_ledg_porcha_arr[$dag_ledg_porcha_dtls_popup[0]] = explode("*", ("'" . $dag_ledg_porcha_dtls_popup[1] . "'*'" . $dag_ledg_porcha_dtls_popup[2] . "'*'" . $dag_ledg_porcha_dtls_popup[3] . "'*'" . $dag_ledg_porcha_dtls_popup[4] . "'*'" . $dag_ledg_porcha_dtls_popup[5] . "'*'" . $dag_ledg_porcha_dtls_popup[6]. "'*'" . $dag_ledg_porcha_dtls_popup[7] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
            } else {
					if($dag_ledg_porcha_dtls_popup[3] != '' && $dag_ledg_porcha_dtls_popup[4] != '' && $dag_ledg_porcha_dtls_popup[5] != '' && $dag_ledg_porcha_dtls_popup[7] != '')
					{
						if ($data_array_land_surveytype_dtls != "")$data_array_land_surveytype_dtls .=",";
					$data_array_land_surveytype_dtls .="('" . $id_land_surveytype . "','" . $dag_ledg_porcha_dtls_popup[1] . "','" . $dag_ledg_porcha_dtls_popup[2] . "','" . $dag_ledg_porcha_dtls_popup[3] . "','" . $dag_ledg_porcha_dtls_popup[4] . "','" . $dag_ledg_porcha_dtls_popup[5] . "','" . $dag_ledg_porcha_dtls_popup[6] . "','" . $dag_ledg_porcha_dtls_popup[7] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
					}
                //$data_array_land_surveytype_dtls .="('" . $id_land_surveytype . "'," . $update_id . ",'" . $dag_ledg_porcha_dtls_popup[2] . "','" . $dag_ledg_porcha_dtls_popup[3] . "','" . $dag_ledg_porcha_dtls_popup[4] . "','" . $dag_ledg_porcha_dtls_popup[5] . "','" . $dagLedgerPorcha_popup[6]. "','" . $dagLedgerPorcha_popup[7] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

                $id_land_surveytype = $id_land_surveytype + 1;
            }
        }
		
		//echo "10**".$data_array_land_surveytype_dtls;die;
		if( str_replace("'", "",$txt_hidden_deleted_ids) != '')
		{
			$deletedIds=str_replace("'", "",$txt_hidden_deleted_ids);
			$field_array = "status_active*is_deleted*updated_by*update_date";
        	$data_array = "'2'*'1'*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";
			
			$rID16 = sql_multirow_update("fam_land_surveytype_dtls", $field_array, $data_array, "id", $deletedIds, 1);
		}
		
        //End 	: Update Dag/Ledger/Porcha Number============================================
        
		//Start : Update VIA Document =======================================
        $txt_via_document_hidden = explode("*", str_replace("'", "", $txt_via_document));

        $id_via_document = return_next_id("id", "fam_land_via_dtls", 1);
        $field_array_via_document_dtls = "id,mst_id,seller_name,father_name,mother_name,selling_date,deed_no,inserted_by,insert_date";
        $field_array_via_document_update = "mst_id*seller_name*father_name*mother_name*selling_date*deed_no*updated_by*update_date";

        $data_array_via_document_dtls = '';

        for ($c = 0; $c < count($txt_via_document_hidden); $c++) {
            $via_document_dtls_popup = explode("_", $txt_via_document_hidden[$c]);
            //print_r($via_document_dtls_popup); 

            $update_sellingDate = $via_document_dtls_popup[5];
            if ($db_type == 0)
                $update_selling_date = change_date_format($update_sellingDate, 'yyyy-mm-dd');
            if ($db_type == 2)
                $update_selling_date = change_date_format(str_replace("'", "", $update_sellingDate), "yyyy-mm-dd", "-", 1);

            if ($via_document_dtls_popup[0] != "") {

                $update_via_doc_arr[] = $via_document_dtls_popup[0];
                $data_array_via_document_arr[$via_document_dtls_popup[0]] = explode("*", ("'" . $via_document_dtls_popup[1] . "'*'" . $via_document_dtls_popup[2] . "'*'" . $via_document_dtls_popup[3] . "'*'" . $via_document_dtls_popup[4] . "'*'" . $update_selling_date . "'*'" . $via_document_dtls_popup[6] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
            } else {
                if ($data_array_via_document_dtls != "") {
                    $data_array_via_document_dtls .=",";
                }
                $data_array_via_document_dtls .="('" . $id_via_document . "'," . $update_id . ",'" . $via_document_dtls_popup[2] . "','" . $via_document_dtls_popup[3] . "','" . $via_document_dtls_popup[4] . "','" . $update_selling_date . "','" . $via_document_dtls_popup[6] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

                $id_via_document = $id_via_document + 1;
            }
        }
        //End 	: Update VIA Document ============================================
        
		//Start : Update Tax Outstanding Amount =======================================
        $txt_tax_outstanding_amount_hidden = explode("*", str_replace("'", "", $txt_tax_outstanding_amount));

        $id_tax = return_next_id("id", "fam_land_tax_dtls", 1);
        $field_array_tax_dtls = "id,mst_id,payment_year,tax_amount,paid_amount,due_amount,inserted_by,insert_date";
        $field_array_tax_update = "mst_id*payment_year*tax_amount*paid_amount*due_amount*updated_by*update_date";

        $data_array_tax_dtls = '';

        for ($c = 0; $c < count($txt_tax_outstanding_amount_hidden); $c++) {
            $tax_dtls_popup = explode("_", $txt_tax_outstanding_amount_hidden[$c]);

            //print_r($tax_dtls_popup); die;

            if ($tax_dtls_popup[0] != "") {

                $update_via_doc_arr[] = $tax_dtls_popup[0];

                $data_array_tax_arr[$tax_dtls_popup[0]] = explode("*", ("'" . $tax_dtls_popup[1] . "'*'" . $tax_dtls_popup[2] . "'*'" . $tax_dtls_popup[3] . "'*'" . $tax_dtls_popup[4] . "'*'" . $tax_dtls_popup[5] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
            } else {
                if ($data_array_tax_dtls != "") {
                    $data_array_tax_dtls .=",";
                }
                $data_array_tax_dtls .="('" . $id_tax . "'," . $update_id . ",'" . $tax_dtls_popup[2] . "','" . $tax_dtls_popup[3] . "','" . $tax_dtls_popup[4] . "','" . $tax_dtls_popup[5] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";

                $id_tax = $id_tax + 1;
            }
        }
        //End 	: Update Tax Outstanding Amount ============================================

        $update_id = str_replace("'", "", $update_id);
        //echo $rID1."&&".$rID2." &&". $rID3."&&".$rID4;  die;
        //echo $update_id;die;
        $rID = sql_update("fam_land_position_mst", $field_array, $data_array, "id", "" . $update_id . "", 0);

        //==Seller Dtls========================================
        if ($data_array_seller_dtls != "") {  //insert seller details
            //echo "insert into fam_land_sellers_dtls($field_array_sellers_dtls) values $data_array_seller_dtls"; die;
            $rID1 = sql_insert("fam_land_sellers_dtls", $field_array_sellers_dtls, $data_array_seller_dtls, 1);
        }
        //update seller details
        if (count($data_array_sellers_arr) > 0) {

            //echo bulk_update_sql_statement("fam_land_sellers_dtls", "id", $field_array_seller_update, $data_array_sellers_arr, $update_seller_arr, 0), 1; die;
            $rID11 = execute_query(bulk_update_sql_statement("fam_land_sellers_dtls", "id", $field_array_seller_update, $data_array_sellers_arr, $update_seller_arr, 0), 1);
        }

        //==========Update Owner
        if ($data_array_owner_dtls != "") {  //insert Owner
            //echo "insert into fam_land_owner_dtls ($field_array_owner_dtls) values $data_array_owner_dtls"; die;
            $rID2 = sql_insert("fam_land_owner_dtls", $field_array_owner_dtls, $data_array_owner_dtls, 1);
        }
        if (count($data_array_owners_arr) > 0) { // Update Owner
            //echo bulk_update_sql_statement("fam_land_owner_dtls", "id", $field_array_owner_update, $data_array_owners_arr, $update_owner_arr, 0), 1; die;
            $rID12 = execute_query(bulk_update_sql_statement("fam_land_owner_dtls", "id", $field_array_owner_update, $data_array_owners_arr, $update_owner_arr, 0), 1);
        }

        //========Update Dag, Ledger, Porcha
        if ($data_array_land_surveytype_dtls != "") {  //insert dag_ledg_porcha_dtls
            //echo "insert into fam_land_surveytype_dtls ($field_array_land_surveytype_dtls) values $data_array_land_surveytype_dtls"; die;
            $rID3 = sql_insert("fam_land_surveytype_dtls", $field_array_land_surveytype_dtls, $data_array_land_surveytype_dtls, 1);
        }

        if (count($data_array_dag_ledg_porcha_arr) > 0) { //update dag_ledg_porcha_dtls
            $rID13 = execute_query(bulk_update_sql_statement("fam_land_surveytype_dtls", "id", $field_array_land_surveytype_update, $data_array_dag_ledg_porcha_arr, $update_dag_ledg_porcha_arr, 0), 1);
        }

        //========Update VIA Document
        if ($data_array_via_document_dtls != "") {  //insert Case Movement Representitive dtls
            //echo "insert into fam_land_via_dtls ($field_array_via_document_dtls) values $data_array_via_document_dtls"; die;
            $rID4 = sql_insert("fam_land_via_dtls", $field_array_via_document_dtls, $data_array_via_document_dtls, 1);
        }
        if (count($data_array_via_document_arr) > 0) {

            //echo bulk_update_sql_statement("fam_land_via_dtls", "id", $field_array_via_document_update, $data_array_via_document_arr, $update_via_doc_arr, 0), 1; die;
            $rID14 = execute_query(bulk_update_sql_statement("fam_land_via_dtls", "id", $field_array_via_document_update, $data_array_via_document_arr, $update_via_doc_arr, 0), 1);
        }

        //========Update Tax Outstanding Amount
        if ($data_array_tax_dtls != "") {//insert Tax Outstanding Amount
            //echo "insert into fam_land_tax_dtls ($field_array_tax_dtls) values $data_array_tax_dtls"; die;
            $rID5 = sql_insert("fam_land_tax_dtls", $field_array_tax_dtls, $data_array_tax_dtls, 1);
        }
        if (count($data_array_tax_arr) > 0) { //Update Tax Outstanding Amount
            //echo bulk_update_sql_statement("fam_land_tax_dtls", "id", $field_array_tax_update, $data_array_tax_arr, $update_via_doc_arr, 0), 1; die;
            $rID15 = execute_query(bulk_update_sql_statement("fam_land_tax_dtls", "id", $field_array_tax_update, $data_array_tax_arr, $update_via_doc_arr, 0), 1);
        }

      // echo "10**".$rID."**".$rID11."**".$rID12."**".$rID13."**".$rID14."**".$rID15."**".$rID16; die;
		
		$purchase_cost = str_replace("'", "", $txt_purchase_cost);

        if ($db_type == 0) {
            if ($rID && $rID11 && $rID12 && $rID13) {
                mysql_query("COMMIT");
                echo "1**" . $update_id . "**" . $purchase_cost;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID11 && $rID12 && $rID13) {
                oci_commit($con);
                echo "1**" . $update_id . "**" . $purchase_cost;
            } else {
                oci_rollback($con);
                echo "10**" . $update_id;
            }
        }
        disconnect($con);
        die;
    }
// End : Update Here----------------------------------------------------------
// Start : Delete Here----------------------------------------------------------
    else if ($operation == 2) {
        $con = connect();
        $field_array = "status_active*is_deleted*updated_by*update_date";
        $data_array = "'2'*'1'*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";



        $update_id = str_replace("'", "", $update_id);

        $rID = sql_delete("fam_land_position_mst", $field_array, $data_array, "id", "" . $update_id . "", 1);
        $rID1 = sql_delete("fam_land_sellers_dtls", $field_array, $data_array, "mst_id", "" . $update_id . "", 1);
        $rID2 = sql_delete("fam_land_owner_dtls", $field_array, $data_array, "mst_id", $update_id, 1);
        $rID3 = sql_delete("fam_land_via_dtls", $field_array, $data_array, "mst_id", $update_id, 1);
        $rID4 = sql_delete("fam_land_surveytype_dtls", $field_array, $data_array, "mst_id", $update_id, 1);
        $rID5 = sql_delete("fam_land_tax_dtls", $field_array, $data_array, "mst_id", $update_id, 1);

        //echo $rID."**".$rID1."**".$rID2."**".$rID3."**".$rID4."**".$rID5; die;

        if ($db_type == 0) {
            if ($rID && $rID1 && $rID2 && $rID3 && $rID4 && $rID5) {
                mysql_query("COMMIT");
                echo "2**" . $update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1 && $rID2 && $rID3 && $rID4 && $rID) {
                oci_commit($con);
                echo "2**" . $update_id;
            } else {
                oci_rollback($con);
                echo "10**" . $update_id;
            }
        }
        disconnect($con);
        die;
    }
// End : Delete Here  ----------------------------------------------------------
}

if ($action == "populate_land_details_form_data") {
    $dara_arr = explode("_", $data);
	
    //print_r($dara_arr); die;
    $data_array = sql_select("select id, asset_id, asset_no, company_id, land_location, address, mouja_name, file_number, amt_of_land, deed_number, east_owner, west_owner, north_owner, south_owner, deed_value, purchase_cost, pre_market_value, booking_date, booking_amount, selling_date, reg_office, court_fee, orig_deed_recpt_no, registry_property, is_mutation, copy_doc_rev_date, original_deed_rev_date, orig_deed_poss_coll_date, land_possession, possession_under, tax_paid_up_to, tax_payment_date, tax_maturity_date, havingcase, mortgage_given, mortgage_no, mortgage_date, mortgage_to, branch_name, which_company from fam_land_position_mst where status_active=1 and is_deleted=0 and id='$dara_arr[0]'");

    foreach ($data_array as $row) {
        echo "document.getElementById('hidden_AssetId').value 			= '" . $row[csf("asset_id")] . "';\n";
        echo "document.getElementById('txt_asset_no').value 			= '" . $row[csf("asset_no")] . "';\n";
        echo "document.getElementById('cbo_company_name').value 		= '" . $row[csf("company_id")] . "';\n";
        // echo "load_drop_down('requires/asset_acquisition_controller','" . $row[csf("company_id")] . "','load_drop_down_location','location_td' );\n";
        echo "document.getElementById('txt_land_location').value 		= '" . $row[csf("land_location")] . "';\n";
        echo "document.getElementById('txt_address').value 				= '" . $row[csf("address")] . "';\n";
        echo "document.getElementById('txt_mouja_name').value 			= '" . $row[csf("mouja_name")] . "';\n";
        echo "document.getElementById('txt_file_number').value 			= '" . $row[csf("file_number")] . "';\n";
        echo "document.getElementById('txt_amt_of_land').value 			= '" . $row[csf("amt_of_land")] . "';\n";
        echo "document.getElementById('txt_deed_number').value 			= '" . $row[csf("deed_number")] . "';\n";
        echo "document.getElementById('txt_east_owner').value 			= '" . $row[csf("east_owner")] . "';\n";
        echo "document.getElementById('txt_west_owner').value 			= '" . $row[csf("west_owner")] . "';\n";
        echo "document.getElementById('txt_north_owner').value	 		= '" . $row[csf("north_owner")] . "';\n";
        echo "document.getElementById('txt_south_owner').value 			= '" . $row[csf("south_owner")] . "';\n";
        echo "document.getElementById('txt_deed_value').value 			= '" . number_format($row[csf("deed_value")]) . "';\n";
        echo "document.getElementById('txt_purchase_cost').value 		= '" . number_format($dara_arr[1]) . "';\n";
        echo "document.getElementById('txt_pre_market_value').value 	= '" . number_format($row[csf("pre_market_value")]) . "';\n";
        echo "document.getElementById('txt_booking_date').value 		= '" . change_date_format($row[csf("booking_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_booking_amount').value 		= '" . number_format($row[csf("booking_amount")]) . "';\n";
        echo "document.getElementById('txt_selling_date').value 		= '" . change_date_format($row[csf("selling_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_reg_office').value 			= '" . $row[csf("reg_office")] . "';\n";
        echo "document.getElementById('txt_court_fee').value 			= '" . number_format($row[csf("court_fee")]) . "';\n";
        echo "document.getElementById('txt_orig_deed_recpt_no').value 	= '" . $row[csf("orig_deed_recpt_no")] . "';\n";
        echo "document.getElementById('cbo_registry_property').value 	= '" . $row[csf("registry_property")] . "';\n";
		echo "document.getElementById('cbo_is_mutation').value 	= '" . $row[csf("is_mutation")] . "';\n";
        echo "document.getElementById('txt_copy_doc_rev_date').value 	= '" . change_date_format($row[csf("copy_doc_rev_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_original_deed_rev_date').value 	= '" . change_date_format($row[csf("original_deed_rev_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_orig_deed_poss_coll_date').value 	= '" . change_date_format($row[csf("orig_deed_poss_coll_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('cbo_land_possession').value 		= '" . $row[csf("land_possession")] . "';\n";
        echo "document.getElementById('txt_possession_under').value 	= '" . $row[csf("possession_under")] . "';\n";
        echo "document.getElementById('txt_tax_paid_up_to').value 		= '" . change_date_format($row[csf("tax_paid_up_to")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_tax_payment_date').value 	= '" . change_date_format($row[csf("tax_payment_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_tax_maturity_date').value 	= '" . change_date_format($row[csf("tax_maturity_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('cbo_havingCase').value 			= '" . $row[csf("havingCase")] . "';\n";
        echo "document.getElementById('cbo_mortgage_given').value 		= '" . $row[csf("mortgage_given")] . "';\n";
        echo "document.getElementById('txt_mortgage_no').value 			= '" . $row[csf("mortgage_no")] . "';\n";
        echo "document.getElementById('txt_mortgage_date').value 		= '" . change_date_format($row[csf("mortgage_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_mortgage_to').value 			= '" . $row[csf("mortgage_to")] . "';\n";
        echo "document.getElementById('txt_branch_name').value 			= '" . $row[csf("branch_name")] . "';\n";
        echo "document.getElementById('txt_which_company').value 		= '" . $row[csf("which_company")] . "';\n";
        echo "document.getElementById('update_id').value 				= '" . $row[csf("id")] . "';\n";

        //=======================Start : Seller Name =======================
        $data_array_seller_name = sql_select("SELECT id, mst_id, seller_name, father_name, mother_name, address, phone_no, qty  FROM fam_land_sellers_dtls  WHERE mst_id='$dara_arr[0]' AND status_active=1 AND is_deleted=0");
        //print_r($data_array_seller_name);
        $sellerNamebreak_down = "";
		$seller_name = "";
        $total_seller_qty = 0;
        foreach ($data_array_seller_name as $val) {
            if ($sellerNamebreak_down != "")
                $sellerNamebreak_down.="*";

            $sellerNamebreak_down.=$val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("seller_name")] . "_" . $val[csf("father_name")] . "_" . $val[csf("mother_name")] . "_" . $val[csf("address")] . "_" . $val[csf("phone_no")] . "_" . $val[csf("qty")];
            $total_seller_qty+=$val[csf("qty")];
			$seller_name=$val[csf("seller_name")];
        }
        echo "document.getElementById('txt_seller_name').value = '" . $sellerNamebreak_down . "';\n";
		echo "document.getElementById('txt_seller').value = '" . $seller_name . "';\n";
        echo "document.getElementById('txt_hidden_sell_qty').value = '" . $total_seller_qty . "';\n";
        //=======================End : Seller Name =======================
		
        //=======================Start 	: Owner Name===============================
        $data_arry_owner_name = sql_select("SELECT id, mst_id, owner_name, father_name, mother_name, phone_no, qty FROM fam_land_owner_dtls  WHERE mst_id='$dara_arr[0]' AND status_active=1 AND is_deleted=0");
        $ownerNameBreakDown = "";
		$owner_name = "";
        foreach ($data_arry_owner_name as $val) {
            if ($ownerNameBreakDown != "") {
                $ownerNameBreakDown .= "*";
            }
            $ownerNameBreakDown .= $val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("owner_name")] . "_" . $val[csf("father_name")] . "_" . $val[csf("mother_name")] . "_" . $val[csf("phone_no")] . "_" . $val[csf("qty")];
			$owner_name=$val[csf("owner_name")];
        }
        echo "document.getElementById('txt_owner_name').value = '" . $ownerNameBreakDown . "';\n";
		echo "document.getElementById('txt_owner').value = '" . $owner_name . "';\n";
        //===========================End 	: Owner Name===============================
		
        //===========================Start 	: Dag/Ledger/Porcha Number===============================
        $data_arry_land_surveytype_dtls = sql_select("SELECT  id, mst_id, landsurveytype, dagno, ledgerno, porchano, s_year, qty FROM fam_land_surveytype_dtls  WHERE mst_id='$dara_arr[0]' AND status_active=1 AND is_deleted=0");
        $LandSurveyTypeBreakDown = "";
        foreach ($data_arry_land_surveytype_dtls as $val) {
            if ($LandSurveyTypeBreakDown != "") {
                $LandSurveyTypeBreakDown .= "*";
            }
            $LandSurveyTypeBreakDown .= $val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("landsurveytype")] . "_" . $val[csf("dagno")] . "_" . $val[csf("ledgerno")] . "_" . $val[csf("porchano")] . "_" . $val[csf("s_year")] . "_" . $val[csf("qty")];
        }
        echo "document.getElementById('txt_dag_ledg_porcha_number').value = '" . $LandSurveyTypeBreakDown . "';\n";
        //===========================End 	: Dag/Ledger/Porcha Number===============================
        
		//===========================Start 	: VIA Document===============================
        $data_arry_via_documents = sql_select("SELECT id, mst_id, seller_name, father_name, mother_name, selling_date, deed_no FROM fam_land_via_dtls WHERE mst_id='$dara_arr[0]' AND status_active=1 AND is_deleted=0");
        $ViaDocumentBreakDown = "";
        $sl = 1;
        foreach ($data_arry_via_documents as $val) {
            if ($ViaDocumentBreakDown != "") {
                $ViaDocumentBreakDown .= "*";
            }
            $ViaDocumentBreakDown .= $val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("seller_name")] . "_" . $val[csf("father_name")] . "_" . $val[csf("mother_name")] . "_" . $val[csf("selling_date")] . "_" . $val[csf("deed_no")];
            $sl++;
        }
        echo "document.getElementById('txt_via_document').value = '" . $ViaDocumentBreakDown . "';\n";
        //===========================End 	: VIA Document===============================
        //===========================Start 	: Tax Outstanding Amount===============================
        $data_array_tax_outstanding_amt = sql_select("SELECT id, mst_id, payment_year, tax_amount, paid_amount, due_amount  FROM fam_land_tax_dtls WHERE mst_id='$dara_arr[0]' AND status_active=1 AND is_deleted=0");
        //print_r($data_array_seller_name);
        $TaxOutstandingAmtBreakDown = "";
        foreach ($data_array_tax_outstanding_amt as $val) {
            if ($TaxOutstandingAmtBreakDown != "") {
                $TaxOutstandingAmtBreakDown .= "*";
            }
            $TaxOutstandingAmtBreakDown.=$val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("payment_year")] . "_" . $val[csf("tax_amount")] . "_" . $val[csf("paid_amount")] . "_" . $val[csf("due_amount")];
        }
        echo "document.getElementById('txt_tax_outstanding_amount').value = '" . $TaxOutstandingAmtBreakDown . "';\n";
        //===========================End 	: Tax Outstanding Amount===============================
        echo "set_button_status(1, permission, 'fnc_land_position_entry',1);\n";
        echo"mortgage_given();";
    }
}

//For testing Update Query
function sql_update_a($strTable, $arrUpdateFields, $arrUpdateValues, $arrRefFields, $arrRefValues, $commit) {
    $strQuery = "UPDATE " . $strTable . " SET ";
    $arrUpdateFields = explode("*", $arrUpdateFields);
    $arrUpdateValues = explode("*", $arrUpdateValues);
    if (is_array($arrUpdateFields)) {
        $arrayUpdate = array_combine($arrUpdateFields, $arrUpdateValues);
        $Arraysize = count($arrayUpdate);
        $i = 1;
        foreach ($arrayUpdate as $key => $value):
            $strQuery .= ($i != $Arraysize) ? $key . "=" . $value . ", " : $key . "=" . $value . " WHERE ";
            $i++;
        endforeach;
    }
    else {
        $strQuery .= $arrUpdateFields . "=" . $arrUpdateValues . " WHERE ";
    }
    $arrRefFields = explode("*", $arrRefFields);
    $arrRefValues = explode("*", $arrRefValues);
    if (is_array($arrRefFields)) {
        $arrayRef = array_combine($arrRefFields, $arrRefValues);
        $Arraysize = count($arrayRef);
        $i = 1;
        foreach ($arrayRef as $key => $value):
            $strQuery .= ($i != $Arraysize) ? $key . "=" . $value . " AND " : $key . "=" . $value . "";
            $i++;
        endforeach;
    }
    else {
        $strQuery .= $arrRefFields . "=" . $arrRefValues . "";
    }

    global $con;
    echo $strQuery;
    die;
    //return $strQuery; die;
    $stid = oci_parse($con, $strQuery);
    $exestd = oci_execute($stid, OCI_NO_AUTO_COMMIT);
    if ($exestd)
        return "1";
    else
        return "0";

    die;
    if ($commit == 1) {
        if (!oci_error($stid)) {
            oci_commit($con);
            return "1";
        } else {
            oci_rollback($con);
            return "10";
        }
    } else
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
	echo $strQuery;die;
    global $con;
	$stid =  oci_parse($con, $strQuery);
	$exestd=oci_execute($stid,OCI_NO_AUTO_COMMIT);
	if ($exestd) 
		return "1";
	else 
		return "0";
	
	die;
	$_SESSION['last_query']=$_SESSION['last_query'].";;".$strQuery;
	if ($commit==1)
	{
		if (!oci_error($stid))
		{
			
		$pc_time= add_time(date("H:i:s",time()),360); 
		$pc_date_time = date("d-M-Y h:i:s",strtotime(add_time(date("H:i:s",time()),360)));
	    $pc_date = date("d-M-Y",strtotime(add_time(date("H:i:s",time()),360)));
		
		$strQuery= "INSERT INTO activities_history ( session_id,user_id,ip_address,entry_time,entry_date,module_name,form_name,query_details,query_type) VALUES ('".$_SESSION['logic_erp']["history_id"]."','".$_SESSION['logic_erp']["user_id"]."','".$_SESSION['logic_erp']["pc_local_ip"]."','".$pc_time."','".$pc_date."','".$_SESSION["module_id"]."','".$_SESSION['menu_id']."','".encrypt($_SESSION['last_query'])."','1')"; 

		mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		 
		$resultss=oci_parse($con, $strQuery);
		oci_execute($resultss);
		$_SESSION['last_query']="";
		oci_commit($con); 
		return "0";
		}
		else
		{
			oci_rollback($con);
			return "10";
		}
	}
	else return 1;
	die;
}