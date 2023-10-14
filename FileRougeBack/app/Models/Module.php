<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    use HasFactory;

    public function user(){
        return $this->BelongsToMany(User::class,"user_modules")->withPivot('id');
    }


    protected $guarded = [];
}
