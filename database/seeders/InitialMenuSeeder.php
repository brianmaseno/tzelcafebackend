<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InitialMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['slug' => 'coffee-collection', 'name' => 'Coffee Collection'],
            ['slug' => 'hot-beverages', 'name' => 'Hot Beverages'],
            ['slug' => 'cold-beverages', 'name' => 'Cold Beverages'],
            ['slug' => 'breakfast', 'name' => 'Breakfast'],
            ['slug' => 'snacks-pastries', 'name' => 'Snacks and Pastries'],
            ['slug' => 'burgers-bites', 'name' => 'Burgers and Bites'],
            ['slug' => 'main-meals', 'name' => 'Main Meals'],
            ['slug' => 'sweet-delights', 'name' => 'Sweet Delights'],
        ];

        $categoryModels = collect($categories)->mapWithKeys(function (array $row, int $index) {
            $slug = $row['slug'];
            $name = $row['name'];
            $model = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => null, 'sort_order' => $index + 1, 'is_active' => true]
            );

            return [$slug => $model];
        });

        // Seed a premium-looking demo menu (images are external URLs).
        $items = [
            [
                'name' => 'Espresso Classico',
                'category' => 'coffee-collection',
                'price' => 350,
                'image' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?auto=format&fit=crop&w=800&q=80',
                'description' => 'Double shot pulled from single-origin Ethiopian beans with a caramel finish.',
                'rating' => 4.9,
                'featured' => true,
            ],
            [
                'name' => 'TZEL Signature Latte',
                'category' => 'coffee-collection',
                'price' => 450,
                'image' => 'https://images.unsplash.com/photo-1461023058943-f07a08016d21?auto=format&fit=crop&w=800&q=80',
                'description' => 'Velvety steamed milk layered over our house espresso with bronze honey drizzle.',
                'rating' => 5.0,
                'featured' => true,
            ],
            [
                'name' => 'Pour-Over Ritual',
                'category' => 'coffee-collection',
                'price' => 500,
                'image' => 'https://images.unsplash.com/photo-1497935586761-b10b12b08a20?auto=format&fit=crop&w=800&q=80',
                'description' => 'Hand-brewed single cup with notes of dark chocolate and bergamot.',
                'rating' => 4.8,
                'featured' => false,
            ],
            [
                'name' => 'Cappuccino Crema',
                'category' => 'coffee-collection',
                'price' => 420,
                'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=800&q=80',
                'description' => 'Rich microfoam cap on a balanced double shot — morning perfection.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'African Tea',
                'category' => 'hot-beverages',
                'price' => 280,
                'image' => 'https://images.unsplash.com/photo-1542556391-0c5c25ee5d42?auto=format&fit=crop&w=800&q=80',
                'description' => 'Kenyan tea brewed strong, served warm with a hint of spice.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Hot Chocolate',
                'category' => 'hot-beverages',
                'price' => 350,
                'image' => 'https://images.unsplash.com/photo-1517578239113-b03992dcdd25?auto=format&fit=crop&w=800&q=80',
                'description' => 'Dark cocoa, silky milk, and a toasted marshmallow finish.',
                'rating' => 4.8,
                'featured' => true,
            ],
            [
                'name' => 'Iced Bronze Mocha',
                'category' => 'cold-beverages',
                'price' => 480,
                'image' => 'https://images.unsplash.com/photo-1517701604719-c2bcded5d3b3?auto=format&fit=crop&w=800&q=80',
                'description' => 'Cold-brew espresso, dark chocolate, and oat milk over artisan ice.',
                'rating' => 4.8,
                'featured' => true,
            ],
            [
                'name' => 'Sunrise Pressed Juice',
                'category' => 'cold-beverages',
                'price' => 400,
                'image' => 'https://images.unsplash.com/photo-1622597467836-f3281f2fd8af?auto=format&fit=crop&w=800&q=80',
                'description' => 'Orange, carrot, ginger, and turmeric — cold-pressed to order.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Breakfast Platter',
                'category' => 'breakfast',
                'price' => 950,
                'image' => 'https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=800&q=80',
                'description' => 'Eggs, toast, sausage, grilled tomato, and seasonal fruit.',
                'rating' => 4.7,
                'featured' => true,
            ],
            [
                'name' => 'Pancakes',
                'category' => 'breakfast',
                'price' => 650,
                'image' => 'https://images.unsplash.com/photo-1495214783159-3503fd1b572d?auto=format&fit=crop&w=800&q=80',
                'description' => 'Fluffy stack served with maple syrup and berries.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Bronze Butter Croissant',
                'category' => 'snacks-pastries',
                'price' => 280,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=800&q=80',
                'description' => 'Flaky, golden layers baked fresh daily with French butter.',
                'rating' => 4.9,
                'featured' => true,
            ],
            [
                'name' => 'Cinnamon Swirl Roll',
                'category' => 'snacks-pastries',
                'price' => 320,
                'image' => 'https://images.unsplash.com/photo-1607952200629-ffe211995e2f?auto=format&fit=crop&w=800&q=80',
                'description' => 'Soft brioche spiraled with Ceylon cinnamon and cream cheese glaze.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Truffle Wagyu Burger',
                'category' => 'burgers-bites',
                'price' => 1850,
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
                'description' => 'Premium wagyu patty, aged cheddar, truffle aioli, and brioche bun.',
                'rating' => 4.9,
                'featured' => true,
            ],
            [
                'name' => 'Herb-Roasted Chicken',
                'category' => 'main-meals',
                'price' => 1650,
                'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?auto=format&fit=crop&w=800&q=80',
                'description' => 'Free-range chicken with rosemary jus, seasonal vegetables, and mashed potato.',
                'rating' => 4.8,
                'featured' => true,
            ],
            [
                'name' => 'Ocean Saffron Risotto',
                'category' => 'main-meals',
                'price' => 2100,
                'image' => 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?auto=format&fit=crop&w=800&q=80',
                'description' => 'Creamy saffron risotto with prawns, scallops, and parmesan crisp.',
                'rating' => 5.0,
                'featured' => true,
            ],
            [
                'name' => 'Dark Chocolate Lava Tart',
                'category' => 'sweet-delights',
                'price' => 550,
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80',
                'description' => 'Warm Belgian chocolate center in a crisp almond shell, dusted with cocoa.',
                'rating' => 5.0,
                'featured' => true,
            ],
            [
                'name' => 'Americano',
                'category' => 'coffee-collection',
                'price' => 320,
                'image' => 'https://images.unsplash.com/photo-1514432324607-09a984d069af?auto=format&fit=crop&w=800&q=80',
                'description' => 'Espresso diluted with hot water for a clean, bold cup.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Flat White',
                'category' => 'coffee-collection',
                'price' => 440,
                'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=800&q=80',
                'description' => 'Silky microfoam over a double ristretto shot.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'Macchiato',
                'category' => 'coffee-collection',
                'price' => 380,
                'image' => 'https://images.unsplash.com/photo-1485808191677-5f86510681a2?auto=format&fit=crop&w=800&q=80',
                'description' => 'Espresso marked with a dollop of steamed milk foam.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Mocha',
                'category' => 'coffee-collection',
                'price' => 480,
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?auto=format&fit=crop&w=800&q=80',
                'description' => 'Espresso, chocolate, and steamed milk — rich and balanced.',
                'rating' => 4.8,
                'featured' => false,
            ],
            [
                'name' => 'Chai Latte',
                'category' => 'hot-beverages',
                'price' => 380,
                'image' => 'https://images.unsplash.com/photo-1571934811356-5cc061b6821f?auto=format&fit=crop&w=800&q=80',
                'description' => 'Spiced tea concentrate with steamed milk and cinnamon dust.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'Green Tea',
                'category' => 'hot-beverages',
                'price' => 250,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
                'description' => 'Light, aromatic sencha served hot.',
                'rating' => 4.4,
                'featured' => false,
            ],
            [
                'name' => 'Herbal Infusion',
                'category' => 'hot-beverages',
                'price' => 260,
                'image' => 'https://images.unsplash.com/photo-1597318181409-c9c4450de6fe?auto=format&fit=crop&w=800&q=80',
                'description' => 'Caffeine-free blend of chamomile, mint, and lemongrass.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Iced Latte',
                'category' => 'cold-beverages',
                'price' => 450,
                'image' => 'https://images.unsplash.com/photo-1517701604719-c2bcded5d3b3?auto=format&fit=crop&w=800&q=80',
                'description' => 'Double espresso over ice with cold milk.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Fresh Lemonade',
                'category' => 'cold-beverages',
                'price' => 320,
                'image' => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?auto=format&fit=crop&w=800&q=80',
                'description' => 'Hand-squeezed lemons with a touch of mint.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Berry Smoothie',
                'category' => 'cold-beverages',
                'price' => 420,
                'image' => 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?auto=format&fit=crop&w=800&q=80',
                'description' => 'Mixed berries blended with yoghurt and honey.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'Iced Tea',
                'category' => 'cold-beverages',
                'price' => 300,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
                'description' => 'Brewed black tea chilled with lemon.',
                'rating' => 4.4,
                'featured' => false,
            ],
            [
                'name' => 'Omelette',
                'category' => 'breakfast',
                'price' => 550,
                'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80',
                'description' => 'Three-egg fold with cheese, peppers, and herbs.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'French Toast',
                'category' => 'breakfast',
                'price' => 600,
                'image' => 'https://images.unsplash.com/photo-1484723091739-30a028e8acb9?auto=format&fit=crop&w=800&q=80',
                'description' => 'Brioche slices with cinnamon, maple syrup, and berries.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'Avocado Toast',
                'category' => 'breakfast',
                'price' => 580,
                'image' => 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?auto=format&fit=crop&w=800&q=80',
                'description' => 'Smashed avocado on sourdough with poached egg.',
                'rating' => 4.8,
                'featured' => true,
            ],
            [
                'name' => 'Blueberry Muffin',
                'category' => 'snacks-pastries',
                'price' => 250,
                'image' => 'https://images.unsplash.com/photo-1607958996333-41aef7caefaa?auto=format&fit=crop&w=800&q=80',
                'description' => 'Moist muffin loaded with fresh blueberries.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Chocolate Chip Cookie',
                'category' => 'snacks-pastries',
                'price' => 180,
                'image' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80',
                'description' => 'Warm, chewy cookie with Belgian chocolate chunks.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Scone',
                'category' => 'snacks-pastries',
                'price' => 220,
                'image' => 'https://images.unsplash.com/photo-1612189330152-0f8f537a7bf5?auto=format&fit=crop&w=800&q=80',
                'description' => 'Buttery scone served with clotted cream and jam.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Classic Beef Burger',
                'category' => 'burgers-bites',
                'price' => 950,
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80',
                'description' => 'Angus beef patty, lettuce, tomato, and house sauce.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'Crispy Chicken Burger',
                'category' => 'burgers-bites',
                'price' => 850,
                'image' => 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?auto=format&fit=crop&w=800&q=80',
                'description' => 'Crispy fillet with slaw and chipotle mayo.',
                'rating' => 4.6,
                'featured' => false,
            ],
            [
                'name' => 'Sweet Potato Fries',
                'category' => 'burgers-bites',
                'price' => 350,
                'image' => 'https://images.unsplash.com/photo-1573080496216-bdf810fc1d97?auto=format&fit=crop&w=800&q=80',
                'description' => 'Crispy fries with sea salt and paprika aioli.',
                'rating' => 4.5,
                'featured' => false,
            ],
            [
                'name' => 'Grilled Salmon',
                'category' => 'main-meals',
                'price' => 1950,
                'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=800&q=80',
                'description' => 'Atlantic salmon with lemon butter and seasonal greens.',
                'rating' => 4.9,
                'featured' => false,
            ],
            [
                'name' => 'Creamy Pasta Alfredo',
                'category' => 'main-meals',
                'price' => 1200,
                'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?auto=format&fit=crop&w=800&q=80',
                'description' => 'Fettuccine in parmesan cream with grilled chicken.',
                'rating' => 4.7,
                'featured' => false,
            ],
            [
                'name' => 'Beef Steak',
                'category' => 'main-meals',
                'price' => 2200,
                'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=800&q=80',
                'description' => 'Grilled sirloin with peppercorn sauce and vegetables.',
                'rating' => 4.9,
                'featured' => true,
            ],
            [
                'name' => 'New York Cheesecake',
                'category' => 'sweet-delights',
                'price' => 480,
                'image' => 'https://images.unsplash.com/photo-1524351199678-941a58a3df50?auto=format&fit=crop&w=800&q=80',
                'description' => 'Classic creamy cheesecake with berry compote.',
                'rating' => 4.8,
                'featured' => false,
            ],
            [
                'name' => 'Tiramisu',
                'category' => 'sweet-delights',
                'price' => 520,
                'image' => 'https://images.unsplash.com/photo-1571877227209-a0d98ea607e9?auto=format&fit=crop&w=800&q=80',
                'description' => 'Espresso-soaked ladyfingers with mascarpone cream.',
                'rating' => 4.9,
                'featured' => true,
            ],
            [
                'name' => 'Vanilla Ice Cream',
                'category' => 'sweet-delights',
                'price' => 350,
                'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=800&q=80',
                'description' => 'Two scoops of Madagascan vanilla bean ice cream.',
                'rating' => 4.6,
                'featured' => false,
            ],
        ];

        foreach ($items as $row) {
            $name = $row['name'];
            $categorySlug = $row['category'];
            $priceKes = $row['price'];
            $category = $categoryModels->get($categorySlug);
            if (! $category) {
                continue;
            }

            MenuItem::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => $row['description'] ?? null,
                    'price_cents' => $priceKes * 100,
                    'image_path' => $row['image'] ?? null,
                    'rating' => $row['rating'] ?? null,
                    'is_featured' => (bool) ($row['featured'] ?? false),
                    'is_active' => true,
                ]
            );
        }
    }
}
