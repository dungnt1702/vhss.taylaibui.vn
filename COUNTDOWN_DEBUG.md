# Hướng dẫn Debug Đồng hồ Đếm ngược

## Vấn đề đã được khắc phục

### 1. Race Condition trong Module Loading
- **Vấn đề**: Các module JavaScript load không đúng thứ tự, dẫn đến lỗi "function not defined"
- **Giải pháp**: Sử dụng dynamic import và đảm bảo thứ tự khởi tạo đúng

### 2. Trùng lặp Logic Khởi tạo
- **Vấn đề**: Cả `vehicles.js` và `vehicle-operations.js` đều có logic khởi tạo đồng hồ đếm ngược
- **Giải pháp**: Chỉ giữ logic khởi tạo trong `vehicle-operations.js`, `vehicles.js` chỉ xử lý UI

### 3. Mất Đồng hồ Đếm ngược khi Reload
- **Vấn đề**: Khi reload trang, đồng hồ đếm ngược không được khôi phục từ database
- **Giải pháp**: Cải thiện hàm `initializeCountdownTimers()` để đọc data attributes và khôi phục timers

## Cách Debug

### 1. Mở Console Browser
- Nhấn F12 hoặc Ctrl+Shift+I (Windows/Linux) hoặc Cmd+Option+I (Mac)
- Chuyển sang tab Console

### 2. Kiểm tra Logs
Khi trang load, bạn sẽ thấy các log sau:
```
Initializing vehicle system...
VehicleManager: Initializing...
VehicleManager: Initialization complete
VehicleOperations: Initializing...
VehicleOperations: Initialization complete
All required modules loaded
All required functions available
Initializing countdown timers...
Vehicle 1: status=running, endTime=1234567890000
Vehicle 1: timeLeft=1800000ms (1800s)
Started countdown timer for vehicle 1
Countdown timers initialization complete
Vehicle system initialization complete
Vehicle wrappers loaded
```

### 3. Sử dụng Test Functions
Trong console, bạn có thể chạy:
```javascript
// Kiểm tra các element đồng hồ đếm ngược
testCountdownElements();

// Kiểm tra các timer đang chạy
testTimers();
```

### 4. Kiểm tra Data Attributes
Mỗi vehicle card có các data attributes:
- `data-vehicle-id`: ID của xe
- `data-status`: Trạng thái hiện tại (running, paused, waiting, expired)
- `data-end-time`: Thời gian kết thúc (milliseconds)
- `data-paused-remaining-seconds`: Thời gian còn lại khi tạm dừng

### 5. Kiểm tra Timer Objects
```javascript
// Xem các timer đang chạy
console.log(window.vehicleOperations.vehicleTimers);

// Xem timer của xe cụ thể
console.log(window.vehicleOperations.vehicleTimers[1]); // Thay 1 bằng ID xe
```

## Các Trường hợp Lỗi Thường gặp

### 1. "function not defined"
- **Nguyên nhân**: Module chưa load xong
- **Giải pháp**: Đợi trang load hoàn toàn, kiểm tra console logs

### 2. Đồng hồ hiển thị 00:00
- **Nguyên nhân**: Data attributes không đúng hoặc timer chưa khởi tạo
- **Giải pháp**: Kiểm tra `data-end-time` và `data-status` trong HTML

### 3. Timer không cập nhật
- **Nguyên nhân**: Timer bị clear hoặc không được khởi tạo
- **Giải pháp**: Kiểm tra `vehicleOperations.vehicleTimers` object

### 4. Mất thời gian khi reload
- **Nguyên nhân**: Database không lưu `end_time` hoặc logic khôi phục sai
- **Giải pháp**: Kiểm tra database và logic trong `initializeCountdownTimers()`

## Cấu trúc Code

### File chính:
- `app.js`: Khởi tạo hệ thống, load modules theo thứ tự
- `vehicle-operations.js`: Xử lý logic đồng hồ đếm ngược
- `vehicles.js`: Xử lý UI và tương tác người dùng
- `vehicle-wrappers.js`: Bridge giữa HTML và JavaScript modules

### Thứ tự khởi tạo:
1. `vehicles.js` - Khởi tạo VehicleManager
2. `vehicle-forms.js` - Khởi tạo VehicleForms
3. `vehicle-operations.js` - Khởi tạo VehicleOperations
4. `vehicle-operations.initializeCountdownTimers()` - Khôi phục timers từ database
5. `vehicle-wrappers.js` - Load wrapper functions

## Test Cases

### 1. Khởi động xe mới
- Bấm "Chạy 30p" hoặc "Chạy 45p"
- Kiểm tra: Timer bắt đầu đếm ngược, status chuyển thành "running"

### 2. Tạm dừng xe
- Bấm "Tạm dừng" khi xe đang chạy
- Kiểm tra: Timer dừng, hiển thị "TẠM DỪNG", status chuyển thành "paused"

### 3. Tiếp tục xe
- Bấm "Tiếp tục" khi xe đang tạm dừng
- Kiểm tra: Timer tiếp tục với thời gian còn lại, status chuyển thành "running"

### 4. Reload trang
- Reload trang khi có xe đang chạy
- Kiểm tra: Timer được khôi phục với thời gian còn lại chính xác

### 5. Hết giờ
- Đợi timer hết giờ
- Kiểm tra: Status chuyển thành "expired", hiển thị "HẾT GIỜ"

## Liên hệ Hỗ trợ

Nếu gặp vấn đề không thể giải quyết:
1. Chụp màn hình console logs
2. Ghi lại các bước thực hiện
3. Ghi lại thông báo lỗi cụ thể
4. Gửi thông tin cho developer
