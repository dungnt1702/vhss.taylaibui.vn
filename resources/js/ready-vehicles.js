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
