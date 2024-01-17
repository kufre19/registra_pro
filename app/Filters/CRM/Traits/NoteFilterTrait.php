<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait NoteFilterTrait
{



    public function last_comment_date($ids = null)
    {
        $comments = explode(',', $ids);

        return $this->builder->when($ids, function (Builder $query) use ($comments) {
            return $query->whereHas('notes', function (Builder $query) use ($comments) {
                $query->whereIn('id', $comments);
            })->with(['notes' => function ($query) {
                $query->latest()->first();
            }]);
        });
    }

    public function hasLastNote($order = 'desc')
    {
        if ($order == "oldest") {
            $order = 'asc';
        } else {
            $order = 'desc';
        }
    
        $subQuery = DB::table('notes')
                       ->select('user_id')
                       ->whereColumn('user_id', 'users.id') // 'users.id' should be the primary key of the user in the users table
                       ->orderBy('created_at', $order)
                       ->limit(1);
    
        $data = $this->builder
            ->whereHas('notes', function ($query) use ($subQuery) {
                $query->whereIn('user_id', $subQuery);
            })
            ->with(['notes' => function ($query) use ($order) {
                $query->orderBy('created_at', $order)->limit(1);
            }]);
    
        return $data;
    }
    



    public function fetch_note_by_order($orderBy)
    {
        return $this->builder->when($orderBy, function (Builder $query) use ($orderBy) {
            return $query->whereHas('notes', function (Builder $query) use ($orderBy) {
                $query->orderBy('created_at', $orderBy); // 'asc' for oldest, 'desc' for newest
            })->with(['notes' => function ($query) use ($orderBy) {
                $query->orderBy('created_at', $orderBy)->first();
            }]);
        });
    }
}
