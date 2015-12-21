<!-- BEGIN: main -->
<input type="hidden" id="allow_country" value="{ALLOW_COUNTRY}" />
<input type="hidden" id="allow_province" value="{ALLOW_PROVINCE}" />
<input type="hidden" id="allow_district" value="{ALLOW_DISTRICT}" />
<input type="hidden" id="is_district" value="{IS_DISTRICT}" />
<input type="hidden" id="multiple_province" value="{MULTIPLE_PROVINCE}" />
<input type="hidden" id="multiple_distric" value="{MULTIPLE_DISTRICT}" />
<input type="hidden" id="blank_title_country" value="{BLANK_TITLE_COUNTRY}" />
<input type="hidden" id="blank_title_province" value="{BLANK_TITLE_PROVINCE}" />
<input type="hidden" id="blank_title_district" value="{BLANK_TITLE_DISTRICT}" />
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
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.country_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{COUNTRY.countryid}" {COUNTRY.selected}>{COUNTRY.title}</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: country -->
	<!-- BEGIN: country_hidden -->
	<input type="hidden" name="countryid" value="{COUNTRYID}" />
	<!-- END: country_hidden -->

	<!-- BEGIN: province -->
	<div class="col-xs-24 col-sm-12 col-md-12">
		<select class="form-control m-bottom" <!-- BEGIN: none_multiple -->name="provinceid"<!-- END: none_multiple --> id="provinceid" <!-- BEGIN: multiple -->name="provinceid[]" multiple="multiple"<!-- END: multiple --> >
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.province_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{PROVINCE.provinceid}" {PROVINCE.selected}>{PROVINCE.type} {PROVINCE.title}</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: province -->

	<!-- BEGIN: district -->
	<div class="col-xs-24 col-sm-12 col-md-12">
		<select class="form-control m-bottom" <!-- BEGIN: none_multiple -->name="districtid"<!-- END: none_multiple --> id="districtid" <!-- BEGIN: multiple -->name="districtid[]"<!-- END: multiple --> >
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.district_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{DISTRICT.districtid}" {DISTRICT.selected}>{DISTRICT.type} {DISTRICT.title}</option>
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
		$(this).val() != 0 && $.ajax({
			method: 'POST',
			url : window.location.href,
			data : nv_location_build_query(),
			success : function( res ){
				$('#form-input').html( res );
			}
		});
	});

	if( $('#districtid').length > 0 ){
		$('#provinceid').change(function(){
			$(this).val() != 0 && $.ajax({
				method: 'POST',
				url : window.location.href,
				data : nv_location_build_query(),
				success : function( res ){
					$('#form-input').html( res );
				}
			});
		});
	}

	function nv_location_build_query(){
		var query = '';
		query += 'location_reload=1';
		query += '&select_countryid=' + $('#countryid').val();
		query += '&select_provinceid=' + $('#provinceid').val();
		query += '&select_districtid=' + $('#districtid').val();
		query += '&multiple_province=' + $('#multiple_province').val();
		query += '&multiple_district=' + $('#multiple_distric').val();
		query += '&is_district=' + $('#is_district').val();
		query += '&allow_country=' + $('#allow_country').val();
		query += '&allow_province=' + $('#allow_province').val();
		query += '&allow_district=' + $('#allow_district').val();
		query += '&blank_title_country=' + $('#blank_title_country').val();
		query += '&blank_title_province=' + $('#blank_title_province').val();
		query += '&blank_title_district=' + $('#blank_title_district').val();

		return query;
	}
</script>
<!-- END: form_input -->