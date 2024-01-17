<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;


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

        $data = $this->builder
            ->whereHas('notes') // Ensure the user has notes
            ->with(['notes' => function ($query) use ($order) {
                $query->orderBy('created_at', $order) // Order the notes by creation date
                    ->limit(1) // Limit to only the latest or oldest note
                    ->first(); // Get the first note based on the order
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
