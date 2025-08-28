// Ready Vehicles JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ready Vehicles page loaded');
    
    // Initialize event listeners
    initializeEventListeners();
});

function initializeEventListeners() {
    // Start timer buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-action="start-timer"]')) {
            const vehicleId = e.target.dataset.vehicleId;
            const duration = parseInt(e.target.dataset.duration);
            startTimer(vehicleId, duration);
        }
        
        if (e.target.matches('[data-action="start-timer-bulk"]')) {
            const duration = parseInt(e.target.dataset.duration);
            startTimerBulk(duration);
        }
        
        if (e.target.matches('[data-action="assign-route"]')) {
            const vehicleId = e.target.dataset.vehicleId;
            assignRoute(vehicleId);
        }
        
        if (e.target.matches('[data-action="assign-route-bulk"]')) {
            assignRouteBulk();
        }
        
        if (e.target.matches('[data-action="assign-route-selected"]')) {
            assignRouteSelected();
        }
        
        if (e.target.matches('[data-action="move-workshop"]')) {
            const vehicleId = e.target.dataset.vehicleId;
            moveToWorkshop(vehicleId);
        }
        
        if (e.target.matches('[data-action="move-workshop-bulk"]')) {
            moveToWorkshopBulk();
        }
        
        if (e.target.matches('[data-action="return-to-yard"]')) {
            returnToYard();
        }
        
        if (e.target.matches('[data-action="close-notification"]')) {
            closeNotification();
        }
    });
    
    // Select all checkboxes
    const selectAllWaiting = document.getElementById('select-all-waiting');
    if (selectAllWaiting) {
        selectAllWaiting.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.vehicle-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    const selectAllTimer = document.getElementById('select-all-timer');
    if (selectAllTimer) {
        selectAllTimer.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('#timer-vehicles input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
}

function startTimer(vehicleId, duration) {
    if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho xe ${vehicleId} trong ${duration} phút?`)) {
        performOperation('/ready-vehicles/start-timer', {
            vehicle_ids: [vehicleId],
            duration: duration
        });
    }
}

function startTimerBulk(duration) {
    const selectedVehicles = getSelectedVehicles();
    if (selectedVehicles.length === 0) {
        alert('Vui lòng chọn ít nhất một xe.');
        return;
    }
    
    if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho ${selectedVehicles.length} xe trong ${duration} phút?`)) {
        performOperation('/ready-vehicles/start-timer', {
            vehicle_ids: selectedVehicles,
            duration: duration
        });
    }
}

function assignRoute(vehicleId) {
    const routeNumber = prompt('Nhập số tuyến đường:');
    if (routeNumber && !isNaN(routeNumber)) {
        performOperation('/ready-vehicles/assign-route', {
            vehicle_ids: [vehicleId],
            route_number: parseInt(routeNumber)
        });
    }
}

function assignRouteBulk() {
    const selectedVehicles = getSelectedVehicles();
    if (selectedVehicles.length === 0) {
        alert('Vui lòng chọn ít nhất một xe.');
        return;
    }
    
    const routeNumber = prompt('Nhập số tuyến đường:');
    if (routeNumber && !isNaN(routeNumber)) {
        performOperation('/ready-vehicles/assign-route', {
            vehicle_ids: selectedVehicles,
            route_number: parseInt(routeNumber)
        });
    }
}

function assignRouteSelected() {
    const routeNumber = document.getElementById('route-select').value;
    const selectedVehicles = getSelectedVehicles();
    
    if (selectedVehicles.length === 0) {
        alert('Vui lòng chọn ít nhất một xe.');
        return;
    }
    
    if (confirm(`Bạn có chắc muốn phân tuyến ${selectedVehicles.length} xe cho đường ${routeNumber}?`)) {
        performOperation('/ready-vehicles/assign-route', {
            vehicle_ids: selectedVehicles,
            route_number: parseInt(routeNumber)
        });
    }
}

function moveToWorkshop(vehicleId) {
    const reason = prompt('Lý do chuyển về xưởng:');
    if (reason) {
        performOperation('/ready-vehicles/move-workshop', {
            vehicle_id: vehicleId,
            reason: reason
        });
    }
}

function moveToWorkshopBulk() {
    const selectedVehicles = getSelectedVehicles();
    if (selectedVehicles.length === 0) {
        alert('Vui lòng chọn ít nhất một xe.');
        return;
    }
    
    const reason = prompt('Lý do chuyển về xưởng:');
    if (reason) {
        performOperation('/ready-vehicles/move-workshop', {
            vehicle_ids: selectedVehicles,
            reason: reason
        });
    }
}

function returnToYard() {
    if (confirm('Bạn có chắc muốn đưa tất cả xe về bãi?')) {
        performOperation('/ready-vehicles/return-yard', {});
    }
}

function closeNotification() {
    const popup = document.getElementById('notification-popup');
    const overlay = document.getElementById('popup-overlay');
    if (popup) popup.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
}

function getSelectedVehicles() {
    const checkboxes = document.querySelectorAll('.vehicle-checkbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

function performOperation(url, data) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification(result.message || 'Thao tác thành công!');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(result.message || 'Có lỗi xảy ra!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra khi thực hiện thao tác', 'error');
    });
}

function showNotification(message, type = 'success') {
    const popup = document.getElementById('notification-popup');
    const overlay = document.getElementById('popup-overlay');
    const messageEl = document.getElementById('notification-message');
    
    if (messageEl) messageEl.textContent = message;
    if (popup) popup.style.display = 'block';
    if (overlay) overlay.style.display = 'block';
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        closeNotification();
    }, 3000);
}
