<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactAdminController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::query()
            ->orderByDesc('id')
            ->paginate(25);

        $unreadCount = ContactMessage::query()->where('is_read', false)->count();

        return view('admin.contacts.index', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function show(ContactMessage $contact): View
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('admin.contacts.show', ['message' => $contact]);
    }

    public function update(Request $request, ContactMessage $contact): RedirectResponse
    {
        $data = $request->validate([
            'is_read' => ['required', 'in:0,1'],
        ]);

        $isRead = (bool) (int) $data['is_read'];

        $contact->update([
            'is_read' => $isRead,
            'read_at' => $isRead ? ($contact->read_at ?? now()) : null,
        ]);

        return back()->with('status', 'Message updated.');
    }

    public function destroy(ContactMessage $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('status', 'Message deleted.');
    }
}
