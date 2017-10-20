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


//---------------- search_file_number ----------------
if ($action == "search_file_number") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    <script>
        function js_file_no(data) {
            //alert(data); die;
            document.getElementById('hidden_system_number').value = data;
            parent.emailwindow.hide();
        }

    </script>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="searchorderfrm_2"  id="searchorderfrm_2" autocomplete="off">
                <table width="475" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                          	<th width="170">File No</th>
                            <th width="100">Asset No</th>                	 
                            <th width="170">Company Name</th>
                            <th width="100"><input type="reset" name="re_button" id="re_button" value="Reset" style="width:70px" class="formbutton"  /></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        	<td><input type="text" name="file_number" id="file_number" style="width:100px;" class="text_boxes"></td>
                            <td><input type="text" name="asset_number" id="asset_number" style="width:100px;" class="text_boxes"></td>
                            <td>
                                <?php
                                echo create_drop_down("cbo_company_name", 170, "select id,company_name from lib_company comp where status_active=1 and is_deleted=0 $company_cond order by company_name", "id,company_name", 1, "-- Select Company --", $selected, "", "", "", "", "", "", "", "", "");
                                ?>
                            </td>

                            <td align="center">
                                 <input type="button" name="btn_show" class="formbutton" value="Show"  style="width:70px;"  onClick="show_list_view(document.getElementById('file_number').value + '_' + document.getElementById('asset_number').value + '_' + document.getElementById('cbo_company_name').value, 'show_file_number_search_listview', 'search_list_view', 'civil_case_controller', 'setFilterGrid(\'list_view\',-1)')"/>
                            </td>
                        </tr>
                        <input type="hidden" id="hidden_system_number" value="" />  
                    </tbody>
                </table> 
                <div align="center" valign="top" id="search_list_view"> </div> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php
}

//-------------- show_file_number_search_listview--------------------
if ($action == "show_file_number_search_listview") 
{
	$ex_data = explode("_", $data);
	//print_r($ex_data);
	
	if ($ex_data[0] == 0) {
        $file_number = "";
    } else {
		$file_number = " AND file_number LIKE '%".$ex_data[0] . "'";
    }
	if ($ex_data[1] == 0) {
        $asset_number = "";
    } else {
		$asset_number = " AND asset_no LIKE '%" . $ex_data[1] ."'";
    }
	
    if ($ex_data[2] == 0)$company_name = ""; else $company_name = " AND company_id='" . $ex_data[2] . "'";

	if ($db_type == 0) {		//for MySql
		$sql = "SELECT id,asset_no,company_id,file_number FROM fam_land_position_mst WHERE  havingcase=1 AND status_active=1 AND is_deleted=0 $file_number $asset_number $company_name";
	}
	if($db_type == 2 || $db_type == 1) // for Oracale and MsSql
	{
		$sql = "SELECT id,asset_no,company_id,file_number FROM fam_land_position_mst WHERE  havingcase=1 AND status_active=1 AND is_deleted=0 $file_number $asset_number $company_name";
	}
    echo create_list_view("list_view", "File Number, Asset No, Company", "100,100", "400", "300", 0, $sql, "js_file_no", "file_number,asset_no,company_id,id", "", 1, "0,0,0",$arr, "file_number,asset_no,id", "requires/civil_case_controller", 'setFilterGrid("list_view",-1);', '0,0,0');
 
}

if($action=="check_file_no")
{
	$data=explode("**",$data);
	$sql="select id,land_id,file_number from fam_civil_case_mst where  status_active=1 AND is_deleted=0 and land_id='$data[1]' and file_number='$data[0]'";
	//echo $sql;die;
	$data_array=sql_select($sql,1);
	if(count($data_array)>0)
	{
		echo "1"."_".$data_array[0][csf('id')]."_".$data_array[0][csf('land_id')];;
	}
	else
	{
		echo "0_";
	}
	exit();	
}


if($action=="fileNoCheck")
{
	$data=explode("**",$data);
	//echo $data; die;
	$sql="select id,land_id,file_number from fam_civil_case_mst where status_active=1 AND is_deleted=0 and file_number=$data[0]";
	//echo $sql;die;
	$data_array=sql_select($sql,1);
	if(count($data_array)>0)
	{
		echo "1"."_".$data_array[0][csf('id')]."_".$data_array[0][csf('land_id')];;
	}
	else
	{
		echo "0_";
	}
	exit();	
}

//---------------- complainant_popup ----------------
if ($action == "complainant_name") 
{
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="complainant_1"  id="complainant_1" autocomplete="off">
                <table id="tbl_complainant" width="615" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                          	<th width="25">SL</th>
                            <th width="150">Name</th>                	 
                            <th width="150">Father's Name</th>
                            <th width="150">Mother's Name</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
						if($txt_complainant_update != ""){
							//echo $txt_complainant_update;
							$complainant_break_down_data = explode("*", $txt_complainant_update);
                            $i = 1;
                            foreach ($complainant_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                        ?>
                        
                        <tr id="complainant_<?php  echo $i ?>">
                            <td><input type="text" name="txtsl_<?php  echo $i ?>" id="txtsl_<?php  echo $i ?>" class="text_boxes" style="width:20px" value="<?php  echo $i ?>"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="complainantName_<?php  echo $i ?>" id="complainantName_<?php  echo $i ?>" class="text_boxes" value="<?php  echo $row_data_arr[2] ?>" style="width:150px" /> </td>
                            <td><input type="text" name="complainantFName_<?php  echo $i ?>" id="complainantFName_<?php  echo $i ?>" class="text_boxes" value="<?php  echo $row_data_arr[3] ?>"style="width:150px" value=""/> </td>
                            <td><input type="text" name="complainantMName_<?php  echo $i ?>" id="complainantMName_<?php  echo $i ?>" class="text_boxes" value="<?php  echo $row_data_arr[4] ?>"style="width:150px" /> </td>
                            <td>
                                <input type="button" name="btnadd_<?php  echo $i ?>" id="btnadd_<?php  echo $i ?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php  echo $i ?>)" style="width:35px"/>
                                &nbsp;
                                <input type="button" name="decrease_<?php  echo $i ?>" id="decrease_<?php  echo $i ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php  echo $i ?>)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateComplId_<?php  echo $i ?>" id="hiddenUpdateComplId_<?php  echo $i ?>" value="<?php  echo $row_data_arr[0]; ?>"   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_<?php  echo $i ?>" id="txtMstID_<?php  echo $i ?>" value="<?php  echo $row_data_arr[1]; ?>"   class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_compl_id" id="deleted_compl_id" />
                            </td>
                        </tr>
                        
                        <?php
						$i++;
							}
                        }else{
						?>
                    
                        <tr id="complainant_1">
                            <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                            <td><input type="text" name="complainantName_1" id="complainantName_1" class="text_boxes" style="width:150px" /> </td>
                            <td><input type="text" name="complainantFName_1" id="complainantFName_1" class="text_boxes" style="width:150px" value=""/> </td>
                            <td><input type="text" name="complainantMName_1" id="complainantMName_1" class="text_boxes" style="width:150px" /> </td>
                            <td>
                                <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                &nbsp;
                                <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       
                                 <input type="hidden" name="hiddenUpdateComplId_1" id="hiddenUpdateComplId_1" value=""   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_compl_id" id="deleted_compl_id" />
                            </td>
                        </tr>
                        <?php }	?>
                    </tbody>
                    <tfoot>
                    	<tr>
                            <td colspan="5" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="complainantDtlsBreakdown()" /></td>
                        </tr>
                        <input type="hidden" name="txt_hidden_complainant_data" id="txt_hidden_complainant_data" value=""   class="text_boxes" style="width:200px" />
                    </tfoot>
                </table> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    <script>
		function complainantDtlsBreakdown() 
		{
            var numberOfComplainant = "";
            var total_row = $("#tbl_complainant tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
				var hiddenUpdateComplId = $("#hiddenUpdateComplId_" + sl).val();
				var txtMstID 			= $("#txtMstID_" + sl).val();
				var complainantName 	= $("#complainantName_" + sl).val();
				var complainantFName 	= $("#complainantFName_" + sl).val();
                var complainantMName 	= $("#complainantMName_" + sl).val();
				
				if (numberOfComplainant != '') {
                    numberOfComplainant += "*" + hiddenUpdateComplId + "_"+ txtMstID + "_" + complainantName + "_" + complainantFName + "_" + complainantMName;
                } else {
                    numberOfComplainant += hiddenUpdateComplId + "_"+ txtMstID + "_" + complainantName + "_" + complainantFName + "_" + complainantMName;
                }
            }
			//alert('Ok');
            $('#txt_hidden_complainant_data').val(numberOfComplainant);
            parent.emailwindow.hide();
        }
		
		function add_break_down_tr(i) 
		{
            var row_num = $('#tbl_complainant tbody tr').length;
            if (row_num != i) {
                return false;
            } else {
                i++;
                if (row_num < row_num + 1) {
                    $("#tbl_complainant tbody tr:last").clone().find("input,select").each(function () {
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
                                return value;
                            },
                            'src': function (_, src) {
                                return src;
                            }
                        });
                    }).end().appendTo("#tbl_complainant tbody");
                    $("#tbl_complainant tbody tr:last ").removeAttr('id').attr('id', 'complainant_' + i);
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
					
                    $('#txtsl_' + i).val(i);
                    $('#complainantName_' + i).val('');
                    $('#complainantFName_' + i).val('');
                    $('#complainantMName_' + i).val('');
					
					$('#hiddenUpdateComplId_' + i).val('');
                    $('#txtMstID_' + i).val('');
					
                    set_all_onclick();
                }
            }
        }

		function fn_deleteRow(rowNo)
		{
            var deleted_row = $("#deleted_compl_id").val();

            if (deleted_row != "") deleted_row = deleted_row + ",";
            var numRow = $('#tbl_complainant tbody tr').length;
            if (numRow != rowNo && numRow != 1){
                return false;
            } else{
                deleted_row = deleted_row + $("#hiddenUpdateComplId_" + rowNo).val();
                $("#complainant_" + rowNo).remove();
            }
            $("#deleted_compl_id").val(deleted_row);
        }
    </script>
    </html>
    <?php
}

//---------------- hostile_popup ----------------
if ($action == "hostile_name") 
{
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="hostile_1"  id="hostile_1" autocomplete="off">
                <table id="tbl_hostile" width="615" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                          	<th width="25">SL</th>
                            <th width="150">Name</th>                	 
                            <th width="150">Father's Name</th>
                            <th width="150">Mother's Name</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    	<?php 
						if($txt_hostile_update != "")
						{
							//echo $txt_hostile_update;
							$hostile_break_down_data = explode("*", $txt_hostile_update);
                            $j = 1;
                            foreach ($hostile_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                        ?>
                        
                        <tr id="hostile_<?php  echo $j; ?>">
                            <td><input type="text" name="txtsl_<?php  echo $j ;?>" id="txtsl_<?php  echo $j ;?>" class="text_boxes" style="width:20px" value="<?php  echo $j ;?>"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="hostileName_<?php  echo $j; ?>" id="hostileName_<?php  echo $j; ?>" value="<?php  echo $row_data_arr[2]; ?>" class="text_boxes" style="width:150px" /> </td>
                            <td><input type="text" name="hostileFName_<?php  echo $j; ?>" id="hostileFName_<?php  echo $j; ?>" value="<?php  echo $row_data_arr[3]; ?>"  class="text_boxes" style="width:150px" value=""/> </td>
                            <td><input type="text" name="hostileMName_<?php  echo $j; ?>" id="hostileMName_<?php  echo $j; ?>" value="<?php  echo $row_data_arr[4]; ?>"  class="text_boxes" style="width:150px" /> </td>
                            <td>
                                <input type="button" name="btnadd_<?php  echo $j; ?>" id="btnadd_<?php  echo $j ;?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php  echo $j ;?>)" style="width:35px"/>
                                &nbsp;
                                <input type="button" name="decrease_<?php  echo $j ;?>" id="decrease_<?php  echo $j; ?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php  echo $j; ?>)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateHosId_<?php  echo $j ;?>" id="hiddenUpdateHosId_<?php  echo $j; ?>"  value="<?php  echo $row_data_arr[0]; ?>"    class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_<?php  echo $j; ?>" id="txtMstID_<?php  echo $j; ?>"  value="<?php  echo $row_data_arr[1]; ?>"    class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_hos_id" id="deleted_hos_id"  class="text_boxes" style="width:200px"/>
                            </td>
                        </tr>
                        
                         <?php
						$j++;
							}
                        }else{
						?>
                        
                        <tr id="hostile_1">
                            <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:20px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                            <td><input type="text" name="hostileName_1" id="hostileName_1" class="text_boxes" style="width:150px" /> </td>
                            <td><input type="text" name="hostileFName_1" id="hostileFName_1" class="text_boxes" style="width:150px" value=""/> </td>
                            <td><input type="text" name="hostileMName_1" id="hostileMName_1" class="text_boxes" style="width:150px" /> </td>
                            <td>
                                <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                &nbsp;
                                <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       
                                 <input type="hidden" name="hiddenUpdateHosId_1" id="hiddenUpdateHosId_1" value=""   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_1" id="txtMstID_1" value="" class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_hos_id" id="deleted_hos_id" class="text_boxes" style="width:200px" />
                            </td>
                        </tr>
                        
                        <?php }	?>
                    </tbody>
                    <tfoot>
                    	<tr>
                            <td colspan="5" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="hostileDtlsBreakdown()" /></td>
                        </tr>
                        <input type="hidden" name="txt_hidden_hostile_data" id="txt_hidden_hostile_data" value=""   class="text_boxes" style="width:200px" />
                    </tfoot>
                </table> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    <script>
		function hostileDtlsBreakdown() 
		{
            var numberOfhostile = "";
            var total_row = $("#tbl_hostile tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
				var hiddenUpdateHosId = $("#hiddenUpdateHosId_" + sl).val();
				var txtMstID 		= $("#txtMstID_" + sl).val();
				var hostileName 	= $("#hostileName_" + sl).val();
				var hostileFName 	= $("#hostileFName_" + sl).val();
                var hostileMName 	= $("#hostileMName_" + sl).val();
                
				if (numberOfhostile != '') {
                    numberOfhostile += "*" + hiddenUpdateHosId + "_"+ txtMstID + "_" + hostileName + "_" + hostileFName + "_" + hostileMName;
                } else {
                    numberOfhostile += hiddenUpdateHosId + "_"+ txtMstID + "_" + hostileName + "_" + hostileFName + "_" + hostileMName;
                }
            }
			//alert('Ok');
            $('#txt_hidden_hostile_data').val(numberOfhostile);
            parent.emailwindow.hide();
        }
		
		function add_break_down_tr(i) 
		{
			//alert( i);
            var row_num = $('#tbl_hostile tbody tr').length;
            if (row_num != i) {
                return false;
            } else {
                i++;
                if (row_num < row_num + 1) {
                    $("#tbl_hostile tbody tr:last").clone().find("input,select").each(function () {
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
                                return value;
                            },
                            'src': function (_, src) {
                                return src;
                            }
                        });
                    }).end().appendTo("#tbl_hostile tbody");
                    $("#tbl_hostile tbody tr:last ").removeAttr('id').attr('id', 'hostile_' + i);
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
					
                    $('#txtsl_' + i).val(i);
                    $('#hostileName_' + i).val('');
                    $('#hostileFName_' + i).val('');
                    $('#hostileMName_' + i).val('');
					
					$('#hiddenUpdateHosId_' + i).val('');
                    $('#txtMstID_' + i).val('');
					
                    set_all_onclick();
                }
            }
        }

		function fn_deleteRow(rowNo)
		{
            var deleted_row = $("#deleted_hos_id").val();

            if (deleted_row != "") deleted_row = deleted_row + ",";
            var numRow = $('#tbl_hostile tbody tr').length;
            if (numRow != rowNo && numRow != 1){
                return false;
            } else{
                deleted_row = deleted_row + $("#hiddenUpdateHosId_" + rowNo).val();
                $("#hostile_" + rowNo).remove();
            }
            $("#deleted_hos_id").val(deleted_row);
        }
    </script>
    </html>
    <?php
}

//---------------advocate_details_popup ----------------
if ($action == "advocate_details") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	//echo $advocate_dtls_update;
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="advocate_1"  id="advocate_1" autocomplete="off">
                <table id="tbl_advocate" width="975" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                          	<th width="25">SL</th>
                            <th width="140">Name</th>  
                            <th width="98">Membership No</th>
                            <th width="80">Contract No</th>
                            <th width="180">Office Address</th>
                            <th width="120">Email</th>              	 
                            <th width="70">Skype ID</th>
                            <th width="83">Current</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
						if($advocate_dtls_update != ""){
							//echo $advocate_dtls_update;
							$advocate_dtls_break_down_data = explode("*", $advocate_dtls_update);
                            $i = 1;
                            foreach ($advocate_dtls_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                        ?>
                        <tr id="advocate_<?php echo $i;?>">
                            <td><input type="text" name="txtsl_<?php echo $i;?>" id="txtsl_<?php echo $i;?>" class="text_boxes" style="width:25px" value="<?php echo $i;?>"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="advocateName_<?php echo $i;?>" id="advocateName_<?php echo $i;?>" value="<?php  echo $row_data_arr[2]; ?>" class="text_boxes" style="width:140px" /> </td>
                            <td><input type="text" name="membershipNo_<?php echo $i;?>" id="membershipNo_<?php echo $i;?>" value="<?php  echo $row_data_arr[3]; ?>" class="text_boxes" style="width:98px" value=""/> </td>
                            <td><input type="text" name="ContractNo_<?php echo $i;?>" id="ContractNo_<?php echo $i;?>" value="<?php  echo $row_data_arr[4]; ?>"  class="text_boxes" style="width:80px" /> </td>
                            <td><input type="text" name="officeAddress_<?php echo $i;?>" id="officeAddress_<?php echo $i;?>" value="<?php  echo $row_data_arr[5]; ?>"  class="text_boxes" style="width:180px" /> </td>
                            <td><input type="text" name="email_<?php echo $i;?>" id="email_<?php echo $i;?>" value="<?php  echo $row_data_arr[6]; ?>"  class="text_boxes" style="width:120px" value=""/> </td>
                            <td><input type="text" name="skypeId_<?php echo $i;?>" id="skypeId_<?php echo $i;?>" value="<?php  echo $row_data_arr[7]; ?>" class="text_boxes" style="width:70px" /> </td>
                            <td>
								<?php echo create_drop_down( "cboCurrent_$i", 83, $yes_no,0,1,'--select--',$row_data_arr[8],''); ?>
                            </td>
                            <td>
                                <input type="button" name="btnadd_<?php echo $i;?>" id="btnadd_<?php echo $i;?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i;?>)" style="width:35px"/>
                                <input type="button" name="decrease_<?php echo $i;?>" id="decrease_<?php echo $i;?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i;?>)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateAdvId_<?php echo $i;?>" id="hiddenUpdateAdvId_<?php echo $i;?>" value="<?php  echo $row_data_arr[0]; ?>"   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_<?php echo $i;?>" id="txtMstID_<?php echo $i;?>" value="<?php  echo $row_data_arr[1]; ?>"   class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_Adv_id" id="deleted_Adv_id" class="text_boxes" style="width:200px" />
                            </td>
                        </tr>
                        
                        <?php
						$i++;
							}
                        }else{
						?>
                        
                        <tr id="advocate_1">
                            <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:25px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="advocateName_1" id="advocateName_1" class="text_boxes" style="width:140px" /> </td>
                            <td><input type="text" name="membershipNo_1" id="membershipNo_1" class="text_boxes" style="width:98px" value=""/> </td>
                            <td><input type="text" name="ContractNo_1" id="ContractNo_1" class="text_boxes" style="width:80px" /> </td>
                            <td><input type="text" name="officeAddress_1" id="officeAddress_1" class="text_boxes" style="width:180px" /> </td>
                            <td><input type="text" name="email_1" id="email_1" class="text_boxes" style="width:120px" value=""/> </td>
                            <td><input type="text" name="skypeId_1" id="skypeId_1" class="text_boxes" style="width:70px" /> </td>
                            <td>
								<?php echo create_drop_down( "cboCurrent_1", 83, $yes_no,0, 1,'--select--',0,''); ?>
                            </td>
                            <td>
								<input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
								<input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       
								<input type="hidden" name="hiddenUpdateAdvId_1" id="hiddenUpdateAdvId_1" value=""   class="text_boxes" style="width:200px" />
								<input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />
								<input type="hidden" name="deleted_Adv_id" id="deleted_Adv_id" class="text_boxes" style="width:200px" />
                            </td>
                        </tr>
                        
                        <?php }	?>
                    </tbody>
                    <tfoot>
                    	<tr>
                            <td colspan="9" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="advocateDtlsBreakdown()" /></td>
                        </tr>
                        <input type="hidden" name="txt_hidden_advocate_data" id="txt_hidden_advocate_data" value=""   class="text_boxes" style="width:200px" />
                    </tfoot> 
                </table> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    <script>
		function advocateDtlsBreakdown() 
		{
            //alert( 'okay');return;
            var numberOfadvocate = "";
            var total_row = $("#tbl_advocate tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
                
                //alert (cbo_partname);
				var hiddenUpdateAdvId = $("#hiddenUpdateAdvId_" + sl).val();
				var txtMstID 		= $("#txtMstID_" + sl).val();
				
				var advocateName 	= $("#advocateName_" + sl).val();
				var membershipNo 	= $("#membershipNo_" + sl).val();
                var ContractNo 		= $("#ContractNo_" + sl).val();
				var officeAddress 	= $("#officeAddress_" + sl).val();
				var email 			= $("#email_" + sl).val();
                var skypeId 		= $("#skypeId_" + sl).val();
				var current 		= $("#cboCurrent_" + sl).val();
                
				if (numberOfadvocate != '') {
                    numberOfadvocate += "*" + hiddenUpdateAdvId + "_"+ txtMstID + "_" + advocateName + "_" + membershipNo + "_" + ContractNo + "_"+ officeAddress + "_"+ email + "_"+ skypeId + "_"+ current;
                } else {
                    numberOfadvocate += hiddenUpdateAdvId + "_"+ txtMstID + "_" + advocateName + "_" + membershipNo + "_" + ContractNo + "_"+ officeAddress + "_"+ email + "_"+ skypeId + "_"+ current;
                }
            }
            $('#txt_hidden_advocate_data').val(numberOfadvocate);
            parent.emailwindow.hide();
        }
		
		function add_break_down_tr(i) 
		{
            var row_num = $('#tbl_advocate tbody tr').length;
			//alert( row_num);
            if (row_num != i) {
                return false;
            } else {
                i++;
                if (row_num < row_num + 1) {
                    $("#tbl_advocate tbody tr:last").clone().find("input,select").each(function () {
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
                                return value;
                            },
                            'src': function (_, src) {
                                return src;
                            }
                        });
                    }).end().appendTo("#tbl_advocate tbody");
                    $("#tbl_advocate tbody tr:last ").removeAttr('id').attr('id', 'advocate_' + i);
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
					
                    $('#txtsl_' + i).val(i);
                    $("#advocateName_" + i).val('');
					$("#membershipNo_" + i).val('');
	                $("#ContractNo_" + i).val('');
					$("#officeAddress_" + i).val('');
					$("#email_" + i).val('');
	                $("#skypeId_" + i).val('');
					$("#cboCurrent_" + i).val('');
					
					$('#hiddenUpdateAdvId_' + i).val('');
                    $('#txtMstID_' + i).val('');
					
                    set_all_onclick();
                }
            }
        }

		function fn_deleteRow(rowNo)
		{
            var deleted_row = $("#deleted_Adv_id").val();

            if (deleted_row != "") deleted_row = deleted_row + ",";
            var numRow = $('#tbl_advocate tbody tr').length;
            if (numRow != rowNo && numRow != 1){
                return false;
            } else{
                deleted_row = deleted_row + $("#hiddenUpdateAdvId_" + rowNo).val();
                $("#advocate_" + rowNo).remove();
            }
            $("#deleted_Adv_id").val(deleted_row);
        }
    </script>
    </html>
    <?php
}

//---------------hearing_dtls_popup ----------------
if ($action == "hearing_details") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="hearing_1"  id="hearing_1" autocomplete="off">
                <table id="tbl_hearing" width="560" cellspacing="0" cellpadding="0" border="0" class="rpt_table" align="center">
                    <thead>
                        <tr>
                          	<th width="25">SL</th>
                            
                            <th width="100">Date</th>  
                            <th width="100">Expenses for Each Date</th>
                            <th width="200">Remarks</th>
                            
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      	<?php 
						if($hearing_dtls_update != ""){
							//echo $advocate_dtls_update;
							$hearinge_dtls_break_down_data = explode("*", $hearing_dtls_update);
                            $i = 1;
                            foreach ($hearinge_dtls_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                        ?>
                        <tr id="hearing_<?php echo $i;?>">
                            <td><input type="text" name="txtsl_<?php echo $i;?>" id="txtsl_<?php echo $i;?>" class="text_boxes" style="width:25px" value="<?php echo $i;?>"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="hearingDate_<?php echo $i;?>" id="hearingDate_<?php echo $i;?>" value="<?php  echo change_date_format($row_data_arr[2]) ?>" class="datepicker" style="width:100px" readonly/> </td>
                            <td><input type="text" name="expenses_<?php echo $i;?>" id="expenses_<?php echo $i;?>" value="<?php  echo $row_data_arr[3]; ?>" class="text_boxes_numeric" style="width:100px" value=""/> </td>
                            <td><input type="text" name="remarks_<?php echo $i;?>" id="remarks_<?php echo $i;?>" value="<?php  echo $row_data_arr[4]; ?>" class="text_boxes" style="width:200px" /> </td>
                            <td>
                                <input type="button" name="btnadd_<?php echo $i;?>" id="btnadd_<?php echo $i;?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $i;?>)" style="width:35px"/>
                                <input type="button" name="decrease_<?php echo $i;?>" id="decrease_<?php echo $i;?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $i;?>)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateHerId_<?php echo $i;?>" id="hiddenUpdateHerId_<?php echo $i;?>" value="<?php  echo $row_data_arr[0]; ?>" class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_<?php echo $i;?>" id="txtMstID_<?php echo $i;?>" value="<?php  echo $row_data_arr[1]; ?>" class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_Her_id" id="deleted_Her_id" class="text_boxes" style="width:200px"/>
                            </td>
                        </tr>
                         <?php
						$i++;
							}
                        }else{
						?>
                        <tr id="hearing_1">
                            <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:25px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="hearingDate_1" id="hearingDate_1" class="datepicker" style="width:100px" readonly /> </td>
                            <td><input type="text" name="expenses_1" id="expenses_1" class="text_boxes_numeric" style="width:100px" value=""/> </td>
                            <td><input type="text" name="remarks_1" id="remarks_1" class="text_boxes" style="width:200px" /> </td>
                            <td>
                                <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateHerId_1" id="hiddenUpdateHerId_1" value=""   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />
                                 <input type="hidden" name="deleted_Her_id" id="deleted_Her_id" class="text_boxes" style="width:200px"/>
                            </td>
                        </tr>
                         <?php	}?>
                        
                    </tbody>
                    <tfoot>
                    	<tr>
                            <td colspan="5" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="hearingDtlsBreakdown()" /></td>
                        </tr>
                        <input type="hidden" name="txt_hidden_hearing_data" id="txt_hidden_hearing_data" value=""   class="text_boxes" style="width:200px" />
                    </tfoot> 
                </table> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    <script>
		function hearingDtlsBreakdown() 
		{
            //alert( 'okay');
            //return;
            var numberOfhearing = "";
            var total_row = $("#tbl_hearing tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
				var hiddenUpdateHerId = $("#hiddenUpdateHerId_" + sl).val();
				var txtMstID 		= $("#txtMstID_" + sl).val();
				var hearingDate 	= $("#hearingDate_" + sl).val();
				var expenses 		= $("#expenses_" + sl).val();
                var remarks 		= $("#remarks_" + sl).val();
                
				if (numberOfhearing != '') {
                    numberOfhearing += "*" + hiddenUpdateHerId + "_"+ txtMstID + "_" + hearingDate + "_" + expenses + "_" + remarks;
                } else {
                    numberOfhearing += hiddenUpdateHerId + "_"+ txtMstID + "_" + hearingDate + "_" + expenses + "_" + remarks ;
                }
            }
			//alert('Ok');
            $('#txt_hidden_hearing_data').val(numberOfhearing);
            //alert($('#txt_hidden_hearing_data').val());
            //return;
			
            parent.emailwindow.hide();
        }
		
		function add_break_down_tr(i) 
		{
			//alert( i);
            var row_num = $('#tbl_hearing tbody tr').length;
			//alert( row_num);
            if (row_num != i) {
                return false;
            } else {
                i++;
                //$('#samplepic_' + i).removeAttr("src,value");
                if (row_num < row_num + 1) {
                    $("#tbl_hearing tbody tr:last").clone().find("input,select").each(function () {
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
                                return value;
                            },
                            'src': function (_, src) {
                                return src;
                            }
                        });
                    }).end().appendTo("#tbl_hearing tbody");
                    $("#tbl_hearing tbody tr:last ").removeAttr('id').attr('id', 'hearing_' + i);
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
					
                    $('#txtsl_' + i).val(i);
					
	                $("#hearingDate_" + i).val('');
					$("#expenses_" + i).val('');
	                $("#remarks_" + i).val('');
					
					$('#hiddenUpdateHerId_' + i).val('');
                    $('#txtMstID_' + i).val('');
					
					 $("#hearingDate_" + i).attr('class','datepicker');
                    set_all_onclick();
                }
            }
        }

		function fn_deleteRow(rowNo)
		{
            var deleted_row = $("#deleted_Her_id").val();

            if (deleted_row != "") deleted_row = deleted_row + ",";
            var numRow = $('#tbl_hearing tbody tr').length;
            if (numRow != rowNo && numRow != 1){
                return false;
            } else{
                deleted_row = deleted_row + $("#hiddenUpdateHerId_" + rowNo).val();
                $("#hearing_" + rowNo).remove();
            }
            $("#deleted_Her_id").val(deleted_row);
        }
    </script>
    </html>
    <?php
}

//---------------judgment_details_popup ----------------
if ($action == "judgment_details") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
	//echo $court_judgment_dtls_update;
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="judgment_1"  id="judgment_1" autocomplete="off">
                <table id="tbl_judgment" class="rpt_table" width="660" cellspacing="0" cellpadding="0" border="0"  align="center">
                    <thead>
                        <tr>
                          	<th width="25">SL</th>
                            <th width="100">Judge Name</th>  
                            <th width="100">Court Name</th>
                            <th width="100">Judgement</th>
                            <th width="180">Remarks</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
						if($court_judgment_dtls_update != ""){
							//echo $advocate_dtls_update;
							$judgment_dtls_break_down_data = explode("*", $court_judgment_dtls_update);
                            $k = 1;
                            foreach ($judgment_dtls_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                        ?>
                        <tr id="judgment_<?php echo $k;?>">
                            <td><input type="text" name="txtsl_<?php echo $k;?>" id="txtsl_<?php echo $k;?>" class="text_boxes" style="width:25px" value="<?php echo $k;?>"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="judgeName_<?php echo $k;?>" id="judgeName_<?php echo $k;?>" value="<?php  echo $row_data_arr[2]; ?>" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="courtName_<?php echo $k;?>" id="courtName_<?php echo $k;?>" value="<?php  echo $row_data_arr[3]; ?>" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="judgement_<?php echo $k;?>" id="judgement_<?php echo $k;?>" value="<?php  echo $row_data_arr[4]; ?>" class="text_boxes" style="width:100px" value=""/> </td>
                            <td><input type="text" name="remarks_<?php echo $k;?>" id="remarks_<?php echo $k;?>" value="<?php  echo $row_data_arr[5]; ?>" class="text_boxes" style="width:180px" /> </td>
                            <td>
                                <input type="button" name="btnadd_<?php echo $k;?>" id="btnadd_<?php echo $k;?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $k;?>)" style="width:35px"/>
                                <input type="button" name="decrease_<?php echo $k;?>" id="decrease_<?php echo $k;?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $k;?>)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateJudgementId_<?php echo $k;?>" id="hiddenUpdateJudgementId_<?php echo $k;?>" value="<?php  echo $row_data_arr[0]; ?>" class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_<?php echo $k;?>" id="txtMstID_<?php echo $k;?>" value="<?php  echo $row_data_arr[1]; ?>" class="text_boxes" style="width:200px" />
                            </td>
                        </tr>
                        <?php 
						$k++;
							}
						}else{
						?>
                        <tr id="judgment_1">
                            <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:25px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="judgeName_1" id="judgeName_1" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="courtName_1" id="courtName_1" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="judgement_1" id="judgement_1" class="text_boxes" style="width:100px" value=""/> </td>
                            <td><input type="text" name="remarks_1" id="remarks_1" class="text_boxes" style="width:180px" /> </td>
                            <td>
                                <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateJudgementId_1" id="hiddenUpdateJudgementId_1" value=""   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_1" id="txtMstID_1" value=""   class="text_boxes" style="width:200px" />
                            </td>
                        </tr>
                        <?php }?>
                    </tbody>
                    <tfoot>
                    	<tr>
                            <td colspan="6" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="judgmentDtlsBreakdown()" /></td>
                        </tr>
                        <input type="hidden" name="txt_hidden_judgment_data" id="txt_hidden_judgment_data" value=""   class="text_boxes" style="width:200px" />
                        <input type="hidden" name="deleted_Judgment_id" id="deleted_Judgment_id" class="text_boxes" style="width:200px"/>
                    </tfoot> 
                </table> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    <script>
		function judgmentDtlsBreakdown() 
		{
            //alert( 'okay');
            //return;
            var numberOfjudgment = "";
            var total_row = $("#tbl_judgment tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
                
                //alert (cbo_partname);
				var hiddenUpdateJudgementId = $("#hiddenUpdateJudgementId_" + sl).val();
				var txtMstID 				= $("#txtMstID_" + sl).val();
				
				var judgeName 				= $("#judgeName_" + sl).val();
				var courtName 				= $("#courtName_" + sl).val();
				var judgement 				= $("#judgement_" + sl).val();
                var remarks 				= $("#remarks_" + sl).val();
                
				if (numberOfjudgment != '') {
                    numberOfjudgment += "*" + hiddenUpdateJudgementId + "_"+ txtMstID + "_" + judgeName + "_" + courtName + "_" + judgement + "_" + remarks;
                } else {
                    numberOfjudgment += hiddenUpdateJudgementId + "_"+ txtMstID + "_" + judgeName + "_" + courtName + "_" + judgement + "_" + remarks ;
                }
            }
			//alert('Ok');
            $('#txt_hidden_judgment_data').val(numberOfjudgment);
            parent.emailwindow.hide();
        }
		
		function add_break_down_tr(i) 
		{
			//alert( i);
            var row_num = $('#tbl_judgment tbody tr').length;
			//alert( row_num);
            if (row_num != i) {
                return false;
            } else {
                i++;
                if (row_num < row_num + 1) {
                    $("#tbl_judgment tbody tr:last").clone().find("input,select").each(function () {
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
                                return value;
                            },
                            'src': function (_, src) {
                                return src;
                            }
                        });
                    }).end().appendTo("#tbl_judgment tbody");
                    $("#tbl_judgment tbody tr:last ").removeAttr('id').attr('id', 'judgment_' + i);
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
                    $('#txtsl_' + i).val(i);
		            $("#judgeName_" + i).val('');
					$("#courtName_" + i).val('');
					$("#judgement_" + i).val('');
	                $("#remarks_" + i).val('');
					$('#hiddenUpdateJudgementId_' + i).val('');
                    $('#txtMstID_' + i).val('');
					
                    set_all_onclick();
                }
            }
        }

		function fn_deleteRow(rowNo)
		{
            var deleted_row = $("#deleted_Judgment_id").val();
            if (deleted_row != "") deleted_row = deleted_row + ",";
            var numRow = $('#tbl_judgment tbody tr').length;
            if (numRow != rowNo && numRow != 1){
                return false;
            } else{
                deleted_row = deleted_row + $("#hiddenUpdateJudgementId_" + rowNo).val();
                $("#judgment_" + rowNo).remove();
            }
            $("#deleted_Judgment_id").val(deleted_row);
        }
    </script>
    </html>
    <?php
}

//---------------case_movement_representative popup ----------------
if ($action == "case_movement_representative") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode, 1);
    extract($_REQUEST);
    ?>
    </head>
    <body>
        <div align="center" style="width:100%;" >
            <form name="caseMovRep_1"  id="caseMovRep_1" autocomplete="off">
                <table id="tbl_caseMovRep" class="rpt_table" width="563" cellspacing="0" cellpadding="0" border="0"  align="center">
                    <thead>
                        <tr>
                          	<th width="25">SL</th>
                            <th width="100">Name</th>  
                            <th width="100">Cell Phone No</th>
                            <th width="100">Email</th>
                            <th width="83">Current</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php 
						if($case_rep_dtls_update != ""){
							$case_rep_dtls_break_down_data = explode("*", $case_rep_dtls_update);
                            $y = 1;
                            foreach ($case_rep_dtls_break_down_data as $row_data) 
							{
                                $row_data_arr = explode("_", $row_data);
                        ?>
                        <tr id="caseMovRep_<?php echo $y;?>">
                            <td><input type="text" name="txtsl_<?php echo $y;?>" id="txtsl_<?php echo $y;?>" class="text_boxes" style="width:25px" value="<?php echo $y;?>"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="Name_<?php echo $y;?>" id="Name_<?php echo $y;?>" value="<?php  echo $row_data_arr[2]; ?>" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="CellNo_<?php echo $y;?>" id="CellNo_<?php echo $y;?>" value="<?php  echo $row_data_arr[3]; ?>" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="emailId_<?php echo $y;?>" id="emailId_<?php echo $y;?>" value="<?php  echo $row_data_arr[4]; ?>" class="text_boxes" style="width:100px" value=""/> </td>
                            <td>
								<?php echo create_drop_down( "cboCurrent_$y", 83, $yes_no,0, 1,'--select--',$row_data_arr[5],''); ?>
                            </td>
                            
                            <td>
                                <input type="button" name="btnadd_<?php echo $y;?>" id="btnadd_<?php echo $y;?>" value="+" class="formbutton" onClick="add_break_down_tr(<?php echo $y;?>)" style="width:35px"/>
                                <input type="button" name="decrease_<?php echo $y;?>" id="decrease_<?php echo $y;?>" value="-" class="formbutton" onClick="fn_deleteRow(<?php echo $y;?>)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateCaseMovtId_<?php echo $y;?>" id="hiddenUpdateCaseMovtId_<?php echo $y;?>" value="<?php  echo $row_data_arr[0]; ?>" class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_<?php echo $y;?>" id="txtMstID_<?php echo $y;?>" value="<?php  echo $row_data_arr[1]; ?>" class="text_boxes" style="width:200px" />
                                 
                            </td>
                        </tr>
                        <?php 
						$y++;
							}
						}else{
							?>
							<tr id="caseMovRep_1">
                            <td><input type="text" name="txtsl_1" id="txtsl_1" class="text_boxes" style="width:25px" value="1"  readonly="readonly" disabled="disabled"/> </td>
                            
                            <td><input type="text" name="Name_1" id="Name_1" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="CellNo_1" id="CellNo_1" class="text_boxes" style="width:100px" /> </td>
                            <td><input type="text" name="emailId_1" id="emailId_1" class="text_boxes" style="width:100px" value=""/> </td>
                            <td>
								<?php echo create_drop_down( "cboCurrent_1", 83, $yes_no,0, 1,'--select--',0,''); ?>
                            </td>
                            
                            <td>
                                <input type="button" name="btnadd_1" id="btnadd_1" value="+" class="formbutton" onClick="add_break_down_tr(1)" style="width:35px"/>
                                <input type="button" name="decrease_1" id="decrease_1" value="-" class="formbutton" onClick="fn_deleteRow(1)" style="width:35px"/>       
                                 
                                 <input type="hidden" name="hiddenUpdateCaseMovtId_1" id="hiddenUpdateCaseMovtId_1" value=""   class="text_boxes" style="width:200px" />
       							<input type="hidden" name="txtMstID_1" id="txtMstID_1" value="" class="text_boxes" style="width:200px" />
                            </td>
                        </tr>
						<?php }?>
                        
                    </tbody>
                    <tfoot>
                    	<tr>
                            <td colspan="6" align="center"><input type="button" name="btn" id="btn" value="Close" class="formbutton" onClick="caseMovRepDtlsBreakdown()" /></td>
                        </tr>
                        <input type="hidden" name="txt_hidden_caseMovRep_data" id="txt_hidden_caseMovRep_data" value=""   class="text_boxes" style="width:200px" />
                        <input type="hidden" name="deleted_caseMovRep_id" id="deleted_caseMovRep_id" class="text_boxes" style="width:200px"/>
                    </tfoot> 
                </table> 
            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    <script>
		function caseMovRepDtlsBreakdown() 
		{
            //alert( 'okay');
            //return;
            var numberOfcaseMovRep = "";
            var total_row = $("#tbl_caseMovRep tbody tr").length;
            for (var sl = 1; sl <= total_row; sl++) {
                
                //alert (cbo_partname);
				var hiddenUpdateCaseMovtId = $("#hiddenUpdateCaseMovtId_" + sl).val();
				var txtMstID 			= $("#txtMstID_" + sl).val();
				
				var Name 				= $("#Name_" + sl).val();
				var CellNo 				= $("#CellNo_" + sl).val();
				var emailId 			= $("#emailId_" + sl).val();
				var cboCurrent 			= $("#cboCurrent_" + sl).val();
                
				if (numberOfcaseMovRep != '') {
                    numberOfcaseMovRep += "*" + hiddenUpdateCaseMovtId + "_"+ txtMstID + "_" + Name + "_" + CellNo + "_" + emailId + "_" + cboCurrent;
                } else {
                    numberOfcaseMovRep += hiddenUpdateCaseMovtId + "_"+ txtMstID + "_" + Name + "_" + CellNo + "_" + emailId + "_" + cboCurrent ;
                }
            }
			//alert('Ok');
            $('#txt_hidden_caseMovRep_data').val(numberOfcaseMovRep);
            //alert($('#txt_hidden_caseMovRep_data').val()); die;
            //return;
			
            parent.emailwindow.hide();
        }
		
		function add_break_down_tr(i) 
		{
			//alert( i);
            var row_num = $('#tbl_caseMovRep tbody tr').length;
			//alert( row_num);
            if (row_num != i) {
                return false;
            } else {
                i++;
                if (row_num < row_num + 1) {
                    $("#tbl_caseMovRep tbody tr:last").clone().find("input,select").each(function () {
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
                                return value;
                            },
                            'src': function (_, src) {
                                return src;
                            }
                        });
                    }).end().appendTo("#tbl_caseMovRep tbody");
                    $("#tbl_caseMovRep tbody tr:last ").removeAttr('id').attr('id', 'caseMovRep_' + i);
                    $('#btnadd_' + i).removeAttr("onclick").attr("onclick", "add_break_down_tr(" + i + ");");
                    $('#decrease_' + i).removeAttr("onclick").attr("onclick", "fn_deleteRow(" + i + ");");
					
                    $('#txtsl_' + i).val(i);
					
		            $("#Name_" + i).val('');
					$("#CellNo_" + i).val('');
					$("#emailId_" + i).val('');
	                $("#cboCurrent_" + i).val('');
					
					$('#hiddenUpdateCaseMovtId_' + i).val('');
                    $('#txtMstID_' + i).val('');
					
                    set_all_onclick();
                }
            }
        }

		function fn_deleteRow(rowNo)
		{
            var deleted_row = $("#deleted_caseMovRep_id").val();

            if (deleted_row != "") deleted_row = deleted_row + ",";
            var numRow = $('#tbl_caseMovRep tbody tr').length;
            if (numRow != rowNo && numRow != 1){
                return false;
            } else{
                deleted_row = deleted_row + $("#hiddenUpdateCaseMovtId_" + rowNo).val();
                $("#caseMovRep_" + rowNo).remove();
            }
            $("#deleted_caseMovRep_id").val(deleted_row);
        }

    </script>
    </html>
    <?php
}

if($action=="fileCheck")
{
	$data=explode("**",$data);
	//echo $data; die;

	$sql="select id,file_number from fam_land_position_mst where status_active=1 AND is_deleted=0 and havingcase=1 and file_number=$data[0]";
	//echo $sql;die;
	$data_array=sql_select($sql,1);
	if(count($data_array)>0)
	{
		echo "1"."_".$data_array[0][csf('id')]."_".$data_array[0][csf('file_number')];;
	}
	else
	{
		echo "0_";
	}
	exit();	
}




if ($action == "save_update_delete_mst") 
{
    $process = array(&$_POST);
    extract(check_magic_quote_gpc($process));

// Start: Insert Here----------------------------------------------------------
    if ($operation == 0) {
        $con = connect();
        if ($db_type == 0) {
            mysql_query("BEGIN");
        }
		if ($db_type == 2) {
            $year_id = " extract(year from insert_date)=";
        }
		
        if ($db_type == 0) {
            $year_id = "YEAR(insert_date)=";
        }
		
		 $id_mst = return_next_id("id", "fam_civil_case_mst", 1);
		 $field_array = "id,file_number,land_id,court_name,case_number,case_filing_date,company_representative,inserted_by,insert_date";
		 
		/*$txt_caseFiling_date = $txt_case_filing_date;
		if ($db_type == 0)$caseFiling_date = change_date_format($txt_caseFiling_date,'yyyy-mm-dd');
		if ($db_type == 2)$caseFiling_date = change_date_format( $txt_caseFiling_date,"yyyy-mm-dd","-",1 );*/
		
        $data_array = "(" . $id_mst . ",". $txt_file_number . "," . $hidden_Land_Id . "," . $txt_court_name . "," . $txt_case_number . "," . $txt_case_filing_date . "," . $txt_company_representative . "," . $_SESSION['logic_erp']['user_id'] . ",'" . $pc_date_time . "')";
		
		
		
		
		
		//Start : Insert Complainant --------------------------------------------------
		$txt_complainant_hidden = explode("*", str_replace("'", "", $txt_complainant));
		//echo $txt_seller_name_hidden; die;
        $id_complainant = return_next_id("id", "fam_civil_case_complainant", 1);
        $field_array_complainant_dtls = "id,mst_id,com_name,com_father_name,com_mother_name,inserted_by,insert_date";

        for ($c = 0; $c < count($txt_complainant_hidden); $c++) 
		{
            $complainant_dtls_popup = explode("_", $txt_complainant_hidden[$c]);
			
            if ($data_array_complainant_dtls != "") $data_array_complainant_dtls .=",";
               
            //print_r($complainant_dtls_popup); die;
			
            $data_array_complainant_dtls .="('" . $id_complainant . "','" . $id_mst . "','" . $complainant_dtls_popup[2] . "','" . $complainant_dtls_popup[3] ."','" . $complainant_dtls_popup[4] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
            $id_complainant = $id_complainant + 1;
		}
		
		//echo "10**insert into fam_civil_case_complainant($field_array_complainant_dtls)values".$data_array_complainant_dtls;die;
		//End : Insert Complainant --------------------------------------------------
		
		
		//Start : Insert Hostile --------------------------------------------------
		$txt_hostile_hidden = explode("*", str_replace("'", "", $txt_hostile));
        $id_hostile = return_next_id("id", "fam_civil_case_hostile", 1);
        $field_array_hostile_dtls = "id,mst_id,hos_name,hos_father_name,hos_mother_name,inserted_by,insert_date";

        for ($a = 0; $a < count($txt_hostile_hidden); $a++) {
            $hostile_name_popup = explode("_", $txt_hostile_hidden[$a]);
            if ($data_array_hostile_dtls != "")$data_array_hostile_dtls .=",";
                
			
			//print_r($hostile_name_popup); die;
            //print_r($id_mst); die;
			
            $data_array_hostile_dtls .="('" . $id_hostile . "','" . $id_mst . "','" . $hostile_name_popup[2] . "','" . $hostile_name_popup[3] . "','" . $hostile_name_popup[4] ."','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
            
			$id_hostile = $id_hostile + 1;
        }
		//echo "10**insert into fam_civil_case_hostile($field_array_hostile_dtls)values".$data_array_hostile_dtls;     die;
		//End  : Insert Hostile --------------------------------------------------
		
		//Start : Insert Advocate Details --------------------------------------------------
		$txt_advocate_dtls_hidden = explode("*", str_replace("'", "", $txt_advocate_details));
		$advocate_id = return_next_id("id", "fam_civil_case_advocate_dtls", 1);
        $field_array_advocate_dtls = "id,mst_id,advo_name,membership_no,contract_no,office_address,email_id,skype_id,move_current,inserted_by,insert_date";
		$data_array_advocate_dtls='';

        for ($a = 0; $a < count($txt_advocate_dtls_hidden); $a++) {
            $advocate_dtls_popup = explode("_", $txt_advocate_dtls_hidden[$a]);
            if ($data_array_advocate_dtls != "")$data_array_advocate_dtls .=",";
                
			
			//print_r($advocate_dtls_popup); die;
            //print_r($id_mst); die;
			
            $data_array_advocate_dtls .="('" . $advocate_id . "','" . $id_mst . "','" . $advocate_dtls_popup[2] . "','" . $advocate_dtls_popup[3] . "','" . $advocate_dtls_popup[4] ."','" . $advocate_dtls_popup[5] ."','" . $advocate_dtls_popup[6] ."','" . $advocate_dtls_popup[7] ."','" . $advocate_dtls_popup[8] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
            
			$advocate_id = $advocate_id + 1;
        }
		//echo "10**insert into fam_civil_case_advocate_dtls($field_array_advocate_dtls)values".$data_array_advocate_dtls; die;
		//End  : Insert Advocate Details --------------------------------------------------

		
		//Start : Insert Hearing --------------------------------------------------
		$txt_hearing_hidden = explode("*", str_replace("'", "", $txt_hearing));
        $hearing_id = return_next_id("id", "fam_civil_case_hearing_dtls", 1);
		
        $field_array_hearing_dtls = "id,mst_id,hearing_date,expenses,remarks,inserted_by,insert_date";

		for ($b = 0; $b < count($txt_hearing_hidden); $b++) 
		{
			$hearingDtls_popup = explode("_", $txt_hearing_hidden[$b]);
			
			if ($data_array_hearing_dtls != "")$data_array_hearing_dtls .=",";
				
			//print_r($hearingDtls_popup); die;
			//print_r($id_mst);die;
			
			$txt_hearing_date = $hearingDtls_popup[2];
			if ($db_type == 0)$hearing_date = change_date_format($txt_hearing_date,'yyyy-mm-dd');
			if ($db_type == 2)$hearing_date = change_date_format( str_replace("'","",$txt_hearing_date),"yyyy-mm-dd","-",1 );
				
			
			//echo $hearing_date; die;
			
			$data_array_hearing_dtls .="('" . $hearing_id . "','" . $id_mst . "','" . $hearing_date. "','" . $hearingDtls_popup[3]. "','" . $hearingDtls_popup[4] ."','" .  $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
			$hearing_id = $hearing_id + 1;
		}
		//echo "10**insert into fam_civil_case_hearing_dtls($field_array_hearing_dtls)values".$data_array_hearing_dtls; die;
		//End : Insert Hearing --------------------------------------------------
		
		//Start : Insert Court Judgment --------------------------------------------------
		$txt_court_judgment_hidden = explode("*", str_replace("'", "", $txt_court_judgment));
        $judgment_id = return_next_id("id", "fam_civil_case_judgment_dtls", 1);
        $field_array_judgment_dtls = "id,mst_id,judge_name,court_name,judgement,remarks,inserted_by,insert_date";

        for ($d = 0; $d < count($txt_court_judgment_hidden); $d++) {
            $judgment_hidden_popup = explode("_", $txt_court_judgment_hidden[$d]);
			
			//print_r($judgment_hidden_popup);die;

            if ($data_array_judgment_dtls != "")$data_array_judgment_dtls .=",";
                
			
            $data_array_judgment_dtls .="('" . $judgment_id . "','" . $id_mst . "','" . $judgment_hidden_popup[2] . "','" . $judgment_hidden_popup[3] ."','" . $judgment_hidden_popup[4] . "','". $judgment_hidden_popup[5]. "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
            $judgment_id = $judgment_id + 1;
		}
		//echo "10**insert into fam_civil_case_judgment_dtls($field_array_judgment_dtls)values".$data_array_judgment_dtls;die;
		//End : Insert Court Judgment --------------------------------------------------
		
		//Start : Insert Case Mov. Representative  --------------------------------------------------
		$txt_case_mov_representative_hidden = explode("*", str_replace("'", "", $txt_case_mov_representative));
        $rep_id = return_next_id("id", "fam_civil_case_movement_rep", 1);
        $field_array_movement_rep_dtls = "id,mst_id,name,cell_phone,email_id,mov_current,inserted_by,insert_date";

        for ($d = 0; $d < count($txt_case_mov_representative_hidden); $d++) 
		{
            $tax_case_mov_rep_hidden_popup = explode("_", $txt_case_mov_representative_hidden[$d]);
			
			//print_r($tax_case_mov_rep_hidden_popup);die;
			
            if ($data_array_movement_rep_dtls != "")$data_array_movement_rep_dtls .=",";
			
            $data_array_movement_rep_dtls .="('" . $rep_id . "','" . $id_mst . "','" . $tax_case_mov_rep_hidden_popup[2] . "','" . $tax_case_mov_rep_hidden_popup[3] ."','" . $tax_case_mov_rep_hidden_popup[4] . "','". $tax_case_mov_rep_hidden_popup[5]. "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
            
			$rep_id = $rep_id + 1;
		}
		//echo "10**insert into fam_civil_case_movement_rep($field_array_movement_rep_dtls)values".$data_array_movement_rep_dtls;die;
		//End : Insert Case Mov. Representative  --------------------------------------------------
		
		if($data_array != ""){
		//echo "10**insert into fam_civil_case_mst($field_array)values".$data_array ;//die;
        $rID = sql_insert("fam_civil_case_mst", $field_array, $data_array, 0);
		}
		
		if($data_array_complainant_dtls != ""){
		//echo "10**insert into fam_civil_case_complainant($field_array_complainant_dtls)values".$data_array_complainant_dtls; //die;
		$rID1 = sql_insert("fam_civil_case_complainant", $field_array_complainant_dtls, $data_array_complainant_dtls, 1);
		}
		
		if($data_array_hostile_dtls != ""){
		//echo "10**insert into fam_civil_case_hostile($field_array_hostile_dtls)values".$data_array_hostile_dtls; die;
		$rID2 = sql_insert("fam_civil_case_hostile", $field_array_hostile_dtls, $data_array_hostile_dtls, 1);
		}
		
		if($data_array_advocate_dtls != ""){
		//echo "10**insert into fam_civil_case_advocate_dtls($field_array_advocate_dtls)values".$data_array_advocate_dtls; die;
		$rID3 = sql_insert("fam_civil_case_advocate_dtls", $field_array_advocate_dtls, $data_array_advocate_dtls, 1);
		}
		
		if($data_array_hearing_dtls != ""){
		//echo "10**insert into fam_civil_case_hearing_dtls($field_array_hearing_dtls)values".$data_array_hearing_dtls; //die;
		$rID4 = sql_insert("fam_civil_case_hearing_dtls", $field_array_hearing_dtls, $data_array_hearing_dtls, 1);
		}
		
		if($data_array_judgment_dtls != ""){
		//echo "10**insert into fam_civil_case_judgment_dtls($field_array_judgment_dtls)values".$data_array_judgment_dtls;//die;
		$rID5 = sql_insert("fam_civil_case_judgment_dtls", $field_array_judgment_dtls, $data_array_judgment_dtls, 1);
		}
		
		if($data_array_movement_rep_dtls != ""){
		//echo "10**insert into fam_civil_case_movement_rep($field_array_movement_rep_dtls)values".$data_array_movement_rep_dtls;die;
		$rID6 = sql_insert("fam_civil_case_movement_rep", $field_array_movement_rep_dtls, $data_array_movement_rep_dtls, 1);
		}
		
		//echo $rID ."**". $rID1 ."**". $rID2 ."**". $rID3 ."**". $rID4 ."**". $rID5 ."**". $rID6; die;

        if ($db_type == 0) {
            if ($rID & $rID1 & $rID2 & $rID3 & $rID4 & $rID5 & $rID6) {
				
                mysql_query("COMMIT");
                echo "0**" . $id_mst;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $id_mst;
            }
        }

        if ($db_type == 2 || $db_type == 1) {
            if ($rID & $rID1 & $rID2 & $rID3 & $rID4 & $rID5 & $rID6) {
                oci_commit($con);
                echo "0**" . $id_mst;
            } else {
                oci_rollback($con);
                echo "10**" . $id_mst;
            }
        }
        disconnect($con);
        die;
    }// End : Insert ------------------------------------------------------
	
    else if ($operation == 1) // Start : Update Here----------------------------------------------------------
	{
        $con = connect();
        
		if ($db_type == 0) {
            mysql_query("BEGIN");
        }
		
		
		//echo $update_id;die;
		
		//Start : FAM_CIVIL_CASE_MST   ---------------------------------------		
        $field_array = "file_number*land_id*court_name*case_number*case_filing_date*company_representative*updated_by*update_date";
		
		
		$field_array_deleted = "status_active*is_deleted*updated_by*update_date";
        $data_array_deleted = "'2'*'1'*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";
		
		
		 $data_array = "" . $txt_file_number . "*" . $hidden_Land_Id . "*" . $txt_court_name . "*" . $txt_case_number . "*" . $txt_case_filing_date . "*" . $txt_company_representative . "*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";
		 
		//echo "update fam_civil_case_mst set(".$field_array.")=".$data_array; die;
		//End : Update FAM_CIVIL_CASE_MST ---------------------------------------
		
		
		
		//Start : Update Complainant Details --------------------------------------------------
		$txt_complainant_hidden = explode("*", str_replace("'", "", $txt_complainant));
        
		$id_complainant = return_next_id("id", "fam_civil_case_complainant", 1);
		
		$field_array_complainant_dtls = "id,mst_id,com_name,com_father_name,com_mother_name,inserted_by,insert_date";
		
		$field_array_complainant_dtls_update = "mst_id*com_name*com_father_name*com_mother_name*updated_by*update_date";
		
		$data_array_complainant_dtls = '';
        for ($c = 0; $c < count($txt_complainant_hidden); $c++)
		{
            $complainant_dtls_popup = explode("_", $txt_complainant_hidden[$c]);
			 if ($complainant_dtls_popup[0] != "") 
			 {
				$update_complainant_arr[] = $complainant_dtls_popup[0];
				$data_array_complainant_arr[$complainant_dtls_popup[0]] = explode("*", ("'" . $complainant_dtls_popup[1] . "'*'" . $complainant_dtls_popup[2] . "'*'" . $complainant_dtls_popup[3] . "'*'". $complainant_dtls_popup[4] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
				
            }
			else
			{
				if ($data_array_complainant_dtls != ""){$data_array_complainant_dtls .=",";}
				$data_array_complainant_dtls .="('" . $id_complainant . "',".$update_id.",'" . $complainant_dtls_popup[2] . "','" . $complainant_dtls_popup[3] ."','" . $complainant_dtls_popup[4] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
				$id_complainant = $id_complainant + 1;
			}
		}
		//update complainant details
		if (count($data_array_complainant_arr) > 0) {
			
			//echo bulk_update_sql_statement("fam_civil_case_complainant", "id", $field_array_complainant_dtls_update, $data_array_complainant_arr, $update_complainant_arr, 0), 1; die;
            $rID11 = execute_query(bulk_update_sql_statement("fam_civil_case_complainant", "id", $field_array_complainant_dtls_update, $data_array_complainant_arr, $update_complainant_arr, 0), 1);
        }
		if (str_replace("'", "", $hidden_deleted_complId) != "")
		{
            $rID10 = sql_multirow_update("fam_civil_case_complainant", $field_array_deleted, $data_array_deleted, "id", str_replace("'", "", $hidden_deleted_complId), '');
        }
		//End 	: Update Complainant Details --------------------------------------------------
		

		//Start : Update hostile Details =======================================
		$txt_hostile_hidden = explode("*", str_replace("'", "", $txt_hostile));
        
		$id_hostile = return_next_id("id", "fam_civil_case_hostile", 1);
		
        $field_array_hostile_dtls = "id,mst_id,hos_name,hos_father_name,hos_mother_name,inserted_by,insert_date";
		
		$field_array_hostile_update = "mst_id*hos_name*hos_father_name*hos_mother_name*updated_by*update_date";
		
		$data_array_hostile_dtls = '';
        for ($c = 0; $c < count($txt_hostile_hidden); $c++) 
		{
            $hostile_dtls_popup = explode("_", $txt_hostile_hidden[$c]);
			 
			 if ($hostile_dtls_popup[0] != "") 
			 {
                
				$update_hostile_arr[] = $hostile_dtls_popup[0];
                
				$data_array_hostiles_arr[$hostile_dtls_popup[0]] = explode("*", ("'" . $hostile_dtls_popup[1] . "'*'" . $hostile_dtls_popup[2] . "'*'" . $hostile_dtls_popup[3] . "'*'". $hostile_dtls_popup[4] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
				
            } 
			else 
			{
	            if ($data_array_hostile_dtls != ""){$data_array_hostile_dtls .=",";}

	            $data_array_hostile_dtls .="('" . $id_hostile . "'," . $update_id . ",'" . $hostile_dtls_popup[2] . "','" . $hostile_dtls_popup[3] ."','" . $hostile_dtls_popup[4] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
	            $id_hostile = $id_hostile + 1;
			}
		}
		
		
		if (str_replace("'", "", $txt_hostile_deleteId) != "")
		{
            $rID11 = sql_multirow_update("fam_civil_case_hostile", $field_array_deleted, $data_array_deleted, "id", str_replace("'", "", $txt_hostile_deleteId), '');
        }

		//echo "insert into fam_civil_case_hostile($field_array_hostile_dtls) values $data_array_hostile_dtls "; die;
		//echo bulk_update_sql_statement("fam_civil_case_hostile", "id", $field_array_hostile_update, $data_array_hostiles_arr, $update_hostile_arr, 0), 1; die;
		//End 	: Update hostile Details ============================================
		
		
		//Start : Update Advocate Details =======================================
		$txt_advocate_details_hidden = explode("*", str_replace("'", "", $txt_advocate_details));
		$id_advocate_dtls = return_next_id("id", "fam_civil_case_advocate_dtls", 1);
        $field_array_advocate_dtls = "id,mst_id,advo_name,membership_no,contract_no,office_address,email_id,skype_id,move_current,inserted_by,insert_date";
		$field_array_advocate_dtls_update = "mst_id*advo_name*membership_no*contract_no*office_address*email_id*skype_id*move_current*updated_by*update_date";
		
		$data_array_advocate_dtls = '';
        for ($d = 0; $d < count($txt_advocate_details_hidden); $d++) {
            $advocate_dtls_popup = explode("_", $txt_advocate_details_hidden[$d]);
			 //print_r($advocate_dtls_popup);die;
			 if ($advocate_dtls_popup[0] != "") {
                
				$update_advocate_dtls_arr[] = $advocate_dtls_popup[0];
                
				$data_array_advocate_dtls_arr[$advocate_dtls_popup[0]] = explode("*", ("'" . $advocate_dtls_popup[1] . "'*'" . $advocate_dtls_popup[2] . "'*'" . $advocate_dtls_popup[3] . "'*'". $advocate_dtls_popup[4] . "'*'". $advocate_dtls_popup[5] . "'*'". $advocate_dtls_popup[6] . "'*'". $advocate_dtls_popup[7] . "'*'". $advocate_dtls_popup[8] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
				
            } else {
            if ($data_array_advocate_dtls != ""){
                $data_array_advocate_dtls .=",";
			}
            $data_array_advocate_dtls .="('" . $id_advocate_dtls . "'," . $update_id . ",'" . $advocate_dtls_popup[2] . "','" . $advocate_dtls_popup[3] ."','" . $advocate_dtls_popup[4] . "','". $advocate_dtls_popup[5] . "','". $advocate_dtls_popup[6] . "','". $advocate_dtls_popup[7] . "','". $advocate_dtls_popup[8] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
            $id_advocate_dtls = $id_advocate_dtls + 1;
			}
		}
		
		if (str_replace("'", "", $txt_Advocate_deleteId) != "")
		{
            $rID12 = sql_multirow_update("fam_civil_case_advocate_dtls", $field_array_deleted, $data_array_deleted, "id", str_replace("'", "", $txt_Advocate_deleteId), '');
        }
		//End 	: Update Advocate Details ============================================


		//Start : Update Hearing Dtsl =======================================
		$txt_hearing_hidden = explode("*", str_replace("'", "", $txt_hearing));
		$hearing_id = return_next_id("id", "fam_civil_case_hearing_dtls", 1);
		$field_array_hearing_dtls = "id,mst_id,hearing_date,expenses,remarks,inserted_by,insert_date";
		$field_array_hearing_dtls_update = "mst_id*hearing_date*expenses*remarks*updated_by*update_date";
		
		$data_array_hearing_dtls = '';
		
        for ($c = 0; $c < count($txt_hearing_hidden); $c++) {
            $hearing_dtls_popup = explode("_", $txt_hearing_hidden[$c]);
			 
			 	$txt_hearing_date = $hearing_dtls_popup[2];
				if ($db_type == 0)$hearing_date = change_date_format($txt_hearing_date,'yyyy-mm-dd');
				if ($db_type == 2)$hearing_date = change_date_format( str_replace("'","",$txt_hearing_date),"yyyy-mm-dd","-",1 );
			 
			 //echo $hearing_date; die;
			 
			 if ($hearing_dtls_popup[0] != "") 
			 {
				$update_hearing_dtls_arr[] = $hearing_dtls_popup[0];
				 
				$data_array_hearing_dtls_arr[$hearing_dtls_popup[0]] = explode("*", ("'" . $hearing_dtls_popup[1] . "'*'" . $hearing_date . "'*'" . $hearing_dtls_popup[3] . "'*'". $hearing_dtls_popup[4] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
				
            } 
			else 
			{
	            if ($data_array_hearing_dtls != "")
				{
	                $data_array_hearing_dtls .=",";
				}
            	$data_array_hearing_dtls .="('" . $hearing_id . "'," . $update_id . ",'" . $hearing_date . "','" . $hearing_dtls_popup[3] ."','" . $hearing_dtls_popup[4] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
            $hearing_id = $hearing_id + 1;
			}
		}
		
		if (str_replace("'", "", $txt_Hearing_deleteId) != "")
		{
            $rID13 = sql_multirow_update("fam_civil_case_hearing_dtls", $field_array_deleted, $data_array_deleted, "id", str_replace("'", "", $txt_Hearing_deleteId), '');
        }
		//End 	: Update Hearing Dtsl ============================================
		
		
		//Start : Update Court Judgment =======================================
		$txt_court_judgment_hidden = explode("*", str_replace("'", "", $txt_court_judgment));
        $judgment_id = return_next_id("id", "fam_civil_case_judgment_dtls", 1);
        $field_array_judgment_dtls = "id,mst_id,judge_name,court_name,judgement,remarks,inserted_by,insert_date";
		$field_array_judgment_dtls_update = "mst_id*judge_name*court_name*judgement*remarks*updated_by*update_date";
		$data_array_judgment_dtls = '';

        for ($c = 0; $c < count($txt_court_judgment_hidden); $c++) 
		{
            $judgment_dtls_popup = explode("_", $txt_court_judgment_hidden[$c]);
			 //echo $judgment_dtls_popup; die;
			 
			 if ($judgment_dtls_popup[0] != "") 
			 {
				$update_judgment_dtls_arr[] = $judgment_dtls_popup[0];
				 
				$data_array_judgment_dtls_arr[$judgment_dtls_popup[0]] = explode("*", ("'" . $judgment_dtls_popup[1] . "'*'" . $judgment_dtls_popup[2] . "'*'" . $judgment_dtls_popup[3] . "'*'". $judgment_dtls_popup[4]. "'*'". $judgment_dtls_popup[5] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
				
            } 
			else 
			{
	            if ($data_array_judgment_dtls != "")
				{
	                $data_array_judgment_dtls .=",";
				}
            	$data_array_judgment_dtls .="('" . $judgment_id . "'," . $update_id . ",'" . $judgment_dtls_popup[2] . "','" . $judgment_dtls_popup[3] ."','" . $judgment_dtls_popup[4] . "','" . $judgment_dtls_popup[5] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
            $judgment_id = $judgment_id + 1;
			}
		}
		
		if (str_replace("'", "", $txt_Judgment_deleteId) != "")
		{
            $rID14 = sql_multirow_update("fam_civil_case_judgment_dtls", $field_array_deleted, $data_array_deleted, "id", str_replace("'", "", $txt_Judgment_deleteId), '');
        }
		//End 	: Update Court Judgment ============================================


        //Start : Update Case Mov. Representative  =======================================
		$txt_case_mov_representative_hidden = explode("*", str_replace("'", "", $txt_case_mov_representative));
         $rep_id = return_next_id("id", "fam_civil_case_movement_rep", 1);
        $field_array_movement_rep_dtls = "id,mst_id,name,cell_phone,email_id,mov_current,inserted_by,insert_date";
		
		$field_array_movement_rep_dtls_update = "mst_id*name*cell_phone*email_id*mov_current*updated_by*update_date";
		$data_array_movement_rep_dtls = '';

        for ($c = 0; $c < count($txt_case_mov_representative_hidden); $c++) 
		{
            $case_mov_rep_dtls_popup = explode("_", $txt_case_mov_representative_hidden[$c]);
			 //echo $case_mov_representative_dtls_popup; die;
			 
			 if ($case_mov_rep_dtls_popup[0] != "") 
			 {
				$update_case_mov_rep_dtls_arr[] = $case_mov_rep_dtls_popup[0];
				 
				$data_array_case_mov_rep_dtls_arr[$case_mov_rep_dtls_popup[0]] = explode("*", ("'" . $case_mov_rep_dtls_popup[1] . "'*'" . $case_mov_rep_dtls_popup[2] . "'*'" . $case_mov_rep_dtls_popup[3] . "'*'". $case_mov_rep_dtls_popup[4]. "'*'". $case_mov_rep_dtls_popup[5] . "'*'" . $_SESSION['logic_erp']['user_id'] . "'*'" . $pc_date_time . "'"));
            } else{ 
	            if ($data_array_movement_rep_dtls != "")
				{
	                $data_array_movement_rep_dtls .=",";
				}
            	$data_array_movement_rep_dtls .="('" . $rep_id . "'," . $update_id . ",'" . $case_mov_rep_dtls_popup[2] . "','" . $case_mov_rep_dtls_popup[3] ."','" . $case_mov_rep_dtls_popup[4] . "','" . $case_mov_rep_dtls_popup[5] . "','" . $_SESSION['logic_erp']['user_id'] . "','" . $pc_date_time . "')";
			
            $rep_id = $rep_id + 1;
			}
		}
		
		if (str_replace("'", "", $txt_Representative_deleteId) != "")
		{
            $rID15 = sql_multirow_update("fam_civil_case_movement_rep", $field_array_deleted, $data_array_deleted, "id", str_replace("'", "", $txt_Representative_deleteId), '');
        }
		//End 	: Update Case Mov. Representative  ============================================

        $update_id = str_replace("'", "", $update_id);
		
		//echo "update fam_civil_case_mst set(".$field_array.")=".$data_array; die;
		$rID = sql_update("fam_civil_case_mst", $field_array, $data_array, "id", "" . $update_id . "", 0);
		
		//===============Complainant================
		if($data_array_complainant_dtls!="")	//insert Complainant
		{
			$rID1 = sql_insert("fam_civil_case_complainant", $field_array_complainant_dtls, $data_array_complainant_dtls, 1);
		}
		if (count($data_array_complainant_arr) > 0) {//Complainant--update
            $rID11 = execute_query(bulk_update_sql_statement("fam_civil_case_complainant", "id", $field_array_complainant_dtls_update, $data_array_complainant_arr, $update_complainant_arr, 0), 1);
        }
		
		
		
		//====================Hostile============
		if($data_array_hostile_dtls!="")	//insert Hostile
		{
			$rID2 = sql_insert("fam_civil_case_hostile", $field_array_hostile_dtls, $data_array_hostile_dtls, 1);
		}
		
		if (count($data_array_hostiles_arr) > 0) {//update Hostile
			
			//echo bulk_update_sql_statement("fam_civil_case_hostile", "id", $field_array_hostile_update, $data_array_hostiles_arr, $update_hostile_arr, 0), 1; die;
            $rID12 = execute_query(bulk_update_sql_statement("fam_civil_case_hostile", "id", $field_array_hostile_update, $data_array_hostiles_arr, $update_hostile_arr, 0), 1);
        }
		
		
		//====================Advocate============
		if($data_array_advocate_dtls != ""){  //insert Advocate
			
			$rID3 = sql_insert("fam_civil_case_advocate_dtls", $field_array_advocate_dtls, $data_array_advocate_dtls, 1);
		}
		
		if (count($data_array_advocate_dtls_arr) > 0) {//advocate dtls--update
			//echo bulk_update_sql_statement("fam_civil_case_advocate_dtls", "id", $field_array_advocate_dtls_update, $data_array_advocate_dtls_arr, $update_advocate_dtls_arr, 0), 1; die;
            $rID13 = execute_query(bulk_update_sql_statement("fam_civil_case_advocate_dtls", "id", $field_array_advocate_dtls_update, $data_array_advocate_dtls_arr, $update_advocate_dtls_arr, 0), 1);
        }
		
		
		//====================Hearing Details============
		if($data_array_hearing_dtls != ""){  //insert Hearing Dtls
			//echo "insert into fam_civil_case_hearing_dtls ($field_array_hearing_dtls) values $data_array_hearing_dtls"; die;
			$rID4 = sql_insert("fam_civil_case_hearing_dtls", $field_array_hearing_dtls, $data_array_hearing_dtls, 1);
		}
		
		if (count($data_array_hearing_dtls_arr) > 0) { // Update Hearing Dtls
			
			//echo bulk_update_sql_statement("fam_civil_case_hearing_dtls", "id", $field_array_hearing_dtls_update, $data_array_hearing_dtls_arr, $update_hearing_dtls_arr, 0), 1; die;
            $rID14 = execute_query(bulk_update_sql_statement("fam_civil_case_hearing_dtls", "id", $field_array_hearing_dtls_update, $data_array_hearing_dtls_arr, $update_hearing_dtls_arr, 0), 1);
        }
		
		
		//====================Judgment Details============
		if($data_array_judgment_dtls != ""){  //insert Judgment Dtls
			//echo "insert into fam_civil_case_judgment_dtls ($field_array_judgment_dtls) values $data_array_judgment_dtls"; die;
			$rID5 = sql_insert("fam_civil_case_judgment_dtls", $field_array_judgment_dtls, $data_array_judgment_dtls, 1);
		}
		
		if (count($data_array_judgment_dtls_arr) > 0) { // Update Judgment Dtls
			
			//echo bulk_update_sql_statement("fam_civil_case_judgment_dtls", "id", $field_array_judgment_dtls_update, $data_array_judgment_dtls_arr, $update_judgment_dtls_arr, 0), 1; die;
            $rID15 = execute_query(bulk_update_sql_statement("fam_civil_case_judgment_dtls", "id", $field_array_judgment_dtls_update, $data_array_judgment_dtls_arr, $update_judgment_dtls_arr, 0), 1);
        }		
		
		//====================Case Movement Representitive dtls============
		if($data_array_judgment_dtls != ""){  //insert Case Movement Representitive dtls
			//echo "insert into fam_civil_case_movement_rep ($field_array_movement_rep_dtls) values $data_array_movement_rep_dtls"; //die;
			$rID6 = sql_insert("fam_civil_case_movement_rep", $field_array_movement_rep_dtls, $data_array_movement_rep_dtls, 1);
		}
		
		if (count($data_array_judgment_dtls_arr) > 0) { // Update Case Movement Representitive dtls
			
			//echo bulk_update_sql_statement("fam_civil_case_movement_rep", "id", $field_array_movement_rep_dtls_update, $data_array_case_mov_rep_dtls_arr, $update_case_mov_rep_dtls_arr, 0), 1; //die;
            $rID16 = execute_query(bulk_update_sql_statement("fam_civil_case_movement_rep", "id", $field_array_movement_rep_dtls_update, $data_array_case_mov_rep_dtls_arr, $update_case_mov_rep_dtls_arr, 0), 1);
        }		
		
        //echo $rID1."**".$rID2." **". $rID3."**".$rID4."**".$rID10;  die;
		//echo $update_id;die;

        if ($db_type == 0) {
            if ($rID) {
                mysql_query("COMMIT");
                echo "1**" . $update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID) {
                oci_commit($con);
                echo "1**" . $update_id;
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
    else if ($operation == 2) 
	{
        $con = connect();
        $field_array = "status_active*is_deleted*updated_by*update_date";
        $data_array = "'2'*'1'*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";
		
		$update_id = str_replace("'", "", $update_id);
		
        $rID = sql_delete("fam_civil_case_mst", $field_array, $data_array, "id", "" . $update_id . "", 1);
		$rID1 = sql_delete("fam_civil_case_complainant", $field_array, $data_array, "mst_id", $update_id, 1);
		$rID2 = sql_delete("fam_civil_case_hostile", $field_array, $data_array, "mst_id", $update_id, 1);
		$rID3 = sql_delete("fam_civil_case_advocate_dtls", $field_array, $data_array, "mst_id", $update_id, 1);
		$rID4 = sql_delete("fam_civil_case_hearing_dtls", $field_array, $data_array, "mst_id", $update_id, 1);
		$rID5 = sql_delete("fam_civil_case_judgment_dtls", $field_array, $data_array, "mst_id", $update_id, 1);
		$rID6 = sql_delete("fam_civil_case_movement_rep", $field_array, $data_array, "mst_id", $update_id, 1);
        
		//echo "10**".$rID."**".$rID1."**". $rID2."**".$rID3."**". $rID4."**".$rID5."**".$rID6;  die;
		
        if ($db_type == 0) {
            if ($rID & $rID1 & $rID2 & $rID3 & $rID4 & $rID5 & $rID6) {
                mysql_query("COMMIT");
                echo "2**" . $update_id;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID & $rID1 & $rID2 & $rID3 & $rID4 & $rID5 & $rID6) {
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



if ($action == "populate_civil_case_details_form_data") 
{
   	//echo $data;die;
    $data_array = sql_select("select id, land_id, file_number, court_name, case_number, case_filing_date, company_representative from fam_civil_case_mst where status_active =1 and is_deleted=0 and id='$data'");
	
	//print_r($data_array);die;
	foreach ($data_array as $row) {
		echo "document.getElementById('update_id').value 						  	= '" . $row[csf("id")] . "';\n";
        echo "document.getElementById('hidden_Land_Id').value 						= '" . $row[csf("land_id")] . "';\n";
        echo "document.getElementById('txt_file_number').value 						= '" . $row[csf("file_number")] . "';\n";
        echo "document.getElementById('txt_court_name').value 						= '" . $row[csf("court_name")] . "';\n";
        echo "document.getElementById('txt_case_number').value 						= '" . $row[csf("case_number")] . "';\n";
		echo "document.getElementById('txt_case_filing_date').value 				= '" . change_date_format($row[csf("case_filing_date")], "dd-mm-yyyy", "-") . "';\n";
		echo "document.getElementById('txt_company_representative').value 			= '" . $row[csf("company_representative")] . "';\n";
		
		
        //=======================Start : Complainant =======================
		$data_array_complainant = sql_select("select id,mst_id, com_name, com_father_name, com_mother_name from fam_civil_case_complainant WHERE mst_id='$data' AND status_active=1 AND is_deleted=0");
		//print_r($data_array_complainant);
		$complainantNamebreak_down = "";
		foreach ($data_array_complainant as $val)
		{
			if ($complainantNamebreak_down != "") $complainantNamebreak_down.="*";
				
			$complainantNamebreak_down.=$val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("com_name")] . "_" . $val[csf("com_father_name")] . "_" . $val[csf("com_mother_name")];
		}
		echo "document.getElementById('txt_complainant').value = '" . $complainantNamebreak_down . "';\n";
		//=======================End : Complainant =======================
		
		//===========================Start 	: Hostile ===============================
		$data_arry_hostile_name = sql_select("select id,mst_id, hos_name, hos_father_name, hos_mother_name  from fam_civil_case_hostile  WHERE mst_id='$data' AND status_active=1 AND is_deleted=0");
		
		//print_r($data_arry_hostile_name);  die;
		
		$hostileNameBreakDown = "";
		foreach($data_arry_hostile_name as $val){
			if($hostileNameBreakDown != ""){
				$hostileNameBreakDown .= "*";
			}
			$hostileNameBreakDown .= $val[csf("id")]."_".$val[csf("mst_id")]."_".$val[csf("hos_name")]."_".$val[csf("hos_father_name")]."_".$val[csf("hos_mother_name")];
		}
		echo "document.getElementById('txt_hostile').value = '" . $hostileNameBreakDown . "';\n";
		//===========================End 	: Hostile ===============================
		
		//===========================Start 	: Advocate Details===============================
		$data_arry_advocate_dtls = sql_select("SELECT  id, mst_id, advo_name, membership_no, contract_no, office_address, email_id, skype_id, move_current  FROM fam_civil_case_advocate_dtls   WHERE mst_id='$data' AND status_active=1 AND is_deleted=0");
		
		//print_r($data_arry_advocate_dtls);
		
		$AdvocateDtlsBreakDown = "";
		foreach($data_arry_advocate_dtls as $val){
			if($AdvocateDtlsBreakDown != ""){
				$AdvocateDtlsBreakDown .= "*";
			}
			$AdvocateDtlsBreakDown .= $val[csf("id")]."_".$val[csf("mst_id")]."_".$val[csf("advo_name")]."_".$val[csf("membership_no")]."_".$val[csf("contract_no")]."_".$val[csf("office_address")]."_".$val[csf("email_id")]."_".$val[csf("skype_id")]."_".$val[csf("move_current")];
		}
		echo "document.getElementById('txt_advocate_details').value = '" . $AdvocateDtlsBreakDown . "';\n";
		//===========================End 	: Advocate Details===============================		
		
		//===========================Start 	: Hearing===============================
		$data_arry_hearing_dtls = sql_select("SELECT  id, mst_id, hearing_date, expenses, remarks  FROM fam_civil_case_hearing_dtls WHERE mst_id='$data' AND status_active=1 AND is_deleted=0");
		
		//print_r($data_arry_hearing_dtls);
		
		$HearingDtlsBreakDown = "";
		$sl = 1;
		foreach($data_arry_hearing_dtls as $val){
			if($HearingDtlsBreakDown != ""){
				$HearingDtlsBreakDown .= "*";
			}
			$HearingDtlsBreakDown .= $val[csf("id")]."_".$val[csf("mst_id")]."_".$val[csf("hearing_date")]."_".$val[csf("expenses")]."_".$val[csf("remarks")];
			$sl++;
		}
		echo "document.getElementById('txt_hearing').value = '" . $HearingDtlsBreakDown . "';\n";
		//===========================End 	: Hearing===============================	
			
		//===========================Start 	: Court Judgment ===============================
		$data_array_judgment = sql_select("SELECT  id, mst_id, judge_name, court_name, judgement, remarks  FROM fam_civil_case_judgment_dtls  WHERE mst_id='$data' AND status_active=1 AND is_deleted=0");
		//print_r($data_array_judgment);
		$judgmentBreakDown = "";
		foreach ($data_array_judgment as $val) {
			if ($judgmentBreakDown != ""){
				$judgmentBreakDown .= "*";
			}
			$judgmentBreakDown.=$val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("judge_name")] . "_" . $val[csf("court_name")] . "_" . $val[csf("judgement")] . "_" . $val[csf("remarks")];
		}
		echo "document.getElementById('txt_court_judgment').value = '" . $judgmentBreakDown . "';\n";
		//===========================End 	: Court Judgment ===============================		
		
			
		//===========================Start 	: Case Mov. Representative  ===============================
		$data_array_case_movement_rep = sql_select("SELECT  id, mst_id, name, cell_phone, email_id, mov_current  FROM fam_civil_case_movement_rep WHERE mst_id='$data' AND status_active=1 AND is_deleted=0");
		//print_r($data_array_case_movement_rep);
		$CaseMovRepBreakDown = "";
		foreach ($data_array_case_movement_rep as $val) {
			if ($CaseMovRepBreakDown != ""){
				$CaseMovRepBreakDown .= "*";
			}
			$CaseMovRepBreakDown.=$val[csf("id")] . "_" . $val[csf("mst_id")] . "_" . $val[csf("name")] . "_" . $val[csf("cell_phone")] . "_" . $val[csf("email_id")] . "_" . $val[csf("mov_current")];
		}
		echo "document.getElementById('txt_case_mov_representative').value = '" . $CaseMovRepBreakDown . "';\n";
		//===========================End 	: Case Mov. Representative  ===============================	
        
		echo "document.getElementById('hidden_deleted_complId').value 						  	= '';\n";
		echo "document.getElementById('txt_hostile_deleteId').value 						  	= '';\n";
		echo "document.getElementById('txt_Advocate_deleteId').value 						  	= '';\n";
		echo "document.getElementById('txt_Hearing_deleteId').value 						  	= '';\n";
		echo "document.getElementById('txt_Judgment_deleteId').value 						  	= '';\n";
		echo "document.getElementById('txt_Representative_deleteId').value 						= '';\n";
		
        echo "set_button_status(1, permission, 'fnc_civil_case_entry',1);\n";
    }
}

