#!/bin/bash

# Create waiting-vehicles.css
cat > resources/css/waiting-vehicles.css << 'WAITING_CSS_EOF'
/* Waiting Vehicles CSS */
.waiting-vehicles-container {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #f59e0b;
}

.vehicle-card.waiting {
    border-left-color: #f59e0b;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-success {
    @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-info {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-waiting {
    @apply bg-yellow-100 text-yellow-800;
}
WAITING_CSS_EOF

# Create running-vehicles.css
cat > resources/css/running-vehicles.css << 'RUNNING_CSS_EOF'
/* Running Vehicles CSS */
.running-vehicles-container {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #3b82f6;
}

.vehicle-card.running {
    border-left-color: #3b82f6;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-warning {
    @apply bg-yellow-600 text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2;
}

.btn-info {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-running {
    @apply bg-blue-100 text-blue-800;
}

.remaining-time {
    font-weight: bold;
    color: #3b82f6;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}
RUNNING_CSS_EOF

# Create paused-vehicles.css
cat > resources/css/paused-vehicles.css << 'PAUSED_CSS_EOF'
/* Paused Vehicles CSS */
.paused-vehicles-container {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #6b7280;
}

.vehicle-card.paused {
    border-left-color: #6b7280;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-success {
    @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-info {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-paused {
    @apply bg-gray-100 text-gray-800;
}

.paused-time {
    font-weight: bold;
    color: #6b7280;
}
PAUSED_CSS_EOF

# Create expired-vehicles.css
cat > resources/css/expired-vehicles.css << 'EXPIRED_CSS_EOF'
/* Expired Vehicles CSS */
.expired-vehicles-container {
    background: linear-gradient(135deg, #fff7ed 0%, #fed7aa 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #f97316;
}

.vehicle-card.expired {
    border-left-color: #f97316;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-success {
    @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-warning {
    @apply bg-yellow-600 text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-expired {
    @apply bg-orange-100 text-orange-800;
}
EXPIRED_CSS_EOF

# Create workshop-vehicles.css
cat > resources/css/workshop-vehicles.css << 'WORKSHOP_CSS_EOF'
/* Workshop Vehicles CSS */
.workshop-vehicles-container {
    background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #ef4444;
}

.vehicle-card.workshop {
    border-left-color: #ef4444;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-success {
    @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-info {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-workshop {
    @apply bg-red-100 text-red-800;
}
WORKSHOP_CSS_EOF

# Create repairing-vehicles.css
cat > resources/css/repairing-vehicles.css << 'REPAIRING_CSS_EOF'
/* Repairing Vehicles CSS */
.repairing-vehicles-container {
    background: linear-gradient(135deg, #faf5ff 0%, #e9d5ff 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #a855f7;
}

.vehicle-card.repairing {
    border-left-color: #a855f7;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-success {
    @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-info {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-repairing {
    @apply bg-purple-100 text-purple-800;
}
REPAIRING_CSS_EOF

# Create maintaining-vehicles.css
cat > resources/css/maintaining-vehicles.css << 'MAINTAINING_CSS_EOF'
/* Maintaining Vehicles CSS */
.maintaining-vehicles-container {
    background: linear-gradient(135deg, #f0f9ff 0%, #c7d2fe 100%);
    min-height: 100vh;
}

.vehicle-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.vehicle-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: #6366f1;
}

.vehicle-card.maintaining {
    border-left-color: #6366f1;
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-success {
    @apply bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2;
}

.btn-info {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.status-badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.status-maintaining {
    @apply bg-indigo-100 text-indigo-800;
}
MAINTAINING_CSS_EOF

# Create attributes-list.css
cat > resources/css/attributes-list.css << 'ATTRIBUTES_CSS_EOF'
/* Attributes List CSS */
.attributes-container {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    min-height: 100vh;
}

.attribute-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.attribute-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.btn {
    @apply inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md transition-colors duration-200;
}

.btn-sm {
    @apply px-2 py-1 text-xs;
}

.btn-primary {
    @apply bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}

.btn-secondary {
    @apply bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
}

.attribute-item {
    @apply flex items-center justify-between p-2 bg-neutral-50 rounded;
}

.attribute-value {
    @apply text-sm text-neutral-700;
}

.delete-btn {
    @apply text-red-500 hover:text-red-700;
}

.modal {
    @apply fixed inset-0 bg-black bg-opacity-50 z-50;
}

.modal-content {
    @apply bg-white rounded-lg shadow-xl max-w-md w-full;
}
ATTRIBUTES_CSS_EOF

echo "All CSS files created successfully!"
