<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Ордери для даной сдєлкі
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);   //связь один к одному
    }
}