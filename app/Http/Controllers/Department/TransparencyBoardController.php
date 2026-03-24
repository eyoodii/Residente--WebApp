<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

/**
 * TransparencyBoardController
 *
 * SBSEC: Official publisher for the LGU Transparency Board.
 * Uploads LGU Memorandums, Barangay Ordinances, and resolutions.
 */
class TransparencyBoardController extends Controller
{
    private const ALLOWED_CATEGORIES = [
        'LGU Memorandum',
        'Barangay Ordinance',
        'Resolution',
        'General Update',
        'Barangay News',
    ];

    public function index()
    {
        $user          = auth()->user();
        $announcements = Announcement::whereIn('category', self::ALLOWED_CATEGORIES)
            ->latest('posted_at')
            ->paginate(20);

        $stats = [
            'published' => Announcement::whereIn('category', self::ALLOWED_CATEGORIES)->where('is_active', true)->count(),
            'drafts'    => Announcement::whereIn('category', self::ALLOWED_CATEGORIES)->where('is_active', false)->count(),
        ];

        return view('department.transparency-board.index', compact('user', 'announcements', 'stats'));
    }

    public function create()
    {
        $user       = auth()->user();
        $categories = self::ALLOWED_CATEGORIES;

        return view('department.transparency-board.create', compact('user', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
            'category'        => 'required|in:' . implode(',', self::ALLOWED_CATEGORIES),
            'target_barangay' => 'nullable|string|max:100',
            'posted_at'       => 'nullable|date',
            'is_active'       => 'boolean',
        ]);

        Announcement::create([
            'title'           => $validated['title'],
            'content'         => $validated['content'],
            'category'        => $validated['category'],
            'target_barangay' => $validated['target_barangay'] ?: null,
            'posted_at'       => $validated['posted_at'] ?? now(),
            'is_active'       => $request->boolean('is_active', false),
        ]);

        return redirect()->route('department.transparency-board.index')
            ->with('success', 'Document published to the Transparency Board.');
    }

    public function edit(Announcement $announcement)
    {
        $user       = auth()->user();
        $categories = self::ALLOWED_CATEGORIES;

        return view('department.transparency-board.edit', compact('user', 'announcement', 'categories'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'content'         => 'required|string',
            'category'        => 'required|in:' . implode(',', self::ALLOWED_CATEGORIES),
            'target_barangay' => 'nullable|string|max:100',
            'posted_at'       => 'nullable|date',
        ]);

        $announcement->update([
            'title'           => $validated['title'],
            'content'         => $validated['content'],
            'category'        => $validated['category'],
            'target_barangay' => $validated['target_barangay'] ?: null,
            'posted_at'       => $validated['posted_at'] ?? $announcement->posted_at,
        ]);

        return redirect()->route('department.transparency-board.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('department.transparency-board.index')
            ->with('success', 'Document removed from the Transparency Board.');
    }

    public function publish(Announcement $announcement)
    {
        $announcement->update(['is_active' => true, 'posted_at' => $announcement->posted_at ?? now()]);

        return back()->with('success', 'Document published to the public board.');
    }

    public function unpublish(Announcement $announcement)
    {
        $announcement->update(['is_active' => false]);

        return back()->with('success', 'Document unpublished (saved as draft).');
    }
}
