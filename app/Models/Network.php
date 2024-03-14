<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;
    protected $fillable = [
        'referral_code',
        'user_id',
        'parent_user_id'
    ]; 
    
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}