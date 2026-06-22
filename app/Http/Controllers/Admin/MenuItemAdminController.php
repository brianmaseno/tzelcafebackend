<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuItemAdminController extends Controller
{
    public function __construct(private readonly CloudinaryService $cloudinary) {}

    public function index(): View
    {
        $items = MenuItem::query()
            ->with(['category'])
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.menu-items.index', ['items' => $items]);
    }

    public function create(): View
    {
        $categories = Category::query()->where('is_active', true)->orderBy('name')->get();
        return view('admin.menu-items.create', ['categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash', Rule::unique('menu_items', 'slug')],
            'description' => ['nullable', 'string', 'max:4000'],
            'price_kes' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_url' => ['nullable', 'url', 'max:2000'],
        ]);

        $slug = $data['slug'] ?: Str::slug($data['name']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->storeMenuImage($request->file('image'));
        } elseif (!empty($data['image_url'])) {
            $imagePath = $data['image_url'];
        }

        MenuItem::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price_cents' => (int) round(((float) $data['price_kes']) * 100),
            'image_path' => $imagePath,
            'rating' => $data['rating'] ?? null,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item created.');
    }

    public function show(MenuItem $menu_item): View
    {
        $menu_item->load('category');
        return view('admin.menu-items.show', ['item' => $menu_item]);
    }

    public function edit(MenuItem $menu_item): View
    {
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.menu-items.edit', ['item' => $menu_item, 'categories' => $categories]);
    }

    public function update(Request $request, MenuItem $menu_item): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['required', 'string', 'max:200', 'alpha_dash', Rule::unique('menu_items', 'slug')->ignore($menu_item->id)],
            'description' => ['nullable', 'string', 'max:4000'],
            'price_kes' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_url' => ['nullable', 'url', 'max:2000'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $imagePath = $menu_item->image_path;
        if ((bool) ($data['remove_image'] ?? false)) {
            $this->deleteMenuImage($imagePath);
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteMenuImage($imagePath);
            $imagePath = $this->storeMenuImage($request->file('image'));
        } elseif (!empty($data['image_url'])) {
            $this->deleteMenuImage($imagePath);
            $imagePath = $data['image_url'];
        }

        $menu_item->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'price_cents' => (int) round(((float) $data['price_kes']) * 100),
            'image_path' => $imagePath,
            'rating' => $data['rating'] ?? null,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('status', 'Menu item updated.');
    }

    public function destroy(MenuItem $menu_item): RedirectResponse
    {
        $this->deleteMenuImage($menu_item->image_path);

        $menu_item->delete();
        return redirect()->route('admin.menu-items.index')->with('status', 'Menu item deleted.');
    }

    private function uploadsDisk(): string
    {
        return (string) config('filesystems.uploads', 'public');
    }

    private function storeMenuImage(\Illuminate\Http\UploadedFile $file): string
    {
        if ($this->cloudinary->isConfigured()) {
            return $this->cloudinary->upload($file);
        }

        $stored = $file->store('menu', $this->uploadsDisk());

        return $this->uploadsDisk() === 'public' ? '/storage/'.$stored : $stored;
    }

    private function deleteMenuImage(?string $imagePath): void
    {
        if (! $imagePath) {
            return;
        }

        if ($this->cloudinary->isCloudinaryUrl($imagePath)) {
            $this->cloudinary->delete($imagePath);

            return;
        }

        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return;
        }

        $relative = str_starts_with($imagePath, '/storage/')
            ? str_replace('/storage/', '', $imagePath)
            : $imagePath;

        Storage::disk($this->uploadsDisk())->delete($relative);
    }
}
