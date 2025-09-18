# Quick Reference - VHSS Modules

## Module Files Mapping

| Module | View | JS | CSS | Controller |
|--------|------|----|----|-----------|
| ActiveVehicles | `active_vehicles.blade.php` | `ActiveVehicles.js` | `active-vehicles.css` | VehicleManagementController |
| WorkshopVehicles | `workshop_vehicles.blade.php` | `WorkshopVehicles.js` | `workshop-vehicles.css` | VehicleManagementController |
| RunningVehicles | `running_vehicles.blade.php` | `RunningVehicles.js` | `running-vehicles.css` | VehicleManagementController |
| PausedVehicles | `paused_vehicles.blade.php` | `PausedVehicles.js` | `paused-vehicles.css` | VehicleManagementController |
| ExpiredVehicles | `expired_vehicles.blade.php` | `ExpiredVehicles.js` | `expired-vehicles.css` | VehicleManagementController |
| ReadyVehicles | `ready_vehicles.blade.php` | `ReadyVehicles.js` | `ready-vehicles.css` | VehicleManagementController |
| RepairingVehicles | `repairing_vehicles.blade.php` | `RepairingVehicles.js` | `repairing-vehicles.css` | VehicleManagementController |
| VehiclesList | `vehicles_list.blade.php` | `VehiclesList.js` | `vehicles-list.css` | VehicleManagementController |
| AttributesList | `attributes_list.blade.php` | `AttributesList.js` | `attributes-list.css` | VehicleManagementController |
| MaintenanceDashboard | `maintenance/dashboard.blade.php` | `MaintenanceDashboard.js` | `maintenance-dashboard.css` | MaintenanceController |
| MaintenanceSchedules | `maintenance/schedules/*.blade.php` | `MaintenanceSchedules.js` | `maintenance-schedules.css` | MaintenanceScheduleController |
| AuthLogin | `auth/login.blade.php` | `AuthLogin.js` | `auth-login.css` | AuthController |
| AuthRegister | `auth/register.blade.php` | `AuthRegister.js` | `auth-register.css` | AuthController |
| AuthForgotPassword | `auth/forgot-password.blade.php` | `AuthForgotPassword.js` | `auth-forgot-password.css` | AuthController |
| UserManagement | `users/*.blade.php` | N/A | N/A | UserManagementController |
| RoleManagement | `roles/*.blade.php` | N/A | N/A | RoleManagementController |

## Key Functions by Module

### ActiveVehicles
- `setupSectionsVisibility()` - Show all sections
- `setupStatsUpdater()` - Update vehicle counts
- `updateTimerVehiclesTable()` - Update timer table
- `populateTimerTable()` - Populate timer data
- `assignTimerBulk()` - Bulk assign timer
- `assignRouteBulk()` - Bulk assign route
- `moveToWorkshopBulk()` - Bulk move to workshop
- `returnSelectedVehiclesToYard()` - Return to yard

### WorkshopVehicles
- `updateCategoryOptions()` - Update category dropdown
- `setupTechnicalUpdateModal()` - Setup technical modal

### RepairingVehicles
- `moveToTesting()` - Move vehicle to testing
- `moveToMaintenance()` - Move vehicle to maintenance
- `orderParts()` - Order parts for vehicle

### MaintenanceDashboard
- `setupStatsCards()` - Setup statistics cards
- `setupRecentSchedules()` - Setup recent schedules
- `setupQuickActions()` - Setup quick action buttons
- `setupCharts()` - Setup Chart.js visualizations
- `handleStatCardClick()` - Handle stat card clicks
- `viewScheduleDetails()` - View schedule details
- `handleQuickAction()` - Handle quick actions

### MaintenanceSchedules
- `setupCalendar()` - Setup calendar functionality
- `setupScheduleList()` - Setup schedule list
- `setupFilters()` - Setup filtering
- `setupModals()` - Setup modals
- `navigateMonth()` - Calendar navigation
- `handleDayClick()` - Handle day selection
- `handleScheduleAction()` - Handle schedule actions
- `applyFilter()` - Apply status filters

### AuthLogin
- `setupFormValidation()` - Setup form validation
- `setupFormSubmission()` - Setup form submission
- `setupSocialLogin()` - Setup social login buttons
- `setupRememberMe()` - Setup remember me functionality
- `setupPasswordVisibility()` - Setup password visibility toggle
- `validateEmailField()` - Validate email input
- `validatePasswordField()` - Validate password input
- `handleLogin()` - Handle login form submission

### AuthRegister
- `setupFormValidation()` - Setup form validation
- `setupFormSubmission()` - Setup form submission
- `setupPasswordStrength()` - Setup password strength indicator
- `setupPasswordConfirmation()` - Setup password confirmation
- `setupTermsAcceptance()` - Setup terms acceptance
- `validateField()` - Validate individual field
- `handleRegister()` - Handle registration form submission

### AuthForgotPassword
- `setupFormValidation()` - Setup form validation
- `setupFormSubmission()` - Setup form submission
- `setupBackToLogin()` - Setup back to login functionality
- `validateEmailField()` - Validate email input
- `handleForgotPassword()` - Handle forgot password form submission
- `showSuccessMessage()` - Show success message

### User & Role Management
- `User::isAdmin()` - Check if user is admin
- `User::hasPermission()` - Check user permission
- `Role::hasPermission()` - Check role permission

## Documentation Quick Access

### Main Documentation
- [README.md](README.md) - Main documentation index
- [ARCHITECTURE_OVERVIEW.md](ARCHITECTURE_OVERVIEW.md) - System architecture
- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - Detailed structure
- [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) - Migration guide

### Module Documentation
- [MODULES/vehicles/structure.md](MODULES/vehicles/structure.md) - Vehicles structure
- [MODULES/vehicles/functions.md](MODULES/vehicles/functions.md) - Vehicles functions
- [MODULES/maintenance/structure.md](MODULES/maintenance/structure.md) - Maintenance structure
- [MODULES/maintenance/functions.md](MODULES/maintenance/functions.md) - Maintenance functions
- [MODULES/auth/structure.md](MODULES/auth/structure.md) - Auth structure
- [MODULES/auth/functions.md](MODULES/auth/functions.md) - Auth functions

### Component Documentation
- [COMPONENTS/modals.md](COMPONENTS/modals.md) - Modal components
- [COMPONENTS/forms.md](COMPONENTS/forms.md) - Form components
- [COMPONENTS/tables.md](COMPONENTS/tables.md) - Table components

## Routes

### Vehicle Management
- `/vehicles/active` - Active vehicles
- `/vehicles/workshop` - Workshop vehicles
- `/vehicles/running` - Running vehicles
- `/vehicles/paused` - Paused vehicles
- `/vehicles/expired` - Expired vehicles
- `/vehicles/ready` - Ready vehicles
- `/vehicles/repairing` - Repairing vehicles
- `/vehicles/list` - Vehicles list
- `/vehicles/attributes` - Attributes list

### Maintenance
- `/maintenance/dashboard` - Maintenance dashboard
- `/maintenance/schedules` - Maintenance schedules
- `/maintenance/schedules/calendar` - Maintenance calendar
- `/maintenance/schedules/create` - Create schedule

### User & Role Management
- `/roles` - Role management
- `/users` - User management
- `/profile` - User profile

## Modal Files
- `active_modals.blade.php` - Active vehicle modals
- `workshop_modals.blade.php` - Workshop modals
- `running_modals.blade.php` - Running vehicle modals
- `paused_modals.blade.php` - Paused vehicle modals
- `expired_modals.blade.php` - Expired vehicle modals
- `ready_modals.blade.php` - Ready vehicle modals
- `repairing_modals.blade.php` - Repairing modals

## API Endpoints
- `POST /api/vehicles/assign-timer` - Assign timer
- `POST /api/vehicles/assign-route` - Assign route
- `POST /api/vehicles/move-workshop` - Move to workshop
- `POST /api/vehicles/return-yard` - Return to yard
- `POST /api/vehicles/pause-timer` - Pause timer
- `POST /api/vehicles/resume-timer` - Resume timer
- `POST /api/vehicles/add-time` - Add time

## Check Before Modifying
1. Read this quick reference
2. Check related functions in VehicleBase
3. Verify CSS/JS isolation
4. Test cross-module compatibility
5. Update documentation if needed
