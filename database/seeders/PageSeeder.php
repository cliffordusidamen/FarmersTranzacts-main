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
                'slug' => Page::PRIVACY_POLICY,
                'title' => 'Privacy policy',
                'description' => 'General privacy policy. Used on the main website',
                'content' => 'At FarmerXpress, your information are secure!',
            ],
            [
                'slug' => 'about',
                'title' => 'About FarmerXpress',
                'description' => 'About content on the main website',
                'content' => 'Welcome to the about page of FarmerXpress!',
            ],
            [
                'slug' => 'seller_about',
                'title' => 'Selling on FarmerXpress',
                'description' => 'Information about selling on Farmer Xpress, displayed on the seller website',
                'content' => 'Selling on FarmerXpress!',
            ],
            [
                'slug' => 'agent_about',
                'title' => 'Agent on FarmerXpress',
                'description' => 'Information about being an agent on Farmer Xpress, displayed on the agent page',
                'content' => 'Agent on FarmerXpress!',
            ],
            [
                'slug' => Page::TERMS,
                'title' => 'terms',
                'description' => 'Terms & Conditions (displayed on the main website)',
                'content' => 'Welcome to the terms page of FarmerXpress!',
            ],
            [
                'slug' => 'seller_terms',
                'title' => 'Terms & Conditions for selling on FarmerXpress',
                'description' => 'Terms & Conditions (displayed on the seller website)',
                'content' => 'Selling on FarmerXpress!',
            ],
            [
                'slug' => 'agent_terms',
                'title' => 'Terms & Conditions for selling for agents',
                'description' => 'Terms & Conditions (displayed on the agent page)',
                'content' => 'agent on FarmerXpress!',
            ],
        ];
        Page::upsert($pages, ['slug']);
    }
}
