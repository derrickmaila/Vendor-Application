function createsuggest(jsonurl,element,addItem) {

	$(element).autocomplete({
		source:function(request,response) {

			$.ajax({
				url:jsonurl,
				dataType:"json",
				data: {
					featureClass:"P",
					style:"full",
					maxRows:12,
					q:request.term
				},
				success:function(data) {
					var responsedata = [];
					$(data).each(function(index,val) {
						
						responsedata.push({
							label:val.name,
							value:val.name,
							control:val.control
						});
					});
							
					response(responsedata);
				}		

			});
		},
		minLength:2,
		select:function(event,ui) {
			addItem(ui);
		},
		open:function() {
			$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
		},
		close:function() {
			$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
		}
		

	});
}