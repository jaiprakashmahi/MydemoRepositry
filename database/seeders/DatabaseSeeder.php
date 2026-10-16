<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // States
        $bihar = \App\Models\State::create([
            'name' => 'Bihar',
            'slug' => 'bihar',
            'code' => 'BR',
            'is_active' => true,
        ]);
        $up = \App\Models\State::create([
            'name' => 'Uttar Pradesh',
            'slug' => 'uttar-pradesh',
            'code' => 'UP',
            'is_active' => true,
        ]);

        // Cities
        \App\Models\City::create(['state_id' => $bihar->id, 'name' => 'Patna', 'slug' => 'patna', 'is_popular' => true]);
        \App\Models\City::create(['state_id' => $bihar->id, 'name' => 'Gaya', 'slug' => 'gaya']);
        \App\Models\City::create(['state_id' => $bihar->id, 'name' => 'Muzaffarpur', 'slug' => 'muzaffarpur']);
        \App\Models\City::create(['state_id' => $up->id, 'name' => 'Lucknow', 'slug' => 'lucknow', 'is_popular' => true]);

        // Property Types
        $types = [
            ['name' => 'Apartment', 'slug' => 'apartment', 'icon' => 'fas fa-building'],
            ['name' => 'Independent House', 'slug' => 'independent-house', 'icon' => 'fas fa-home'],
            ['name' => 'Villa', 'slug' => 'villa', 'icon' => 'fas fa-tree'],
            ['name' => 'Plot / Land', 'slug' => 'plot-land', 'icon' => 'fas fa-map', 'is_residential' => true],
            ['name' => 'Office Space', 'slug' => 'office-space', 'icon' => 'fas fa-briefcase', 'is_residential' => false],
            ['name' => 'Shop / Retail', 'slug' => 'shop-retail', 'icon' => 'fas fa-store', 'is_residential' => false],
        ];
        foreach ($types as $type) {
            \App\Models\PropertyType::create($type);
        }

        // Categories
        $cats = [
            ['name' => 'Apartments', 'slug' => 'apartments', 'icon' => 'fas fa-building'],
            ['name' => 'Villas', 'slug' => 'villas', 'icon' => 'fas fa-tree'],
            ['name' => 'Plots', 'slug' => 'plots', 'icon' => 'fas fa-map'],
            ['name' => 'Commercial', 'slug' => 'commercial', 'icon' => 'fas fa-briefcase'],
        ];
        foreach ($cats as $cat) {
            \App\Models\Category::create($cat);
        }

        // Amenities
        $amenities = [
            ['name' => 'Swimming Pool', 'slug' => 'swimming-pool', 'icon' => 'fas fa-swimmer', 'group' => 'luxury'],
            ['name' => 'Gym', 'slug' => 'gym', 'icon' => 'fas fa-dumbbell', 'group' => 'luxury'],
            ['name' => 'Parking', 'slug' => 'parking', 'icon' => 'fas fa-parking', 'group' => 'basic'],
            ['name' => 'Lift', 'slug' => 'lift', 'icon' => 'fas fa-elevator', 'group' => 'basic'],
            ['name' => '24x7 Security', 'slug' => '24x7-security', 'icon' => 'fas fa-shield-alt', 'group' => 'safety'],
            ['name' => 'Power Backup', 'slug' => 'power-backup', 'icon' => 'fas fa-bolt', 'group' => 'utility'],
            ['name' => 'Wifi', 'slug' => 'wifi', 'icon' => 'fas fa-wifi', 'group' => 'utility'],
            ['name' => 'Garden', 'slug' => 'garden', 'icon' => 'fas fa-leaf', 'group' => 'outdoor'],
        ];
        foreach ($amenities as $a) {
            \App\Models\Amenity::create($a);
        }
    }
}
