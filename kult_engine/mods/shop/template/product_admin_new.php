ko:!admin:!
<div class="k_block">
	<form method="POST" id="form" class="k_input" style="margin-right:auto;margin-left:auto;" enctype="multipart/form-data">
		<input type="hidden" name="fonc" value="new_product">
		<input type="text" name="price" class="k_input" placeholder="kt:!price:!" /><br>
		<input type="text" name="tags[]" multiple class="k_input" placeholder="kt:!tags:!" /><br>
		<input type="text" name="reduction" class="k_input" placeholder="kt:!reduction:!" /><br>
  		<input name="pic[]" type="file" multiple placeholder="kt:!nppic:!" class="k_input" /><br>
		<input type="submit" class="k_input k_control" value="ok">
	</form>
</div>
	<script>
	$(document).ready(function(){
		var langs = JSON.parse(kons.langs);
		for (var i = 0; i < langs.length; i++) {
			$("#form").prepend('<input type="text" name="description_'+langs[i]+'" class="k_input" placeholder="'+langs[i]+'  kt:!description:!" /><br>')
			$("#form").prepend('<input type="text" name="name_'+langs[i]+'" class="k_input" placeholder="'+langs[i]+'  kt:!productname:!" /><br>')

		}
	});

	</script>
	</body>
	</html>