<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleaningItem;
use Illuminate\Http\Request;

class CleaningController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'pending');
        $q = CleaningItem::with(['rental.renter', 'listing']);
        if ($filter !== 'all') $q->where('status', $filter);
        $items = $q->latest()->paginate(20)->withQueryString();
        $counts = [
            'all'         => CleaningItem::count(),
            'pending'     => CleaningItem::where('status','pending')->count(),
            'in_progress' => CleaningItem::where('status','in_progress')->count(),
            'completed'   => CleaningItem::where('status','completed')->count(),
        ];
        return view('cleaning.index', compact('items','counts','filter'));
    }

    public function complete(CleaningItem $item)
    {
        $item->update(['status'=>'completed','completed_at'=>now()]);
        return back()->with('success', 'Item marked as cleaned.');
    }

    public function assign(Request $request, CleaningItem $item)
    {
        $request->validate(['assigned_to'=>'required|string|max:100']);
        $item->update(['status'=>'in_progress','assigned_to'=>$request->assigned_to]);
        return back()->with('success', 'Item assigned to '.$request->assigned_to.'.');
    }
}
