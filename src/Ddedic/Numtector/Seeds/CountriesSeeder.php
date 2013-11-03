<?php namespace Ddedic\Numtector\Seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorsSeeder extends Seeder {

    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run() {

        $plans = array(
            array(
                'name'              => 'Default plan',
                'description'       => 'Default plan with strict 25% price adjustment',
                'price_adjustment'  => '25',
                'strict'            => '1',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            ),

            array(
                'name'              => 'Cheap plan',
                'description'       => 'Default plan with strict 10% price adjustment',
                'price_adjustment'  => '10',
                'strict'            => '1',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            )
        );
        DB::table('plans')->insert($plans);
        $this->command->info('Operators Table Seeded');



    }
}