<?php
	include("connect.php");
	$db->checkAdminLogin();

	$ctable 	= "module";
	$ctable1 	= "Module";
	$page 		= "user_module";
	$page_title = "Manage ".$ctable1;

	$module_id = $db->clean($_REQUEST['module_id']);
	$user_id = $db->clean($_REQUEST['user_id']);
	///print_r($_REQUEST);
	//exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $page_title . ' | ' .  ADMINTITLE; ?></title>
	<?php include("include/css.php"); ?>
</head>
<body id="page-top">
	<div id="wrapper">
		<!-- Sidebar -->
		<?php include("include/left.php"); ?>
		<!-- End of Sidebar -->

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<?php include('include/header.php'); ?>
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h4 mb-0 text-gray-900"><?php echo $page_title; ?></h1>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="box border-top-info">
								<div class="card">
									<div class="card-body">
										<!-- For Table 1 -->
										<div class="box-header with-border">
											<div class="col-md-12 ">
												<form action="#" onSubmit="return searchByName();">
													<input type="hidden" name="module_id" id="module_id" value="<?=$module_id?>">
													<input type="hidden" name="user_id" id="user_id" value="<?=$user_id?>">
													<div class="row">
														<div class="col-md-3">
															<div class="form-group">
																<input type="text" value="" name="searchName" class="form-control" placeholder="Search Here..." id="searchName" value="">
															</div>
														</div>
														<div class="col-md-3">
															<button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
															<button type="submit" class="btn btn-danger" title="Clear Search Result" value="clear" onClick="clearSearchByName();"><i class="fa fa-times"></i></button>
															<a onClick="window.location.reload()" class="btn btn-warning" title="Reload Page"><i class="fa fa-sync"></i></a>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div class="box-body no-padding">
											<div class="col-md-12 table-responsive">
												<div class="loading-div" style="display:none;">
													<div><img style="width:10%;margin-left:440px;" src="<?php echo SITEURL?>images/loader.svg"></div>
												</div>
												<div id="results"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include("include/footer.php"); ?>
		</div>

	</div>

	<!-- Bootstrap core JavaScript-->
	<?php include("include/js.php"); ?>
	<script type="text/javascript">
	/*Table 1 */
	var searchName="";
	function searchByName(){
		searchName = $("#searchName").val();
		displayRecords(10);
		return false;
	}
	function clearSearchByName(){
		searchName = "";
		$("#searchName").val("");
		displayRecords(10);
	}
	$("#searchName").keyup(function(event){
		if(event.keyCode == 13){
			$("#searchByName").click();
		}
	});
	function loadDataTable(data_url,page=""){
		setTimeout(function(){
			$("#results" ).load( data_url,{"page":page},function(){
				$('#example').DataTable({
					"bPaginate": false,
					"bFilter": false,
					"bInfo": false,
					"bAutoWidth": false, 
					"aoColumns": [
						{ "sWidth": "5%","bSortable": false }, 
						{ "sWidth": "30%" },
						{ "sWidth": "60%" }, 
						{ "sWidth": "5%" }, 
						
					]
				});
				$(".loading-div").fadeOut(500);
				$("#results").fadeIn();
			}); //load initial records
		},1500);
	}
	
	function displayRecords(numRecords) {
		var searchName 	= $("#searchName").val();
		searchName 	    = encodeURIComponent(searchName.trim());
		var module_id 	= $("#module_id").val();
		module_id 	    = encodeURIComponent(module_id.trim());
		var user_id 	= $("#user_id").val();
		user_id 	    = encodeURIComponent(user_id.trim());
		var data_url    = "<?php echo ADMINURL; ?>get_user_module.php?show=" + numRecords + "&searchName=" + searchName+"&module_id="+module_id+"&user_id="+user_id;
		$("#results").html("");
		$(".loading-div").show();
		loadDataTable(data_url);
		
		//executes code below when user click on pagination links
		$("#results").on("click",".paging_simple_numbers a", function (e){
			e.preventDefault();
			var numRecords  = $("#numRecords").val();
			$(".loading-div").show(); //show loading element
			var page = $(this).attr("data-page"); //get page number from link
			loadDataTable(data_url,page);
		});
		$("#results").on( "change", "#numRecords", function (e){
			e.preventDefault();
			var numRecords  = $("#numRecords").val();
			$(".loading-div").show(); //show loading element
			var page = $(this).attr("data-page"); //get page number from link
			loadDataTable(data_url,page);
		});
	}

	// used when user change row limit
	function changeDisplayRowCount(numRecords) {
		displayRecords(numRecords);
	}

	$(document).ready(function() {
		displayRecords(10);
	});

	

	

</script>
</body>

</html>