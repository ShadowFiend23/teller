$(function(){

	$(".navlink").removeClass(".active");
    var path = window.location.pathname;
    var page = path.split("/"). pop();
    page = page.split(".")[0];
	let initial;

	function ajaxError(jqXHR, exception){
        if (jqXHR.status === 0) {
            Swal.fire('Failed', "No Internet Connection.", 'error');
        } else if (jqXHR.status == 404) {
            Swal.fire('Failed', "Server Error. Try Again later", 'error');
        } else if (jqXHR.status == 500) {
            Swal.fire('Failed', "Server Error. Try Again later", 'error');
        } else if (exception === 'parsererror') {
            Swal.fire('Failed', "Server Error. Try Again later", 'error');
        } else if (exception === 'timeout') {
            Swal.fire('Failed', "Very Slow Connection. Try Again Later.", 'error');
        } else if (exception === 'abort') {
            Swal.fire('Failed', "Connection Aborted.", 'error');
        } else {
            Swal.fire('Failed', 'Uncaught Error.\n' + jqXHR.responseText, 'error');
        }
    }

	function updateEndTime() {
		initial = window.setTimeout( 
		function() {
			$.get("ajax/updateEndTime.php", function(data){


			});
		}, 1800000);
	}

	if(page === "index" || page === ""){
		setInterval(() =>{
			$.get("ajax/tellerStatus.php", function(data){
				$("#tellerStatus").html(data);
			  });
		}, 5000);
	} else if(page === "list"){
		
		$('#tellerListTable').DataTable({
			"lengthChange": false,
			"pageLength" : 5,
			"language": {
				"emptyTable": "No Data Found"
			}
		});
	}else if(page === "user"){
		
		updateEndTime();

	}

	document.onkeydown = function(e) {
		// left key
		if(e.which === 39){
			$.get("ajax/next.php", function(data){
				let response = $.parseJSON(data);

				if(response.result){
					$("#userCurServing").html(response.serving);
					window.clearTimeout(initial);
					updateEndTime();
				}else{
					Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
				}

			});
		}

	};
	$("#nextServing").on("click",function(){
		$.get("ajax/next.php", function(data){
			let response = $.parseJSON(data);

			if(response.result){
				$("#userCurServing").html(response.serving);
				window.clearTimeout(initial);
				updateEndTime();
			}else{
				Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
			}

		});
	})

	$("#stopServing").on("click",function(){
		window.clearTimeout(initial);
		$.get("ajax/stopTime.php", function(data){
			let response = $.parseJSON(data);

			if(response.result){
				Swal.fire({
					title: 'Success',
					html: response.msg,
					timer: 3000,
					type:"success",
					showConfirmButton: false,
					allowOutsideClick: false
				})
			}else{
				Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
			}

		});
	});
	
	$("#logoutBtn").on("click",function(){
        window.location.href = "http://teller.occcicoop.com/logout.php"
    })


	$("#addTellerForm").on('submit',function(e){
		e.preventDefault();
		let frmData = new FormData($(this)[0]);
		$.ajax({
			url:"ajax/saveUser.php",
			type:"POST",
			data: frmData,
			success:function(data){
				let response = $.parseJSON(data);
				if(response.result){
					Swal.fire({
						title: 'Success',
						html: response.msg,
						timer: 3000,
						type:"success",
						showConfirmButton: false,
						allowOutsideClick: false
					}).then(function() {
						$("#addTellerForm")[0].reset();
					});
				}else{
					Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
				}
			},
			cache: false,
			contentType: false,
			processData: false,
			error: function (jqXHR, exception){
                ajaxError(jqXHR, exception)
            }
		})
	})

	$("#userSignOn").click(function(){
		$.get("ajax/userSignOn.php", function(data){
			let response = $.parseJSON(data);

			if(response.result){
				Swal.fire({
					title: 'Success',
					html: response.msg,
					timer: 3000,
					type:"success",
					showConfirmButton: false,
					allowOutsideClick: false
				}).then(function() {
					$("#userSignOn").prop("disabled","disabled");
					$("#stopServing,#nextServing").prop("disabled",false);
				});
			}else{
				Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
			}
		});
	})

	$("#bAdminSignOn").on("click",function(){
		
		$.get("ajax/bAdminSignOn.php", function(data){
			let response = $.parseJSON(data);

			if(response.result){
				$("#bankingDate").html(response.bankingDate);
				Swal.fire({
					title: 'Success',
					html: response.msg,
					timer: 3000,
					type:"success",
					showConfirmButton: false,
					allowOutsideClick: false
				}).then(function() {
					$("#bAdminSignOn").prop("disabled","disabled");
				});
			}else{
				Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
			}
		});
	});

	$(".toPrint").on("click",function(){
		let tranType = $(this).data("type");

		$.ajax({
			url:"ajax/print.php",
			type:"POST",
			data: {tranType : tranType},
			success:function(data){
				let response = $.parseJSON(data);

				if(response.result){
					$("#"+response.type).html(response.serving);
				}else{
					Swal.fire('Failed', "<h6>"+ response.msg +"</h6>", 'error');
				}
			}
		})
	})
})