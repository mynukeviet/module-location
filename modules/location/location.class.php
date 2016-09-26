<?php

/**
 * @Project NUKEVIET 4.x
 * @Author hongoctrien (hongoctrien@2mit.org)
 * @Copyright (C) 2015 hongoctrien. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Wed, 16 Dec 2015 07:05:25 GMT
 */

class Location
{
    private $select_countryid = '';
    private $select_provinceid = '';
    private $select_districtid = '';
    private $allow_country = '';
    private $allow_province = '';
    private $allow_district = '';
    private $allow_ward = '';
    private $multiple_province = 0;
    private $multiple_district = 0;
    private $multiple_ward = 0;
    private $is_district = 0;
    private $is_ward = 0;
    private $blank_title_country = 0;
    private $blank_title_province = 0;
    private $blank_title_district = 0;
    private $blank_title_ward = 0;
    private $name_country = 'countryid';
    private $name_province = 'provinceid';
    private $name_district = 'districtid';
    private $name_ward = 'wardid';
    private $index = 0;
    private $col_class = 'col-xs-24 col-sm-12 col-md-12';

    public function setSelectCountryid($select_countryid)
    {
        $this->select_countryid = $select_countryid;
    }

    public function setSelectProvinceid($select_provinceid)
    {
        $this->select_provinceid = $select_provinceid;
    }

    public function setAllowCountry($allow_country)
    {
        $this->allow_country = $allow_country;
    }

    public function setAllowProvince($allow_province)
    {
        $this->allow_province = $allow_province;
    }

    public function setAllowDistrict($allow_district)
    {
        $this->allow_district = $allow_district;
    }

    public function setAllowWard($allow_ward)
    {
        $this->allow_ward = $allow_ward;
    }

    public function setMultipleProvince($multiple_province)
    {
        $this->multiple_province = $multiple_province;
    }

    public function setMultipleDistrict($multiple_district)
    {
        $this->multiple_district = $multiple_district;
    }

    public function setMultipleWard($multiple_ward)
    {
        $this->multiple_ward = $multiple_ward;
    }

    public function setIsDistrict($is_district)
    {
        $this->is_district = $is_district;
    }

    public function setIsWard($is_ward)
    {
        $this->is_ward = $is_ward;
    }

    public function setBlankTitleCountry($blank_title_country)
    {
        $this->blank_title_country = $blank_title_country;
    }

    public function setBlankTitleProvince($blank_title_province)
    {
        $this->blank_title_province = $blank_title_province;
    }

    public function setBlankTitleDistrict($blank_title_district)
    {
        $this->blank_title_district = $blank_title_district;
    }

    public function setBlankTitleWard($blank_title_ward)
    {
        $this->blank_title_ward = $blank_title_ward;
    }

    public function setNameCountry($name_country)
    {
        $this->name_country = $name_country;
    }

    public function setNameProvince($name_province)
    {
        $this->name_province = $name_province;
    }

    public function setNameDistrict($name_district)
    {
        $this->name_district = $name_district;
    }

    public function setNameWard($name_ward)
    {
        $this->name_ward = $name_ward;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function setColClass($col_class)
    {
        $this->col_class = $col_class;
    }

    public function buildInput($template = 'default', $module = 'location')
    {
        global $db, $db_config, $site_mods, $global_config, $lang_module, $location_array_config;

        $in = ! empty($this->allow_country) ? ' AND countryid IN (' . $this->allow_country . ')' : '';
        $result_country = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_country WHERE status=1' . $in . ' ORDER BY weight ASC');
        $array_country = array();
        $array_province = array();
        $array_district = array();
        $array_ward = array();
        $i = 0;

        $first_country = $this->select_countryid;
        while ($row_country = $result_country->fetch()) {
            if ($i == 0 and empty($first_country)){
                $first_country = $row_country['countryid'];
            }
            $row_country['selected'] = $this->select_countryid == $row_country['countryid'] ? 'selected="selected"' : '';
            $array_country[$row_country['countryid']] = $row_country;
            $i ++;
        }

        $j = 0;
        $first_province = $this->select_provinceid;
        $in = ! empty($this->allow_province) ? ' AND provinceid IN (' . $this->allow_province . ')' : '';
        $result_province = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_province WHERE status=1 AND countryid=' . $first_country . $in . ' ORDER BY weight ASC');
        while ($row_province = $result_province->fetch()) {
            if ($j == 0 and empty($first_province))
                $first_province = $row_province['provinceid'];

                if (is_array($this->select_provinceid)) {
                    $row_province['selected'] = in_array($row_province['provinceid'], $this->select_provinceid) ? 'selected="selected"' : '';
                } else {
                    $row_province['selected'] = $this->select_provinceid == $row_province['provinceid'] ? 'selected="selected"' : '';
                }
                $array_province[$row_province['provinceid']] = $row_province;
                $j ++;
        }

        if ($this->is_district) {
            $j = 0;
            $first_district = $this->select_districtid;

            $in = ! empty($this->allow_district) ? ' AND districtid IN (' . $this->allow_district . ')' : '';
            $result_district = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_district WHERE status=1 AND provinceid=' . $first_province . $in . ' ORDER BY weight ASC');
            while ($row_district = $result_district->fetch()) {
                if ($j == 0 and empty($first_district))
                    $first_district = $row_district['districtid'];

                    if (is_array($this->select_districtid)) {
                        $row_district['selected'] = in_array($row_district['districtid'], $this->select_districtid) ? 'selected="selected"' : '';
                    } else {
                        $row_district['selected'] = $this->select_districtid == $row_district['districtid'] ? 'selected="selected"' : '';
                    }
                    $array_district[$row_district['districtid']] = $row_district;
            }
        }

        if ($this->is_ward) {
            $in = ! empty($this->allow_ward) ? ' AND wardid IN (' . $this->allow_ward . ')' : '';
            $result_ward = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_ward WHERE status=1 AND districtid=' . $first_district . $in . ' ORDER BY wardid DESC');
            while ($row_ward = $result_ward->fetch()) {
                if (is_array($this->select_wardid)) {
                    $row_ward['selected'] = in_array($row_ward['wardid'], $this->select_wardid) ? 'selected="selected"' : '';
                } else {
                    $row_ward['selected'] = $this->select_wardid == $row_ward['wardid'] ? 'selected="selected"' : '';
                }
                $array_ward[$row_ward['wardid']] = $row_ward;
            }
        }

        include NV_ROOTDIR . '/modules/location/language/admin_' . NV_LANG_INTERFACE . '.php';

        $xtpl = new XTemplate('form_input.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $site_mods[$module]['module_file']);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('CONFIG', array(
            'select_countryid' => $this->select_countryid,
            'allow_country' => $this->allow_country,
            'allow_province' => $this->allow_province,
            'allow_district' => $this->allow_district,
            'allow_ward' => $this->allow_ward,
            'multiple_province' => $this->multiple_province,
            'multiple_district' => $this->multiple_district,
            'multiple_ward' => $this->multiple_ward,
            'is_district' => $this->is_district,
            'is_ward' => $this->is_ward,
            'blank_title_country' => $this->blank_title_country,
            'blank_title_province' => $this->blank_title_province,
            'blank_title_district' => $this->blank_title_district,
            'blank_title_ward' => $this->blank_title_ward,
            'name_country' => $this->name_country,
            'name_province' => $this->name_province,
            'name_district' => $this->name_district,
            'name_ward' => $this->name_ward,
            'index' => $this->index,
            'col_class' => $this->col_class
        ));

        if (! empty($array_country)) {
            if ($i > 1) {
                if ($this->blank_title_country) {
                    $xtpl->parse('form_input.country.blank_title');
                }
                foreach ($array_country as $country) {
                    $xtpl->assign('COUNTRY', $country);
                    $xtpl->parse('form_input.country.loop');
                }
                $xtpl->parse('form_input.country');
            } else {
                $xtpl->assign('COUNTRYID', $first_country);
                $xtpl->parse('form_input.country_hidden');
            }
        }

        if (! empty($array_province)) {
            if ($this->blank_title_province) {
                $xtpl->parse('form_input.province.blank_title');
            }
            foreach ($array_province as $province) {
                $xtpl->assign('PROVINCE', $province);

                if ($location_array_config['allow_type'] and ! empty($province['type'])) {
                    $xtpl->parse('form_input.province.loop.type');
                }

                $xtpl->parse('form_input.province.loop');
            }
            if ($this->multiple_province) {
                $xtpl->parse('form_input.province.multiple');
            } else {
                $xtpl->parse('form_input.province.none_multiple');
            }
            $xtpl->parse('form_input.province');
        }

        if (! empty($array_district)) {
            if ($this->blank_title_district) {
                $xtpl->parse('form_input.district.blank_title');
            }
            foreach ($array_district as $district) {
                $xtpl->assign('DISTRICT', $district);

                if ($location_array_config['allow_type'] and ! empty($district['type'])) {
                    $xtpl->parse('form_input.district.loop.type');
                }

                $xtpl->parse('form_input.district.loop');
            }
            if ($this->multiple_district) {
                $xtpl->parse('form_input.district.multiple');
            } else {
                $xtpl->parse('form_input.district.none_multiple');
            }
            $xtpl->parse('form_input.district');
        }

        if (! empty($array_ward)) {
            if ($this->blank_title_ward) {
                $xtpl->parse('form_input.ward.blank_title');
            }
            foreach ($array_ward as $ward) {
                $xtpl->assign('WARD', $ward);

                if ($location_array_config['allow_type'] and ! empty($ward['type'])) {
                    $xtpl->parse('form_input.ward.loop.type');
                }

                $xtpl->parse('form_input.ward.loop');
            }
            if ($this->multiple_ward) {
                $xtpl->parse('form_input.ward.multiple');
            } else {
                $xtpl->parse('form_input.ward.none_multiple');
            }
            $xtpl->parse('form_input.ward');
        }

        $xtpl->parse('form_input');
        $form_input = $xtpl->text('form_input');

        $xtpl->assign('FORM_INPUT', $form_input);

        $xtpl->parse('main');
        return $xtpl->text('main');

    }
}