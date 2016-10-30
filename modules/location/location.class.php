<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 */
class Location
{

    private $select_countryid = '';

    private $select_provinceid = '';

    private $select_districtid = '';

    private $select_wardid = '';

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

    public function set($key, $value)
    {
        if ($key == 'SelectCountryid') {
            $this->setSelectCountryid($value);
        } elseif ($key == 'SelectProvinceid') {
            $this->setSelectProvinceid($value);
        } elseif ($key == 'SelectDistrictid') {
            $this->setSelectDistrictid($value);
        } elseif ($key == 'SelectWardid') {
            $this->setSelectWardid($value);
        } elseif ($key == 'AllowCountry') {
            $this->setAllowCountry($value);
        } elseif ($key == 'AllowProvince') {
            $this->setAllowProvince($value);
        } elseif ($key == 'AllowDistrict') {
            $this->setAllowDistrict($value);
        } elseif ($key == 'AllowWard') {
            $this->setAllowWard($value);
        } elseif ($key == 'MultipleProvince') {
            $this->setMultipleProvince($value);
        } elseif ($key == 'MultipleDistrict') {
            $this->setMultipleDistrict($value);
        } elseif ($key == 'MultipleWard') {
            $this->setMultipleWard($value);
        } elseif ($key == 'IsDistrict') {
            $this->setIsDistrict($value);
        } elseif ($key == 'IsWard') {
            $this->setIsWard($value);
        } elseif ($key == 'BlankTitleCountry') {
            $this->setBlankTitleCountry($value);
        } elseif ($key == 'BlankTitleProvince') {
            $this->setBlankTitleProvince($value);
        } elseif ($key == 'BlankTitleDistrict') {
            $this->setBlankTitleDistrict($value);
        } elseif ($key == 'BlankTitleWard') {
            $this->setBlankTitleWard($value);
        } elseif ($key == 'NameCountry') {
            $this->setNameCountry($value);
        } elseif ($key == 'NameProvince') {
            $this->setNameProvince($value);
        } elseif ($key == 'NameDistrict') {
            $this->setNameDistrict($value);
        } elseif ($key == 'NameWard') {
            $this->setNameWard($value);
        } elseif ($key == 'Index') {
            $this->setIndex($value);
        } elseif ($key == 'ColClass') {
            $this->setColClass($value);
        }
    }

    private function setSelectCountryid($select_countryid)
    {
        $this->select_countryid = $select_countryid;
    }

    private function setSelectProvinceid($select_provinceid)
    {
        $this->select_provinceid = $select_provinceid;
    }

    private function setSelectDistrictid($select_districtid)
    {
        $this->select_districtid = $select_districtid;
    }

    private function setSelectWardid($select_wardid)
    {
        $this->select_wardid = $select_wardid;
    }

    private function setAllowCountry($allow_country)
    {
        $this->allow_country = $allow_country;
    }

    private function setAllowProvince($allow_province)
    {
        $this->allow_province = $allow_province;
    }

    private function setAllowDistrict($allow_district)
    {
        $this->allow_district = $allow_district;
    }

    private function setAllowWard($allow_ward)
    {
        $this->allow_ward = $allow_ward;
    }

    private function setMultipleProvince($multiple_province)
    {
        $this->multiple_province = $multiple_province;
    }

    private function setMultipleDistrict($multiple_district)
    {
        $this->multiple_district = $multiple_district;
    }

    private function setMultipleWard($multiple_ward)
    {
        $this->multiple_ward = $multiple_ward;
    }

    private function setIsDistrict($is_district)
    {
        $this->is_district = $is_district;
    }

    private function setIsWard($is_ward)
    {
        $this->is_ward = $is_ward;
    }

    private function setBlankTitleCountry($blank_title_country)
    {
        $this->blank_title_country = $blank_title_country;
    }

    private function setBlankTitleProvince($blank_title_province)
    {
        $this->blank_title_province = $blank_title_province;
    }

    private function setBlankTitleDistrict($blank_title_district)
    {
        $this->blank_title_district = $blank_title_district;
    }

    private function setBlankTitleWard($blank_title_ward)
    {
        $this->blank_title_ward = $blank_title_ward;
    }

    private function setNameCountry($name_country)
    {
        $this->name_country = $name_country;
    }

    private function setNameProvince($name_province)
    {
        $this->name_province = $name_province;
    }

    private function setNameDistrict($name_district)
    {
        $this->name_district = $name_district;
    }

    private function setNameWard($name_ward)
    {
        $this->name_ward = $name_ward;
    }

    private function setIndex($index)
    {
        $this->index = $index;
    }

    private function setColClass($col_class)
    {
        $this->col_class = $col_class;
    }

    public function getArrayCountry($inArrayId = array())
    {
        global $db, $db_config, $nv_Cache;
        
        $where = '';
        if (! empty($inArrayId)) {
            $where .= ' AND countryid IN(' . implode(',', $inArrayId) . ')';
        }
        
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_location_country WHERE status=1 ' . $where . ' ORDER BY weight ASC';
        $array_country = $nv_Cache->db($sql, 'countryid', 'location');
        
        return $array_country;
    }

    public function getArrayProvince($inArrayId = array(), $countryid = 0)
    {
        global $db, $db_config, $nv_Cache;
        
        $where = '';
        if (! empty($inArrayId)) {
            $where .= ' AND provinceid IN(' . implode(',', $inArrayId) . ')';
        }
        
        if (! empty($countryid)) {
            if (is_array($countryid)) {
                $where .= ' AND countryid IN (' . implode(',', $countryid) . ')';
            } else {
                $where .= ' AND countryid=' . $countryid;
            }
        }
        
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_location_province WHERE status=1 ' . $where . ' ORDER BY weight ASC';
        $array_province = $nv_Cache->db($sql, 'provinceid', 'location');
        
        return $array_province;
    }

    public function getArrayDistrict($inArrayId = array(), $provinceid = 0)
    {
        global $db, $db_config, $nv_Cache;
        
        $where = '';
        if (! empty($inArrayId)) {
            $where .= ' AND districtid IN(' . implode(',', $inArrayId) . ')';
        }
        
        if (! empty($provinceid)) {
            if (is_array($provinceid)) {
                $where .= ' AND provinceid IN (' . implode(',', $provinceid) . ')';
            } else {
                $where .= ' AND provinceid=' . $provinceid;
            }
        }
        
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_location_district WHERE status=1 ' . $where . ' ORDER BY weight ASC';
        $array_district = $nv_Cache->db($sql, 'districtid', 'location');
        
        return $array_district;
    }

    public function getArrayWard($inArrayId = array(), $districtid = 0)
    {
        global $db, $db_config, $nv_Cache;
        
        $where = '';
        if (! empty($inArrayId)) {
            $where .= ' AND wardid IN(' . implode(',', $inArrayId) . ')';
        }
        
        if (! empty($districtid)) {
            if (is_array($districtid)) {
                $where .= ' AND districtid IN (' . implode(',', $districtid) . ')';
            } else {
                $where .= ' AND districtid=' . $districtid;
            }
        }
        
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_location_ward WHERE status=1 ' . $where . ' ORDER BY title ASC';
        $array_ward = $nv_Cache->db($sql, 'wardid', 'location');
        
        return $array_ward;
    }

    public function getCountryInfo($countryid)
    {
        $array = $this->getArrayCountry();
        return $array[$countryid];
    }

    public function getProvinceInfo($provinceid)
    {
        $array = $this->getArrayProvince();
        return $array[$provinceid];
    }

    public function getDistricInfo($districtid)
    {
        $array = $this->getArrayDistrict();
        return $array[$districtid];
    }

    public function getWardInfo($wardid)
    {
        $array = $this->getArrayWard();
        return $array[$wardid];
    }

    public function locationString($provinceid = 0, $districtid = 0, $wardid = 0)
    {
        global $db, $db_config, $site_mods, $module_config;
        
        $location_array_config = $module_config['location'];
        $string = array();
        
        if (! empty($wardid)) {
            $ward_info = $this->getWardInfo($wardid);
            $string[] = $ward_info['title'];
        }
        
        if (! empty($districtid)) {
            $district_info = $this->getDistricInfo($districtid);
            $string[] = $district_info['title'];
        }
        
        if (! empty($provinceid)) {
            $province_info = $this->getProvinceInfo($provinceid);
            $string[] = $province_info['title'];
        }
        
        return implode(', ', $string);
    }

    public function buildInput($template = 'default', $module = 'location')
    {
        global $db, $db_config, $site_mods, $global_config, $lang_module, $module_config;
        
        $array_country = $array_province = $array_district = $array_ward = array();
        
        $location_array_config = $module_config['location'];
        
        $i = 0;
        $first_country = $this->select_countryid;
        $allow_country = ! empty($this->allow_country) ? array(
            $this->allow_country
        ) : array();
        $array_country = $this->getArrayCountry($allow_country);
        foreach ($array_country as $index => $value) {
            if ($i == 0 and empty($first_country)) {
                $first_country = $index;
            }
            $i ++;
        }
        
        $j = 0;
        $first_province = $this->select_provinceid;
        $allow_province = ! empty($this->allow_province) ? array(
            $this->allow_province
        ) : array();
        $array_province = $this->getArrayProvince($allow_province, $first_country);
        foreach ($array_province as $index => $value) {
            if ($j == 0 and empty($first_province)) {
                $first_province = $index;
            }
            $j ++;
        }
        
        if ($this->is_district and ! $this->multiple_province) {
            $j = 0;
            $first_district = $this->select_districtid;
            $allow_district = ! empty($this->allow_district) ? array(
                $this->allow_district
            ) : array();
            $array_district = $this->getArrayDistrict($allow_district, $first_province);
            foreach ($array_district as $index => $value) {
                if ($j == 0 and empty($first_district)) {
                    $first_district = $index;
                }
            }
            
            if ($this->is_ward and ! $this->multiple_district) {
                $allow_ward = ! empty($this->allow_ward) ? array(
                    $this->allow_ward
                ) : array();
                $array_ward = $this->getArrayWard($allow_ward, $first_district);
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
                foreach ($array_country as $countryid => $country) {
                    $country['selected'] = $this->select_countryid == $countryid ? 'selected="selected"' : '';
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
            foreach ($array_province as $provinceid => $province) {
                
                if (is_array($this->select_provinceid)) {
                    $province['selected'] = in_array($provinceid, $this->select_provinceid) ? 'selected="selected"' : '';
                } else {
                    $province['selected'] = $this->select_provinceid == $provinceid ? 'selected="selected"' : '';
                }
                
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
            foreach ($array_district as $districid => $district) {
                
                if (is_array($this->select_districtid)) {
                    $district['selected'] = in_array($districid, $this->select_districtid) ? 'selected="selected"' : '';
                } else {
                    $district['selected'] = $this->select_districtid == $districid ? 'selected="selected"' : '';
                }
                
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
            foreach ($array_ward as $wardid => $ward) {
                
                if (is_array($this->select_wardid)) {
                    $ward['selected'] = in_array($wardid, $this->select_wardid) ? 'selected="selected"' : '';
                } else {
                    $ward['selected'] = $this->select_wardid == $wardid ? 'selected="selected"' : '';
                }
                
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