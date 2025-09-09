<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_type_id',
        'task_name',
        'description',
        'is_required',
        'sort_order'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }

    // Scopes
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_required', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}