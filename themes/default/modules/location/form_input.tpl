<!-- BEGIN: main -->
<input type="hidden" id="allow_country_{CONFIG.index}" value="{CONFIG.allow_country}" />
<input type="hidden" id="allow_province_{CONFIG.index}" value="{CONFIG.allow_province}" />
<input type="hidden" id="allow_district_{CONFIG.index}" value="{CONFIG.allow_district}" />
<input type="hidden" id="allow_ward_{CONFIG.index}" value="{CONFIG.allow_ward}" />
<input type="hidden" id="is_district_{CONFIG.index}" value="{CONFIG.is_district}" />
<input type="hidden" id="is_ward_{CONFIG.index}" value="{CONFIG.is_ward}" />
<input type="hidden" id="multiple_province_{CONFIG.index}" value="{CONFIG.multiple_province}" />
<input type="hidden" id="multiple_distric_{CONFIG.index}" value="{CONFIG.multiple_district}" />
<input type="hidden" id="multiple_ward_{CONFIG.index}" value="{CONFIG.multiple_ward}" />
<input type="hidden" id="blank_title_country_{CONFIG.index}" value="{CONFIG.blank_title_country}" />
<input type="hidden" id="blank_title_province_{CONFIG.index}" value="{CONFIG.blank_title_province}" />
<input type="hidden" id="blank_title_district_{CONFIG.index}" value="{CONFIG.blank_title_district}" />
<input type="hidden" id="blank_title_ward_{CONFIG.index}" value="{CONFIG.blank_title_ward}" />
<input type="hidden" id="name_country_{CONFIG.index}" value="{CONFIG.name_country}" />
<input type="hidden" id="name_province_{CONFIG.index}" value="{CONFIG.name_province}" />
<input type="hidden" id="name_district_{CONFIG.index}" value="{CONFIG.name_district}" />
<input type="hidden" id="name_ward_{CONFIG.index}" value="{CONFIG.name_ward}" />
<input type="hidden" id="index_{CONFIG.index}" value="{CONFIG.index}" />
<input type="hidden" id="col_class_{CONFIG.index}" value="{CONFIG.col_class}" />

<div id="form-input-{CONFIG.index}">{FORM_INPUT}</div>
<!-- END: main -->

<!-- BEGIN: form_input -->

<!-- BEGIN: select2 -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<!-- END: select2 -->

<div class="row location-row">
	<!-- BEGIN: country -->
	<div class="{CONFIG.col_class} m-bottom country">
		<select class="form-control location" data-type="countryid" name="{CONFIG.name_country}" id="countryid-{CONFIG.index}">
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.country_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{COUNTRY.countryid}"{COUNTRY.selected}>{COUNTRY.title}</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: country -->
	<!-- BEGIN: country_hidden -->
	<input type="hidden" name="{CONFIG.name_country}" value="{COUNTRYID}" />
	<!-- END: country_hidden -->

	<!-- BEGIN: province -->
	<div class="{CONFIG.col_class} m-bottom province">
		<select class="form-control location" data-type="provinceid"
			<!-- BEGIN: none_multiple -->name="{CONFIG.name_province}"
			<!-- END: none_multiple --> id="provinceid-{CONFIG.index}"
			<!-- BEGIN: multiple -->name="{CONFIG.name_province}[]" multiple="multiple"
			<!-- END: multiple --> >
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.province_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{PROVINCE.provinceid}"{PROVINCE.selected}><!-- BEGIN: type -->{PROVINCE.type}
				<!-- END: type -->{PROVINCE.title}
			</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: province -->

	<!-- BEGIN: district -->
	<div class="{CONFIG.col_class} m-bottom district">
		<select class="form-control location" data-type="districtid"
			<!-- BEGIN: none_multiple -->name="{CONFIG.name_district}"
			<!-- END: none_multiple --> id="districtid-{CONFIG.index}"
			<!-- BEGIN: multiple -->name="{CONFIG.name_district}[]" multiple="multiple"
			<!-- END: multiple --> >
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.district_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{DISTRICT.districtid}"{DISTRICT.selected}><!-- BEGIN: type -->{DISTRICT.type}
				<!-- END: type -->{DISTRICT.title}
			</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: district -->

	<!-- BEGIN: ward -->
	<div class="{CONFIG.col_class} m-bottom ward">
		<select class="form-control location" data-type="wardid"
			<!-- BEGIN: none_multiple -->name="{CONFIG.name_ward}"
			<!-- END: none_multiple --> id="wardid-{CONFIG.index}"
			<!-- BEGIN: multiple -->name="{CONFIG.name_ward}[]" multiple="multiple"
			<!-- END: multiple --> >
			<!-- BEGIN: blank_title -->
			<option value="0">---{LANG.ward_cc}---</option>
			<!-- END: blank_title -->
			<!-- BEGIN: loop -->
			<option value="{WARD.wardid}"{WARD.selected}><!-- BEGIN: type -->{WARD.type}
				<!-- END: type -->{WARD.title}
			</option>
			<!-- END: loop -->
		</select>
	</div>
	<!-- END: ward -->
</div>
<script>
$(document).ready(function() {
    $('#countryid-{CONFIG.index}, #provinceid-{CONFIG.index}, #districtid-{CONFIG.index}, #wardid-{CONFIG.index}').select2({
        theme: 'bootstrap',
        language: '{NV_LANG_INTERFACE}'
    });

    $('#countryid-{CONFIG.index}').change(function(){
        $(this).val() != 0 && $.ajax({
            method: 'POST',
            url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=location',
            data : nv_location_build_query({CONFIG.index}),
            success : function( res ){
                $('#form-input-{CONFIG.index}').html( res );
            }
        });
        
		if(typeof nv_location_change === 'function'){
			nv_location_change('countryid', $(this).val());
    	}
    });

    $('#provinceid-{CONFIG.index}').change(function(){
		if( $('#districtid-{CONFIG.index}').length > 0 ){
			$(this).val() != 0 && $.ajax({
		        method: 'POST',
		        url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=location',
		        data : nv_location_build_query({CONFIG.index}),
		        success : function( res ){
		            $('#form-input-{CONFIG.index}').html( res );
		        }
			});
		}

		if(typeof nv_location_change === 'function'){
			nv_location_change('provinceid', $(this).val());
    	}
    });
    
	$('#districtid-{CONFIG.index}').change(function(){
		if( $('#wardid-{CONFIG.index}').length > 0 ){
			$(this).val() != 0 && $.ajax({
		        method: 'POST',
		        url : nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=location',
		        data : nv_location_build_query({CONFIG.index}),
		        success : function( res ){
		            $('#form-input-{CONFIG.index}').html( res );
		        }
		    });
		}
		
		if(typeof nv_location_change === 'function'){
    		nv_location_change('districtid', $(this).val());
    	}
	});
	
	$('#wardid-{CONFIG.index}').change(function(){
		if(typeof nv_location_change === 'function'){
			nv_location_change('wardidid', $(this).val());
    	}		
	});
});

if (typeof nv_location_build_query != 'function'){
	function nv_location_build_query(index){
	    var query = '';
	    query += 'location_reload=1';
	    query += '&select_countryid=' + $('#countryid-' + index).val();
	    query += '&select_provinceid=' + $('#provinceid-' + index).val();
	    query += '&select_districtid=' + $('#districtid-' + index).val();
	    query += '&select_wardid=' + $('#wardid-' + index).val();
	    query += '&multiple_province=' + $('#multiple_province_' + index).val();
	    query += '&multiple_district=' + $('#multiple_distric_' + index).val();
	    query += '&multiple_ward=' + $('#multiple_ward_' + index).val();
	    query += '&is_district=' + $('#is_district_' + index).val();
	    query += '&is_ward=' + $('#is_ward_' + index).val();
	    query += '&allow_country=' + $('#allow_country_' + index).val();
	    query += '&allow_province=' + $('#allow_province_' + index).val();
	    query += '&allow_district=' + $('#allow_district_' + index).val();
	    query += '&allow_ward=' + $('#allow_ward_' + index).val();
	    query += '&blank_title_country=' + $('#blank_title_country_' + index).val();
	    query += '&blank_title_province=' + $('#blank_title_province_' + index).val();
	    query += '&blank_title_district=' + $('#blank_title_district_' + index).val();
	    query += '&blank_title_ward=' + $('#blank_title_ward_' + index).val();
	    query += '&name_country=' + $('#name_country_' + index).val();
	    query += '&name_province=' + $('#name_province_' + index).val();
	    query += '&name_district=' + $('#name_district_' + index).val();
	    query += '&name_ward=' + $('#name_ward_' + index).val();
	    query += '&index=' + $('#index_' + index).val();
	    query += '&col_class=' + $('#col_class_' + index).val();

	    return query;
	}	
}
</script>
<!-- END: form_input -->