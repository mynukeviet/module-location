# Module địa điểm cho NukeViet 4
# Hướng dẫn cài đặt
1. Tải về phiên bản mới nhất tại https://github.com/hongoctrien/module-location/releases
2. Cài đặt cho NukeViet với phiên bản tuơng ứng

# Hướng dẫn sử dụng

Module cung cấp các phương thức giúp lập trình viên có thể tích hợp cho các module khác trong việc xử lý dữ liệu về địa điểm.

### Sử dụng Selectbox chọn địa điểm

Bạn có thể dễ dàng thêm mô hình chọn địa điểm theo cấp độ từ cao đến thấp (VD: Chọn Tỉnh / Thành phố xong sẽ hiển thị danh sách các Quận / Huyện của Tỉnh đó).

![](https://mynukeviet.net/uploads/ung-dung/2016/images/module-dia-diem-nukeviet.png)

**Yêu cầu bắt buộc:** Module location phải được cài đặt cùng ứng dụng bạn muốn sử dụng 

1, Bạn cần thêm và khởi tạo class **Location**. Class này được xây dựng sẵn cho module location.

```
require_once NV_ROOTDIR . '/modules/location/location.class.php';
$location = new Location();
```

2, Thiết lập các thuộc tính

Sau khi khởi tạo đối lượng $location, bạn có thể sử dụng phương thức **set** khởi tạo các thuộc tính theo cú pháp sau:
```
$location->set('[tên-thuộc-tính]', [giá-trị]);
```

**[tên-thuộc-tính] được mô tả trong danh sách sau:**
- **SelectCountryid**: ID Quốc gia được chọn. Trường hợp có nhiều quốc gia, bạn có thể chỉ định Quốc gia mặc định được chọn khi hiển thị.
- **SelectProvinceid**: ID Tỉnh/Thành phố được chọn.
- **SelectDistrictid**: ID Quận/Huyện được chọn.
- **SelectWardid**: ID Xã/Phường được chọn.
- **AllowCountry**: ID Quốc gia được sử dụng. Trường hợp dữ liệu bạn có nhiều quốc gia, bạn có thể đưa vào danh sách ID các quốc gia được hiển thị lên selectbox, cách nhau bởi dấu phẩy ",".
- **AllowProvince**: ID Tỉnh/Thành phố được sử dụng. Trường hợp dữ liệu bạn có nhiều Tỉnh/Thành phố, bạn có thể đưa vào danh sách ID các Tỉnh/Thành phố được hiển thị lên selectbox, cách nhau bởi dấu phẩy ",".
- **AllowDistrict**: ID Quận/Huyện được sử dụng. Trường hợp dữ liệu bạn có nhiều Quận/Huyện, bạn có thể đưa vào danh sách ID các Quận/Huyện được hiển thị lên selectbox, cách nhau bởi dấu phẩy ",".
- **AllowWard**: ID Xã/Phường được sử dụng. Trường hợp dữ liệu bạn có nhiều Xã/Phường, bạn có thể đưa vào danh sách ID các Xã/Phường được hiển thị lên selectbox, cách nhau bởi dấu phẩy ",".
- **MultipleProvince**: Tùy chọn cho phép chọn một hoặc nhiều Tỉnh/Thành phố. (Mặc định: false)
- **MultipleDistrict**: Tùy chọn cho phép chọn một hoặc nhiều Quận/Huyện. (Mặc định: false)
- **MultipleWard**: Tùy chọn cho phép chọn một hoặc nhiều Xã/Phường. (Mặc định: false)
- **IsDistrict**: Tùy chọn cho phép sử dụng cấp Quận/Huyện hay không. (Mặc định: false)
- **IsWard**: Tùy chọn cho phép sử dụng cấp Xã/Phường hay không. (Mặc định: false)
- **BlankTitleCountry**: Tùy chọn cho phép sử dụng giá trị rỗng cho Quốc gia hay không. (Mặc định: false)
- **BlankTitleProvince**: Tùy chọn cho phép sử dụng giá trị rỗng cho Tỉnh/Thành phố hay không. (Mặc định: false)
- **BlankTitleDistrict**: Tùy chọn cho phép sử dụng giá trị rỗng cho Quận/Huyện hay không. (Mặc định: false)
- **BlankTitleWard**: Tùy chọn cho phép sử dụng giá trị rỗng cho Xã/Phường hay không. (Mặc định: false)
- **NameCountry**: Thuộc tính name cho selectbox chọn Quốc gia. (Mặc định: countryid)
- **NameProvince**: Thuộc tính name cho selectbox chọn Tỉnh/Thành phố. (Mặc định: provinceid)
- **NameDistrict**: Thuộc tính name cho selectbox chọn Quận/Huyện. (Mặc định: districtid)
- **NameWard**: Thuộc tính name cho selectbox chọn Xã/Phường. (Mặc định: wardid)
- **Index**: Nếu bạn sử dụng nhiều hơn 1 hộp chọn địa điểm trên trang cần khai báo index. (Giá trị: 0, 1, 2, 3,......)
- **ColClass**: Css class định dạng cho selectbox.

3, Sau khi khởi tạo và thiết lập, sử dụng phương thức **buildInput** để bắt đầu xây dựng cụm selectbox
```
$input = $location->buildInput();
```
Và sử dụng **assign** để xuất ra giao diện
``` 
$xtpl->assign('LOCATION', $input);
```

**Code mẫu hoàn thiện**
```
require_once NV_ROOTDIR . '/modules/location/location.class.php';
$location = new Location();
$location->set('IsDistrict', true);
$location->set('IsWard', true);

$input = $location->buildInput();
$xtpl->assign('LOCATION', $input);
```

### Các phương thức khác
Để sử dụng các phương thức này, việc đầu tiên bạn cũng cần khai báo và khởi tạo **class Location**
```
require_once NV_ROOTDIR . '/modules/location/location.class.php';
$location = new Location();
```

**Cú pháp**
```
$location->[tên-phương-thức]
```

**[tên-phương-thức] được mô tả như sau**
- **getArrayCountry**:
 - Cú pháp: `getArrayCountry($inArray = array())`. **$inArray** là mảng các ID Quốc gia cần lấy  
 - Ví dụ: `getArrayCountry(array(1,2,3))`
- **getArrayProvince**:
 - Cú pháp: `getArrayProvince($inArray = array(), $countryid = 0)`. **$inArray** là mảng các ID Tỉnh / Thành phố cần lấy, **$countryid** là ID (hoặc mảng các ID) Quốc gia (mà Tỉnh/Thành phố trực thuộc)
 - Ví dụ: `getArrayProvince(array(1,2,3), 1)`
- **getArrayDistrict**: Tương tự `getArrayProvince`
- **getArrayWard**: Tương tự `getArrayProvince`
- **getCountryInfo**: Trả về mảng thông tin Quốc gia. Cú pháp: `getCountryInfo($countryid)`.
- **getProvinceInfo**: Trả về mảng thông tin Tỉnh/Thành phố. Cú pháp: `getProvinceInfo($provinceid)`.
- **getDistricInfo**: Trả về mảng thông tin Quận/Huyện. Cú pháp: `getDistricInfo($districtid)`.
- **getWardInfo**: Trả về mảng thông tin Xã/Phường. Cú pháp: `getWardInfo($wardid)`.
- **locationString**: Trả về chuỗi địa chỉ đã được xử lý. Cú pháp: `locationString($provinceid = 0, $districtid = 0, $wardid = 0)`

## Thông tin tác giả
* Ứng dụng NukeViet (https://mynukeviet.net)
* contact@mynukeviet.net
* 0169 2777 913 -|- 0905 908 430
