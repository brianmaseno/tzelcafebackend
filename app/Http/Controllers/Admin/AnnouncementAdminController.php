<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementAdminController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->orderByDesc((new Announcement())->getKeyName())
            ->paginate(20);

        return view('admin.announcements.index', ['announcements' => $announcements]);
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['required', 'in:all,customers,admins'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        Announcement::create($data);

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Announcement created.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', ['announcement' => $announcement]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'audience' => ['required', 'in:all,customers,admins'],
            'is_active' => ['nullable', 'boolean'],
            'sent_at' => ['nullable', 'date'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $announcement->update($data);

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Announcement updated.');
    }

    public function send(Announcement $announcement): RedirectResponse
    {
        $recipients = User::query()
            ->when($announcement->audience === 'customers', fn ($q) => $q->where('is_admin', false))
            ->when($announcement->audience === 'admins', fn ($q) => $q->where('is_admin', true))
            ->get(['email', 'name']);

        if ($recipients->isEmpty()) {
            return back()->withErrors(['send' => 'No recipients found for this audience.']);
        }

        $html = view('emails.announcement', [
            'subject' => $announcement->subject,
            'body' => $announcement->body,
        ])->render();

        $brevo = app(\App\Services\BrevoService::class);
        $sent = 0;

        foreach ($recipients as $user) {
            try {
                $brevo->sendTransactional(
                    [['email' => $user->email, 'name' => $user->name]],
                    $announcement->subject,
                    $html
                );
                $sent++;
            } catch (\Throwable) {
                // continue with remaining recipients
            }
        }

        $announcement->update(['sent_at' => now(), 'is_active' => true]);

        return back()->with('status', "Announcement sent to {$sent} recipient(s).");
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('status', 'Announcement deleted.');
    }
}

