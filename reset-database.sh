#!/bin/bash

echo "Resetting database with fresh data..."
echo

echo "Running migrations..."
php artisan migrate:fresh --seed

echo
echo "Database reset completed!"
echo
echo "Data summary:"
php artisan tinker --execute="echo 'Users: ' . App\Models\User::count() . '\n'; echo 'Vehicles: ' . App\Models\Vehicle::count() . '\n'; echo 'Roles: ' . App\Models\Role::count() . '\n'; echo 'Permissions: ' . App\Models\Permission::count() . '\n'; echo 'Vehicle Attributes: ' . App\Models\VehicleAttribute::count() . '\n';"

echo
echo "All demo users have password: 12345678"
echo
