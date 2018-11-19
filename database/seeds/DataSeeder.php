<?php

// use App\Models\Article;
// use App\Models\Comment;
use App\Models\ClinicType;
use App\Models\User;

class DataSeeder extends BaseSeeder
{

    protected $usersCount = 20;

    protected $clinicTypes = ["In-patient", "Out-patient ",];

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $users = $this->factory->of(User::class)->times($this->usersCount)->create();

        $clinicTypes = collect($this->clinicTypes)->map(function ($type) {
            return ClinicType::create(['name' => $type]);
        });

        // $users->random($this->usersCount * 0.75)->each(function ($user) use ($tags) {
        //     $this->factory->of(Article::class)->times(rand(1, 5))->create([
        //         'user_id' => $user->id,
        //     ])->each(function (Article $article) use ($tags) {
        //         $article->tags()->sync($tags->random()->pluck('id')->toArray());
        //     });
        // });

        // $articles = Article::all();

        // $articles->each(function (Article $article) {
        //     $this->factory->of(Comment::class)->times(rand(0, 5))->create(
        //         ['article_id' => $article->id, 'user_id' => $this->faker->numberBetween(1, $this->usersCount)]);
        // });

        // $articles->each(function (Article $article) use ($users) {
        //     $article->favorites()->sync($users->random()->pluck('id')->toArray());
        // });

        // $users->each(function (User $user) use ($users) {
        //     $user->followings()->sync($users->random(rand(0, 10))->pluck('id')->toArray());
        // });
    }
}
