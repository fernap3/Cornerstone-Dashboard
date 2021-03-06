<?php
require ("header.php");
?>
<script src="sorttable.js"></script>
<div class="dashboard-cont" style="padding-top:110px;">
	<div class="contacts-title">
	<h1 class="pull-left">Customer Service</h1>
	</div>
<div class="dashboard-detail">
	<div class="search-cont">
	<div class="searchcont-detail">
		<div class="search-boxleft">
				<label>Quick Search</label>
				<input id="search" name="frmSearch" type="text" placeholder="Search for a specific job">
		</div>
	</div>
	</div>
<div class="clear"></div>
<?php

require ("connection.php");

$sql = "";

$result_users = mysqli_query($conn, "SELECT * FROM users WHERE department = 'Customer Service'");
$count = 1;
while($row_users = $result_users->fetch_assoc()){
	$user = $row_users['user'];
	if($count == 1){
		$sql = "SELECT * FROM job_ticket WHERE processed_by = '$user'";
	}
	else{
		$sql = $sql . " UNION SELECT * FROM job_ticket WHERE processed_by = '$user'";
	}
	
	$count = $count + 1;
}

$result = null;
if($sql != ""){
	$result = mysqli_query($conn, $sql);
}

if($result != null){
	$job_count = 1;
	while($row = $result->fetch_assoc()){
		if(isset($_POST['assign_to' . $job_count])){
			$user_name = $_SESSION['user'];
			date_default_timezone_set('America/New_York');
			$today = date("Y-m-d G:i:s");
			$a_p = date("A");
			$processed_by = $_POST['assign_to' . $job_count];
			$job_id = $row["job_id"];
			$job = "updated job ticket " . $job_id;
			mysqli_query($conn, "UPDATE job_ticket SET processed_by = '$processed_by' WHERE job_id = '$job_id'");
			$result_processed_by = mysqli_query($conn, "SELECT processed_by FROM job_ticket WHERE job_id = '$job_id'");
			$row_processed_by = $result_processed_by->fetch_assoc();
			$processed_by = $row_processed_by['processed_by'];
			$sql100 = "INSERT INTO timestamp (user,time,job, a_p,processed_by,viewed) VALUES ('$user_name', '$today','$job', '$a_p','$processed_by','no')";
			$result100 = $conn->query($sql100) or die('Error querying database 101.');
		}
		
		$job_count = $job_count + 1;
	}
}


$result = mysqli_query($conn,"SELECT * FROM users WHERE department = 'Customer Service'");

echo " <div class='allcontacts-table'><table border='0' cellspacing='0' cellpadding='0' class='table-bordered allcontacts-table' >"; // start a table tag in the HTML
echo "<tbody>";
echo "<tr valign='top'><th class='allcontacts-title'>All Active Jobs<span class='allcontacts-subtitle'></span></th></tr>";
echo "<tr valign='top'><td colspan='2'><table id = 'customer_s_table' border='0' cellspacing='0' cellpadding='0' class='table-striped main-table contacts-list'><thead><tr valign='top' class='contact-headers'><th class='maintable-thtwo data-header' data-name='job_id' data-index='0'>Job ID</th><th class='maintable-thtwo data-header' data-name='client_name' data-index='1'>Client Name</th><th class='maintable-thtwo data-header' data-name='client_name' data-index='2'>Assign To</th><th class='maintable-thtwo data-header' data-name='project_name' data-index='2'>Job Name</th><th class='maintable-thtwo data-header' data-name='postage' data-index='3'>Postage</th><th class='maintable-thtwo data-header' data-name='invoice_number' data-index='4'>Invoice #</th><th class='maintable-thtwo data-header' data-name='residual_returned' data-index='5'>Residuals Returned</th><th class='maintable-thtwo data-header' data-name='2week_followup' data-index='6'>Follow Up</th><th class='maintable-thtwo data-header' data-name='notes' data-index='7'>Notes</th><th class='maintable-thtwo data-header' data-name='status' data-index='8'>Status</th><th class='maintable-thtwo data-header' data-name='reason' data-index='9'>Reason</th></tr></thead><tbody>";


if ($result->num_rows > 0) {
    // output data of each row
	$job_count = 1;
    while($row = $result->fetch_assoc()) {
		$temp=$row['user'];
		$result1 = mysqli_query($conn,"SELECT * FROM job_ticket WHERE processed_by = '$temp'");
		while($row1 = $result1->fetch_assoc()){
			$foo = $row1['job_id'];
			$result2 = mysqli_query($conn, "SELECT * FROM customer_service WHERE job_id = '$foo'");
			$row2 = $result2->fetch_assoc();
			
			echo "<tr></th><td><a href='edit_cs.php?job_id=$foo'>".$row1["job_id"]."</a></td><td>". $row1["client_name"]. "</td><td>";
			echo "<form method = 'post' action = ''>";
			echo "<select name = 'assign_to" . $job_count . "' style = 'font-size: 10px; width: 80px' onchange = 'this.form.submit()'>";
			$processed_by = $row1['processed_by'];
			$sql1 = "SELECT first_name, last_name, user FROM users WHERE user = '$processed_by'";
			$result = mysqli_query($conn, $sql1);
			if($result->num_rows > 0){
				$row = $result->fetch_assoc();
				echo "<option selected = 'selected' value = '" . $row['user'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
			}
			else{
				echo "<option selected = 'selected'></option>";
			}
				
			$sql = "SELECT first_name, last_name, user FROM users";
			$result = mysqli_query($conn, $sql);
			while($row = $result->fetch_assoc()){
				echo "<option value = '" . $row['user'] . "'>" . $row['first_name'] . ' ' .  $row['last_name'] . "</option>";
					
			}
			echo "</select></form>";
			echo "</td><td>". $row1["project_name"]. "</td><td>".  $row2["postage"]."</td><td>". $row2["invoice_number"]. "</td><td>". $row2["residual_returned"]. "</td><td>". $row2["2week_followup"]."</td><td>". $row2["notes"]. "</td><td>". $row2["status"]. "</td><td>". $row2["reason"]. "</td></tr>";
			$job_count = $job_count + 1;
		}
    }
	echo "</tbody></table></td></tr></tbody></table></div>";
} else {
    echo "0 results";
}

$conn->close();

?>
<div class="allcontacts-breadcrumbs">
	<div class="allcontacts-breadcrumbsleft pull-left page-control">
		<nav>
			<ul class="pagination" id = "pag">
				<li id = 'prev_button' class = 'previous' style = 'display:none;' onclick = "prevPage();">Prev</li>
			</ul>
		</nav>
	</div>
	<div class="items-per-page-cont pull-right">
		<label>Jobs Per Page</label>
		<select class="per-page-val" id = "item_count" onchange = "changeCount()">
			<option value="10">10</option>
			<option value="25">25</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select>
	</div>
</div>
</div>
</div>
<script src="sorttable.js"></script>
<script type="text/javascript" src="jquery-latest.js"></script> 
<script type="text/javascript" src="jquery.tablesorter.js"></script> 
<script>

var subtractValue = 3;
var prevSubValue = 4;

$("#search").keyup(function(){
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#customer_s_table tbody tr"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
               $(this).hide();
            else
               $(this).show();                
        });
    }); 
$(document).ready(function() 
    { 
        $("#customer_s_table").tablesorter(); 
		pageCreator();
    } 
);
function pageCreator(){
	$('table.table-striped.main-table.contacts-list').each(function() {
		var currentPage = 0;
		numPerPage = parseInt(document.getElementById('item_count').value);
		var $table = $(this);
		var $ul = $('ul.pagination');
		$table.bind('repaginate', function() {
			$table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
		});
		$table.trigger('repaginate');
		var numRows = $table.find('tbody tr').length;
		var numPages = Math.ceil(numRows / numPerPage);
		var count = 1;
		for (var page = 0; page < numPages; page++) {
			var i = $('<li id = id' + count + ' class = "current"></li>').text(page + 1).bind('click', {
				newPage: page
			}, function(event) {
				currentPage = event.data['newPage'];
				$table.trigger('repaginate');
				$(this).addClass('clickable').siblings().removeClass('clickable');
			}).appendTo($ul).addClass('current');
			count = count + 1;
		}
		if(numPages > 5){
			$("<li id = 'next_button' class = 'next' onclick = nextPage();>Next</li>").appendTo($ul);
		}
	});
	document.getElementById("id1").className = "current clickable";
	var ul = document.getElementById("pag");
	
	if(ul.childNodes.length > 5){
		for(var i = 6; i < ul.childNodes.length - 1; i++){
			ul.children[i].style.display = "none";
			document.getElementById("next_button").style.display = "inline";
		}
	}
}
function changeCount(){
	numPerPage = parseInt(document.getElementById('item_count').value);
	$('#pag').empty();
	$("<li id = 'prev_button' class = 'previous' style = 'display:none;' onclick = prevPage();>Prev</li>").appendTo("#pag");
	subtractValue = 1;
	prevSubValue = 2;
	pageCreator();
}
function prevPage(){
	document.getElementById("next_button").style.display = "inline";
	var ul = document.getElementById("pag");
	var length1 = ul.childNodes.length - prevSubValue;
	var oldDisplayIndex = 0;
	for(var i = length1; i >= 1; i--){
		if(ul.children[i].style.display == "inline"){
			oldDisplayIndex = i;
			break;
		}
	}
	
	var newDisplayIndex = 0;
	for(var i = oldDisplayIndex; i >= 1; i--){
		newDisplayIndex = i;
		if(ul.children[i].style.display == "none"){
			break;
		}
		else{
			ul.children[i].style.display = "none";
		}
	}
	
	var count = 0;
	var lastIndex = 0;
	for(var i = newDisplayIndex; i >= 1; i--){
		lastIndex = i;
		if(count == 5){
			break;
		}
		else{
			//alert("Hi");
			ul.children[i].style.display = "inline";
			count = count + 1;
		}
	}
	if(lastIndex == 1){
		document.getElementById("prev_button").style.display = "none";
	}
}
function nextPage(){
	document.getElementById("prev_button").style.display = "inline";
	var ul = document.getElementById("pag");
	
	var displayCount = 0;
	var lastIndexCheck = 0;
	for(var i = 1; i < ul.childNodes.length - 1; i++){
		if(ul.children[i].style.display != "none"){
			displayCount++;
		}
		lastIndexCheck = i;
		if(displayCount == 5){
			break;
		}
	}
	if(lastIndexCheck != ul.childNodes.length - 2){
		var oldDisplayIndex = 0;
		for(var i = 1; i < ul.childNodes.length - 1; i++){
			if(ul.children[i].style.display != "none"){
				oldDisplayIndex = i;
				break;
			}
		}
		
		var newDisplayIndex = 0;
		for(var i = oldDisplayIndex; i < ul.childNodes.length - 1; i++){
			if(ul.children[i].style.display == "none"){
				newDisplayIndex = i;
				break;
			}
			else{
				ul.children[i].style.display = "none";
			}
		}
		
		var count = 0;
		var lastIndex = 0;
		for(var i = newDisplayIndex; i < (ul.childNodes.length - subtractValue); i++){
			if(count == 5){
				break;
			}
			else{
				ul.children[i].style.display = "inline";
				count = count + 1;
				lastIndex = i;
			}
			if(i == ul.childNodes.length){
				break;
			}
		}
		if(ul.children[lastIndex + 1].className == "next" || count < 5){
			document.getElementById("next_button").style.display = "none";
		}
	}
}
</script>