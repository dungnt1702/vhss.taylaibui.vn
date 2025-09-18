# VHSS Project Structure Documentation

## Overview
Hệ thống quản lý xe với các module riêng biệt, mỗi module có CSS/JS riêng để tránh xung đột.

## Core Architecture

### Base Classes
- **VehicleBase.js** - Base class cho tất cả vehicle modules
  - Chứa: Common functions, modal management, API calls
  - Được extend bởi: ActiveVehicles, WorkshopVehicles, RunningVehicles, etc.

- **MaintenanceModule.js** - Base class cho tất cả maintenance modules
  - Chứa: Common functions, modal management, API calls
  - Được extend bởi: MaintenanceDashboard, MaintenanceSchedules, etc.

- **AuthModule.js** - Base class cho tất cả authentication modules
  - Chứa: Form validation, loading states, error handling, API calls
  - Được extend bởi: AuthLogin, AuthRegister, AuthForgotPassword, etc.

### Vehicle Management System

#### 1. ActiveVehicles Module
**Files:**
- `resources/views/vehicles/active_vehicles.blade.php` - Main view
- `resources/js/vehicles/ActiveVehicles.js` - Logic
- `resources/css/vehicles/active-vehicles.css` - Styling

**Key Functions:**
- `init()` - Initialize page
- `setupActiveSpecificFeatures()` - Setup specific features
- `setupSectionsVisibility()` - Show all sections by default
- `setupStatsUpdater()` - Update vehicle counts
- `updateTimerVehiclesTable()` - Update timer table
- `populateTimerTable()` - Populate timer table with data
- `setupRowClickHandlers()` - Handle table row clicks
- `assignTimerBulk()` - Bulk assign timer
- `assignRouteBulk()` - Bulk assign route
- `moveToWorkshopBulk()` - Bulk move to workshop
- `returnSelectedVehiclesToYard()` - Return vehicles to yard

**Dependencies:**
- Extends VehicleBase
- Uses global modal functions from VehicleBase
- Interacts with: Ready vehicles, Timer vehicles, Routing vehicles tables

#### 2. WorkshopVehicles Module
**Files:**
- `resources/views/vehicles/workshop_vehicles.blade.php`
- `resources/js/vehicles/WorkshopVehicles.js`
- `resources/css/vehicles/workshop-vehicles.css`

**Key Functions:**
- `init()` - Initialize workshop page
- `setupWorkshopSpecificFeatures()` - Setup workshop features
- `updateCategoryOptions()` - Update category dropdown
- `setupTechnicalUpdateModal()` - Setup technical update modal

#### 3. RunningVehicles Module
**Files:**
- `resources/views/vehicles/running_vehicles.blade.php`
- `resources/js/vehicles/RunningVehicles.js`
- `resources/css/vehicles/running-vehicles.css`

#### 4. PausedVehicles Module
**Files:**
- `resources/views/vehicles/paused_vehicles.blade.php`
- `resources/js/vehicles/PausedVehicles.js`
- `resources/css/vehicles/paused-vehicles.css`

#### 5. ExpiredVehicles Module
**Files:**
- `resources/views/vehicles/expired_vehicles.blade.php`
- `resources/js/vehicles/ExpiredVehicles.js`
- `resources/css/vehicles/expired-vehicles.css`

#### 6. ReadyVehicles Module
**Files:**
- `resources/views/vehicles/ready_vehicles.blade.php`
- `resources/js/vehicles/ReadyVehicles.js`
- `resources/css/vehicles/ready-vehicles.css`

#### 7. RepairingVehicles Module
**Files:**
- `resources/views/vehicles/repairing_vehicles.blade.php`
- `resources/js/vehicles/RepairingVehicles.js`
- `resources/css/vehicles/repairing-vehicles.css`

**Key Functions:**
- `moveToTesting()` - Move vehicle to testing
- `moveToMaintenance()` - Move vehicle to maintenance
- `orderParts()` - Order parts for vehicle

#### 8. VehiclesList Module
**Files:**
- `resources/views/vehicles/vehicles_list.blade.php`
- `resources/js/vehicles/VehiclesList.js`
- `resources/css/vehicles/vehicles-list.css`

#### 9. AttributesList Module
**Files:**
- `resources/views/vehicles/attributes_list.blade.php`
- `resources/js/vehicles/AttributesList.js`
- `resources/css/vehicles/attributes-list.css`

#### 10. MaintenanceSchedule Module
**Files:**
- `resources/views/maintenance/schedules/index.blade.php` - Main schedules list
- `resources/views/maintenance/schedules/calendar.blade.php` - Calendar view
- `resources/views/maintenance/schedules/create.blade.php` - Create schedule
- `resources/views/maintenance/schedules/show.blade.php` - Show schedule details
- `resources/views/maintenance/schedules/perform.blade.php` - Perform maintenance
- `resources/views/maintenance/dashboard.blade.php` - Maintenance dashboard
- `app/Http/Controllers/MaintenanceScheduleController.php` - Controller

**Key Functions:**
- `index()` - List maintenance schedules
- `calendar()` - Show calendar view
- `create()` - Show create form
- `store()` - Store new schedule
- `show()` - Show schedule details
- `perform()` - Show perform maintenance form
- `storeRecord()` - Store maintenance record

**Models:**
- `MaintenanceSchedule` - Main schedule model
- `MaintenanceType` - Maintenance type model
- `MaintenanceRecord` - Maintenance record model
- `MaintenanceTask` - Maintenance task model

## Main Controller & Views

### VehicleManagementController
**File:** `app/Http/Controllers/vehicles/VehicleManagementController.php`
**Routes:** `/vehicles/{filter}` where filter = active, workshop, running, paused, expired, ready, repairing, list, attributes

### MaintenanceScheduleController
**File:** `app/Http/Controllers/MaintenanceScheduleController.php`
**Routes:** `/maintenance/*` - All maintenance-related routes

### Main View Structure
**File:** `resources/views/vehicles/vehicles_management.blade.php`
- Extends `layouts.app`
- Conditionally includes child views based on `$filter`
- Loads specific CSS/JS based on filter
- Includes modals based on filter

## User & Role Management System

### User Model
**File:** `app/Models/User.php`
**Key Methods:**
- `isAdmin()` - Check if user is admin
- `isUser()` - Check if user is regular user
- `canManageVehicles()` - Check vehicle management permission
- `canManageVehicleAttributes()` - Check attributes management permission
- `hasPermission(string $permission)` - Check specific permission
- `hasAnyPermission(array $permissions)` - Check any permission
- `hasAllPermissions(array $permissions)` - Check all permissions

### Role Model
**File:** `app/Models/Role.php`
**Key Methods:**
- `hasPermission(string $permission)` - Check role permission
- `permissions()` - Get role permissions

### Permission Model
**File:** `app/Models/Permission.php`
**Key Methods:**
- `roles()` - Get roles with this permission

### Role Management Controller
**File:** `app/Http/Controllers/RoleManagementController.php`
**Key Functions:**
- `index()` - List roles
- `create()` - Show create role form
- `store()` - Store new role
- `edit()` - Show edit role form
- `update()` - Update role
- `destroy()` - Delete role
- `permissions()` - Show role permissions
- `updatePermissions()` - Update role permissions
- `users()` - Show role users
- `assignRole()` - Assign role to user

### User Management Controller
**File:** `app/Http/Controllers/UserManagementController.php`
**Key Functions:**
- `index()` - List users
- `create()` - Show create user form
- `store()` - Store new user
- `edit()` - Show edit user form
- `update()` - Update user
- `destroy()` - Delete user
- `assignRole()` - Assign role to user

### Middleware
**Files:**
- `app/Http/Middleware/AdminMiddleware.php` - Admin-only access
- `app/Http/Middleware/RoleMiddleware.php` - Role-based access

**Usage:**
- `AdminMiddleware` - Requires admin role
- `RoleMiddleware` - Requires specific roles (e.g., 'admin', 'user')

## Modal System

### Global Modals (VehicleBase.js)
- `openStatusModal()` - Open status modal
- `showNotificationModal()` - Show notification modal
- `closeNotificationModal()` - Close notification modal
- `openVehicleDetailModal()` - Open vehicle detail modal
- `openWorkshopModal()` - Open workshop modal
- `closeMoveWorkshopModal()` - Close move workshop modal
- `closeEditNotesModal()` - Close edit notes modal

### Filter-specific Modals
- **Active:** `resources/views/vehicles/partials/modals/active_modals.blade.php`
- **Workshop:** `resources/views/vehicles/partials/modals/workshop_modals.blade.php`
- **Running:** `resources/views/vehicles/partials/modals/running_modals.blade.php`
- **Paused:** `resources/views/vehicles/partials/modals/paused_modals.blade.php`
- **Expired:** `resources/views/vehicles/partials/modals/expired_modals.blade.php`
- **Ready:** `resources/views/vehicles/partials/modals/ready_modals.blade.php`
- **Repairing:** `resources/views/vehicles/partials/modals/repairing_modals.blade.php`

## CSS Architecture

### Global CSS
- `resources/css/app.css` - Main app styles
- `resources/css/vehicles/vehicles-list.css` - Common vehicle list styles

### Module-specific CSS
Each module has its own CSS file following pattern:
`resources/css/vehicles/{module-name}.css`

## JavaScript Architecture

### Global Functions
- `resources/js/vehicles/VehicleBase.js` - Base class and global functions
- `resources/js/app.js` - App-wide JavaScript

### Module-specific JavaScript
Each module has its own JS file following pattern:
`resources/js/vehicles/{ModuleName}.js`

## API Endpoints

### Vehicle Operations
- `POST /api/vehicles/assign-timer` - Assign timer to vehicles
- `POST /api/vehicles/assign-route` - Assign route to vehicles
- `POST /api/vehicles/move-workshop` - Move vehicles to workshop
- `POST /api/vehicles/return-yard` - Return vehicles to yard
- `POST /api/vehicles/pause-timer` - Pause vehicle timer
- `POST /api/vehicles/resume-timer` - Resume vehicle timer
- `POST /api/vehicles/add-time` - Add time to vehicle

## Maintenance Management System

### 1. MaintenanceDashboard Module
**Files:**
- `resources/views/maintenance/dashboard.blade.php` - Main dashboard
- `resources/js/maintenance/MaintenanceDashboard.js` - Dashboard logic
- `resources/css/maintenance/maintenance-dashboard.css` - Dashboard styling

**Key Functions:**
- `init()` - Initialize dashboard
- `setupStatsCards()` - Setup statistics cards
- `setupRecentSchedules()` - Setup recent schedules
- `setupQuickActions()` - Setup quick action buttons
- `setupCharts()` - Setup Chart.js visualizations
- `handleStatCardClick()` - Handle stat card clicks
- `viewScheduleDetails()` - View schedule details
- `handleQuickAction()` - Handle quick actions

**Dependencies:**
- Extends MaintenanceModule
- Uses Chart.js for visualizations
- Interacts with: MaintenanceSchedules, API endpoints

### 2. MaintenanceSchedules Module
**Files:**
- `resources/views/maintenance/schedules/calendar.blade.php` - Calendar view
- `resources/views/maintenance/schedules/index.blade.php` - Schedule list
- `resources/views/maintenance/schedules/create.blade.php` - Create schedule
- `resources/views/maintenance/schedules/show.blade.php` - Schedule details
- `resources/js/maintenance/MaintenanceSchedules.js` - Schedule logic
- `resources/css/maintenance/maintenance-schedules.css` - Schedule styling

**Key Functions:**
- `init()` - Initialize schedules page
- `setupCalendar()` - Setup calendar functionality
- `setupScheduleList()` - Setup schedule list
- `setupFilters()` - Setup filtering
- `setupModals()` - Setup modals
- `navigateMonth()` - Calendar navigation
- `handleDayClick()` - Handle day selection
- `handleScheduleAction()` - Handle schedule actions
- `applyFilter()` - Apply status filters

**Dependencies:**
- Extends MaintenanceModule
- Interacts with: Calendar, Schedule list, Modals

### Authentication Management System

#### 1. AuthLogin Module
**Files:**
- `resources/views/auth/login.blade.php` - Login page
- `resources/js/auth/AuthLogin.js` - Login logic
- `resources/css/auth/auth-login.css` - Login styling

**Key Functions:**
- `init()` - Initialize login page
- `setupFormValidation()` - Setup form validation
- `setupFormSubmission()` - Setup form submission
- `setupSocialLogin()` - Setup social login buttons
- `setupRememberMe()` - Setup remember me functionality
- `setupPasswordVisibility()` - Setup password visibility toggle
- `validateEmailField()` - Validate email input
- `validatePasswordField()` - Validate password input
- `handleLogin()` - Handle login form submission
- `handleSocialLogin()` - Handle social login
- `togglePasswordVisibility()` - Toggle password visibility

**Dependencies:**
- Extends AuthModule
- Interacts with: Login form, Social providers, API endpoints

#### 2. AuthRegister Module
**Files:**
- `resources/views/auth/register.blade.php` - Registration page
- `resources/js/auth/AuthRegister.js` - Registration logic
- `resources/css/auth/auth-register.css` - Registration styling

**Key Functions:**
- `init()` - Initialize registration page
- `setupFormValidation()` - Setup form validation
- `setupFormSubmission()` - Setup form submission
- `setupPasswordStrength()` - Setup password strength indicator
- `setupPasswordConfirmation()` - Setup password confirmation
- `setupTermsAcceptance()` - Setup terms acceptance
- `validateField()` - Validate individual field
- `validateNameField()` - Validate name input
- `validatePhoneField()` - Validate phone input
- `handleRegister()` - Handle registration form submission
- `updatePasswordStrength()` - Update password strength indicator

**Dependencies:**
- Extends AuthModule
- Interacts with: Registration form, Password strength, API endpoints

#### 3. AuthForgotPassword Module
**Files:**
- `resources/views/auth/forgot-password.blade.php` - Forgot password page
- `resources/js/auth/AuthForgotPassword.js` - Forgot password logic
- `resources/css/auth/auth-forgot-password.css` - Forgot password styling

**Key Functions:**
- `init()` - Initialize forgot password page
- `setupFormValidation()` - Setup form validation
- `setupFormSubmission()` - Setup form submission
- `setupBackToLogin()` - Setup back to login functionality
- `validateEmailField()` - Validate email input
- `handleForgotPassword()` - Handle forgot password form submission
- `showSuccessMessage()` - Show success message
- `showBackToLoginButton()` - Show back to login button

**Dependencies:**
- Extends AuthModule
- Interacts with: Forgot password form, API endpoints

## Database Tables
- `vehicles` - Main vehicle table
- `vehicle_statuses` - Vehicle status tracking
- `vehicle_routes` - Vehicle route assignments
- `users` - User management
- `roles` - Role definitions
- `maintenance_schedules` - Maintenance scheduling
- `maintenance_records` - Maintenance history
- `maintenance_types` - Types of maintenance
- `maintenance_technicians` - Technician assignments

## Documentation Structure

### Main Documentation
- `docs/README.md` - Main documentation index
- `docs/ARCHITECTURE_OVERVIEW.md` - System architecture overview
- `docs/PROJECT_STRUCTURE.md` - Detailed project structure (this file)
- `docs/QUICK_REFERENCE.md` - Quick reference for developers
- `docs/MIGRATION_GUIDE.md` - Migration guide and best practices

### Module Documentation
- `docs/MODULES/vehicles/structure.md` - Vehicles module structure
- `docs/MODULES/vehicles/functions.md` - Vehicles module functions
- `docs/MODULES/maintenance/structure.md` - Maintenance module structure
- `docs/MODULES/maintenance/functions.md` - Maintenance module functions
- `docs/MODULES/auth/structure.md` - Auth module structure
- `docs/MODULES/auth/functions.md` - Auth module functions

### Component Documentation
- `docs/COMPONENTS/modals.md` - Modal components
- `docs/COMPONENTS/forms.md` - Form components
- `docs/COMPONENTS/tables.md` - Table components

### Integration Documentation
- `docs/INTEGRATION/api.md` - API integration guide
- `docs/INTEGRATION/database.md` - Database integration
- `docs/INTEGRATION/frontend.md` - Frontend integration

## Key Dependencies

### JavaScript Dependencies
- All vehicle modules extend VehicleBase
- Global modal functions defined in VehicleBase
- Each module initializes on DOMContentLoaded

### CSS Dependencies
- Tailwind CSS for base styling
- Module-specific CSS for custom styling
- Responsive design with mobile-first approach

## Development Guidelines

### When Modifying a Module:
1. Check this documentation for related functions
2. Verify CSS/JS isolation
3. Test cross-module compatibility
4. Update documentation if structure changes

### File Naming Conventions:
- Views: `{module_name}.blade.php`
- JavaScript: `{ModuleName}.js` (PascalCase)
- CSS: `{module-name}.css` (kebab-case)
- Controllers: `{ModuleName}Controller.php`

## Last Updated
2024-12-19 - Initial structure documentation
