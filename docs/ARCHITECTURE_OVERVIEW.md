# Kiến trúc hệ thống VHSS - Tổng quan

## 🏗️ Kiến trúc tổng thể

Hệ thống VHSS được xây dựng theo kiến trúc modular với các nguyên tắc:

- **Module Isolation**: Mỗi module hoạt động độc lập, tránh xung đột
- **BEM Naming Convention**: CSS classes theo chuẩn BEM để dễ bảo trì
- **JavaScript Module System**: Sử dụng ES6 modules với base classes
- **Responsive Design**: Mobile-first approach với Tailwind CSS
- **Documentation-Driven**: Mọi thay đổi đều được ghi chép

## 📁 Cấu trúc thư mục

```
resources/
├── css/
│   ├── common/           # CSS chung cho toàn hệ thống
│   │   ├── base.css      # Base styles, buttons, forms
│   │   └── responsive.css # Responsive utilities
│   ├── vehicles/         # CSS cho vehicle management
│   ├── maintenance/      # CSS cho maintenance system
│   └── auth/            # CSS cho authentication
├── js/
│   ├── common/          # JavaScript chung
│   │   └── ModuleLoader.js # Module loading system
│   ├── vehicles/        # JavaScript cho vehicles
│   ├── maintenance/     # JavaScript cho maintenance
│   └── auth/           # JavaScript cho authentication
└── views/
    ├── vehicles/        # Blade views cho vehicles
    ├── maintenance/     # Blade views cho maintenance
    └── auth/           # Blade views cho authentication
```

## 🧩 Module System

### Base Classes

#### 1. VehiclesModule.js
```javascript
class VehiclesModule {
    constructor(pageType) {
        this.pageType = pageType;
        this.namespace = `vehicles-${pageType}`;
        this.initialized = false;
        this.eventListeners = new Map();
    }
    
    init() { /* Initialization logic */ }
    _setupPageSpecificFeatures() { /* Override in child classes */ }
    addEventListener() { /* Event listener management */ }
    cleanupEventListeners() { /* Cleanup on destroy */ }
}
```

#### 2. MaintenanceModule.js
```javascript
class MaintenanceModule {
    constructor(pageType) {
        this.pageType = pageType;
        this.namespace = `maintenance-${pageType}`;
        // Similar structure to VehiclesModule
    }
}
```

#### 3. AuthModule.js
```javascript
class AuthModule {
    constructor(pageType) {
        this.pageType = pageType;
        this.namespace = `auth-${pageType}`;
        // Similar structure with auth-specific features
    }
}
```

### Module Loading System

#### ModuleLoader.js
```javascript
class ModuleLoader {
    static async loadModule(moduleName, pageType, commonDependencies = []) {
        // Load CSS and JS files for specific module
        // Handle dependencies and initialization
    }
}
```

## 🎨 CSS Architecture

### BEM Naming Convention

```css
/* Block */
.vehicles-active { }

/* Element */
.vehicles-active__header { }
.vehicles-active__table { }

/* Modifier */
.vehicles-active__table--loading { }
.vehicles-active__button--primary { }
```

### CSS Organization

1. **Common CSS** (`resources/css/common/`)
   - `base.css`: Base styles, buttons, forms, status badges
   - `responsive.css`: Responsive utilities, media queries

2. **Module-specific CSS** (`resources/css/{module}/`)
   - Mỗi module có CSS riêng
   - Sử dụng BEM naming convention
   - Không có xung đột giữa các modules

## 🔧 JavaScript Architecture

### Module Pattern

```javascript
// Base class
class VehiclesModule {
    constructor(pageType) {
        this.pageType = pageType;
        this.namespace = `vehicles-${pageType}`;
        this.initialized = false;
        this.eventListeners = new Map();
    }
}

// Specific implementation
class ActiveVehicles extends VehiclesModule {
    constructor() {
        super('active');
    }
    
    _setupPageSpecificFeatures() {
        // Active vehicles specific logic
    }
}
```

### Event Management

```javascript
// Add event listener with tracking
this.addEventListener(element, 'click', handler);

// Cleanup all listeners
this.cleanupEventListeners();
```

### Namespace Isolation

Mỗi module có namespace riêng để tránh xung đột:
- `vehicles-active`
- `vehicles-workshop`
- `maintenance-dashboard`
- `auth-login`

## 📱 Responsive Design

### Mobile-First Approach

```css
/* Base styles for mobile */
.vehicles-active__table {
    @apply min-w-full;
}

/* Desktop styles */
@media (min-width: 768px) {
    .vehicles-active__table {
        @apply min-w-0;
    }
}
```

### Responsive Utilities

```css
/* Common responsive classes */
.desktop-only { /* Hide on mobile */ }
.mobile-only { /* Hide on desktop */ }
.table-responsive { /* Horizontal scroll on mobile */ }
```

## 🗄️ Database Architecture

### Core Tables

- `vehicles` - Thông tin xe
- `vehicle_statuses` - Trạng thái xe
- `vehicle_routes` - Tuyến đường
- `users` - Người dùng
- `roles` - Vai trò
- `maintenance_schedules` - Lịch bảo trì
- `maintenance_records` - Hồ sơ bảo trì

### Relationships

```php
// Vehicle Model
class Vehicle extends Model {
    public function statuses() {
        return $this->hasMany(VehicleStatus::class);
    }
    
    public function routes() {
        return $this->hasMany(VehicleRoute::class);
    }
}
```

## 🔐 Authentication & Authorization

### Role-Based Access Control

```php
// Middleware
class RoleMiddleware {
    public function handle($request, Closure $next, $role) {
        if (!auth()->user()->hasRole($role)) {
            abort(403);
        }
        return $next($request);
    }
}
```

### User Roles

- **Admin**: Full access to all features
- **Manager**: Vehicle management, limited maintenance
- **Operator**: Basic vehicle operations
- **Viewer**: Read-only access

## 🚀 Performance Optimization

### Asset Bundling

- **Vite**: Modern build tool for CSS/JS
- **Module Splitting**: Load only required modules
- **Tree Shaking**: Remove unused code
- **Minification**: Compress assets for production

### Caching Strategy

- **Laravel Cache**: Route, view, config caching
- **Browser Caching**: Static assets with versioning
- **Database Query Caching**: Frequently accessed data

## 📊 Monitoring & Debugging

### Error Handling

```javascript
// Global error handling
window.addEventListener('error', (event) => {
    console.error(`[${this.namespace}] Error:`, event.error);
});
```

### Logging

```javascript
// Namespaced logging
console.log(`[${this.namespace}] Module initialized`);
```

## 🔄 Development Workflow

### 1. Adding New Module

1. Tạo CSS file trong `resources/css/{module}/`
2. Tạo JS file extends base class
3. Tạo Blade views
4. Cập nhật Vite config
5. Cập nhật documentation

### 2. Modifying Existing Module

1. Kiểm tra documentation
2. Rà soát các functions liên quan
3. Thực hiện thay đổi
4. Test module isolation
5. Cập nhật documentation

### 3. Best Practices

- **Always use BEM naming** for CSS classes
- **Namespace JavaScript** functions and variables
- **Document all changes** in relevant files
- **Test module isolation** before committing
- **Follow responsive design** principles

## 📚 Documentation Structure

- `docs/PROJECT_STRUCTURE.md` - Cấu trúc chi tiết
- `docs/QUICK_REFERENCE.md` - Tham khảo nhanh
- `docs/ARCHITECTURE_OVERVIEW.md` - Tổng quan kiến trúc
- `docs/MODULES/` - Chi tiết từng module
- `docs/COMPONENTS/` - Chi tiết components
- `docs/INTEGRATION/` - Hướng dẫn tích hợp

## 🎯 Future Enhancements

### Planned Features

1. **Real-time Updates**: WebSocket integration
2. **Advanced Analytics**: Dashboard with charts
3. **Mobile App**: React Native companion
4. **API Documentation**: Swagger/OpenAPI
5. **Testing Suite**: Unit and integration tests

### Scalability Considerations

- **Microservices**: Split into smaller services
- **Database Sharding**: Distribute data across servers
- **CDN Integration**: Global content delivery
- **Load Balancing**: Handle high traffic
- **Caching Layers**: Redis, Memcached

---

*Tài liệu này được cập nhật thường xuyên. Vui lòng kiểm tra phiên bản mới nhất trước khi thực hiện thay đổi.*
