<?php

namespace App\Models;

class Keyword extends ModelBase
{
    public function scopeFindByWord($query, string $word, $reversed = false) 
    {
        $query->distinct()
            ->where($reversed ? 'reversed_normalized_keyword_unaccented' : 'normalized_keyword_unaccented', 'like', $word)
            ->whereNotNull('sense_id');
    }
}
