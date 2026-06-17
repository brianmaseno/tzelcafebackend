@extends('admin.layout')

@section('title', 'Announcements')

@section('content')
  <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Announcements</p>
      <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Messages &amp; Campaigns</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">Create announcements for customers or internal admin messaging.</p>
    </div>
    <a
      href="{{ route('admin.announcements.create') }}"
      class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink hover:bg-tzel-gold"
    >
      New Announcement
    </a>
  </div>

  <div class="mt-8 overflow-hidden rounded-3xl border border-white/5 bg-tzel-espresso/40">
    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="border-b border-white/5 text-xs tracking-[0.35em] text-tzel-bronze uppercase">
          <tr>
            <th class="px-6 py-4">Subject</th>
            <th class="px-6 py-4">Audience</th>
            <th class="px-6 py-4">Sent at</th>
            <th class="px-6 py-4">Active</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($announcements as $a)
            <tr class="text-tzel-sand/90">
              <td class="px-6 py-4">
                <div class="font-medium text-tzel-cream">{{ $a->subject }}</div>
                <div class="mt-1 line-clamp-1 text-xs text-tzel-muted">{{ $a->body }}</div>
              </td>
              <td class="px-6 py-4">{{ $a->audience }}</td>
              <td class="px-6 py-4">
                {{ $a->sent_at ? $a->sent_at->format('Y-m-d H:i') : '—' }}
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex rounded-full border px-3 py-1 text-xs {{ $a->is_active ? 'border-tzel-bronze/30 bg-tzel-bronze/10 text-tzel-gold' : 'border-white/10 bg-white/5 text-tzel-muted' }}">
                  {{ $a->is_active ? 'Yes' : 'No' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <form method="POST" action="{{ route('admin.announcements.send', $a) }}" class="inline">
                  @csrf
                  <button class="text-tzel-gold hover:text-tzel-bronze" onclick="return confirm('Send this announcement via email?')">Send</button>
                </form>
                <span class="mx-2 text-white/10">|</span>
                <a class="text-tzel-gold hover:text-tzel-bronze" href="{{ route('admin.announcements.edit', $a) }}">Edit</a>
                <span class="mx-2 text-white/10">|</span>
                <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" class="inline">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-200 hover:text-red-100" onclick="return confirm('Delete this announcement?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td class="px-6 py-10 text-center text-tzel-muted" colspan="5">No announcements yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-white/5 px-6 py-4">
      {{ $announcements->links() }}
    </div>
  </div>
@endsection

