<?php


namespace App\Filters\Traits;


use Illuminate\Database\Eloquent\Builder;

trait UserFilterTrait
{
    public function typeFilter($type = null)
    {
        return $this->builder->when($type, function (Builder $builder) use ($type) {
            $builder->whereHas('roles', function (Builder $builder) use ($type) {
                $builder->when($type == 'app', function (Builder $builder) {
                    $builder->whereNull('brand_id');
                }, function (Builder $builder) {
                    $builder->whereNotNull('brand_id');
                });
            });
        });
    }

    //filter by first, last or both name
    public function name($name = null)
    {
        $name = explode(' ',$name);
        $this->builder
            ->Where(function ($query) use($name ) {
                for ($i = 0; $i < count($name ); $i++){
                    $query
                        ->where('first_name', 'like',  '%' . $name[$i] .'%')
                        ->orWhere('last_name', 'like',  '%' . $name[$i] .'%');
                }
            })->get();

    }
}
