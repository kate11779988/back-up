<?php
	include("connect.php");
	$ctable 	= "module";
	$ctable1 	= "Module";
	$page 		= "user_module";

	//for sidebar active menu
	$ctable_where = '';
	if(isset($_REQUEST['searchName']) && $_REQUEST['searchName']!=""){
		$ctable_where .= " (
					name like '%".$_REQUEST['searchName']."%'
					
		) AND ";
	}
	//print_r($_REQUEST);
	//exit;

	$ctable_where .= "isDelete=0 AND course_id=".$_REQUEST['module_id']."";
	$item_per_page =  ($_REQUEST["show"] <> "" && is_numeric($_REQUEST["show"]) ) ? intval($_REQUEST["show"]) : 10;

	if(isset($_REQUEST["page"]) && $_REQUEST["page"]!=""){
		$page_number = filter_var($_REQUEST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
		if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
	}else{
		$page_number = 1; //if there's no page number, set it to 1
	}

	$get_total_rows = $db->getTotalRecord($ctable,$ctable_where); //hold total records in variable

	//break records into pages
	$total_pages   = ceil($get_total_rows/$item_per_page);

	//get starting position to fetch the records
	$page_position = (($page_number-1) * $item_per_page);
	$pagiArr       = array($item_per_page, $page_number, $get_total_rows, $total_pages);
	$ctable_r      = $db->getData($ctable,"*",$ctable_where,"id DESC limit $page_position, $item_per_page");
?>
<form action="" name="frm" id="frm" method="post">
    <input type="hidden" name="hdnmode" id="hdnmode" value="">
    <input type="hidden" name="hdndb" id="hdndb" value="<?php echo $ctable; ?>">
		<?php
			//$db->getDeleteButton();
			//$db->getAddButton($page, $ctable1);
		?>
		<table id="example" class="table table-striped table-bordered table-colored">
			<thead>
				<tr>
					<th><input type="checkbox" name="chkall" id="chkall" onclick="javascript:check_all();"></th>
					<th>No.</th>
					<th>Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(@mysqli_num_rows($ctable_r)>0){
					$count = 0;
					while($ctable_d = @mysqli_fetch_assoc($ctable_r)){
					$count++;
					?>
					<tr>
						<td><input type="checkbox" name="chkid[]" value="<?php echo $ctable_d['id']; ?>"></td>
						<td><?php echo $count+$page_position; ?></td>
						<td><?php echo $ctable_d['name']; ?></td>
						
						
						<td>
							<a style="color: #36b9cc;" href="<?php echo ADMINURL; ?>Manage-User-Lesson/<?php echo $ctable_d['id']; ?>/<?php echo $_REQUEST['user_id']; ?>" title="Lesson"><i class="fa fa-list"></i></a>
	                		
    					</td>
					</tr>
					<?php
					}
				}
				?>
			</tbody>
		</table>
		<?php 
			//$db->getDeleteButton();
			//$db->getAddButton($page, $ctable1);
			$db->getTablePaginationBlock($pagiArr);			
		?>
</form>