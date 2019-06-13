<div class="container">
	<div class="row">
		<div id="body_left" class="col-sm-12 col-md-9">
			{$main_body}
		</div>
		<div id="body_right" class="col-sm-12 col-md-3">
			<div class="body_right_content">
				<h3>{$l_promo}</h3>
				<!-- BEGINMODULE ce_core -->
				mode = product_list
				items = random
				display = grid
				limit = 1
				tag = HOT
				<!-- ENDMODULE -->
			</div>

			<div class="body_right_content">
				<h3>{$l_search}</h3>
				<form method="get" action="shop_search.php">
					<input type="hidden" name="cmd" value="search" />
					<p><input type="text" name="query" />
					<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" style="padding:0"></span></button></p>
				</form>
			</div>

			<div class="body_right_content">
			{$module_box_L1}
			</div>

			<div class="body_right_content">
			{$module_box_L2}
			</div>

			<div class="body_right_content">
			{$module_box_R1}
			</div>

			<div class="body_right_content">
			{$module_box_R2}
			</div>
		</div>
	</div>
</div>