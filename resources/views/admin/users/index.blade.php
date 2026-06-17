@extends('admin.layout')

@section('title', 'Users')

@section('content')
  <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Users</p>
      <h1 class="mt-2 font-serif text-3xl text-tzel-cream">Customer &amp; Admin Accounts</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">Create admins, manage users, and reset access.</p>
    </div>
    <a
      href="{{ route('admin.users.create') }}"
      class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink hover:bg-tzel-gold"
    >
      New User
    </a>
  </div>

  <div class="mt-8 overflow-hidden rounded-3xl border border-white/5 bg-tzel-espresso/40">
    <div class="overflow-x-auto">
      <table class="min-w-full text-left text-sm">
        <thead class="border-b border-white/5 text-xs tracking-[0.35em] text-tzel-bronze uppercase">
          <tr>
            <th class="px-6 py-4">Name</th>
            <th class="px-6 py-4">Email</th>
            <th class="px-6 py-4">Role</th>
            <th class="px-6 py-4">Created</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($users as $u)
            <tr class="text-tzel-sand/90">
              <td class="px-6 py-4 font-medium text-tzel-cream">{{ $u->name }}</td>
              <td class="px-6 py-4">{{ $u->email }}</td>
              <td class="px-6 py-4">
                <span class="inline-flex rounded-full border px-3 py-1 text-xs {{ $u->is_admin ? 'border-tzel-bronze/30 bg-tzel-bronze/10 text-tzel-gold' : 'border-white/10 bg-white/5 text-tzel-muted' }}">
                  {{ $u->is_admin ? 'Admin' : 'Customer' }}
                </span>
              </td>
              <td class="px-6 py-4 text-tzel-muted">{{ $u->created_at?->format('Y-m-d') }}</td>
              <td class="px-6 py-4 text-right">
                <a class="text-tzel-gold hover:text-tzel-bronze" href="{{ route('admin.users.edit', $u) }}">Edit</a>
                <span class="mx-2 text-white/10">|</span>
                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-200 hover:text-red-100" onclick="return confirm('Delete this user?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td class="px-6 py-10 text-center text-tzel-muted" colspan="5">No users found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-white/5 px-6 py-4">
      {{ $users->links() }}
    </div>
  </div>
@endsection

