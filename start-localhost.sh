#!/bin/bash

echo "🚀 Starting Laravel development server..."

# Kill any existing PHP processes on port 8000
pkill -f "php artisan serve" 2>/dev/null || true

# Wait a moment
sleep 1

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000

echo "🛑 Server stopped"

