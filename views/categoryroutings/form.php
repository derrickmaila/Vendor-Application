
<div id="system">
<form name="routing" id="routing">
	<table class="nano-form" width="100%">
		<tr>
			<td>* Email</td>
			<td>
				<input type="text" value="<?=$email?>" onblur="validate(this.value,'email-error');" id="email" name="email" />
				<span id="email-error"></span>
			</td>
		</tr>
		<tr>
			<td>* Description</td>
			<td>
				<input type="text" value="<?=$description?>" onblur="validate(this.value,'description-error');"  id="description" name="description"/>
				<span id="description-error"></span>
			</td>
		</tr>
		<tr>
			<td>* ID</td>
			<td>
				<input type="text" value="<?=$id?>" onblur="validate(this.value,'id-error');"  id="id" name="id"/>
				<span id="id-error"></span>
			</td>
		</tr>
		<tr>
			<td>* Name</td>
			<td>
				<input type="text" value="<?=$name?>" onblur="validate(this.value,'name-error');"  id="name" name="name" />
				<span id="name-error"></span>
			</td>
		</tr>
	</table>
	</form>
</div>

<script type="text/javascript">
	function validate(val,obj) {
		var id = "#"+obj;
		if(val == "") {

			$(id).parent().parent().css("background","#FFBABA");
			$(id).parent().parent().css("border","1px solid red");
			$(id).parent().parent().addClass("ui-corner-all");
			$(id).css("color","red");
			$(id).html("* This is a required field");
			return false;
		} else {
			$(id).parent().parent().removeAttr("style");
			$(id).removeAttr("style");
			$(id).html("");
			return true;
		}
	}
</script>
