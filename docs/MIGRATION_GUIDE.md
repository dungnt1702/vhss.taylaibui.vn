# Hướng dẫn Migration và Best Practices

## 🔄 Migration từ cấu trúc cũ

### 1. CSS Migration

#### Trước (Cấu trúc cũ)
```css
/* Inline styles hoặc global CSS */
.vehicle-table { }
.button { }
.status { }
```

#### Sau (Cấu trúc mới)
```css
/* Module-specific với BEM naming */
.vehicles-active__table { }
.vehicles-active__button { }
.vehicles-active__status { }
```

#### Migration Steps
1. **Identify module**: Xác định module nào sử dụng CSS class
2. **Apply BEM naming**: Chuyển đổi sang BEM convention
3. **Move to module CSS**: Di chuyển vào file CSS của module
4. **Update HTML**: Cập nhật class names trong Blade templates
5. **Test**: Kiểm tra không có xung đột

### 2. JavaScript Migration

#### Trước (Cấu trúc cũ)
```javascript
// Global functions
function openModal() { }
function closeModal() { }

// Inline event handlers
<button onclick="openModal()">Open</button>
```

#### Sau (Cấu trúc mới)
```javascript
// Module-based approach
class ActiveVehicles extends VehiclesModule {
    constructor() {
        super('active');
    }
    
    _setupPageSpecificFeatures() {
        this.addEventListener(button, 'click', this.openModal.bind(this));
    }
    
    openModal() { }
}
```

#### Migration Steps
1. **Create module class**: Tạo class extends base module
2. **Move functions**: Di chuyển functions vào module class
3. **Setup event listeners**: Sử dụng addEventListener thay vì onclick
4. **Add namespace**: Đảm bảo có namespace riêng
5. **Test isolation**: Kiểm tra không có xung đột

### 3. Blade Template Migration

#### Trước (Cấu trúc cũ)
```blade
@extends('layouts.app')
@section('content')
    <div class="container">
        <table class="vehicle-table">
            <!-- content -->
        </table>
    </div>
@endsection
```

#### Sau (Cấu trúc mới)
```blade
<div class="vehicles-active">
    <div class="vehicles-active__header">
        <h2 class="vehicles-active__title">Active Vehicles</h2>
    </div>
    <div class="vehicles-active__content">
        <table class="vehicles-active__table">
            <!-- content -->
        </table>
    </div>
</div>
```

## 🎯 Best Practices

### 1. CSS Best Practices

#### ✅ DO
```css
/* Use BEM naming convention */
.vehicles-active__table { }
.vehicles-active__table--loading { }
.vehicles-active__button--primary { }

/* Use Tailwind utilities */
.vehicles-active__container {
    @apply max-w-7xl mx-auto px-4 py-6;
}

/* Responsive design */
@media (max-width: 768px) {
    .vehicles-active__table {
        @apply min-w-full overflow-x-auto;
    }
}
```

#### ❌ DON'T
```css
/* Don't use generic class names */
.table { }
.button { }
.status { }

/* Don't use inline styles */
<div style="color: red;">Text</div>

/* Don't use !important unless necessary */
.button { color: red !important; }
```

### 2. JavaScript Best Practices

#### ✅ DO
```javascript
// Use module pattern
class ActiveVehicles extends VehiclesModule {
    constructor() {
        super('active');
    }
    
    _setupPageSpecificFeatures() {
        this.setupEventListeners();
        this.initializeComponents();
    }
    
    setupEventListeners() {
        this.addEventListener(button, 'click', this.handleClick.bind(this));
    }
    
    handleClick(event) {
        // Handle click
    }
}

// Use proper error handling
try {
    await this.fetchData(url);
} catch (error) {
    console.error(`[${this.namespace}] Error:`, error);
    this.showNotification('Error occurred', 'error');
}
```

#### ❌ DON'T
```javascript
// Don't use global functions
function openModal() { }

// Don't use inline event handlers
<button onclick="openModal()">Open</button>

// Don't forget cleanup
// Always cleanup event listeners
this.cleanupEventListeners();
```

### 3. Blade Template Best Practices

#### ✅ DO
```blade
{{-- Use semantic HTML structure --}}
<div class="vehicles-active">
    <header class="vehicles-active__header">
        <h1 class="vehicles-active__title">Active Vehicles</h1>
    </header>
    
    <main class="vehicles-active__content">
        <table class="vehicles-active__table">
            <thead class="vehicles-active__table-header">
                <tr>
                    <th class="vehicles-active__table-cell">Vehicle</th>
                </tr>
            </thead>
        </table>
    </main>
</div>

{{-- Use conditional rendering --}}
@if($vehicles->count() > 0)
    <div class="vehicles-active__list">
        @foreach($vehicles as $vehicle)
            <div class="vehicles-active__item">
                {{ $vehicle->name }}
            </div>
        @endforeach
    </div>
@else
    <div class="vehicles-active__empty">
        No vehicles found
    </div>
@endif
```

#### ❌ DON'T
```blade
{{-- Don't use inline styles --}}
<div style="color: red; font-size: 16px;">Text</div>

{{-- Don't use generic class names --}}
<div class="container">
    <table class="table">
        <!-- content -->
    </table>
</div>

{{-- Don't mix concerns --}}
<div class="vehicles-active">
    <script>
        // JavaScript should be in separate files
    </script>
    <style>
        /* CSS should be in separate files */
    </style>
</div>
```

## 🔧 Development Workflow

### 1. Adding New Feature

1. **Plan**: Xác định module và components cần thiết
2. **Create**: Tạo CSS, JS, và Blade files
3. **Implement**: Code theo best practices
4. **Test**: Kiểm tra module isolation
5. **Document**: Cập nhật documentation
6. **Review**: Code review trước khi merge

### 2. Modifying Existing Feature

1. **Read**: Đọc documentation và code hiện tại
2. **Analyze**: Phân tích impact của thay đổi
3. **Plan**: Lập kế hoạch thay đổi
4. **Implement**: Thực hiện thay đổi
5. **Test**: Test toàn bộ module
6. **Update**: Cập nhật documentation

### 3. Debugging Issues

1. **Check Console**: Xem JavaScript errors
2. **Check Network**: Xem API calls
3. **Check CSS**: Xem styling issues
4. **Check Namespace**: Đảm bảo không có conflicts
5. **Check Documentation**: Tham khảo docs

## 📊 Testing Strategy

### 1. Module Isolation Testing

```bash
# Run module conflict test
php test_module_isolation.php
```

### 2. Build Testing

```bash
# Test Vite build
npm run build

# Check for build errors
npm run dev
```

### 3. Browser Testing

1. **Desktop**: Chrome, Firefox, Safari
2. **Mobile**: iOS Safari, Chrome Mobile
3. **Responsive**: Test all breakpoints
4. **Performance**: Check loading times

## 🚀 Deployment Checklist

### Pre-deployment

- [ ] All modules tested for conflicts
- [ ] Vite build successful
- [ ] Documentation updated
- [ ] Code reviewed
- [ ] Performance tested

### Post-deployment

- [ ] Monitor for errors
- [ ] Check performance metrics
- [ ] Verify all features working
- [ ] Update documentation if needed

## 🔍 Troubleshooting

### Common Issues

#### 1. CSS Not Loading
```bash
# Clear Laravel cache
php artisan cache:clear
php artisan view:clear

# Rebuild assets
npm run build
```

#### 2. JavaScript Errors
```javascript
// Check namespace conflicts
console.log(window.ModuleLoader);

// Check module initialization
console.log(window.activeVehicles);
```

#### 3. Module Conflicts
```bash
# Run conflict detection
php test_module_isolation.php
```

### Debug Tools

1. **Browser DevTools**: Console, Network, Elements
2. **Laravel Debugbar**: Database queries, routes
3. **Vite DevTools**: Asset bundling, hot reload
4. **Custom Test Scripts**: Module isolation testing

## 📚 Resources

### Documentation
- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)
- [QUICK_REFERENCE.md](QUICK_REFERENCE.md)
- [ARCHITECTURE_OVERVIEW.md](ARCHITECTURE_OVERVIEW.md)

### External Resources
- [BEM Methodology](https://getbem.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Laravel Documentation](https://laravel.com/docs)
- [Vite Documentation](https://vitejs.dev/)

---

*Hướng dẫn này được cập nhật thường xuyên. Vui lòng kiểm tra phiên bản mới nhất.*
