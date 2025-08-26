# Cấu Trúc Code VHSS System

## Tổng Quan
Hệ thống VHSS đã được tái cấu trúc để dễ quản lý và bảo trì hơn. Các controller và model đã được tách thành các file riêng biệt tương ứng với các view.

## Cấu Trúc Thư Mục

### Controllers
```
app/Http/Controllers/vehicles/
├── ActiveVehiclesController.php      # Quản lý xe đang hoạt động
├── VehiclesListController.php        # Quản lý danh sách xe
├── GridDisplayController.php         # Quản lý hiển thị dạng lưới
├── VehicleAttributesController.php   # Quản lý thuộc tính xe
└── VehicleManagementController.php   # Quản lý chung xe (CRUD)
```

### Models
```
app/Models/vehicles/
├── VehicleStatus.php                 # Quản lý trạng thái xe
├── VehicleRoute.php                  # Quản lý tuyến đường xe
└── VehicleTiming.php                 # Quản lý thời gian xe
```

## Chi Tiết Các Controller

### 1. ActiveVehiclesController
**Chức năng:** Quản lý xe đang hoạt động (xe ngoài bãi)
**Methods:**
- `index()` - Hiển thị trang xe đang hoạt động
- `startTimer()` - Bắt đầu bấm giờ cho xe
- `assignRoute()` - Phân tuyến cho xe
- `returnToYard()` - Đưa xe về bãi
- `pause()` - Tạm dừng xe
- `resume()` - Tiếp tục xe
- `getVehiclesByStatus()` - Lấy xe theo trạng thái

### 2. VehiclesListController
**Chức năng:** Quản lý danh sách xe với bộ lọc
**Methods:**
- `index()` - Hiển thị danh sách xe với bộ lọc
- `getVehicles()` - API lấy danh sách xe

### 3. GridDisplayController
**Chức năng:** Quản lý hiển thị xe dạng lưới
**Methods:**
- `index()` - Hiển thị xe dạng lưới
- `getVehiclesForGrid()` - API lấy xe cho grid

### 4. VehicleAttributesController
**Chức năng:** Quản lý thuộc tính xe
**Methods:**
- `index()` - Hiển thị trang quản lý thuộc tính
- `getAllAttributes()` - API lấy tất cả thuộc tính
- `getAttributesByType()` - API lấy thuộc tính theo loại

### 5. VehicleManagementController
**Chức năng:** Quản lý chung xe (CRUD operations)
**Methods:**
- `index()` - Hiển thị danh sách xe
- `create()` - Form tạo xe mới
- `store()` - Lưu xe mới
- `show()` - Hiển thị chi tiết xe
- `edit()` - Form chỉnh sửa xe
- `update()` - Cập nhật xe
- `destroy()` - Xóa xe
- `updateStatus()` - Cập nhật trạng thái xe
- `updateRoute()` - Cập nhật tuyến đường xe
- `moveToWorkshop()` - Chuyển xe về xưởng

## Chi Tiết Các Model

### 1. VehicleStatus
**Chức năng:** Quản lý trạng thái xe
**Constants:**
- `STATUS_ACTIVE` - Xe sẵn sàng chạy
- `STATUS_INACTIVE` - Xe trong xưởng
- `STATUS_RUNNING` - Xe đang chạy
- `STATUS_WAITING` - Xe đang chờ
- `STATUS_EXPIRED` - Xe hết giờ
- `STATUS_PAUSED` - Xe tạm dừng
- `STATUS_GROUP` - Xe trong tuyến

**Methods:**
- `getDisplayName()` - Lấy tên hiển thị trạng thái
- `getColorClass()` - Lấy class CSS cho trạng thái
- `getAvailableTransitions()` - Lấy các chuyển đổi trạng thái hợp lệ
- `isValidTransition()` - Kiểm tra chuyển đổi trạng thái hợp lệ

### 2. VehicleRoute
**Chức năng:** Quản lý tuyến đường xe
**Constants:**
- `DEFAULT_ROUTES` - Danh sách tuyến đường mặc định

**Methods:**
- `getRouteName()` - Lấy tên tuyến đường
- `getVehiclesByRoute()` - Lấy xe theo tuyến đường
- `getRouteStatistics()` - Lấy thống kê tuyến đường
- `isValidRoute()` - Kiểm tra tuyến đường hợp lệ

### 3. VehicleTiming
**Chức năng:** Quản lý thời gian xe
**Methods:**
- `calculateRemainingTime()` - Tính thời gian còn lại
- `formatTime()` - Định dạng thời gian
- `isExpired()` - Kiểm tra xe hết giờ
- `getSessionProgress()` - Lấy tiến độ phiên

## Routes

### Main Routes
- `/vehicles` - Quản lý xe chính
- `/vehicles-list` - Danh sách xe
- `/grid-display` - Hiển thị dạng lưới
- `/active-vehicles` - Xe đang hoạt động
- `/vehicles/attributes` - Thuộc tính xe

### API Routes
- `/api/vehicles` - API danh sách xe
- `/api/vehicles/grid` - API xe cho grid
- `/api/vehicles/attributes` - API thuộc tính xe
- `/api/active-vehicles/*` - API xe đang hoạt động

## Lợi Ích Của Cấu Trúc Mới

1. **Dễ Quản Lý:** Mỗi controller/model chỉ xử lý một chức năng cụ thể
2. **Dễ Bảo Trì:** Code được tổ chức rõ ràng, dễ tìm và sửa
3. **Tái Sử Dụng:** Các model có thể được sử dụng bởi nhiều controller
4. **Mở Rộng:** Dễ dàng thêm tính năng mới
5. **Testing:** Dễ dàng viết test cho từng component

## Sử Dụng

### Trong Controller
```php
use App\Models\vehicles\VehicleStatus;
use App\Models\vehicles\VehicleRoute;

// Sử dụng VehicleStatus
$statusName = VehicleStatus::getDisplayName('active');
$colorClass = VehicleStatus::getColorClass('running');

// Sử dụng VehicleRoute
$routeName = VehicleRoute::getRouteName(1);
$routeStats = VehicleRoute::getRouteStatistics();
```

### Trong View
```php
// Sử dụng route mới
<a href="{{ route('vehicles.list') }}">Danh sách xe</a>
<a href="{{ route('active-vehicles.index') }}">Xe đang hoạt động</a>
<a href="{{ route('vehicles.grid') }}">Hiển thị dạng lưới</a>
```

## Lưu Ý

- Tất cả các view hiện có vẫn hoạt động bình thường
- Các route cũ đã được chuyển hướng đến controller mới
- Không cần thay đổi gì trong view
- Chỉ cần cập nhật navigation để sử dụng route mới
