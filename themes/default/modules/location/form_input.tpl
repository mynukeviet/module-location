<!-- BEGIN: main -->
<input type="hidden" id="allow_country" value="{CONFIG.allow_country}" />
<input type="hidden" id="allow_province" value="{CONFIG.allow_province}" />
<input type="hidden" id="allow_district" value="{CONFIG.allow_district}" />
<input type="hidden" id="allow_ward" value="{CONFIG.allow_ward}" />
<input type="hidden" id="is_district" value="{CONFIG.is_district}" />
<input type="hidden" id="is_ward" value="{CONFIG.is_ward}" />
<input type="hidden" id="multiple_province" value="{CONFIG.multiple_province}" />
<input type="hidden" id="multiple_distric" value="{CONFIG.multiple_district}" />
<input type="hidden" id="multiple_ward" value="{CONFIG.multiple_ward}" />
<input type="hidden" id="blank_title_country" value="{CONFIG.blank_title_country}" />
<input type="hidden" id="blank_title_province" value="{CONFIG.blank_title_province}" />
<input type="hidden" id="blank_title_district" value="{CONFIG.blank_title_district}" />
<input type="hidden" id="blank_title_ward" value="{CONFIG.blank_title_ward}" />

<div id="form-input">
    {FORM_INPUT}
</div>
<!-- END: main -->

<!-- BEGIN: form_input -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2-bootstrap.min.css">

<div class="row">
    <!-- BEGIN: country -->
    <div class="col-xs-24 col-sm-12 col-md-12 m-bottom">
        <select class="form-control" name="countryid" id="countryid">
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
    <div class="col-xs-24 col-sm-12 col-md-12 m-bottom">
        <select class="form-control" <!-- BEGIN: none_multiple -->name="provinceid"<!-- END: none_multiple --> id="provinceid" <!-- BEGIN: multiple -->name="provinceid[]" multiple="multiple"<!-- END: multiple --> >
            <!-- BEGIN: blank_title -->
            <option value="0">---{LANG.province_cc}---</option>
            <!-- END: blank_title -->
            <!-- BEGIN: loop -->
            <option value="{PROVINCE.provinceid}" {PROVINCE.selected}><!-- BEGIN: type -->{PROVINCE.type} <!-- END: type -->{PROVINCE.title}</option>
            <!-- END: loop -->
        </select>
    </div>
    <!-- END: province -->

    <!-- BEGIN: district -->
    <div class="col-xs-24 col-sm-12 col-md-12 m-bottom">
        <select class="form-control" <!-- BEGIN: none_multiple -->name="districtid"<!-- END: none_multiple --> id="districtid" <!-- BEGIN: multiple -->name="districtid[]" multiple="multiple"<!-- END: multiple --> >
            <!-- BEGIN: blank_title -->
            <option value="0">---{LANG.district_cc}---</option>
            <!-- END: blank_title -->
            <!-- BEGIN: loop -->
            <option value="{DISTRICT.districtid}" {DISTRICT.selected}><!-- BEGIN: type -->{DISTRICT.type} <!-- END: type -->{DISTRICT.title}</option>
            <!-- END: loop -->
        </select>
    </div>
    <!-- END: district -->

    <!-- BEGIN: ward -->
    <div class="col-xs-24 col-sm-12 col-md-12 m-bottom">
        <select class="form-control" <!-- BEGIN: none_multiple -->name="wardid"<!-- END: none_multiple --> id="wardid" <!-- BEGIN: multiple -->name="wardid[]" multiple="multiple"<!-- END: multiple --> >
            <!-- BEGIN: blank_title -->
            <option value="0">---{LANG.ward_cc}---</option>
            <!-- END: blank_title -->
            <!-- BEGIN: loop -->
            <option value="{WARD.wardid}" {WARD.selected}><!-- BEGIN: type -->{WARD.type} <!-- END: type -->{WARD.title}</option>
            <!-- END: loop -->
        </select>
    </div>
    <!-- END: ward -->
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script>
    $('#countryid, #provinceid, #districtid, #wardid').select2({
        theme: 'bootstrap',
        language: '{NV_LANG_INTERFACE}'
    });

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

    if( $('#wardid').length > 0 ){
        $('#districtid').change(function(){
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
        query += '&select_wardid=' + $('#wardid').val();
        query += '&multiple_province=' + $('#multiple_province').val();
        query += '&multiple_district=' + $('#multiple_distric').val();
        query += '&multiple_ward=' + $('#multiple_ward').val();
        query += '&is_district=' + $('#is_district').val();
        query += '&is_ward=' + $('#is_ward').val();
        query += '&allow_country=' + $('#allow_country').val();
        query += '&allow_province=' + $('#allow_province').val();
        query += '&allow_district=' + $('#allow_district').val();
        query += '&allow_ward=' + $('#allow_ward').val();
        query += '&blank_title_country=' + $('#blank_title_country').val();
        query += '&blank_title_province=' + $('#blank_title_province').val();
        query += '&blank_title_district=' + $('#blank_title_district').val();
        query += '&blank_title_ward=' + $('#blank_title_ward').val();

        return query;
    }
</script>
<!-- END: form_input -->