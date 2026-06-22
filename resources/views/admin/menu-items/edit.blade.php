@extends('admin.layout')

@section('title', 'Edit Menu Item')

@section('content')
  <div class="mb-8">
    <p class="text-xs font-medium tracking-[0.4em] text-tzel-bronze uppercase">Menu</p>
    <h1 class="mt-3 font-serif text-3xl font-semibold text-tzel-cream">Edit Menu Item</h1>
    <p class="mt-2 text-sm text-tzel-sand/80">{{ $item->name }}</p>
  </div>

  <form method="POST" enctype="multipart/form-data" action="{{ route('admin.menu-items.update', $item) }}" class="max-w-3xl space-y-5 rounded-2xl border border-white/5 bg-tzel-espresso/30 p-6">
    @csrf
    @method('PATCH')

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm text-tzel-sand" for="category_id">Category</label>
        <select id="category_id" name="category_id" required
          class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze">
          @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" @selected((string) old('category_id', $item->category_id) === (string) $cat->id)>{{ $cat->name }}</option>
          @endforeach
        </select>
        @error('category_id') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="mb-1.5 block text-sm text-tzel-sand" for="price_kes">Price (KES)</label>
        <input id="price_kes" name="price_kes" type="number" step="0.01" value="{{ old('price_kes', ($item->price_cents ?? 0) / 100) }}" required
          class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
        @error('price_kes') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
      </div>
    </div>

    <div>
      <label class="mb-1.5 block text-sm text-tzel-sand" for="name">Name</label>
      <input id="name" name="name" value="{{ old('name', $item->name) }}" required
        class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
      @error('name') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1.5 block text-sm text-tzel-sand" for="slug">Slug</label>
      <input id="slug" name="slug" value="{{ old('slug', $item->slug) }}" required
        class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
      @error('slug') <p class="mt-2 text-sm text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1.5 block text-sm text-tzel-sand" for="description">Description (optional)</label>
      <textarea id="description" name="description" rows="4"
        class="w-full resize-none rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze"
      >{{ old('description', $item->description) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <p class="mt-1 text-xs text-tzel-muted">Uploads are stored on Cloudinary when CLOUDINARY_URL is set (recommended for production).</p>
        <label class="mb-1.5 mt-4 block text-sm text-tzel-sand" for="image">Replace image (optional)</label>
        <input id="image" name="image" type="file" accept="image/*"
          class="block w-full text-sm text-tzel-sand file:mr-4 file:rounded-full file:border-0 file:bg-tzel-bronze file:px-4 file:py-2 file:text-sm file:font-semibold file:text-tzel-ink hover:file:bg-tzel-gold" />
      </div>
      <div>
        <label class="mb-1.5 block text-sm text-tzel-sand" for="image_url">or Image URL</label>
        <input id="image_url" name="image_url" value="{{ old('image_url') }}"
          class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream placeholder:text-tzel-muted focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze"
          placeholder="https://images.unsplash.com/..." />
      </div>
    </div>

    @if ($item->imageUrl())
      <div class="rounded-2xl border border-white/5 bg-tzel-ink/30 p-4">
        <div class="flex items-center gap-4">
          <img src="{{ $item->imageUrl() }}" alt="{{ $item->name }}" class="h-20 w-20 rounded-xl object-cover ring-1 ring-white/10" />
          <div class="flex-1">
            <div class="text-sm text-tzel-sand">Current image</div>
            <div class="mt-1 break-all text-xs text-tzel-muted">{{ $item->image_path }}</div>
          </div>
          <label class="flex items-center gap-2 text-sm text-tzel-sand">
            <input type="checkbox" name="remove_image" value="1" class="rounded border-white/20 bg-tzel-ink/40 text-tzel-bronze" />
            Remove
          </label>
        </div>
      </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-3">
      <div>
        <label class="mb-1.5 block text-sm text-tzel-sand" for="rating">Rating (optional)</label>
        <input id="rating" name="rating" type="number" step="0.1" min="0" max="5" value="{{ old('rating', $item->rating) }}"
          class="w-full rounded-xl border border-white/10 bg-tzel-ink/40 px-4 py-3 text-tzel-cream focus:border-tzel-bronze focus:outline-none focus:ring-1 focus:ring-tzel-bronze" />
      </div>
      <div class="flex items-center gap-3 pt-6">
        <input id="is_featured" name="is_featured" type="checkbox" value="1" @checked(old('is_featured', $item->is_featured))
          class="rounded border-white/20 bg-tzel-ink/40 text-tzel-bronze" />
        <label for="is_featured" class="text-sm text-tzel-sand">Featured</label>
      </div>
      <div class="flex items-center gap-3 pt-6">
        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $item->is_active))
          class="rounded border-white/20 bg-tzel-ink/40 text-tzel-bronze" />
        <label for="is_active" class="text-sm text-tzel-sand">Active</label>
      </div>
    </div>

    <div class="flex gap-3">
      <a href="{{ route('admin.menu-items.index') }}"
        class="inline-flex items-center justify-center rounded-full border border-white/10 px-6 py-3 text-sm text-tzel-cream hover:border-tzel-bronze/40 hover:text-tzel-gold">
        Back
      </a>
      <button
        class="inline-flex items-center justify-center rounded-full bg-tzel-bronze px-6 py-3 text-sm font-semibold text-tzel-ink transition hover:bg-tzel-gold">
        Save
      </button>
    </div>
  </form>

  <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}" class="mt-6 max-w-3xl">
    @csrf
    @method('DELETE')
    <button class="text-sm text-red-300 hover:text-red-200">
      Delete menu item
    </button>
  </form>
@endsection

