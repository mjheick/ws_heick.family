$(document).ready(function() {
	$("#search").keyup(function(){
		if ($("#search").val().length > 2) {
			$.ajax({
				url: "search.php",
				dataType: "json",
				data: { "data": $("#search").val() },
				success: function(data, textStatus, xhr) {
					let resultsMessage = 'No Results Found';
					if (data && (data.length > 0)) {
						resultsMessage = '';
						for (let x = 0; x < data.length; x++) {
							resultsMessage += '<div class="row"><div class="col text-center background-black"><a href="tree.php?id=' + data[x].id + '">' + data[x].fullname + '</a></div></div>';
						}
					}
					$("#search-results").html('<div class="col text-center">' + resultsMessage + '</div>');
				}
			});
		} else {
			$("#search-results").html('');
		}
	});
});