<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateDummyQuote extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quotes = [
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],
            [
                'quote' => 'Laravel Product CRUD Tutorial',
            ],

        ];

        foreach ($quotes as $key => $value)
        {
            Quote::create([
                'quote' => $value['quote'],
            ]);
        }
    }
}
