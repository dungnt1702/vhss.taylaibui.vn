// Paused Vehicles JavaScript
const vehicleOperations = {
    // Resume vehicle
    resumeVehicle: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn tiếp tục xe ${vehicleId}?`)) {
            this.performOperation(`/api/active-vehicles/${vehicleId}/resume`, {});
        }
    },

    // Return vehicle to yard
    returnToYard: function(vehicleId) {
        if (confirm(`Bạn có chắc muốn đưa xe ${vehicleId} về bãi?`)) {
            this.performOperation('/api/active-vehicles/return-yard', {
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
