@extends('admin.layout')

@section('title', 'Contact Messages')

@section('content')
  <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Inbox</p>
      <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Contact Messages</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">
        Messages from the website contact form.
        @if ($unreadCount > 0)
          <span class="text-tzel-gold">{{ $unreadCount }} unread</span>
        @endif
      </p>
    </div>
  </div>

  <div class="mt-8 overflow-hidden rounded-3xl border border-white/5 bg-tzel-espresso/40">
    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="border-b border-white/5 text-xs tracking-[0.35em] text-tzel-bronze uppercase">
          <tr>
            <th class="px-6 py-4">From</th>
            <th class="px-6 py-4">Subject / Preview</th>
            <th class="px-6 py-4">Received</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($messages as $msg)
            <tr class="{{ $msg->is_read ? 'text-tzel-sand/70' : 'text-tzel-cream' }}">
              <td class="px-6 py-4">
                <div class="font-medium">{{ $msg->name }}</div>
                <div class="text-xs text-tzel-muted">{{ $msg->email }}</div>
                @if ($msg->phone)
                  <div class="text-xs text-tzel-muted">{{ $msg->phone }}</div>
                @endif
              </td>
              <td class="px-6 py-4 max-w-xs">
                <div class="truncate font-medium">{{ $msg->subject ?: '—' }}</div>
                <div class="mt-1 line-clamp-1 text-xs text-tzel-muted">{{ $msg->message }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ $msg->created_at->format('Y-m-d H:i') }}</td>
              <td class="px-6 py-4">
                <span class="inline-flex rounded-full border px-3 py-1 text-xs {{ $msg->is_read ? 'border-white/10 text-tzel-muted' : 'border-tzel-bronze/30 bg-tzel-bronze/10 text-tzel-gold' }}">
                  {{ $msg->is_read ? 'Read' : 'New' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('admin.contacts.show', $msg) }}" class="text-tzel-gold hover:text-tzel-bronze">View</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-10 text-center text-tzel-muted">No contact messages yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="border-t border-white/5 px-6 py-4">{{ $messages->links() }}</div>
  </div>
@endsection
