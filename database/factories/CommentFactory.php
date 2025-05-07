<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $createdAt = $this->getRandomCreatedAt();
        $updatedAt = $this->getRandomUpdatedAt($createdAt);

        return [
            'text' => $this->faker->text(),
            'is_anonymous' => $this->faker->boolean(),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,

            'article_id' => Article::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }

    private function getRandomCreatedAt(): Carbon
    {
        return Carbon::now()->subHours(mt_rand(1, 10000));
    }

    private function getRandomUpdatedAt(Carbon $createdAt): Carbon
    {
        return mt_rand(0, 1)
            ? (clone $createdAt)->addHours(mt_rand(1, 10))
            : $createdAt;
    }
}
