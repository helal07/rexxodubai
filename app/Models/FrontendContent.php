<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendContent extends Model
{
    protected $fillable = ['section', 'key', 'type', 'value', 'is_file'];

    public function getParsedValueAttribute()
    {
        if ($this->type === 'json') {
            return json_decode($this->value, true);
        }
        return $this->value;
    }
}
