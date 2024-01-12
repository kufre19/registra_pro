<?php

namespace App\Http\Controllers\CRM\Tags;

use App\Filters\CRM\TagFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Tags\TagRequest as Request;
use App\Models\CRM\Tag\Tag;
use App\Models\CRM\Tag\Taggable;
use App\Services\CRM\Tags\TagService;

class TagController extends Controller
{
    public function __construct(TagService $service, TagFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->select('id', 'name', 'color_code')
            ->get();
    }

    public function searchTagLists(Request $request)
    {
        return $this->service
        ->where('name', 'like', '%'.$request->name.'%')
        ->select('id', 'name', 'color_code')
        ->paginate(
            request(
                'per_page',
                \Request::get('per_page') ?? 10
            )
        );
    }

    public function store(Request $request)
    {
        return $this->service->save();
    }


    public function show(Tag $tag)
    {
        return $tag;
    }


    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->all());

        return updated_responses('tags');
    }


    public function destroy(Tag $tag)
    {
        if (Taggable::query()->where('tag_id', $tag->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => __t('tag_already_in_use')
            ], 422);

        }else{
            if ($tag->delete()){
                return deleted_responses('tags');
            }
        }
    }



}
