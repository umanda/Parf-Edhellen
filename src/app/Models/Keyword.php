<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    public function scopeFindByWord($query, string $word, $reversed = false) 
    {
        $query->distinct()
            ->where($reversed ? 'reversed_normalized_keyword' : 'normalized_keyword', 'like', $word)
            ->whereNotNull('sense_id')
            ->orderBy('keyword', 'asc');
    }
}
