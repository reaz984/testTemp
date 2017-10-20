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
$store_library = return_library_array("select id,store_name from lib_store_location", "id", "store_name");



//--------------------------------------------------------------------------------------------
//load drop down company location==============================
if ($action == "load_drop_down_location") {
    echo create_drop_down("cbo_location", 170, "select id,location_name from lib_location where status_active =1 and is_deleted=0 and company_id='$data' order by location_name", "id,location_name", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "2", "", "");
    exit();
}

//load drop down company Store location=======================
if ($action == "load_drop_down_store") {
    echo create_drop_down("cbo_store", 170, "select id,store_name from lib_store_location where status_active=1 and is_deleted=0 and company_id='$data' order by store_location", "id,store_name", 1, "-- Select Store --", $selected, "", "", "", "", "", "", "11", "", "");
    exit();
}

//load drop down Asset Type
if ($action == "load_drop_down_category") {
    if ($data == 1) {		//Land
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "21,22,23,24", "", "", "", "4", "", "");
    } elseif ($data == 2) {		//Building
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "41,42,43,44,45,46,47", "", "", "", "4", "", "");
    } elseif ($data == 3) {		//Furniture
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "61,62,63,64,65,66,67,68,69", "", "", "", "4", "", "");
    } elseif ($data == 4) {	//Fixtures
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "71", "", "", "", "4", "", "");
    } elseif ($data == 5) {		//Machinery
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "91", "", "", "", "4", "", "");
    } elseif ($data == 6) { 	//Equipment
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "101", "", "", "", "4", "", "");
    } elseif ($data == 7) { 	//Power Generation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "111", "", "", "", "4", "", "");
    } elseif ($data == 8) { 	//Computer
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "81,82,83,84,85,86,87,88", "", "", "", "4", "", "");
    } elseif ($data == 9) { 	//Electric Appliance
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "121", "", "", "", "4", "", "");
    } elseif ($data == 10) { 	//Transportation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "131,132,133,134,135,136,137,138,139,140", "", "", "", "4", "", "");
    } elseif ($data == 11) { 	//Others
        echo create_drop_down("cbo_category", 170, $blank_array, "", 1, "--- Select ---", $selected, "", "", "", "", "", "", "4", "", "");
    }
    exit();
}
//PopUp QTY -----------------------------
if ($action == "asset_qty_popup") {
    //load_html_head_contents($title, $path, $filter, $popup, $unicode, $multi_select, $am_chart)
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    //echo $hidden_set_break_down;
    //echo "<pre>";
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_1"  id="searchorderfrm_1" autocomplete="off">
                <input type="hidden" id="asset_id">
                <table width="510" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center" id="tbl_qty">
                    <thead>
                    <th width="20">SL</th>
                    <th width="170">Part Name</th>
                    <th width="35">Qty</th>
                    <th width="150">Serial No</th>
                    <th width="80">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        if ($hidden_set_break_down != "") {
                            //echo $hidden_set_break_down;
                            $hidden_set_break_down_data = explode("*", $hidden_set_break_down);
                            $i = 1;
                            foreach ($hidden_set_break_down_data as $row_data) {
                                $row_data_arr = explode("_", $row_data);
                                ?>
                                <tr id="assetqty_<?php echo $i; ?>">
                                    <td><input type="text" name="txtsl_<?php echo $i; ?>" id="txtsl_<?php echo $i; ?>" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> </td>
                                    <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                    <td>
                                        <?php
                                        echo create_drop_down("cbopartname_" . $i, 170, $asset_category, "", 1, "--- Select ---", $row_data_arr[0], "", "", "83,84,88,89");
                                        ?>	
                                    </td>
                                    <td><input type="text" name="txtqtyset_<?php echo $i; ?>" id="txtqtyset_<?php echo $i; ?>" class="text_boxes_numeric" style="width:35px" value="<?php echo $row_data_arr[1] ?>"/> </td>
                                    <td><input type="text" name="txtserialno_<?php echo $i; ?>" id="txtserialno_<?php echo $i; ?>" class="text_boxes" style="width:150px" value="<?php echo $row_data_arr[2]; ?>" /> </td>
                                    <td>
                                        <input type="button" name="btnadd_<?php echo $i; ?>" id="btnadd_<?php echo $i; ?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="button" name="decrease_<?php echo $i; ?>" id="decrease_<?php echo $i; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i; ?>)" style="width:35px"/>
                                        <input type="hidden" name="hiddenUpdatesetId_<?php echo $i; ?>" id="hiddenUpdatesetId_<?php echo $i; ?>" class="text_boxes_numeric"  value="<?php echo $row_data_arr[3]; ?>" width="30"/>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        } else {
                            ?>
                            <tr id="assetqty_1">
                                <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                                <!-- <td><input type="text" name="txtpartname_1" id="txtpartname_1" class="text_boxes" style="width:200px" /> </td> -->
                                <td>
                                    <?php
                                    echo create_drop_down("cbopartname_1", 170, $asset_category, "", 1, "--- Select ---", $selected, "", "", "83,84,88,89");
                                    ?>	
                                </td>
                                <td><input type="text" name="txtqtyset_1" id="txtqtyset_1" class="text_boxes_numeric" style="width:35px" value=""/> </td>
                                <td><input type="text" name="txtserialno_1" id="txtserialno_1" class="text_boxes" style="width:150px" /> </td>
                                <td>
                                    <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                    <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/> 
                                    <input type="hidden" name="hiddenUpdatesetId_1" id="hiddenUpdatesetId_1" class="text_boxes_numeric"  value=""/>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="asset_set_breakdown()" /></td>
                        </tr>
                    </tfoot>
                </table>  
                <input type="hidden" name="txt_hidden_data" id="txt_hidden_data" class="text_boxes" style="width:170px" /> 
                <input type="hidden" name="deleted_set_id" id="deleted_set_id" />  
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <!-- For incrimate field -->
    <script>
        function add_break_down_tr(i) {
            var row_num = $('#tbl_qty tbody tr').length;
            if (row_num != i) {
                return false;
            } else {
                i++;
                //$('#samplepic_' + i).removeAttr("src,value");
                if (row_num < 4) {
                    $("#tbl_qty tbody tr:last").clone().find("input,select").each(function () {
                        $(this).attr({
                            'id': function (_, id) {
                                var id = id.split("_");
                                //alert(id);
                                return id[0] + "_" + i
                            },
                            'name': function (_, name) {
                                return name + i
                            },
                            'value': function (_, value) {
                                return value
                            },
                            'src': function (_, src) {
                                return src
                            }
                        });
                    }).end().appendTo("#tbl_qty tbody");
                    $("#tbl_qty tbody tr:last ").removeAttr('id').attr('id', 'assetqty_' + i);
                    //$("#txtqtyset_"+i).removeAttr('class','text_boxes_numeric').attr('class', 'text_boxes_numeric');
                    //$('#decrease_'+i).removeAttr("value").attr("value","-");
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
                    $('#txtsl_' + i).val(i);
                    $('#cbopartname_' + i).val('');
                    $('#txtqtyset_' + i).val('');
                    $('#txtserialno_' + i).val('');
                    $('#hiddenUpdatesetId_' + i).val('');
                    set_all_onclick();
                }
            }
        }

        function fn_deleteRow(rowNo)
        {
            var deleted_row = $("#deleted_set_id").val();

            if (deleted_row != "")
                deleted_row = deleted_row + ",";
            var numRow = $('#tbl_qty tbody tr').length;
            if (numRow != rowNo && numRow != 1)
            {
                return false;
            } else
            {
                deleted_row = deleted_row + $("#hiddenUpdatesetId_" + rowNo).val();
                $("#assetqty_" + rowNo).remove();

            }
            $("#deleted_set_id").val(deleted_row);
//alert(deleted_row)
        }

        function asset_set_breakdown() {
            //alert( 'okay');
            //return;
            var purchase_set = "";
            var total_row = $("#tbl_qty tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
                var cbo_partname = $("#cbopartname_" + sl).val();
                //alert (cbo_partname);
                var txtqtyset = $("#txtqtyset_" + sl).val();
                var txtserialno = $("#txtserialno_" + sl).val();
                var hiddenUpdatesetId = $("#hiddenUpdatesetId_" + sl).val();
                if (purchase_set != '') {
                    purchase_set += "*" + cbo_partname + "_" + txtqtyset + "_" + txtserialno + "_" + hiddenUpdatesetId;
                } else {
                    purchase_set += cbo_partname + "_" + txtqtyset + "_" + txtserialno + "_" + hiddenUpdatesetId;
                }
            }
            $('#txt_hidden_data').val(purchase_set);
            //alert(purchase_set);
            //return;
            parent.emailwindow.hide();
        }
        //$('.drag-controls img').html('');
    </script>
    </html>
    <?php
}

if($action=="load_group")
{
	$sql="select asset_group  from tbl_test_mst  where status_active=1 and is_deleted=0 and company_id =$data";
	echo "[".substr(return_library_autocomplete( $sql, "asset_group" ), 0, -1)."]";
	exit();	
}

if($action=="load_specification")
{
	$sql="select specification  from tbl_test_mst  where status_active=1 and is_deleted=0 and company_id =$data";
	echo "[".substr(return_library_autocomplete( $sql, "specification" ), 0, -1)."]";
	exit();	
}

if($action=="load_supplier")
{
	$sql="select a.supplier_name from lib_supplier a, lib_supplier_tag_company b  where a.id=b.supplier_id and status_active=1 and is_deleted=0 and b.tag_company=$data";
	//echo $sql; die;
	echo "[".substr(return_library_autocomplete( $sql, "supplier_name" ), 0, -1)."]";
	exit();	
}


//-----------------PopUp Serial No -----------------------------
if ($action == "serial_no_popup") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);
    //echo "<pre>";
    //print_r( $hidden_serial_break_down);
    ?>
	<script>
		function copy_date_to_all()
		{
			var rowCount = $('#tbl_serial_no tbody tr').length;
			var corr_date = $('#txtWarranty_1').val();
			
			if(corr_date=='') {
			alert("Select Date");
			$('#copy_date').attr('checked', false); 
			return;
			}
				
			if (document.getElementById('copy_date').checked == true) 
			{
				for(var i = 1; i<= rowCount; i++)
				{
					$('#txtWarranty_'+i).val(corr_date);
				}
				
			}
			else 
			{
				for(var i = 1; i<= rowCount; i++)
				{
					$('#txtWarranty_'+i).val('');
				}
			}
		}
	</script>
	    
    
    
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="SerialNoPopUpFrm_1"  id="SerialNoPopUpFrm_1" autocomplete="off">
            	<table width="450" cellspacing="0" cellpadding="0" border="none" class="">
                	<tr>
                        <td width="10" colspan="3"></td>
                        <td width="145">Copy Date : &nbsp; &nbsp;<input type="checkbox" id="copy_date" name="copy_date" title="Copy Date" value="" onClick="copy_date_to_all()"/> </td>
                    </tr>
                </table>
                <table width="450" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center" id="tbl_serial_no">
                    <thead>
                    <th width="20">SL</th>
                    <th width="165">Serial No</th>
                    <th width="35">Qty</th>
                    <th width="130">Warranty</th>
                    <th width="130">Action</th>
                    <!-- <th width="135">Asset No</th> -->
                    </thead>
                    <tbody>
                        <?php
                        $txt_serial_no_hidden = explode("*", str_replace("'", "", $hidden_serial_break_down));
                        $asset_no_data_arr = array();
                        for ($c = 0; $c < count($txt_serial_no_hidden); $c++) {
                            $txt_serial_no_hidden_data = explode("_", $txt_serial_no_hidden[$c]);
                            //print_r($acquisition_set_popup); die;
                            $txt_warranty_date = $txt_serial_no_hidden_data[3];
                            $warranty_date = change_date_format($txt_warranty_date);

                            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['serial'] = $txt_serial_no_hidden_data[1];
                            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['warenty'] = $warranty_date;
                            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['asset_no'] = $txt_serial_no_hidden_data[4];
                            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['id'] = $txt_serial_no_hidden_data[5];
                        }
                        //print_r ($asset_no_data_arr);

                        if ($txt_qty != "") {
                            for ($i = 1; $i <= $txt_qty; $i++) {
                                ?>
                                <tr id="assetSlNo_<?php echo $i; ?>">
                                    <td><input type="text" name="" id="" class="text_boxes" style="width:20px" value="<?php echo $i; ?>"  readonly="readonly" disabled="disabled"/> </td>
                                    <td><input type="text" name="txtSerialNo_<?php echo $i; ?>" id="txtSerialNo_<?php echo $i; ?>" class="text_boxes" style="width:165px" value="<?php echo $asset_no_data_arr[$i]['serial'] ?>"/> </td>
                                    <td><input type="text" name="txtQty_<?php echo $i; ?>" id="txtQty_<?php echo $i; ?>" class="text_boxes_numeric" style="width:35px" value="1" readonly disabled="disabled"/> </td>
                                    <td><input type="text" name="txtWarranty_<?php echo $i; ?>" id="txtWarranty_<?php echo $i; ?>" class="datepicker" style="width:130px" value="<?php echo $asset_no_data_arr[$i]['warenty'] ?>" readonly/> </td>
                            <input type="hidden" name="txtAssetNo_<?php echo $i; ?>" id="txtAssetNo_<?php echo $i; ?>" class="text_boxes" style="width:135px" readonly disabled="disabled" value="<?php echo $asset_no_data_arr[$i]['asset_no'] ?>"/></td> 
                            <td>
                                <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow1(<?php echo $i; ?>)" style="width:35px"/>
                                <input type="hidden" name="hiddenSlUpdateId_<?php echo $i; ?>" id="hiddenSlUpdateId_<?php echo $i; ?>" class="text_boxes_numeric"  value="<?php echo $asset_no_data_arr[$i]['id']; ?>"/>
                            </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="serial_no_popup()" /></td>
                        </tr>
                    </tfoot>
                </table>    
                <td> 
                    <input type="hidden" name="txt_serial_no_hidden_data" id="txt_serial_no_hidden_data" class="text_boxes" style="width:135px"/>
                    <input type="hidden" name="txt_NumberOfRow" id="txt_NumberOfRow" class="text_boxes" style="width:135px"/></td>
                    <input type="hidden" name="deleted_sl_id" id="deleted_sl_id" /> 
                </td>
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>

    <!-- For incrimate field -->
    <script>
        
		/*function fn_deleteRow1(rowNo) {
            var numRow = $('#tbl_serial_no tbody tr').length;
            if (numRow > 1 && numRow == rowNo) {
                $("#assetSlNo_" + rowNo).remove();
            }
        }
		
		*/
		function fn_deleteRow1(rowNo)
        {
            var deleted_row = $("#deleted_sl_id").val();

            if (deleted_row != "") deleted_row = deleted_row + ",";
                
            var numRow = $('#tbl_serial_no tbody tr').length;
            if (numRow != rowNo && numRow != 1)
            {
                return false;
            } else
            {
                deleted_row = deleted_row + $("#hiddenSlUpdateId_" + rowNo).val();
                $("#assetSlNo_" + rowNo).remove();

            }
            $("#deleted_sl_id").val(deleted_row);
//alert(deleted_row)
        }
		
		
		
		


        function serial_no_popup() {
            //alert( 'okay');
            //return;
            var serial_no = "";
            var total_row = $("#tbl_serial_no tbody tr").length;
            for (var b = 1; b <= total_row; b++) {
                var txtSerialNo = $("#txtSerialNo_" + b).val();
                var txtQty = $("#txtQty_" + b).val();
                var txtWarranty = $("#txtWarranty_" + b).val();
                var txtAssetNo = $("#txtAssetNo_" + b).val();
                var txthiddenSlUpdateId = $("#hiddenSlUpdateId_" + b).val();
                //alert(txthiddenSlUpdateId); 
                if (serial_no != '') {
                    serial_no += "*" + b + "_" + txtSerialNo + "_" + txtQty + "_" + txtWarranty + "_" + txtAssetNo + "_" + txthiddenSlUpdateId;
                } else {
                    serial_no += b + "_" + txtSerialNo + "_" + txtQty + "_" + txtWarranty + "_" + txtAssetNo + "_" + txthiddenSlUpdateId;
                }
            }

            $('#txt_serial_no_hidden_data').val(serial_no);
            $('#txt_NumberOfRow').val(total_row);
            //alert($('#txt_serial_no_hidden_data').val());
            //return;
            parent.emailwindow.hide();
        }
    </script>
    </html>
    <?php
}

//----------------PopUp Entry_Number Search----------------
if ($action == "search_asset_entry") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    <script>
        function js_set_value(id) {
            //alert(id);
            document.getElementById('hidden_system_number').value = id;
            parent.emailwindow.hide();
        }

    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="980" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                                           	 
                            <th width="170">Company Name</th>
                            <th width="170">Location</th>
                            <th width="110">Asset Type</th>
                            <th width="170">Category</th>
                            <th width="90">Asset No</th> 
                            <!--<th width="100">Supplier</th>-->
                            <th width="210" align="center" >Date Range</th>
                            <th width="80"><input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  /></th>           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <td>
                            <?php
							
                                echo create_drop_down("cbo_company_name", 170, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "-- Select Company --", $cbo_company_name, "load_drop_down( 'test_page_controller', this.value, 'load_drop_down_location', 'src_location_td');", "", "", "", "", "", "", "", "");
                             ?>
                            </td>
                            <td id="src_location_td">
                            <?php
                                echo create_drop_down("cbo_location", 170, $blank_array, "", 1, "-- Select Location --", $selected, "", "", "", "", "", "", "", "", "");
                             ?>
                            </td>
                            <td>
                                <?php
                                echo create_drop_down("cbo_aseet_type", 110, $asset_type, "", 1, "--- Select ---", $selected, "load_drop_down( 'test_page_controller', this.value, 'load_drop_down_category', 'src_category_td' );", "", "", "", "", "", "", "", "");
                                ?>
                            </td>
                            <td id="src_category_td">
							<?php
                           		echo create_drop_down("cbo_category", 170, $blank_array, "", 1, "--- Select ---", $selected, "", "", "", "", "", "", "", "", "");
                            ?>
                            </td>
                           <td>
                                <input type="text" name="asset_number" id="asset_number" style="width:90px;" class="text_boxes">
                            </td>
                            <td align="">
                                <input name="txt_date_from" id="txt_date_from" class="datepicker" style="width:66px" placeholder="From" readonly/>-
                                <input name="txt_date_to" id="txt_date_to" class="datepicker" style="width:66px" placeholder="To" readonly/>
                            </td>  

                            <td align="center">
                                <input type="button" name="btn_show" class="formbutton" value="Show" onClick="show_list_view(document.getElementById('asset_number').value + '_' + document.getElementById('cbo_company_name').value + '_' + document.getElementById('cbo_location').value + '_' + document.getElementById('cbo_aseet_type').value + '_' + document.getElementById('cbo_category').value + '_' + document.getElementById('txt_date_from').value + '_' + document.getElementById('txt_date_to').value, 'show_searh_active_listview', 'searh_list_view', 'test_page_controller', 'setFilterGrid(\'list_view\',-1)')" style="width:70px;" />		
                            </td>
                        </tr>
                        <tr>                  
                            <td align="center" height="40" valign="middle" colspan="7">
                                <?php echo load_month_buttons(1); ?>
                                <!-- Hidden field here-------->
                                <input type="hidden" id="hidden_system_number" value="" />
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


if ($action == "show_searh_active_listview") {
    $ex_data = explode("_", $data);
	//and c.asset_no LIKE '%" . $ex_data[0] ."'";
    if ($ex_data[0] == 0) $asset_number = ""; 	else $asset_number 	= " and c.asset_no LIKE '%" . $ex_data[0] ."'";
    if ($ex_data[1] == 0) $company_id = ""; 	else $company_id 	= " and a.company_id='" . $ex_data[1] . "'";
    if ($ex_data[2] == 0) $location = ""; 		else $location 		= " and a.location='" . $ex_data[2] . "'";
    if ($ex_data[3] == 0) $aseet_type = ""; 	else $aseet_type 	= " and a.asset_type='" . $ex_data[3] . "'";
    if ($ex_data[4] == 0) $category = ""; 		else $category 		= " and a.asset_category='" . $ex_data[4] . "'";

    $txt_date_from = $ex_data[5];
    $txt_date_to = $ex_data[6];

    if ($ex_data[1] == 0) 
	{
        echo "Please Company first";
        die;
    }

    if ($db_type == 0) {//for mysql
        if ($txt_date_from != "" || $txt_date_to != "") 
		{
            $tran_date = " and a.purchase_date between '" . change_date_format($txt_date_from, 'yyyy-mm-dd') . "' and '" . change_date_format($txt_date_to, 'yyyy-mm-dd') . "'";
        }
        $sql = "SELECT  a.id, a.entry_no, a.location, a.asset_type, a.asset_category, a.store, a.purchase_date, a.qty  FROM tbl_test_mst a where a.status_active=1 AND a.is_deleted=0 $category $aseet_type $location $company_id $tran_date";
    }

    if ($db_type == 2) {//for oracal
        if ($txt_date_from != "" && $txt_date_to != "") 
		{
            $tran_date = " and a.purchase_date between '" . change_date_format($txt_date_from, '', '', 1) . "' and '" . change_date_format($txt_date_to, '', '', 1) . "'";
        }
        $sql = "SELECT  a.id, a.entry_no, a.location, a.asset_type, a.asset_category, a.store, a.purchase_date, a.qty  FROM tbl_test_mst a  WHERE a.status_active=1 AND a.is_deleted=0 $category $aseet_type $location $company_id  $tran_date";
        //echo $sql;
    }

	$company_location = return_library_array("select id,location_name from lib_location where status_active =1 and is_deleted=0", "id", "location_name");
	$arr = array(2 => $company_location, 3 => $asset_type, 4 => $asset_category, 5 => $store_library);
	
	echo create_list_view("list_view", "Entry No,Asset No,Location,Type,Category,Store,Purchase Date,Qty", "150,130,150,90,90,120,90,50","978","300",0,$sql,"js_set_value","id", "", 1, "0,0,location,asset_type,asset_category,store,0,0", $arr, "entry_no,location,asset_type,asset_category,store,purchase_date,qty", "test_page_controller", '', '0,0,0,0,0,0,3,1');
}


//--------------Show List View--------------------
if ($action == "show_asset_active_listview") 
{
	
	
  //echo $data; die;	
  $sql = "SELECT  a.id, c.serial_no, c.asset_no, c.warranty_date FROM tbl_test_mst a, fam_acquisition_sl_dtls c  WHERE a.id = c.mst_id AND c.mst_id =$data AND c.status_active = 1 AND c.is_deleted = 0";
  
  echo create_list_view("list_view", "Serial No, Asset No, Warranty Date", "90,100", "400", "400", 0, $sql, "get_details_form_data", "id", "'populate_asset_details_form_data'", 1, "0,0,0",$arr, "serial_no,asset_no,warranty_date", "requires/test_page_controller", 'setFilterGrid("list_view",-1);', '0,0,3,0');

}

//-----------------PopUp Supplier Search-----------------------------
if ($action == "search_supplier") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    ?>
    <script>
        function js_set_value(id) {
            document.getElementById('txt_selected_id').value = id;
            parent.emailwindow.hide();
        }
    </script>
    <?php
    extract($_REQUEST);

    $sql = "select a.id,a.supplier_name from lib_supplier a, lib_supplier_tag_company b  where  a.id=b.supplier_id and b.tag_company=$cbo_company_name 
	and a.is_deleted=0  and a.status_active=1 order by a.supplier_name";
	//echo $sql; die;
    //$new_conn=integration_params(1);
    echo create_list_view("list_view", "Supplier Name", "300", "355", "310", 0, $sql, "js_set_value", "id,supplier_name", "", 1, "0", $arr, "supplier_name", "", "setFilterGrid('list_view',-1)", "0", "", "");

    echo "<input type='hidden' id='txt_selected_id' />";
    echo "<input type='hidden' id='txt_selected' />";
    exit();
}


if ($action == "save_update_delete_mst") {
    $process = array(&$_POST);
    extract(check_magic_quote_gpc($process));
	
	
	
	$txt_purchase_cost = str_replace(",", "", $txt_purchase_cost);
	$txt_accumulated_dep = str_replace(",", "", $txt_accumulated_dep);
	$txt_salvage_value = str_replace(",", "", $txt_salvage_value);
	
	//echo $txt_purchase_cost;die;
	

// Start: Insert Here----------------------------------------------------------
    if ($operation == 0) {
        $con = connect();
        if ($db_type == 0) {
            mysql_query("BEGIN");
        }

        $id = return_next_id("id", "tbl_test_mst", 1);
        if ($db_type == 2) {
            $year_id = " extract(year from insert_date)=";
        }
        if ($db_type == 0) {
            $year_id = "YEAR(insert_date)=";
        }

        $new_entry_no = explode("*", return_mrr_number(str_replace("'", "", $cbo_company_name), '', 'AA', date("Y", time()), 5, "select entry_no_prefix,entry_no_prefix_num,entry_no from tbl_test_mst where company_id=$cbo_company_name and " . $year_id . "" . date('Y', time()) . " order by entry_no_prefix_num desc", "entry_no_prefix", "entry_no_prefix_num"));
		
        $field_array = "id,entry_no,entry_no_prefix,entry_no_prefix_num,company_id,location,specification,asset_type,asset_category,asset_uom,asset_group,store,qty,serial_no,brand,origin,purchase_date,cost_per_unit,purc_currency,accumu_dep,salvage_value,deprec_rate,depreciation_method,dia_width,gauge,extra_cylinder,no_of_feeder,prod_capacity,capacity_uom,sequence_no,supplier,supplier_id,recive_mode,inserted_by,insert_date";
		
        $data_array = "(" . $id . ",'" . $new_entry_no[0] . "','" . $new_entry_no[1] . "','" . $new_entry_no[2] . "'," . $cbo_company_name . "," . $cbo_location . "," . $txt_specification . "," . $cbo_aseet_type . "," . $cbo_category . "," . $cbo_asset_uom . "," . $txt_asset_group . "," . $cbo_store . "," . $txt_qty . "," . $txt_serial_no . "," . $txt_brand . "," . $cbo_origin . "," . $txt_purchase_date . "," . $txt_purchase_cost . "," . $cbo_purc_currency . "," . $txt_accumulated_dep . "," . $txt_salvage_value . "," . $txt_depreciation_rate . "," . $cbo_depreciation_method . "," . $txt_dia_width . "," . $txt_gauge . "," . $txt_extra_cylinder . "," . $txt_no_of_feeder . "," . $txt_prod_capacity . "," . $cbo_capacity_uom . "," . $txt_sequence_no . "," . $txt_supplier . "," . $hidden_supplier_id . "," . $cbo_rec_mode . "," . $_SESSION['logic_erp']['user_id'] . ",'" . $pc_date_time . "')";

/*
        //Start : Set Qty PopUp data ---------------------------------------
        $txt_set_qty_hidden = explode("*", str_replace("'", "", $txt_set_qty_hidden));

        $id_set = return_next_id("id", "fam_acquisition_set_dtls", 1);
        $field_array_acquisition_set = "id,mst_id,part_name,qty_set,serial_no,inserted_by,insert_date";

        for ($a = 0; $a < count($txt_set_qty_hidden); $a++) 
		{
			$acquisition_set_popup = explode("_", $txt_set_qty_hidden[$a]);
			if ($data_array_acquisition_set != "")
			$data_array_acquisition_set.=",";
			//print_r($acquisition_set_popup); die;
			$data_array_acquisition_set.="('" . $id_set . "','" . $id . "','" . $acquisition_set_popup[0] . "','" . $acquisition_set_popup[1] . "','" . $acquisition_set_popup[2] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			$id_set = $id_set + 1;
        }
        //echo "10**insert into fam_acquisition_set_dtls($field_array_acquisition_set)values".$data_array_acquisition_set;die;
        //End : Set Qty PopUp data ---------------------------------------
        
		//Start : Set Serial No PopUp data ---------------------------------txt_serial_no_hidden------
        $txt_serial_no_hidden = explode("*", str_replace("'", "", $txt_serial_no_hidden));
        $id_sl = return_next_id("id", "fam_acquisition_sl_dtls", 1);

        $field_array_serial = "id,asset_prifix,asset_type,asset_category,mst_id,serial_no,qty,warranty_date,asset_no,cost_per_unit,inserted_by,insert_date";
        $asset_no_data_arr = array();
        for ($c = 0; $c < count($txt_serial_no_hidden); $c++) 
		{
            $txt_serial_no_hidden_data = explode("_", $txt_serial_no_hidden[$c]);
            
			$txt_warranty_date = $txt_serial_no_hidden_data[3];
			
            if ($db_type == 0)$warranty_date = change_date_format($txt_warranty_date, 'yyyy-mm-dd');
            if ($db_type == 2)$warranty_date = change_date_format($txt_warranty_date, 'DD-MMM-YYYY', '-', 1);
			
            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['serial'] = $txt_serial_no_hidden_data[1];
            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['warenty'] = $warranty_date;
            $asset_no_data_arr[$txt_serial_no_hidden_data[0]]['asset_no'] = $txt_serial_no_hidden_data[4];
        }

        //$sql = "select max(asset_prifix) as prifix  from fam_acquisition_sl_dtls  where asset_type=$cbo_aseet_type and asset_category=$cbo_category ";
		$sql = "select max(asset_prifix) as prifix  from fam_acquisition_sl_dtls  where asset_type=$cbo_aseet_type";
        $result_asset_prifix_id = sql_select($sql);
        
		$asset_prifix = str_pad(str_replace("'", "", $cbo_company_name), 2, "0", STR_PAD_LEFT) . "" . str_pad(str_replace("'", "", $cbo_aseet_type), 2, "0", STR_PAD_LEFT) . "" . str_pad(str_replace("'", "", $cbo_category), 3, "0", STR_PAD_LEFT);
        
		$asset_prifix_id = $result_asset_prifix_id[0][csf('prifix')] + 1;
        
		for ($j = 1; $j <= str_replace("'","", $txt_qty); $j++) 
		{
			$asset_no = $asset_prifix . "" . str_pad(str_replace("'", "", $asset_prifix_id), 5, "0", STR_PAD_LEFT);
			if ($data_array_serial != "")$data_array_serial.=",";
			
			$data_array_serial.="('" . $id_sl . "'," . $asset_prifix_id . "," . $cbo_aseet_type . "," . $cbo_category . ",'" . $id . "','" . $asset_no_data_arr[$j]['serial'] . "','1','" . $asset_no_data_arr[$j]['warenty'] . "','" . $asset_no ."'," . $txt_purchase_cost . ",'" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
			$asset_prifix_id = $asset_prifix_id + 1;
			$id_sl = $id_sl + 1;
        }
	
        //echo "10**insert into fam_acquisition_sl_dtls($field_array_serial)values".$data_array_serial;die;
        //End : Set Serial No PopUp data ---------------------------------------



 $asset_uom = str_replace("'", "", $cbo_asset_uom);

        if ($asset_uom == 58) {
            if (trim($data_array_acquisition_set) != "") {
                $rID1 = sql_insert("fam_acquisition_set_dtls", $field_array_acquisition_set, $data_array_acquisition_set, 1);
            }
        }

        if (trim($data_array_serial) != "") {
            $rID2 = sql_insert("fam_acquisition_sl_dtls", $field_array_serial, $data_array_serial, 1);
        }

*/



        $rID = sql_insert("tbl_test_mst", $field_array, $data_array, 0);


       

		//echo "10**".$rID; die;

        if ($db_type == 0) {
            if ($rID) {
                mysql_query("COMMIT");
                echo "0**" . $new_entry_no[0] . "**" . $id."**".$cbo_company_name;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $new_entry_no[0] . "**" . $id;
            }
        }

        if ($db_type == 2 || $db_type == 1) {
            if ($rID) {
                oci_commit($con);
                echo "0**" . $new_entry_no[0] . "**" . $id."**".$cbo_company_name;
            } else {
                oci_rollback($con);
                echo "10**" . $new_entry_no[0] . "**" . $id;
            }
        }
        disconnect($con);
        die;
    }
// End : Insert ------------------------------------------------------

// Start : Update Here----------------------------------------------------------
    else if ($operation == 1)
	{
        $con = connect();
        if ($db_type == 0)
		{
            mysql_query("BEGIN");
        }

		$field_array = "company_id*location*specification*asset_type*asset_category*asset_group*store*qty*recive_mode*serial_no*brand*origin*purchase_date*cost_per_unit*purc_currency*accumu_dep*deprec_rate*depreciation_method*dia_width*gauge*extra_cylinder*no_of_feeder*prod_capacity*capacity_uom*sequence_no*supplier*supplier_id*asset_uom*salvage_value*updated_by*update_date";
		
		$data_array = "" . $cbo_company_name . "*" . $cbo_location . "*" . $txt_specification . "*" . $cbo_aseet_type . "*" . $cbo_category . "*" . $txt_asset_group . "*" . $cbo_store . "*" . $txt_qty . "*" . $cbo_rec_mode . "*" . $txt_serial_no . "*" . $txt_brand . "*" . $cbo_origin . "*" . $txt_purchase_date . "*" . $txt_purchase_cost . "*" . $cbo_purc_currency . "*" . $txt_accumulated_dep . "*" . $txt_depreciation_rate . "*" . $cbo_depreciation_method . "*" . $txt_dia_width . "*" . $txt_gauge . "*" . $txt_extra_cylinder . "*" . $txt_no_of_feeder . "*" . $txt_prod_capacity . "*" . $cbo_capacity_uom . "*" . $txt_sequence_no . "*" . $txt_supplier . "*" . $hidden_supplier_id . "*" . $cbo_asset_uom . "*" . $txt_salvage_value . "*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";
		
		
        $rID1 = sql_update_a("tbl_test_mst", $field_array, $data_array, "id", "" . $update_id . "", 0);
        $rID2 = 1;
        $rID3 = 1;
        $rID4 = 1;
		
       


        $txt_entry_no = str_replace("'", "", $txt_entry_no);
        $update_id = str_replace("'", "", $update_id);
		
        echo "10**".$rID1;  die;

        if ($db_type == 0) {
            if ($rID1) {
                mysql_query("COMMIT");
                echo "1**" . $txt_entry_no . "**" . $update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $txt_entry_no . "**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID1) {
                oci_commit($con);
                echo "1**" . $txt_entry_no . "**" . $update_id;
            } else {
                oci_rollback($con);
                echo "10**" . $txt_entry_no . "**" . $update_id;
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
		
		$rID2 = sql_delete("tbl_test_mst", $field_array, $data_array, "id", "" . $update_id . "", 1);
		$rID3 = sql_delete("fam_acquisition_set_dtls", $field_array, $data_array, "mst_id", "" . $update_id . "", 1);
		$rID4 = sql_delete("fam_acquisition_sl_dtls", $field_array, $data_array, "mst_id", "" . $update_id . "", 1);
		
        $txt_entry_no = str_replace("'", "", $txt_entry_no);
        $update_id = str_replace("'", "", $update_id);

        if ($db_type == 0) {
            if ($rID2 && $rID3 && $rID4) {
                mysql_query("COMMIT");
                echo "2**" . $txt_entry_no . "**" . $update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $txt_entry_no . "**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID2 && $rID3 && $rID4) {
                oci_commit($con);
                echo "2**" . $txt_entry_no . "**" . $update_id;
            } else {
                oci_rollback($con);
                echo "10**" . $txt_entry_no . "**" . $update_id;
            }
        }
        disconnect($con);
        die;
    }
// End : Delete Here  ----------------------------------------------------------
}

if ($action == "populate_asset_details_form_data")
{
    $data_array = sql_select("select id, entry_no, company_id, location, specification, asset_type, asset_category, asset_group, store, qty, recive_mode, serial_no, brand, origin, purchase_date, cost_per_unit, purc_currency, accumu_dep, deprec_rate, depreciation_method, dia_width, gauge, extra_cylinder, no_of_feeder, prod_capacity, capacity_uom,  sequence_no,supplier,supplier_id,asset_uom,salvage_value from tbl_test_mst where id='$data'");
	
    foreach ($data_array as $row)
	{
        echo "document.getElementById('txt_entry_no').value 		= '" . $row[csf("entry_no")] . "';\n";
        echo "document.getElementById('cbo_company_name').value 	= '" . $row[csf("company_id")] . "';\n";
        echo "load_drop_down('requires/test_page_controller','" . $row[csf("company_id")] . "','load_drop_down_location','location_td' );\n";
        echo "document.getElementById('cbo_location').value 		= '" . $row[csf("location")] . "';\n";
        echo "document.getElementById('txt_specification').value 	= '" . $row[csf("specification")] . "';\n";
        echo "document.getElementById('cbo_aseet_type').value 		= '" . $row[csf("asset_type")] . "';\n";
		echo "document.getElementById('cbo_aseet_type').disabled = true;\n";
        echo "load_drop_down('requires/test_page_controller','" . $row[csf("asset_type")] . "','load_drop_down_category','category_td' );\n";
		
		
		if($row[csf("asset_type")]==1 || $row[csf("asset_type")]==2)
		{
			
			echo "document.getElementById('cbo_asset_uom').value 		= '" . $row[csf("asset_uom")] . "';\n";
			echo "document.getElementById('cbo_asset_uom').disabled = true;\n";
        	echo "document.getElementById('txt_qty').value 				= '" . $row[csf("qty")] . "';\n";
			echo "document.getElementById('txt_qty').disabled = true;\n";
		}
		else
		{
			echo "document.getElementById('cbo_asset_uom').value 		= '" . $row[csf("asset_uom")] . "';\n";
			echo "document.getElementById('txt_qty').value 				= '" . $row[csf("qty")] . "';\n";
		}
		
		
        echo "document.getElementById('cbo_category').value 		= '" . $row[csf("asset_category")] . "';\n";
        echo "document.getElementById('txt_asset_group').value 		= '" . $row[csf("asset_group")] . "';\n";
        
        echo "document.getElementById('cbo_rec_mode').value 		= '" . $row[csf("recive_mode")] . "';\n";
        echo "document.getElementById('txt_serial_no').value 		= '" . $row[csf("serial_no")] . "';\n";
        echo "load_drop_down('requires/test_page_controller','" . $row[csf("company_id")] . "','load_drop_down_store','store_td' );\n";
        echo "document.getElementById('cbo_store').value 			= '" . $row[csf("store")] . "';\n";
        echo "document.getElementById('txt_supplier').value 		= '" . $row[csf("supplier")] . "';\n";
        echo "document.getElementById('hidden_supplier_id').value 	= '" . $row[csf("supplier_id")] . "';\n";
        echo "document.getElementById('txt_brand').value 			= '" . $row[csf("brand")] . "';\n";
        echo "document.getElementById('cbo_origin').value 			= '" . $row[csf("origin")] . "';\n";
        echo "document.getElementById('txt_purchase_date').value 	= '" . change_date_format($row[csf("purchase_date")], "dd-mm-yyyy", "-") . "';\n";
        echo "document.getElementById('txt_purchase_cost').value 	= '" . number_format($row[csf("cost_per_unit")]) . "';\n";
        echo "document.getElementById('cbo_purc_currency').value 	= '" . $row[csf("purc_currency")] . "';\n";
        echo "document.getElementById('txt_accumulated_dep').value 	= '" . number_format($row[csf("accumu_dep")]) . "';\n";
        echo "document.getElementById('txt_salvage_value').value 	= '" . number_format($row[csf("salvage_value")]) . "';\n";
        echo "document.getElementById('cbo_depreciation_method').value = '" . $row[csf("depreciation_method")] . "';\n";
        echo "document.getElementById('txt_dia_width').value 		= '" . $row[csf("dia_width")] . "';\n";
        echo "document.getElementById('txt_gauge').value 		  	= '" . $row[csf("gauge")] . "';\n";
        echo "document.getElementById('txt_extra_cylinder').value 	= '" . $row[csf("extra_cylinder")] . "';\n";
        echo "document.getElementById('txt_no_of_feeder').value 	= '" . $row[csf("no_of_feeder")] . "';\n";
        echo "document.getElementById('txt_prod_capacity').value 	= '" . $row[csf("prod_capacity")] . "';\n";
        echo "document.getElementById('cbo_capacity_uom').value 	= '" . $row[csf("capacity_uom")] . "';\n";
        echo "document.getElementById('txt_sequence_no').value 		= '" . $row[csf("sequence_no")] . "';\n";
        echo "document.getElementById('txt_depreciation_rate').value = '" . $row[csf("deprec_rate")] . "';\n";
        
        echo "document.getElementById('update_id').value = '" . $row[csf("id")] . "';\n";
        echo "set_button_status(1, permission, 'fnc_asset_acquisition_entry',1);\n";
        echo "asset_uom_set();\n";
		echo "splite_date_update();\n";
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
