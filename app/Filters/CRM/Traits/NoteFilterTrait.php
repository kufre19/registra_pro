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

    // public function hasLastNote($order = 'desc')
    // {
    //     // Define the order based on the input.
    //     $order = $order == "oldest" ? 'asc' : 'desc';

    //     // Create a subquery that gets the latest or oldest note for each user.
    //     $notesSubQuery = DB::table('notes')
    //         ->selectRaw('noteable_id, MAX(created_at) as latest_note_date')
    //         ->groupBy('noteable_id');

    //     // Join this subquery with the main query.
    //     $data = $this->builder
    //         ->joinSub($notesSubQuery, 'note_sub', function ($join) {
    //             $join->on('people.id', '=', 'note_sub.noteable_id');
    //         })
    //         ->with(['notes' => function ($query) use ($order) {
    //             $query->orderBy('created_at', $order)->take(1);
    //         }])
    //         ->select('people.*', 'note_sub.latest_note_date');

    //     return $data;
    // }

    public function hasLastNote($order = 'desc')
    {
        if ($order == "oldest") {
            $order = 'asc';
        } else {
            $order = 'desc';
        }

        // Subquery to get the latest or oldest note id for each user
        $notesSubQuery = DB::table('notes')
            ->selectRaw('MAX(id) as last_note_id, noteable_id')
            ->groupBy('noteable_id');

        $data = $this->builder
            ->joinSub($notesSubQuery, 'latest_notes', 'latest_notes.noteable_id', '=', 'people.id')
            ->join('notes', 'notes.id', '=', 'latest_notes.last_note_id')
            ->select('people.*', 'notes.created_at as last_note_date')
            ->orderBy('last_note_date', $order);

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
