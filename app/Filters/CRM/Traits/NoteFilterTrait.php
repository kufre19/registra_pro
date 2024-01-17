<?php

namespace App\Filters\CRM\Traits;

use App\Models\CRM\Person\Person;
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

    public function hasLastNote($orderBy = 'desc')
    {
        if ($orderBy !== 'asc' && $orderBy !== 'desc') {
            $orderBy = 'desc';
        }
    
        // Add a raw subquery for the latest notes
        $latestNotesSubquery = DB::table('notes')
            ->select('noteable_id', DB::raw('MAX(created_at) as last_note_created_at'))
            ->where('noteable_type', Person::class)
            ->groupBy('noteable_id');
    
        // Join the subquery with the main query
        $this->builder->joinSub($latestNotesSubquery, 'latest_notes', function ($join) {
            $join->on('people.id', '=', 'latest_notes.noteable_id');
        })
        ->orderBy('last_note_created_at', $orderBy);
    
        return $this->builder;
    }
    


    public function fetch_note_by_order($orderBy)
    {
        return $this->builder->when($orderBy, function (Builder $query) use ($orderBy) {
            return $query->whereHas('notes', function (Builder $query) use ($orderBy) {
                $query->orderBy('created_at', $orderBy); // 'asc' for oldest, 'desc' for newest
            })->with(['notes' => function ($query) use ($orderBy) {
                // $query->orderBy('created_at', $orderBy)->first();
            }]);
        });
    }
}
