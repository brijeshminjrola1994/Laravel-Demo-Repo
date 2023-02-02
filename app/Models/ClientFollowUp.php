<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ClientDetails;


class ClientFollowUp extends Model
{
    use HasFactory;

    protected $table = 'client_follow_up';
    protected $fillable = ['remark'];
    protected $dates = ['next_follow_up_date', 'created_at'];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
  
    public function client()
    {
        return $this->belongsTo(ClientDetails::class, 'id');
    }
   
}
