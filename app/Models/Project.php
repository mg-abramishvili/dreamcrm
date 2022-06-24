<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function calculations()
    {
        return $this->hasMany(Calculation::class, 'project_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function endclient()
    {
        return $this->belongsTo(Client::class, 'end_client_id');
    }

    public function production()
    {
        return $this->hasOne(Production::class);
    }
}
