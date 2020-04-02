<?php

use Illuminate\Database\Seeder;
use App\Post; // importo il Model Post
use App\Tag; // importo il Model User
use Faker\Generator as Faker; // Mi permette di usare Faket
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        
        for ($i=0; $i < 15; $i++) { 
            $newTag = new Tag; // Istanzio l’oggetto Post 
            $newTag->name = $faker->word();
            $newTag->save();
        }
    }
}
