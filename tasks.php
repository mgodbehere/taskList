<!doctype html>
<html>
<head>
	<title>Task List</title>
	<!-- Bootstrap & Icons links -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kalam">
	<!-- Jquery java file  -->
	<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
	<?php
	// get list of all tasks
	$file = "tasks.ini";
	$allTasks = parse_ini_file($file, true);
	$display_lists = false; // holds the tasks for displaying
	$viewTask = false; // holds the task modals
	
	// if task list is empty then we display the no tasks created
	if(empty($allTasks))
	{
		$display_lists = "<div class='d-flex flex-row'>The are no task lists</div>";
	}
	// display each task title
	else
	{
		$id = 0; // used to give each viewModal unique id
		foreach($allTasks as $key => $task)
		{
			$id++;
			$noteTask = false; // holds the task notes
			$noteList = false; // holds the 5 tasks for the page view
			if(isset($task['note']))
			{
				foreach($task['note'] as $keyNote => $item)
				{
					//$noteTask = $noteTask . "<div class='d-flex flex-row'><button type='button' class='btn btn-danger' onmouseup='$(this).parent().remove(), deleteTask(`$keyNote`, `$key`)'><i class='bi bi-trash'></i></button><button type='button' class='btn btn-success' onmouseup='editTask(`$keyNote`, `$key`)'><i class='bi bi-pencil'></i></button>$item</div>";
					// alternate way of displaying using input-groups
					$noteTask = $noteTask . "<div class='d-flex flex-row input-group my-1'><input id='note$key$keyNote' style='background-color:transparent;' type='text' class='form-control' value='$item' disabled><button type='button' class='btn btn-success visually-hidden' id='editConfirm$key$keyNote' onmouseup='editTask(`$keyNote`, `$key`)'><i class='bi bi-check'></i></button><button type='button' class='btn btn-success' id='edit$key$keyNote' onmouseup='showEditBtn(`$keyNote`, `$key`)'><i class='bi bi-pencil'></i></button><button type='button' class='btn btn-danger' onmouseup='$(this).parent().remove(), deleteTask(`$keyNote`, `$key`)'><i class='bi bi-trash'></i></button></div>";
					if($keyNote < 4)
					{
						$noteList = $noteList . "<li class='list-group-item' style='background-color:transparent; color:#d6204e'>$item</li>";
					}
					if($keyNote == 5)
					{
						$noteList = $noteList . "<li class='list-group-item' style='background-color:transparent; color:#d6204e'>...</li>";
					}
				}
			}
			$display_lists = "$display_lists<div class='col-3 p-3 m-3 border rounded justify-content-center' style='height: 286px; font-family: Kalam; background-color:#F8F1AE; color:#d6204e'><div class='d-flex flex-row border-bottom pb-2'><div class='col-10 align-self-center'><span class='h4'>" . $task['title'] . "</span></div><div class='col-2 d-flex justify-content-center'><button type='button' class='btn btn-outline-dark btn-sm' data-bs-toggle='modal' data-bs-target='#viewTask$id'><i class='bi bi-pencil'></i></button></div></div><div class='d-flex flex-row pt-2'><ul id='pageListGroup$key' class='list-group list-group-flush'>$noteList</ul></div></div>";
			$viewTask = $viewTask . "<div class='modal fade' id='viewTask$id' tabindex'-1'>
										<div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
											<div class='modal-content' style='background-color:#F8F1AE; border-color: black'>
												<div class='modal-header' style='font-family: Kalam; border-color: #d6204e'>
													<h1 class='modal-title fs-5' id='viewTaskTitle' style='color:#d6204e'>".$task['title']."</h1>
													<button type='button' class='btn-close' data-bs-dismiss='modal'></button>
												</div>
												<div class='modal-body' style='font-family: Kalam;' id='viewTaskBody$id'>
													<div class='d-flex flex-column'>
														$noteTask
													</div>
												</div>
												<div class='modal-footer' style='border-color: #d6204e'>
													<button id='addBtn$id' type='button' class='btn btn-outline-dark' onclick='addTask(viewTaskBody$id, `$key`, `addBtn$id`)'>Add New Line</button>
													<button type='button' class='btn btn-outline-dark' data-bs-dismiss='modal'>Close</button>
												</div>
											</div>
										</div>
									</div>";
			
		}
	}
	?>
	<script>
	tempID = 99; // used in the createTempModal function when creating a unique id
	function createTempModal(title, taskId)
	{
		tempID++;
		modal =	"<div class='modal fade' id='viewTaskT"+ tempID +"' tabindex'-1'>"+
					"<div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>"+
						"<div class='modal-content' style='background-color:#F8F1AE; border-color: black'>"+
							"<div class='modal-header' style='font-family: Kalam; border-color: #d6204e'>"+
								"<h1 class='modal-title fs-5' id='viewTaskTitle' style='color:#d6204e'>"+ title +"</h1>"+
								"<button type='button' class='btn-close' data-bs-dismiss='modal'></button>"+
							"</div>"+
							"<div class='modal-body' style='font-family: Kalam;' id='viewTaskBodyT"+ tempID +"'>"+
								"<div class='d-flex flex-column'>"+
									""+
								"</div>"+
							"</div>"+
							"<div class='modal-footer' style='border-color: #d6204e'>"+
								"<button id='addBtnT"+ tempID +"' type='button' class='btn btn-outline-dark' onclick='addTask(viewTaskBodyT" + tempID + ", `" + taskId + "`, `addBtnT"+ tempID +"`)'>Add New Line</button>"+
								"<button type='button' class='btn btn-outline-dark' data-bs-dismiss='modal'>Close</button>"+
							"</div>"+
						"</div>"+
					"</div>"+
				"</div>";
				
		$("#modalDiv").append(modal); // appends the temp modal to the division which holds all the modals
	}
	function addPage()
	{
		// appends the noteContainer division with a temp new note with title input box
		$("#addPageBtn").prop("disabled", true);
		$("#noteContainer > #addPageBtnDiv").before("<div class='col-3 p-3 m-3 border rounded justify-content-center' style='height: 286px; font-family: Kalam; background-color:#F8F1AE; color:#d6204e'><div id='newPage' class='d-flex flex-row border-bottom pb-2'><div id='titleInput' class='col-10 align-self-center input-group my-1'><input type='text' class='form-control' placeholder='Enter Note Title'><button type='button' class='btn btn-success' onmouseup='savePage($(this).parent().find(`input`).val())'><i class='bi bi-check'></i></button><button type='button' class='btn btn-danger' onmouseup='$(this).parent().parent().parent().remove(); $(`#addPageBtn`).prop(`disabled`, false)'><i class='bi bi-trash'></i></button></div></div><div class='d-flex flex-row pt-2'><ul id='pageListGroupT' class='list-group list-group-flush'></ul></div></div>");
	}
	
	function savePage(title)
	{
		// saves the blank page to the ini file
		// amost identical to saveTask consider condesing into 1 function
		fileName = "<?php echo($file); ?>"; // grabs the filename from the php var
		if(title == "")
		{
			alert("Title can't be empty");
		}
		else
		{
			console.log(title);
			$.ajax({
				url:"taskUpdate.php",
				type: "post",
				dataType: "json",
				data:{title:title, filename:fileName, mode:4},
				success:function(data){
					console.log(data['noteID']);
					pageId = data['noteID'];
					title = data['title'];
					$("#titleInput").remove(); // removing the input div before adding it as heading
					createTempModal(title, pageId); // creates a modal for displaying with temp ID which is normally done on page refresh
					$("#newPage").append("<div class='col-10 align-self-center'><span class='h4'>"+ title + "</span></div><div class='col-2 d-flex justify-content-center'><button type='button' class='btn btn-outline-dark btn-sm' data-bs-toggle='modal' data-bs-target='#viewTaskT"+ tempID +"'><i class='bi bi-pencil'></i></button></div>");
					$("#newPage").removeAttr("id"); // removing the id from the new title row so we don't use the same id more than once
					$("#pageListGroupT").attr("id", "pageListGroup"+data['noteID']); // update the new page list id so when a new note is entered it updates it on the overview
					$("#addPageBtn").prop("disabled", false); // enabling the add page button
					},
				}
			);
		}
	}
	
	function addTask(divID, noteID, btnID)
	{
		inputDivID = divID['id'];
		$("#"+btnID).prop("disabled", true);
		$(divID).append("<div id='"+inputDivID+"inputRow' class='d-flex flex-row input-group my-1'><input type='text' class='form-control' placeholder='Enter New Task'><button type='button' class='btn btn-success' onmouseup='saveTask(`"+inputDivID+"`,`"+noteID+"`, $(this).parent().find(`input`).val(), `"+btnID+"`)'><i class='bi bi-check'></i></button><button type='button' class='btn btn-danger' onmouseup='$(this).parent().remove(); $(`#"+btnID+"`).prop(`disabled`, false)'><i class='bi bi-trash'></i></button></div>");
	}
	
	function saveTask(rowID, id, note, btnID)
	{
		fileName = "<?php echo($file); ?>"; // grabs the filename from the php var
		noteID = id; // gets the id from the hidden field from the modal title
		console.log("rowID "+rowID);
		console.log("id "+id);
		console.log("note "+note);
		console.log("btnID "+btnID);
		if(note == "")
		{
			alert("Note can't be empty");
		}
		else
		{
			$.ajax({
				url:"taskUpdate.php",
				type: "post",
				data:{filename:fileName, note:note, id:id, mode:1},
				success:function(keyNote){
					$("#"+rowID+"inputRow").remove();
					$("#"+rowID).append("<div class='d-flex flex-row input-group my-1'><input id='note"+id+keyNote+"' style='background-color:transparent;' type='text' class='form-control' value='"+note+"' disabled><button type='button' class='btn btn-success visually-hidden' id='editConfirm"+id+keyNote+"' onmouseup='editTask(`"+keyNote+"`, `"+id+"`)'><i class='bi bi-check'></i></button><button type='button' class='btn btn-success' id='edit"+id+keyNote+"' onmouseup='showEditBtn(`"+keyNote+"`, `"+id+"`)'><i class='bi bi-pencil'></i></button><button type='button' class='btn btn-danger' onmouseup='$(this).parent().remove(), deleteTask(`"+keyNote+"`, `"+id+"`)'><i class='bi bi-trash'></i></button></div>");
					$("#"+btnID).prop("disabled", false);
					console.log("len "+$("#pageListGroup"+id+"> li").length);
					if($("#pageListGroup"+id+"> li").length < 4)
					{
						$("#pageListGroup"+id).append("<li class='list-group-item' style='background-color:transparent; color:#d6204e'>"+note+"</li>");
					}
					if($("#pageListGroup"+id+"> li").length == 4)
					{
						$("#pageListGroup"+id).append("<li class='list-group-item' style='background-color:transparent; color:#d6204e'>...</li>");
					}
					},
				}
			);
		}
	}
	
	function deleteTask(noteID, id)
	{
		fileName = "<?php echo($file); ?>"; // grabs the filename from the php var
		$.ajax({
			url:"taskUpdate.php",
			type: "post",
			data:{filename:fileName, noteKey:noteID, id:id, mode:2},
			success:function(data){
				// nothing happens here, but a notification to the user here would be good
				},
			}
		);
	}
	
	function showEditBtn(keyNote, id)
	{
		// change the edit button into the confirm edit by hiding and showing, also enable the input and change background for repersentation
		$('#edit'+id+keyNote).addClass('visually-hidden');
		$('#editConfirm'+id+keyNote).removeClass('visually-hidden');
		$('#note'+id+keyNote).prop('disabled', false);
		$('#note'+id+keyNote).css({'background-color': 'white'});
	}
	
	function editTask(keyNote, id)
	{
		// undo the showEditBtn function and reverse everything back to default
		$('#note'+id+keyNote).prop('disabled', true);
		$('#note'+id+keyNote).css({'background-color': 'transparent'});
		$('#edit'+id+keyNote).removeClass('visually-hidden');
		$('#editConfirm'+id+keyNote).addClass('visually-hidden');
		
		// call the ajax script and update the note in the ini file
		fileName = "<?php echo($file); ?>"; // grabs the filename from the php var
		noteText = $('#note'+id+keyNote).val();
		$.ajax({
			url:"taskUpdate.php",
			type: "post",
			data:{filename:fileName, noteKey:keyNote, noteText:noteText, id:id, mode:3},
			success:function(data){
				// we don't do anything here but wanted to, we could
				},
			}
		);
	}
	</script>
</head>
<body>
	<div id="noteContainer" class="m-auto p-3 row d-flex justify-content-center">
		<div class='d-flex justify-content-center'><span class='h3' style='font-family: Kalam; color:#d6204e'>INI Notepad</span></div>
		<?php echo($display_lists); ?>
		<div id='addPageBtnDiv' class='col-12 p-3 m-3 d-flex justify-content-center'><button id='addPageBtn' type='button' class='btn btn-outline-dark' onclick='addPage()'>Create a new Note</button></div>
	</div>
<!-- This division holds all the modal windows that are hidden from view until called on -->
<div id='modalDiv'>
	<?php echo($viewTask); ?>
</div>

</body>
<!-- Bootstrap for styling -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</html>