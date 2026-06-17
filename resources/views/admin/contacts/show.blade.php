@extends('admin.layout')

@section('title', 'Contact Message')

@section('content')
  <div class="mb-6">
    <a href="{{ route('admin.contacts.index') }}" class="text-sm text-tzel-gold hover:text-tzel-bronze">← Back to inbox</a>
  </div>

  <div class="max-w-3xl rounded-3xl border border-white/5 bg-tzel-espresso/40 p-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div>
        <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Contact message</p>
        <h1 class="mt-2 font-serif text-2xl text-tzel-cream">{{ $message->name }}</h1>
        <p class="mt-1 text-sm text-tzel-sand/80">{{ $message->email }}</p>
        @if ($message->phone)
          <p class="mt-1 text-sm text-tzel-sand/80">
            <a href="tel:{{ preg_replace('/\s+/', '', $message->phone) }}" class="text-tzel-gold hover:underline">{{ $message->phone }}</a>
          </p>
        @endif
      </div>
      <span class="inline-flex rounded-full border px-3 py-1 text-xs {{ $message->is_read ? 'border-white/10 text-tzel-muted' : 'border-tzel-bronze/30 bg-tzel-bronze/10 text-tzel-gold' }}">
        {{ $message->is_read ? 'Read' : 'New' }}
      </span>
    </div>

    @if ($message->subject)
      <p class="mt-6 text-sm text-tzel-muted">Subject</p>
      <p class="mt-1 text-tzel-cream">{{ $message->subject }}</p>
    @endif

    <p class="mt-6 text-sm text-tzel-muted">Message</p>
    <div class="mt-2 whitespace-pre-wrap rounded-2xl border border-white/5 bg-tzel-ink/30 p-6 text-sm leading-relaxed text-tzel-sand/90">{{ $message->message }}</div>

    <p class="mt-6 text-xs text-tzel-muted">Received {{ $message->created_at->format('F j, Y \a\t g:i A') }}</p>

    <div class="mt-8 flex flex-wrap gap-3">
      <form method="POST" action="{{ route('admin.contacts.update', $message) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="is_read" value="{{ $message->is_read ? '0' : '1' }}">
        <button type="submit" class="rounded-full border border-white/10 px-5 py-2.5 text-sm text-tzel-sand hover:border-tzel-bronze/50 hover:text-tzel-gold">
          Mark as {{ $message->is_read ? 'unread' : 'read' }}
        </button>
      </form>
      <form method="POST" action="{{ route('admin.contacts.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="rounded-full border border-red-500/30 px-5 py-2.5 text-sm text-red-200 hover:bg-red-500/10">Delete</button>
      </form>
    </div>
  </div>
@endsection
