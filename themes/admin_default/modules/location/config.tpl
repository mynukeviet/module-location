<!-- BEGIN: main -->
<form action="" method="post" class="form-horizontal">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-4 text-right"><strong>{LANG.config_allow_type}</strong></label>
				<div class="col-sm-20">
					<label><input type="checkbox" name="allow_type" value="1" {ck_allow_type} />{LANG.config_allow_type_note}</label>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" class="btn btn-primary" value="{LANG.save}" name="savesetting" />
	</div>
</form>
<!-- BEGIN: main -->