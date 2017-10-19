ko:!admin:!
<div class="k_block">
	<form method="POST" id="form" class="k_input" style="margin-right:auto;margin-left:auto;" enctype="multipart/form-data">
		<input type="hidden" name="fonc" value="new_magasin">
		<input type="text" name="pays" class="k_input" placeholder="kt:!country:!" /><br>
		<input type="text" name="rue" class="k_input" placeholder="kt:!street:!" /><br>
		<input type="text" name="ville" class="k_input" placeholder="kt:!city:!" /><br>


		<input type="submit" class="k_input k_control" value="ok">
	</form>
</div>
	<script>
	$(document).ready(function(){
		var langs = JSON.parse(kons.langs);
		for (var i = 0; i < langs.length; i++) {
			$("#form").prepend('<input type="text" name="name_'+langs[i]+'" class="k_input" placeholder="'+langs[i]+'  kt:!magasinname:!" /><br>')

		}
	});

	</script>
	</body>
	</html>