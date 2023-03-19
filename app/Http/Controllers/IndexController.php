<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {
        return Inertia::render('Index', [
            'tags' => Tag::all()->pluck('name')->toArray(),
            'selectedTags' => Tag::where("is_selected", true)->pluck('name')
        ]);
    }

    public function updateTag(Request $request)
    {
        try {
            Tag::updateOrInsert([
                "name" => $request->name
            ], [
                "is_selected" => $request->is_selected
            ]);
            return response()->json(['status' => 200], 200);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['status' => 500, "message" => $e->getMessage()], 500);
        }
    }
}
