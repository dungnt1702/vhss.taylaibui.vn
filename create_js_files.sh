#!/bin/bash

# Create ready-vehicles.js
cat > resources/js/ready-vehicles.js << 'READY_JS_EOF'
// Ready Vehicles JavaScript
const vehicleOperations = {
    // Start vehicle timer
    startVehicle: function(vehicleId, duration) {
        if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho xe ${vehicleId} trong ${duration} phút?`)) {
            this.performOperation('/ready-vehicles/start-timer', {
                vehicle_ids: [vehicleId],
                duration: duration
            });
        }
    },

    // Assign route to vehicle
    assignRoute: function(vehicleId) {
        const routeNumber = prompt('Nhập số tuyến đường:');
        if (routeNumber && !isNaN(routeNumber)) {
            this.performOperation('/ready-vehicles/assign-route', {
                vehicle_ids: [vehicleId],
                route_number: parseInt(routeNumber)
            });
        }
    },

    // Move vehicle to workshop
    moveToWorkshop: function(vehicleId) {
        const reason = prompt('Lý do chuyển về xưởng:');
        if (reason) {
            this.performOperation('/ready-vehicles/move-workshop', {
                vehicle_id: vehicleId,
                reason: reason
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ready Vehicles page loaded');
});
READY_JS_EOF

# Create waiting-vehicles.js
cat > resources/js/waiting-vehicles.js << 'WAITING_JS_EOF'
// Waiting Vehicles JavaScript
const vehicleOperations = {
    // Start vehicle timer
    startVehicle: function(vehicleId, duration) {
        if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho xe ${vehicleId} trong ${duration} phút?`)) {
            this.performOperation('/ready-vehicles/start-timer', {
                vehicle_ids: [vehicleId],
                duration: duration
            });
        }
    },

    // Assign route to vehicle
    assignRoute: function(vehicleId) {
        const routeNumber = prompt('Nhập số tuyến đường:');
        if (routeNumber && !isNaN(routeNumber)) {
            this.performOperation('/ready-vehicles/assign-route', {
                vehicle_ids: [vehicleId],
                route_number: parseInt(routeNumber)
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Waiting Vehicles page loaded');
});
WAITING_JS_EOF

# Create running-vehicles.js
cat > resources/js/running-vehicles.js << 'RUNNING_JS_EOF'
// Running Vehicles JavaScript
const vehicleOperations = {
    // Pause vehicle
    pauseVehicle: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn tạm dừng xe ${vehicleId}?`)) {
            this.performOperation(`/api/vehicles/${vehicleId}/pause`, {});
        }
    },

    // Return vehicle to yard
    returnToYard: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn đưa xe ${vehicleId} về bãi?`)) {
            this.performOperation('/api/vehicles/return-yard', {
                vehicle_id: vehicleId
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Running Vehicles page loaded');
    
    // Update remaining time every minute
    setInterval(updateRemainingTimes, 60000);
});

// Update remaining times for all vehicles
function updateRemainingTimes() {
    const timeElements = document.querySelectorAll('[id^="remaining-time-"]');
    timeElements.forEach(element => {
        // This would typically call an API to get updated times
        // For now, just update the display
        console.log('Updating remaining time for:', element.id);
    });
}
RUNNING_JS_EOF

# Create paused-vehicles.js
cat > resources/js/paused-vehicles.js << 'PAUSED_JS_EOF'
// Paused Vehicles JavaScript
const vehicleOperations = {
    // Resume vehicle
    resumeVehicle: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn tiếp tục xe ${vehicleId}?`)) {
            this.performOperation(`/api/vehicles/${vehicleId}/resume`, {});
        }
    },

    // Return vehicle to yard
    returnToYard: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn đưa xe ${vehicleId} về bãi?`)) {
            this.performOperation('/api/vehicles/return-yard', {
                vehicle_id: vehicleId
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Paused Vehicles page loaded');
});
PAUSED_JS_EOF

# Create expired-vehicles.js
cat > resources/js/expired-vehicles.js << 'EXPIRED_JS_EOF'
// Expired Vehicles JavaScript
const vehicleOperations = {
    // Return vehicle to yard
    returnToYard: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn đưa xe ${vehicleId} về bãi?`)) {
            this.performOperation('/api/vehicles/return-yard', {
                vehicle_id: vehicleId
            });
        }
    },

    // Move vehicle to workshop
    moveToWorkshop: function(vehicleId) {
        const reason = prompt('Lý do chuyển về xưởng:');
        if (reason) {
            this.performOperation('/vehicles/move-workshop', {
                vehicle_id: vehicleId,
                reason: reason
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Expired Vehicles page loaded');
});
EXPIRED_JS_EOF

# Create workshop-vehicles.js
cat > resources/js/workshop-vehicles.js << 'WORKSHOP_JS_EOF'
// Workshop Vehicles JavaScript
const vehicleOperations = {
    // Return vehicle to yard
    returnToYard: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn đưa xe ${vehicleId} về bãi?`)) {
            this.performOperation('/api/vehicles/return-yard', {
                vehicle_id: vehicleId
            });
        }
    },

    // Edit vehicle
    editVehicle: function(vehicleId) {
        window.location.href = `/vehicles/${vehicleId}/edit`;
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Workshop Vehicles page loaded');
});
WORKSHOP_JS_EOF

# Create repairing-vehicles.js
cat > resources/js/repairing-vehicles.js << 'REPAIRING_JS_EOF'
// Repairing Vehicles JavaScript
const vehicleOperations = {
    // Complete repair
    completeRepair: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn hoàn thành sửa chữa xe ${vehicleId}?`)) {
            this.performOperation(`/api/vehicles/${vehicleId}/complete-repair`, {});
        }
    },

    // Update repair status
    updateRepairStatus: function(vehicleId) {
        const status = prompt('Cập nhật trạng thái sửa chữa:');
        if (status) {
            this.performOperation(`/api/vehicles/${vehicleId}/update-repair-status`, {
                status: status
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Repairing Vehicles page loaded');
});
REPAIRING_JS_EOF

# Create maintaining-vehicles.js
cat > resources/js/maintaining-vehicles.js << 'MAINTAINING_JS_EOF'
// Maintaining Vehicles JavaScript
const vehicleOperations = {
    // Complete maintenance
    completeMaintenance: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn hoàn thành bảo trì xe ${vehicleId}?`)) {
            this.performOperation(`/api/vehicles/${vehicleId}/complete-maintenance`, {});
        }
    },

    // Update maintenance status
    updateMaintenanceStatus: function(vehicleId) {
        const status = prompt('Cập nhật trạng thái bảo trì:');
        if (status) {
            this.performOperation(`/api/vehicles/${vehicleId}/update-maintenance-status`, {
                status: status
            });
        }
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Maintaining Vehicles page loaded');
});
MAINTAINING_JS_EOF

# Create attributes-list.js
cat > resources/js/attributes-list.js << 'ATTRIBUTES_JS_EOF'
// Attributes List JavaScript
const attributesManager = {
    // Add new attribute
    addAttribute: function(type) {
        const value = prompt(`Nhập giá trị cho ${this.getTypeName(type)}:`);
        if (value) {
            this.performOperation('/api/attributes/add', {
                type: type,
                value: value
            });
        }
    },

    // Delete attribute
    deleteAttribute: function(type, value) {
        if (confirm(`Bạn có chắc muốn xóa ${this.getTypeName(type)} "${value}"?`)) {
            this.performOperation('/api/attributes/delete', {
                type: type,
                value: value
            });
        }
    },

    // Get type name in Vietnamese
    getTypeName: function(type) {
        const typeNames = {
            'color': 'màu sắc',
            'seat': 'số ghế',
            'power': 'công suất',
            'wheel_size': 'kích thước bánh'
        };
        return typeNames[type] || type;
    },

    // Perform AJAX operation
    performOperation: function(url, data) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thực hiện thao tác');
        });
    }
};

// Global functions for onclick handlers
function addAttribute(type) {
    attributesManager.addAttribute(type);
}

function deleteAttribute(type, value) {
    attributesManager.deleteAttribute(type, value);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Attributes List page loaded');
});
ATTRIBUTES_JS_EOF

echo "All JavaScript files created successfully!"
