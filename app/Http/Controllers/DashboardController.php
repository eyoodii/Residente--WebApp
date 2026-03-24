<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display the resident dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve the currently authenticated resident
        $resident = Auth::user();

        // Redirect privileged roles to their proper dashboards — department staff check MUST come
        // before isAdmin() because department staff have role='admin' + department_role set.
        if ($resident->isDepartmentStaff()) {
            return redirect()->route('department.dashboard');
        }

        if ($resident->isSuperAdmin() || $resident->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Check if email is not verified
        $emailNotVerified = !$resident->hasVerifiedEmail();
        
        // Fetch announcements by category for the Public Information Board (10 per category with pagination support)
        $memos = Announcement::published()
            ->where('category', 'LGU Memorandum')
            ->forResident($resident->barangay === 'Pending Update' ? null : $resident->barangay)
            ->latest('posted_at')
            ->take(10)
            ->get();
            
        $ordinances = Announcement::published()
            ->where('category', 'Barangay Ordinance')
            ->forResident($resident->barangay === 'Pending Update' ? null : $resident->barangay)
            ->latest('posted_at')
            ->take(10)
            ->get();
            
        $news = Announcement::published()
            ->whereIn('category', ['Barangay News', 'General Update', 'Health Service'])
            ->forResident($resident->barangay === 'Pending Update' ? null : $resident->barangay)
            ->latest('posted_at')
            ->take(10)
            ->get();
        
        // Fetch the 5 most recent announcements for the timeline
        $announcements = Announcement::published()
            ->forResident($resident->barangay === 'Pending Update' ? null : $resident->barangay)
            ->latest('posted_at')
            ->take(5)
            ->get();
        
        // Get service request statistics (only if email verified)
        $serviceStats = [
            'pending' => 0,
            'completed' => 0,
            'ready_for_pickup' => 0,
            'total' => 0,
        ];
        
        $recentRequests = collect([]);
        
        if (!$emailNotVerified) {
            $serviceStats = [
                'pending' => $resident->serviceRequests()->whereIn('status', ['pending', 'in-progress'])->count(),
                'completed' => $resident->serviceRequests()->where('status', 'completed')->count(),
                'ready_for_pickup' => $resident->serviceRequests()->where('status', 'ready-for-pickup')->count(),
                'total' => $resident->serviceRequests()->count(),
            ];
            
            // Get recent service requests (last 5)
            $recentRequests = $resident->serviceRequests()
                ->with('service')
                ->latest('requested_at')
                ->take(5)
                ->get();
        }
        
        // Get unread notifications
        $notifications = $resident->unreadNotifications()->take(5)->get();
        $unreadNotificationCount = $resident->unreadNotifications()->count();
        
        // Check if resident needs to complete profile
        $needsProfileCompletion = $resident->purok === 'Pending Update' ||
                                  !$resident->profile_matched;
        
        // Check verification status
        $needsEmailVerification = $emailNotVerified;
        $needsPhilSysVerification = !$resident->philsys_verified_at && !$emailNotVerified;
        $needsOnboarding = $resident->philsys_verified_at && !$resident->is_onboarding_complete;
        
        return view('dashboard', compact(
            'resident', 
            'announcements',
            'memos',
            'ordinances',
            'news',
            'serviceStats',
            'recentRequests',
            'notifications',
            'unreadNotificationCount',
            'needsProfileCompletion',
            'needsEmailVerification',
            'needsPhilSysVerification',
            'needsOnboarding'
        ));
    }
    
    /**
     * Load more announcements via AJAX
     */
    public function loadMoreAnnouncements(Request $request)
    {
        $resident = Auth::user();
        $category = $request->get('category');
        $offset = $request->get('offset', 0);
        $limit = 10;
        
        $query = Announcement::published()
            ->forResident($resident->barangay === 'Pending Update' ? null : $resident->barangay)
            ->latest('posted_at')
            ->skip($offset)
            ->take($limit);
            
        // Filter by category
        if ($category === 'memos') {
            $query->where('category', 'LGU Memorandum');
        } elseif ($category === 'ordinances') {
            $query->where('category', 'Barangay Ordinance');
        } elseif ($category === 'news') {
            $query->whereIn('category', ['Barangay News', 'General Update', 'Health Service']);
        }
        
        $announcements = $query->get()->map(function ($announcement) {
            return [
                'title' => $announcement->title,
                'content_preview' => \Str::limit($announcement->content, 150),
                'category' => $announcement->category,
                'category_badge_color' => $announcement->category_badge_color,
                'formatted_posted_at' => $announcement->formatted_posted_at,
            ];
        });
        
        $hasMore = Announcement::published()
            ->forResident($resident->barangay === 'Pending Update' ? null : $resident->barangay)
            ->when($category === 'memos', fn($q) => $q->where('category', 'LGU Memorandum'))
            ->when($category === 'ordinances', fn($q) => $q->where('category', 'Barangay Ordinance'))
            ->when($category === 'news', fn($q) => $q->whereIn('category', ['Barangay News', 'General Update', 'Health Service']))
            ->count() > ($offset + $limit);
        
        return response()->json([
            'announcements' => $announcements,
            'hasMore' => $hasMore,
        ]);
    }
}
