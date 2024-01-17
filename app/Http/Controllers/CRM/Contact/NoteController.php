<?php

namespace App\Http\Controllers\CRM\Contact;

use App\Http\Controllers\Controller;
use App\Models\CRM\Note\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{

    public function index()
    {
        return Note::select('id', 'created_at','title')->get();
    }

    public function searchNoteLists(Request $request)
    {
        return Note::query()
        ->when($request->has('notes'), function ($query) use ($request) {
            // Assuming 'notes_order' is the request parameter that holds the selected order value
            $order = $request->notes === 'oldest' ? 'asc' : 'desc';
            $query->orderBy('created_at', $order);
        })
        ->select('id', 'created_at','title') 
        ->paginate(
            $request->input('per_page', 10) // Use the 'per_page' request parameter or default to 10
        );
    
    }
}
