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


if ($action == "load_drop_down_category") {
    if ($data == 5) {  //Machinery
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "load_drop_down( 'requires/service_schedule_controller', document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+this.value, 'load_drop_down_group', 'group_td'); show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", "", "91");
    } elseif ($data == 6) {  //Equipment
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "load_drop_down( 'requires/service_schedule_controller', document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+this.value, 'load_drop_down_group', 'group_td'); show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", "", "101");
    } elseif ($data == 7) {  //Power Generation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "load_drop_down( 'requires/service_schedule_controller', document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+this.value, 'load_drop_down_group', 'group_td'); show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", "", "111");
    } elseif ($data == 8) {  //Computer
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "load_drop_down( 'requires/service_schedule_controller', document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+this.value, 'load_drop_down_group', 'group_td'); show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", "", "81,82,83,84,85,86,87,88");
    } elseif ($data == 9) {  //Electric Appliance
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "load_drop_down( 'requires/service_schedule_controller', document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+this.value, 'load_drop_down_group', 'group_td'); show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", "", "121");
    } elseif ($data == 10) {  //Transportation
        echo create_drop_down("cbo_category", 170, $asset_category, "", 1, "--- Select ---", $selected, "load_drop_down( 'requires/service_schedule_controller', document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+this.value, 'load_drop_down_group', 'group_td'); show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", "", "131,132,133,134,135,136,137,138,139,140");
    }
    exit();
}

//load_drop_down_group
if ($action == "load_drop_down_group") {
    //using Group by
    //echo $data; die;
    $ex_data = explode("_", $data);

    //echo $ex_data[2]; die;

    if ($ex_data[0] == 0) $company_id1 = ""; 		else $company_id1 = " and company_id='" . $ex_data[0] . "'";
    if ($ex_data[1] == 0) $asset_type1 = ""; 		else $asset_type1 = " and asset_type='" . $ex_data[1] . "'";
    if ($ex_data[2] == 0) $asset_category1 = ""; 	else $asset_category1 = " and asset_category='" . $ex_data[2] . "'";

    if ($db_type == 0) {
        //MySql
        echo create_drop_down("cbo_group", 170, "SELECT GROUP_CONCAT(DISTINCT id ORDER BY id DESC SEPARATOR ',')AS acqiistion_id,asset_group FROM fam_acquisition_mst where status_active = 1 and is_deleted = 0  $company_id1 $asset_type1 $asset_category1  GROUP BY asset_group", "asset_group,asset_group", 1, "-- Select Group --", $selected, "check_service_schedule();get_php_form_data( document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value+'_'+document.getElementById('cbo_group').value, 'populate_asset_id', 'requires/service_schedule_controller');show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value+'_'+document.getElementById('cbo_group').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", 0);
    } else {
        //Oracle
        echo create_drop_down("cbo_group", 170, "SELECT LISTAGG(ID , ',') WITHIN GROUP (ORDER BY ID ) AS acqiistion_id, asset_group FROM fam_acquisition_mst where status_active = 1 and is_deleted = 0 $company_id1 $asset_type1 $asset_category1 group by asset_group order by asset_group", "asset_group,asset_group", 1, "-- Select Group --", $selected, "check_service_schedule();get_php_form_data( document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value+'_'+document.getElementById('cbo_group').value, 'populate_asset_id', 'requires/service_schedule_controller');show_list_view(document.getElementById('cbo_company_name').value+'_'+document.getElementById('cbo_aseet_type').value+'_'+document.getElementById('cbo_category').value+'_'+document.getElementById('cbo_group').value,'show_asset_active_listview','asset_list_view','requires/service_schedule_controller','');", 0);
    }

    exit();
}

if ($action == "check_schedule") {
    //echo $data; die;
    $data = explode("**", $data);

    $sql = "select id,asset_ids, company_id, asset_type, asset_category, asset_group, days_interval, service_year, rate_per_service  from fam_service_schedule_mst  where  is_deleted=0 and status_active=1 and company_id='" . $data[0] . "' and asset_type='" . $data[1] . "' and asset_category='" . $data[2] . "' and asset_group='" . $data[3] . "' and service_year='" . $data[4] . "'";


    $data_array = sql_select($sql, 1);
    if (count($data_array) > 0) {
        echo "1" . "_" . $data_array[0][csf('id')] . "_" . $data_array[0][csf('company_id')];
        ;
    } else {
        echo "0_";
    }
    exit();
}

//----Show List View--------------------
if ($action == "show_asset_active_listview") {
    echo load_html_head_contents("Popup Info", "../../../", 1, 1, $unicode);
    extract($_REQUEST);

    //echo $db_type; //die;
    $ex_data = explode("_", $data);
	
	if ($ex_data[0] == 0) $company_id1 = " and a.company_id=$data"; else $company_id1 = " and a.company_id='" . $ex_data[0] . "'";
	if ($ex_data[1] == 0) $asset_type1 = ""; 						else $asset_type1 = " and a.asset_type='" . $ex_data[1] . "'";
	if ($ex_data[2] == 0) $asset_category1 = ""; 					else $asset_category1 = " and a.asset_category='" . $ex_data[2] . "'";
	if ($ex_data[3] == '')$asset_group1 = ""; 						else $asset_group1 = " and a.asset_group='" . $ex_data[3] . "'";
	
	$company_name = return_library_array("select id,company_name from lib_company", "id", "company_name");
	$arr = array(1 => $company_name, 2 => $asset_type, 3 => $asset_category);
    ?>
    </head>

    <body>
        <div align="center" style="width:100%;" >
            <form name="listview_1"  id="listview_1" autocomplete="off">
                <input type="hidden" id="hidden_asset_ids">
                <table width="807" align="center" class="rpt_table" rules="all" id="tbl_list_search">
                    <thead align="center" class="table_header">
                    <th width="40">SL</th>
                    <th width="180">Company</th>
                    <th width="100">Asset Type</th>
                    <th width="100">Category</th>
                    <th width="100">Group</th>
                    <th width="60">Days Interval</th>
                    <th width="100">Rate Per Service</th>
                    <th width="117">Year</th>
                    </thead>
                    <tbody class="table_body">
                        <?php
                       
                        //FOR ORACLE
                        $sql = sql_select("select a.id, a.company_id, a.asset_type, a.asset_category, a.asset_group, a.days_interval, a.service_year,a.rate_per_service from  fam_service_schedule_mst a where a.status_active = 1 and a.is_deleted = 0 $company_id1 $asset_type1 $asset_category1 $asset_group1 order by a.service_year desc");

                        $i = 0;
                        foreach ($sql as $row) 
						{
                            $i++;
                            if ($i % 2 == 0) $bgcolor = "#E9F3FF";  else $bgcolor = "#FFFFFF";
                            ?>
                            <tr style="cursor: pointer; cursor: hand;" align="center" bgcolor="<? echo $bgcolor; ?>"  id="tr_<? echo $i; ?>" height="20" onClick="get_php_form_data(<?php echo $row[csf("id")]; ?>, 'populate_asset_data', 'requires/service_schedule_controller')">
                                <td width="40"><?php echo $i; ?></td>
                                <td width="180"><?php echo $arr[1][$row[csf("company_id")]]; ?></td>
                                <td width="100"><?php echo $arr[2][$row[csf("asset_type")]]; ?></td>
                                <td width="100"><?php echo $arr[3][$row[csf("asset_category")]]; ?></td>
                                <td width="100"><?php echo $row[csf("asset_group")]; ?></td>
                                <td width="60"><?php echo $row[csf("days_interval")]; ?></td>
                                <td width="100" align="right"><?php echo number_format($row[csf("rate_per_service")],2); ?></td>
                                <td width="100"><?php echo $row[csf("service_year")]; ?></td>
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

            </form>
        </div>
    </body>           
    <script src="../../../includes/functions_bottom.js" type="text/javascript"></script>
    </html>
    <?php
}

//Print
if ($action == "print_routine_service_dtls") {
    //echo "$data";die;
    $data = explode("*", $data);
    $company = $data[0];
    $update_id = $data[1];
    $report_title = $data[2];
    $cbo_starting_year = $data[3];
    //$asset_type_id = $data[4];
    //$category = $data[5];
    //$gorup = $data[6];

    $country_arr = return_library_array("select id, country_name from  lib_country", "id", "country_name");
    $company_array = array();
    $company_name = sql_select("select id, company_name from lib_company");
    foreach ($company_name as $row) {
        $company_array[$row[csf('id')]] = $row[csf('company_name')];
    }

    $companyID_arr = return_library_array("select master_tble_id , image_location  from common_photo_library  where   is_deleted = 0", "master_tble_id", "image_location");
//print_r($companyID_arr); die;
    ?>
    <div style="width:700;">
        <table width="700" cellspacing="0" align="">
            <tr>
                <td colspan="8" align="center" style="font-size:20px"><strong><? echo $company_array[$data[0]]; ?></strong></td>
            </tr>
            <tr class="form_caption">
                <!-- <td><img src='../../<? //echo $companyID_arr[$data[0]];          ?>' height='50' width='50' align="middle" alt="Company Logo"/></td> -->
                <td  colspan="8" align="center" style="font-size:14px">  
                    <?php
                    $nameArray = sql_select("select plot_no,level_no,road_no,block_no,country_id,province,city,zip_code,email,website from lib_company where id=$data[0]");
                    foreach ($nameArray as $result) {
                        ?>
                        <? echo $result[csf('plot_no')]; ?> 
                        ,<? echo $result[csf('level_no')] ?>
                        ,<? echo $result[csf('road_no')]; ?> 
                        ,<? echo $result[csf('block_no')]; ?> 
                        ,<? echo $result[csf('city')]; ?> 
                        ,<? echo $result[csf('zip_code')]; ?> 
                        ,<?php echo $result[csf('province')]; ?> 
                        ,<? echo $country_arr[$result[csf('country_id')]]; ?><br> 
                        Email Address : <? echo $result[csf('email')]; ?>, 
                        Website No: <?
                        echo $result[csf('website')];
                    }
                    ?> 
                </td>  
            </tr>

            <tr>
                <td colspan="8" align="center" style="font-size:16px"><strong><?php echo $report_title . "(" . $cbo_starting_year . ")" ?></strong></td>
            </tr>
            <?
            //echo "select id, company_id, asset_type, asset_category, asset_group, service_year, rate_per_service  from fam_service_schedule_mst where status_active=1 and  is_deleted = 0 and id='$update_id'"; die;

            $company_data_array = sql_select("select id, company_id, asset_type, asset_category, asset_group, service_year, rate_per_service  from fam_service_schedule_mst where status_active=1 and  is_deleted = 0 and id='$update_id'");
            foreach ($company_data_array as $com_row) {
                //echo $com_row[csf('asset_type')]; die;	
                ?>
                <tr>
                    <td width="113"><strong>Asset Type :</strong></td> 	<td width="120px"><? echo $asset_type[$com_row[csf('asset_type')]]; ?></td>
                    <td width="113"><strong>Group:</strong></td> 		<td width="120px"><? echo $com_row[csf('asset_group')]; ?></td>
                    <td width="113"><strong>Category:</strong></td> 	<td width="120px"><? echo $asset_category[$com_row[csf('asset_category')]]; ?></td>
                </tr>
                <tr>
                    <td width="155"><strong>Rate Per Service :</strong></td>	<td  width="100px"><? echo $com_row[csf('rate_per_service')]; ?></td>
                </tr>
                <?
            }
            ?>
        </table>
        <br>
        <div style="width:100%;">
            <table align="" cellspacing="0" width="700"  border="1" rules="all" class="rpt_table" >
                <thead bgcolor="#dddddd" align="center">
                <th width="30">SL</th>
                <th width="150" >Asset No</th>
                <th width="150" >Service No</th>
                <th width="150" >Service Date</th>
                </thead>
                <tbody> 

                    <?
                    $asset_array = return_library_array("select id,asset_no from fam_acquisition_sl_dtls", "id", "asset_no");
                    $service_schedule_arr = sql_select("select id,asset_id,service_no,service_date from fam_service_schedule_dtls where status_active=1 and is_deleted=0 and mst_id ='$update_id'");
                    $i = 0;
                    foreach ($service_schedule_arr as $ssval) {
                        if ($i % 2 == 0)
                            $bgcolor = "#E9F3FF";
                        else
                            $bgcolor = "#FFFFFF";

                        $i++;
                        ?>
                        <tr bgcolor="<? echo $bgcolor; ?>">
                            <td align="center"><? echo $i; ?></td>

                            <td  align="center"><? echo $asset_array[$ssval[csf("asset_id")]]; ?></td>

                            <td align="center"><? echo $ssval[csf("service_no")]; ?></td>
                            <td  align="center"><? echo change_date_format($ssval[csf("service_date")]); ?></td>

                        </tr>
                        <?
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <?
            //echo signature_table(9, $data[0], "1100px");
            ?>
        </div>
    </div> 
    <script type="text/javascript" src="../../../js/jquery.js"></script>
    <script type="text/javascript" src="../../../js/jquerybarcode.js"></script>
    <script>
        /*
         function generateBarcode( valuess ){
             
         var value = valuess;//$("#barcodeValue").val();
         // alert(value)
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
         //$("#barcode_img_id").html('11');
         value = {code:value, rect: false};
             
         $("#barcode_img_id").show().barcode(value, btype, settings);
             
         } 
             
         generateBarcode('<? //echo //$data[3];          ?>');
             
         */
    </script>
    <?
    exit();
}

if ($action == "populate_asset_data") {
    //echo $data; die;
    $data_array = sql_select("select id, asset_ids , company_id , asset_type, asset_category, asset_group, days_interval, service_year, rate_per_service  from fam_service_schedule_mst  where status_active = 1 and is_deleted=0 and id='$data'");
    foreach ($data_array as $row) {
        echo "document.getElementById('cbo_company_name').value = '" . $row[csf("company_id")] . "';\n";
        echo "document.getElementById('hidden_asset_ids').value = '" . $row[csf("asset_ids")] . "';\n";
        echo "document.getElementById('cbo_aseet_type').value = '" . $row[csf("asset_type")] . "';\n";
        echo "load_drop_down('requires/service_schedule_controller','" . $row[csf("asset_type")] . "','load_drop_down_category','category_td' );\n";
        echo "document.getElementById('cbo_category').value = '" . $row[csf("asset_category")] . "';\n";
        echo "load_drop_down('requires/service_schedule_controller','" . $row[csf("company_id")] . '_' . $row[csf("asset_type")] . '_' . $row[csf("asset_category")] . "','load_drop_down_group','cbo_group' );\n";
        echo "document.getElementById('cbo_group').value = '" . $row[csf("asset_group")] . "';\n";
        echo "document.getElementById('txt_days_interval').value = '" . $row[csf("days_interval")] . "';\n";
        echo "document.getElementById('cbo_starting_year').value = '" . $row[csf("service_year")] . "';\n";
        echo "document.getElementById('txt_rate_per_service').value = '" . $row[csf("rate_per_service")] . "';\n";
        echo "document.getElementById('update_id').value = '" . $row[csf("id")] . "';\n";

        echo "set_button_status(1, permission, 'fnc_service_schedule_entry',1);\n";
    }
}

if ($action == "populate_asset_id") {
    $data = explode("_", $data);

    if ($db_type == 0) {
        //MySql	
        $data_array = sql_select("select group_concat(distinct b.id order by b.id asc separator ',') AS asset_id from fam_acquisition_mst a, fam_acquisition_sl_dtls b where  a.id=b.mst_id and a.status_active = 1 and a.is_deleted=0 and  a.company_id=$data[0] and  a.asset_type=$data[1] and  a.asset_category=$data[2] and  a.asset_group='" . $data[3] . "' ");
    } else {
        //Oracle	
        $data_array = sql_select("select LISTAGG(b.id , ',') WITHIN GROUP (ORDER BY b.id) AS asset_id  from fam_acquisition_mst a, fam_acquisition_sl_dtls b  where a.id=b.mst_id and a.status_active = 1 and a.is_deleted=0 and  a.company_id=$data[0] and  a.asset_type=$data[1] and  a.asset_category=$data[2] and  a.asset_group='" . $data[3] . "' ");
    }

    foreach ($data_array as $row) {
        echo "document.getElementById('hidden_asset_ids').value = '" . $row[csf("asset_id")] . "';\n";
    }
}


////==========//SAVE//============//UPDATE//=============//DELETE//==============//Start//==================////===================================
if ($action == "save_update_delete") {
    $process = array(&$_POST);
    extract(check_magic_quote_gpc($process));

// Start: Insert -----------------------------------------------------
    if ($operation == 0) 
	{
        $con = connect();

        if ($db_type == 0) {
            mysql_query("BEGIN");
        }
		
		/*
		echo "select id, asset_ids, company_id, asset_type, asset_category, asset_group, service_year
from fam_service_schedule_mst where status_active=1 and  is_deleted = 0 and asset_ids = $hidden_asset_ids and company_id = $cbo_company_name and asset_type = $cbo_aseet_type and asset_category = $cbo_category and asset_group = $cbo_group and service_year =$cbo_starting_year "; die;
		sql_select("select id, asset_ids, company_id, asset_type, asset_category, asset_group, service_year
from fam_service_schedule_mst where status_active=1 and  is_deleted = 0 and asset_ids = $hidden_asset_ids and company_id = $cbo_company_name and asset_type = $cbo_aseet_type and asset_category = $cbo_category and asset_group = $cbo_group and service_year =$cbo_starting_year ");
		*/
		



        $mst_id = return_next_id("id", "fam_service_schedule_mst", 1);
        $field_array = "id,asset_ids,company_id,asset_type,asset_category,asset_group,days_interval,service_year,rate_per_service,inserted_by,insert_date";
        $data_array = "(" . $mst_id . "," . $hidden_asset_ids . "," . $cbo_company_name . "," . $cbo_aseet_type . "," . $cbo_category . "," . $cbo_group . "," . $txt_days_interval . "," . $cbo_starting_year . "," . $txt_rate_per_service . "," . $_SESSION['logic_erp']['user_id'] . ",'" . $pc_date_time . "')";


        $dtls_id = return_next_id("id", "fam_service_schedule_dtls", 1);
        $field_array_dtls = "id,mst_id,asset_id,service_no,service_date,inserted_by,insert_date";


        if ($cbo_starting_year != '') {
            $yearCheck = str_replace("'", "", $cbo_starting_year);
            $days = '';
            if ($yearCheck % 4 == 0) {
                $days = 366;
            } else {
                $days = 365;
            }

            $year_first_day = "" . $yearCheck . "-01-01";

            $totalServiceNO = 0;
            $k = str_replace("'", "", $txt_days_interval);

            $hidden_asset_ids_arr = explode(",", str_replace("'", "", $hidden_asset_ids));
            for ($sl = 0; $sl < count($hidden_asset_ids_arr); $sl++) {
                $asset_id = $hidden_asset_ids_arr[$sl];
                $j = 0;
                // second loop start 
                for ($i = 1; $i <= $days; $i+=$k) {
                    $j++;
                    $day_add = $i - 1;
                    $start_date = add_date($year_first_day, $day_add);

                    if ($db_type == 0) {
                        $start_date = change_date_format($start_date, 'yyyy-mm-dd');
                    }
                    if ($db_type == 2) {
                        $start_date = change_date_format($start_date, 'yyyy-mm-dd', '-', 1);
                    }

                    if ($data_array_dtls != "")
                        $data_array_dtls.=",";
                    $data_array_dtls .= "(" . $dtls_id . "," . $mst_id . "," . $asset_id . "," . $j . ",'" . $start_date . "'," . $_SESSION['logic_erp']['user_id'] . ",'" . $pc_date_time . "')";

                    $dtls_id = $dtls_id + 1;
                }
                // second loop end 
            }
        }
        //echo "10**insert into fam_service_schedule_mst($field_array) values $data_array"; die;
        //echo "10**insert into fam_service_schedule_dtls($field_array_dtls) values $data_array_dtls"; //die;

        $rID = sql_insert("fam_service_schedule_mst", $field_array, $data_array, 0);
        $rID1 = sql_insert("fam_service_schedule_dtls", $field_array_dtls, $data_array_dtls, 0);

        //echo "10**".$rID ."**".$rID1;die;
		
        $cbo_company_name = str_replace("'", "", $cbo_company_name);
        if ($db_type == 0) {
            if ($rID && $rID1) {
                mysql_query("COMMIT");
                echo "0**" . $mst_id . "**" . $cbo_company_name;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $mst_id;
            }
        }

        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1) {
                oci_commit($con);
                echo "0**" . $mst_id . "**" . $cbo_company_name;
            } else {
                oci_rollback($con);
                echo "10**" . $mst_id;
            }
        }


        disconnect($con);
        die;
    }
// End 	: Insert -----------------------------------------------------
// Start : Update Here------------------------------------------------
    else if ($operation == 1) 
	{
        $con = connect();
        if ($db_type == 0) {
            mysql_query("BEGIN");
        }
		
        $field_array = "asset_ids*company_id*asset_type*asset_category*asset_group*days_interval*service_year*rate_per_service*updated_by*update_date";
        $data_array = "" . $hidden_asset_ids . "*" . $cbo_company_name . "*" . $cbo_aseet_type . "*" . $cbo_category . "*" . $cbo_group . "*" . $txt_days_interval . "*" . $cbo_starting_year . "*" . $txt_rate_per_service . "*" . $_SESSION['logic_erp']['user_id'] . "*'" . $pc_date_time . "'";

        //Update ===fam_service_schedule_dtls
        $dtls_id = return_next_id("id", "fam_service_schedule_dtls", 1);
        $field_array_dtls = "id,mst_id,asset_id,service_no,service_date,inserted_by,insert_date";

        if ($cbo_starting_year != '') {
            $yearCheck = str_replace("'", "", $cbo_starting_year);
            $days = '';
            if ($yearCheck % 4 == 0) {
                $days = 366;
            } else {
                $days = 365;
            }

            $year_first_day = "" . $yearCheck . "-01-01";

            $totalServiceNO = 0;

            $k = str_replace("'", "", $txt_days_interval);

            $hidden_asset_ids_arr = explode(",", str_replace("'", "", $hidden_asset_ids));
            for ($sl = 0; $sl < count($hidden_asset_ids_arr); $sl++) {

                $asset_id = $hidden_asset_ids_arr[$sl];
                $j = 0;
                // second loop start 
                for ($i = 1; $i <= $days; $i+=$k) {
                    $j++;
                    $day_add = $i - 1;
                    $start_date = add_date($year_first_day, $day_add);

                    if ($db_type == 0) {
                        $start_date = change_date_format($start_date, 'yyyy-mm-dd');
                    }
                    if ($db_type == 2) {
                        $start_date = change_date_format($start_date, 'yyyy-mm-dd', '-', 1);
                    }

                    if ($data_array_dtls != "")
                        $data_array_dtls.=",";
                    $data_array_dtls .= "(" . $dtls_id . "," . $update_id . "," . $asset_id . "," . $j . ",'" . $start_date . "'," . $_SESSION['logic_erp']['user_id'] . ",'" . $pc_date_time . "')";

                    $dtls_id = $dtls_id + 1;
                }
                // second loop end 
            }
        }

        if (count($data_array_dtls) > 0) {
            $rID1 = execute_query("delete from fam_service_schedule_dtls where mst_id=$update_id");
            if ($rID1) {
                $rID2 = sql_insert("fam_service_schedule_dtls", $field_array_dtls, $data_array_dtls, 0);
            }
            //echo bulk_update_sql_statement("fam_service_schedule_dtls","id", $field_array_update_dtls, $data_array_serv_dtls, $update_dtls_id_arr, 0);
            //$rID13 = sql_update_a("fam_service_schedule_intervals", $field_array_interval, $data_array_intervals, "id", "" . $update_id . "", 0);
        }
        //echo "10**insert into fam_service_schedule_dtls($field_array_dtls) values $data_array_dtls"; //die;

        $rID = sql_update("fam_service_schedule_mst", $field_array, $data_array, "id", "" . $update_id . "", 0);

        //echo "10**".$rID."**".$rID1."**". $rID2;  die;
		
		$update_id = str_replace("'", "", $update_id);
		
        if ($db_type == 0) {
            if ($rID && $rID1 && $rID2) {
                mysql_query("COMMIT");
                echo "1**" . $update_id . "**" . $cbo_company_name;
            } else {
                mysql_query("ROLLBACK");
                echo "10**" . $update_id;
            }
        }
        if ($db_type == 2 || $db_type == 1) {
            if ($rID && $rID1 && $rID2) {
                oci_commit($con);
                echo "1**" . $update_id . "**" . $cbo_company_name;
            } else {
                oci_rollback($con);
                echo "10**" . $update_id;
            }
        }
        disconnect($con);
        die;
    }
// End : Update Here--------------------------------------------------
// Delete Here----------------------------------------------------------
	else if ($operation == 2)   
	{
		$con = connect();
		
		$field_array="status_active*is_deleted*updated_by*update_date";
		$data_array="'2'*'1'*".$_SESSION['logic_erp']['user_id']."*'".$pc_date_time."'";
		
		
		
		echo "10**"."Siddique sir instruction is : Delete action is not allowed . Date: 23-07-2016"; die;
		
		
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

////======================////===========================//End//==================////===================================
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
