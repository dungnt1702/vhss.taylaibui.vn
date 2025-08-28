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
