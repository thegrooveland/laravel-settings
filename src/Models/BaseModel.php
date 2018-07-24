<?php

namespace Grooveland\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $selectFields = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $searchField = 'user';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'user'
    ];

    public function scopeList($query)
    {
        return $query->with('owner');
    }

    public function scopeOne($query, $value, bool $isId = false)
    {
        $query->select($this->selectFields);
        
        if ($isId) {
            $query->where('id', $value);
        } else {
            $query->where($this->searchField, $value);
        }

        return $query->first();
    }
}
