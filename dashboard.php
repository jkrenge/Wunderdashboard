<?php

	// functions
	
	function shortString ($s, $l) {
		if (strlen($s) > $l) return substr($s, 0, ($l-3))."...";
		else return $s;
	}
	
	// get credentials
	
	if (empty($_POST['email'])) {
		
		header("Location: index.php");
		exit;
		
	}
	
	$wlUser = $_POST['email'];
	$wlPass = $_POST['pass'];

	// Require the API class
	require_once('api/api.class.php');
	require_once('api/api.files.class.php');
	
	// construct the Wunderlist class using user Wunderlist e-mailaddress and password	
	try
	{
		$wunderlist = new Wunderlist($wlUser, $wlPass);
	}
	catch(Exception $e)
	{
/* 		die( $e->getMessage() ); */
		
		header("Location: index.php?f=y");
		exit;

	}
	
	// get and save lists
	try
	{
		// get available lists
		$lists = $wunderlist->getLists();
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		// $e->getCode() contains the error code	
	}
	$listedTasks = array();
	foreach ($lists as $list) { 
		$temp_dayArray = array("overdue" => array());
		$temp_day = new DateTime("now");
		for ($i = 0; $i < 8; $i++) {
			$temp_dayArray[$temp_day->format('Y-m-d')] = array();
			$temp_day->add(date_interval_create_from_date_string('1 day'));
		}
		
		$listedTasks[$list['title']] = $temp_dayArray;
	}
	
	// get tasks
	try
	{
		// get available tasks
		// parameter: include completed tasks? true / false
		$tasks = $wunderlist->getTasks(false);
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
		// $e->getCode() contains the error code	
	}
	
	// filter tasks to relevant ones
	
	foreach ($tasks as $task) {
	
		if ($task['title'] !== null && $task['due_date'] !== null) {
	
			$dueDate = new DateTime($task['due_date']);
			$today = new DateTime("now");
			$diff = $dueDate->diff($today)->format("%a");
			
			if($diff < 7) {
			
				$cleanTask = array(
					'title' => $task['title'],
					'dueDate' => $task['due_date'],
					'list' => $lists[$task['list_id']]['title']
				);
				
				if ($dueDate < $today) $dayIndex = 'overdue';
				else $dayIndex = $cleanTask['dueDate'];
				
				array_push($listedTasks[$cleanTask['list']][$dayIndex], $cleanTask);
				
			}
		
		}
		
	}
	
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wunderdashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <link href="css/fixed-footer.css" rel="stylesheet">
    
  </head>

  <body>

<table class="table table-hover table-bordered">
 <thead>
 	<tr>
<?php

	$days = array("overdue");
	$formattedDays = array("overdue");
	$tDay = new DateTime("now");
	for ($i = 0; $i < 8; $i++) {
		array_push($days, $tDay->format('Y-m-d'));
		array_push($formattedDays, $tDay->format('l')."<br />".$tDay->format('d.m.Y'));
		$tDay->add(date_interval_create_from_date_string('1 day'));
	}
	
	echo "<td></td>";
	$counter = 0;
	foreach ($formattedDays as $day) {
		$tdClass = "";
		if ($counter > 5) $tdClass = " class=\"visible-lg\"";
		
		echo "<td".$tdClass.">".$day."</td>";
		
		$counter++;
	}
	
?>
 	</tr>
 </thead>
 <tbody>
<?php
	
	foreach ($listedTasks as $listName => $list) {
		echo "<tr>";
		echo "<td>".shortString ($listName, 15)."</td>";
		
		$counter = 0;
		foreach ($days as $day) {
			$tdClass = "";
			if ($counter > 5) $tdClass = " class=\"visible-lg\"";
		
			echo "<td".$tdClass.">";
			$tasksOfListAndDay = $list[$day];
			
			foreach ($tasksOfListAndDay as $task) {
				echo "<span class=\"label label-info\" data-toggle=\"tooltip\" title=\"".$task['title']."\">".shortString ($task['title'], 25)."</span><br />";
				
			}
			
			echo "</td>";
			$counter++;
		}
		
		echo "</tr>";
	}
	
?>
 </tbody>
</table>

<?php include("footer.php"); ?>

<script src="js/jquery-2.0.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $("[data-toggle='tooltip']").tooltip({placement: 'bottom auto'});
    });
</script>

  </body>
</html>