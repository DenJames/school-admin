<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    //
    public function download(Document $document)
    {
        if ($document->documentable->user_id == auth()->id() || Auth::user()->hasRole('admin')) {
            return response()->download(Storage::disk('public')->path($document->path), $document->filename);
        }

        throw new AuthorizationException;
    }

    public function delete(Document $document)
    {
        if ($document->documentable->user_id == auth()->id() || Auth::user()->hasRole('admin')) {
            Storage::disk('public')->delete($document->path);
            $document->delete();

            return redirect()->back();
        }

        throw new AuthorizationException;
    }

    public function view(Document $document)
    {
        if ($document->documentable->user_id == auth()->id() || Auth::user()->hasRole('admin')) {
            return response()->file(Storage::disk('public')->path($document->path));
        }

        throw new AuthorizationException;
    }
}
