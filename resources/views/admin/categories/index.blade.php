@extends('admin.layout')

@section('title', 'Categories')

@section('content')
  <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Menu</p>
      <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">Categories</h1>
      <p class="mt-2 text-sm text-tzel-sand/80">Create and organize categories shown on the website.</p>
    </div>
    <a
      href="{{ route('admin.categories.create') }}"
      class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold"
    >
      Add Category
    </a>
  </div>

  <div class="overflow-hidden rounded-2xl border border-white/5 bg-tzel-espresso/30">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-white/5">
        <thead class="bg-tzel-ink/30">
          <tr class="text-left text-xs font-medium tracking-wider text-tzel-muted uppercase">
            <th class="px-6 py-4">Name</th>
            <th class="px-6 py-4">Slug</th>
            <th class="px-6 py-4">Items</th>
            <th class="px-6 py-4">Active</th>
            <th class="px-6 py-4">Sort</th>
            <th class="px-6 py-4"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse ($categories as $category)
            <tr class="hover:bg-white/5">
              <td class="px-6 py-4">
                <div class="font-medium text-tzel-cream">{{ $category->name }}</div>
                @if ($category->description)
                  <div class="mt-1 line-clamp-1 text-xs text-tzel-muted">{{ $category->description }}</div>
                @endif
              </td>
              <td class="px-6 py-4 text-sm text-tzel-sand">{{ $category->slug }}</td>
              <td class="px-6 py-4 text-sm text-tzel-sand">{{ $category->menuItems()->count() }}</td>
              <td class="px-6 py-4 text-sm">
                <span class="inline-flex items-center rounded-full border border-white/10 bg-tzel-ink/40 px-3 py-1 text-xs text-tzel-sand">
                  {{ $category->is_active ? 'Yes' : 'No' }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-tzel-sand">{{ $category->sort_order }}</td>
              <td class="px-6 py-4 text-right">
                <a
                  href="{{ route('admin.categories.edit', $category) }}"
                  class="inline-flex items-center justify-center rounded-full border border-white/10 px-4 py-2 text-sm text-tzel-cream hover:border-tzel-bronze/40 hover:text-tzel-gold"
                >
                  Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-sm text-tzel-muted">No categories yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="border-t border-white/5 px-6 py-4">
      {{ $categories->links() }}
    </div>
  </div>
@endsection

