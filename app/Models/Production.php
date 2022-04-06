<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'project_id',
        'user_id',
        'name',
        'priority',
        'status',
        'invoice_number',
        'payment_type',
        'serial_number',
        'activation_key',
        'supply_info',
        'start_date',
        'end_date',
        'description',
        'additional_hardware',
        'additional_for_tribune',
        'contacts',
        'email',
        'ral',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ProductionItem::class);
    }
}
