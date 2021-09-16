<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'name' => Page::PRIVACY_POLICY,
                'title' => 'Privacy policy',
                'content' => 'At FarmerXpress, your information are secure!',
            ],
            [
                'name' => 'about',
                'title' => 'About FarmerXpress',
                'content' => 'Welcome to the about page of FarmerXpress!',
            ],
            [
                'name' => 'seller_about',
                'title' => 'Selling on FarmerXpress',
                'content' => 'Selling on FarmerXpress!',
            ],
            [
                'name' => 'logistics_about',
                'title' => 'Logistics on FarmerXpress',
                'content' => 'Logistics on FarmerXpress!',
            ],
            [
                'name' => Page::TERMS,
                'title' => 'terms',
                'content' => 'Welcome to the terms page of FarmerXpress!',
            ],
            [
                'name' => 'seller_terms',
                'title' => 'Selling on FarmerXpress',
                'content' => 'Selling on FarmerXpress!',
            ],
            [
                'name' => 'logistics_terms',
                'title' => 'Logistics on FarmerXpress',
                'content' => 'Logistics on FarmerXpress!',
            ],
        ];
        Page::upsert($pages, ['name']);
    }
}
