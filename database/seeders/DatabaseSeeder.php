<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Sticker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Badr_kassa',
            'email' => 'badr@stickers.com',
            'password' => Hash::make('badr1997@'), // Use a secure password
            'role' => 'admin', // Set role to admin
        ]);
        Category::create(
            [
                'name' => 'Anime',
                'slug' => 'anime',
                'description' => 'Anime',
                'image' => 'anime-1346364892.jpeg',
            ]

        );
        Category::create(
            [
                'name' => 'Girls',
                'slug' => 'girls',
                'description' => 'girls',
                'image' => 'girls-stickers-5023471257.jpeg',
            ]
        );
        Category::create(
            [
                'name' => 'Music',
                'slug' => 'music',
                'description' => 'music',
                'image' => 'music-5801807409.png',
            ]
        );

        Sticker::create([
            'name' => '80 piece anime stickers',
            'description' => '80 piece anime stickers',
            'slug' => '80-piece-anime-stickers',
            'category_id' => 1,
            'caracteristics' => ['80 piece', 'taille 3 a 12 cm'],
            'price' => 90,
            'discount' => 10,
            'image' => ['80-piece-anime-stickers242762469.png', '80-piece-anime-stickers6288380855.png', '80-piece-anime-stickers7300666431.png']
        ]);
        Sticker::create([
            'name' => 'Aesthetic vintage',
            'description' => 'aesthetic vintage',
            'slug' => 'aesthetic-vintage',
            'category_id' => 1,
            'caracteristics' => ['28 piece', 'taille 3 a 12 cm'],
            'price' => 50,
            'discount' => 10,
            'image' => ['aesthetic-vintage-stickers2248908470.png', 'aesthetic-vintage-stickers5968577116.png']
        ]);
        Sticker::create([
            'name' => 'Attack on titan',
            'description' => 'Attack on titan',
            'slug' => 'attack-on-titan',
            'category_id' => 1,
            'caracteristics' => ['36 piece', 'taille 3 a 12 cm'],
            'price' => 60,
            'discount' => 10,
            'image' => ['attack-on-titan2300321698.png', 'attack-on-titan2571997062.png', 'attack-on-titan5740695109.png','attack-on-titan7928513380.png','attack-on-titan9673027737.png']
        ]);
        Sticker::create([
            'name' => 'Death note',
            'description' => 'Death note',
            'slug' => 'death-note',
            'category_id' => 1,
            'caracteristics' => ['22 piece', 'taille 3 a 12 cm'],
            'price' => 49,
            'discount' => 10,
            'image' => ['death-note355054884.png', 'death-note2238414325.png', 'death-note3311449987.png','death-note5582353623.png']
        ]);
        Sticker::create([
            'name' => 'Dragon ball',
            'description' => 'Dragon ball',
            'slug' => 'dragon-ball',
            'category_id' => 1,
            'caracteristics' => ['37 piece', 'taille 3 a 12 cm'],
            'price' => 59,
            'discount' => 10,
            'image' => ['dragon-ball685163873.png', 'dragon-ball1538128875.png', 'dragon-ball4168557812.png','dragon-ball7861320100.png','dragon-ball8736014297.png']
        ]);
        Sticker::create([
            'name' => 'Naruto',
            'description' => 'Naruto',
            'slug' => 'Naruto',
            'category_id' => 1,
            'caracteristics' => ['30 piece', 'taille 3 a 12 cm'],
            'price' => 50,
            'discount' => 10,
            'image' => ['naruto918504695.png', 'naruto3920276450.png', 'naruto4447400398.png','naruto8933648566.png','naruto9497965378.png']
        ]);
        Sticker::create([
            'name' => 'One piece',
            'description' => 'One piece',
            'slug' => 'one-piece',
            'category_id' => 1,
            'caracteristics' => ['50 piece', 'taille 3 a 12 cm'],
            'price' => 75,
            'discount' => 10,
            'image' => ['one-piece275639780.png', 'one-piece919744723.png', 'one-piece970687298.png','one-piece2738857341.png','one-piece9473122229.png']
        ]);
        Sticker::create([
            'name' => 'Rockmetal bands',
            'description' => 'Rockmetal bands',
            'slug' => 'rockmetal-bands',
            'category_id' => 3,
            'caracteristics' => ['46 piece', 'taille 3 a 12 cm'],
            'price' => 100,
            'discount' => 10,
            'image' => ['rockmetal-bands-sticker2285269265.png', 'rockmetal-bands-sticker5149729106.png', 'rockmetal-bands-sticker5979959556.png','rockmetal-bands-sticker9546576166.png']
        ]);
        Sticker::create([
            'name' => 'Cute girls',
            'description' => 'Cute girls',
            'slug' => 'cute-girls',
            'category_id' => 2,
            'caracteristics' => ['38 piece', 'taille 3 a 12 cm'],
            'price' => 60,
            'discount' => 10,
            'image' => ['cute-girls-stickers3129912395.png', 'cute-girls-stickers3798583741.png']
        ]);

    }
}
