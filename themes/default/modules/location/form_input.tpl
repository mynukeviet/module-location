<!-- BEGIN: main -->
<div id="form-input">
	{FORM_INPUT}
</div>
<!-- END: main -->

<!-- BEGIN: form_input -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<div class="row">
	<!-- BEGIN: country -->
	<div class="col-xs-24 col-sm-12 col-md-12">
		<select class="form-control m-bottom" name="countryid" id="countryid">
			<!-- BEGIN: loop -->
			<option value="{COUNTRY.countryid}" {COUNTRY.selected}>{COUNTRY.title}</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: country -->

	<!-- BEGIN: province -->
	<div class="col-xs-24 col-sm-12 col-md-12">
		<select class="form-control m-bottom" <!-- BEGIN: none_multiple -->name="provinceid"<!-- END: none_multiple --> id="provinceid" <!-- BEGIN: multiple -->name="provinceid[]" multiple="multiple" data-multiple="{MULTPLE}"<!-- END: multiple --> >
			<!-- BEGIN: loop -->
			<option value="{PROVINCE.provinceid}" {PROVINCE.selected}>{PROVINCE.title}</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: province -->

	<!-- BEGIN: district -->
	<div class="col-xs-24 col-sm-12 col-md-12">
		<select class="form-control m-bottom" <!-- BEGIN: none_multiple -->name="districtid"<!-- END: none_multiple --> id="districtid" <!-- BEGIN: multiple -->name="districtid[]" multiple="multiple" data-multiple="{MULTPLE}"<!-- END: multiple --> >
			<!-- BEGIN: loop -->
			<option value="{DISTRICT.districtid}">{DISTRICT.title}</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: district -->
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>
<script>
	$('#countryid, #provinceid, #districtid').select2();

	$('#countryid').change(function(){
		$.ajax({
			method: 'POST',
			url : window.location.href,
			data : 'location_reload=1&countryid=' + $(this).val() + '&provinceid=' + $('#province').val() + '&districtid=' + $('#districtid').val() + '&multiple_province=' + $('#provinceid').data('multiple') + '&multiple_district=' + $('#districtid').data('multiple'),
			success : function( res ){
				$('#form-input').html( res );
			}
		});
	});

	if( $('#districtid').length > 0 ){
		$('#provinceid').change(function(){
			$.ajax({
				method: 'POST',
				url : window.location.href,
				data : 'location_reload=1&countryid=' + $('#countryid').val() + '&provinceid=' + $(this).val() + '&districtid=' + $('#districtid').val() + '&multiple_district=' + $('#districtid').data('multiple'),
				success : function( res ){
					$('#form-input').html( res );
				}
			});
		});
	}
</script>
<!-- END: form_input -->