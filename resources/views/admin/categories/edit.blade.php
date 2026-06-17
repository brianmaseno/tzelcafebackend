@extends('admin.layout')

@section('title', 'Edit Category')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Menu</p>
    <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">Edit Category</h1>
    <p class="mt-2 text-sm text-tzel-sand/80">{{ $category->name }}</p>
  </div>

  <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="max-w-2xl space-y-5 rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6">
    @csrf
    @method('PATCH')

    <div>
      <label class="mb-1.5 block text-sm text-tzel-sand" for="name">Name</label>
      <input id="name" name="name" value="{{ old('name', $category->name) }}" required
        class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
      @error('name') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1.5 block text-sm text-tzel-sand" for="slug">Slug</label>
      <input id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required
        class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
      @error('slug') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1.5 block text-sm text-tzel-sand" for="description">Description (optional)</label>
      <textarea id="description" name="description" rows="4"
        class="w-full resize-none rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze"
      >{{ old('description', $category->description) }}</textarea>
      @error('description') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm text-tzel-sand" for="sort_order">Sort order</label>
        <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $category->sort_order) }}"
          class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
      </div>
      <div class="flex items-center gap-3 pt-6">
        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $category->is_active))
          class="rounded border-white/20 bg-tzel-ink/40 text-tzel-bronze" />
        <label for="is_active" class="text-sm text-tzel-sand">Active</label>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('admin.categories.index') }}"
        class="inline-flex items-center justify-center rounded-full border border-white/10 px-6 py-3 text-sm text-tzel-cream hover:border-tzel-bronze/40 hover:text-tzel-gold">
        Back
      </a>
      <button
        class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold">
        Save
      </button>
    </div>
  </form>

  <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-6 max-w-2xl">
    @csrf
    @method('DELETE')
    <button class="text-sm text-red-300 hover:text-red-200">
      Delete category
    </button>
    <p class="mt-2 text-xs text-tzel-muted">
      Deletion is blocked if the category has menu items.
    </p>
  </form>
@endsection

