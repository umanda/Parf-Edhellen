<?php

namespace App\Models;

class Role extends ModelBase
{
    public function scopeForAccount($query, Account $account) 
    {
        $query->join('account_role_rels as ag', 'id', '=', 'ag.role_id')
            ->where('ag.account_id', $account->id)
            ->select('name', 'id');
    }
}
