<?php

use Illuminate\Database\Seeder;
use App\Post; // importo il Model Post
use App\User; // importo il Model User
use Faker\Generator as Faker; // Mi permette di usare Faket
use Illuminate\Support\Str; // Mi permette di usare lo slug



class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i=0; $i < 10; $i++) { 
            $newPost = new Post; // Istanzio l’oggetto Post 
            $newPost->title = $faker->sentence(3);
            $newPost->body = $faker->text(255);
            //Ad ogni slug aggiungo un numero Random
            $newPost->slug = Str::finish(Str::slug($newPost->title), rand(1, 1000000));            
            $newPost->user_id = 1;
            $newPost->save();
        }

    }
}
