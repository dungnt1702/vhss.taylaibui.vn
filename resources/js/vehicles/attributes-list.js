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
