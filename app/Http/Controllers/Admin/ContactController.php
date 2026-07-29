<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'open');
        $q = ContactMessage::latest();
        if ($filter !== 'all') $q->where('status', $filter);
        $messages = $q->paginate(20)->withQueryString();
        $counts = [
            'all'      => ContactMessage::count(),
            'open'     => ContactMessage::where('status','open')->count(),
            'resolved' => ContactMessage::where('status','resolved')->count(),
        ];
        return view('contact.index', compact('messages','counts','filter'));
    }

    public function show(ContactMessage $message)
    {
        return view('contact.show', compact('message'));
    }

    public function resolve(Request $request, ContactMessage $message)
    {
        $request->validate(['admin_notes'=>'nullable|string|max:1000']);
        $message->update(['status'=>'resolved','admin_notes'=>$request->admin_notes,'resolved_at'=>now()]);
        return back()->with('success', 'Message marked as resolved.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contact')->with('success', 'Message deleted.');
    }
}
