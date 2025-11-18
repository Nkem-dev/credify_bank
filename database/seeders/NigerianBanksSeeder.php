<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NigerianBanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // populate 21 nigerian cbn approved banks
        $banks = [
         ['code' => '044', 'name' => 'Access Bank'],
         ['code' => '023', 'name' => 'Citibank Nigeria'],
         ['code' => '063', 'name' => 'Diamond Bank'],
         ['code' => '050', 'name' => 'Ecobank Nigeria'],
         ['code' => '084', 'name' => 'Enterprise Bank'],
         ['code' => '070', 'name' => 'Fidelity Bank'],
         ['code' => '011', 'name' => 'First Bank of Nigeria'],
         ['code' => '214', 'name' => 'First City Monument Bank'],
         ['code' => '058', 'name' => 'Guaranty Trust Bank'],
         ['code' => '030', 'name' => 'Heritage Bank'],
         ['code' => '301', 'name' => 'Jaiz Bank'],
         ['code' => '082', 'name' => 'Keystone Bank'],
         ['code' => '076', 'name' => 'Polaris Bank'],
         ['code' => '101', 'name' => 'Providus Bank'],
         ['code' => '039', 'name' => 'Stanbic IBTC Bank'],
         ['code' => '232', 'name' => 'Sterling Bank'],
         ['code' => '032', 'name' => 'Union Bank of Nigeria'],
         ['code' => '033', 'name' => 'United Bank For Africa'],
         ['code' => '215', 'name' => 'Unity Bank'],
         ['code' => '035', 'name' => 'Wema Bank'],
         ['code' => '057', 'name' => 'Zenith Bank'],
        ];
        // loops through your array of banks
        foreach ($banks as $bank) {
            // uses laravel query builder to target the nigerian banks database table and if the bank exists and matches , update it but if not insert new recordr
            DB::table('nigerian_banks')->updateOrInsert(
                ['code' => $bank['code']],
                [
                'name' => $bank['name'],
                'created_at' => now(),
                'updated_at' => now(),
        ]
                
            );
        }
    }
}
