<?php

namespace Database\Seeders;

use App\Models\HelpPage;
use Illuminate\Database\Seeder;

class HelpPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HelpPage::upsert($this->_getInitialPages(), ['slug']);
    }

    /**
     * Get the initial pages to seed the database with.
     * 
     * @return array
     */
    private function _getInitialPages(): array
    {
        return [
            [
                'slug' => 'place-and-track-orders',
                'title' => 'Place and track orders',
                'description' => 'Track your order status and see your order history',
                'content' => 'Place and track orders!',
                'published' => true,
            ],
            [
                'slug' => 'returns-and-refunds',
                'title' => 'Returns and refunds',
                'description' => 'Return and refund policy',
                'content' => 'Place and track orders!',
                'published' => true,
            ],
            [
                'slug' => 'order-cancellation',
                'title' => 'Order cancellation',
                'description' => 'Cancellation policy',
                'content' => 'How to cancel your order!',
                'published' => true,
            ],
            [
                'slug' => 'how-to-pay',
                'title' => 'Payment methods',
                'description' => 'How to pay items',
                'content' => '<h4>How to pay for items</h4><img src="https://farmerstranzacts.com/molla-template/images/payments.png" />',
                    'published' => true,
            ],
        ];
    }

}
