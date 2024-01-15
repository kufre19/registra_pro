<?php

namespace App\Filters\CRM\Traits;

use Illuminate\Database\Eloquent\Builder;


trait CommentFilterTrait
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
}
