<?php


namespace App\Filters\CRM\Traits;

trait TitleFilterTrait
{

    public function title($value = null)
    {
        $this->builder
            ->where('title', 'LIKE', "%{$value}%");
    }

}
