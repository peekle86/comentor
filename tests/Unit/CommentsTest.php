<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\ArticleCommented;


beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->article = Article::factory()->create();
    $this->comment = Comment::factory()->create();
});

test('guest tryna get comments', function () {
    $this->get('/api/comments')
        ->assertUnauthorized();
});

test('user tryna get comments with different filters', function () {
    Article::factory(10)->create();
    User::factory(10)->create();
    Comment::factory(20)->create();
    Comment::factory()->create([
        'text' => 'Exactly'
    ]);

    $this->actingAs($this->user)
        ->get('/api/comments')
        ->assertOk()
        ->assertJsonCount(15, 'data')
        ->assertJsonStructure(['data', 'links', 'meta']);

    // filter by article_id
    $commentsByArticleId = $this->actingAs($this->user)
        ->get('/api/comments?' . http_build_query([
            'article_id' => $this->article->id,
        ]))
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);

    foreach ($commentsByArticleId['data'] as $index => $item) {
        $this->assertEquals(
            $this->article->id,
            $item['article_id'],
            "Item at index {$index} has unexpected article_id: " . $item['article_id']
        );
    }

    // filter by user_id
    $commentsByArticleId = $this->actingAs($this->user)
        ->get('/api/comments?' . http_build_query([
            'user_id' => $this->user->id,
        ]))
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);

    foreach ($commentsByArticleId['data'] as $index => $item) {
        $this->assertEquals(
            $this->user->id,
            $item['user_id'],
            "Item at index {$index} has unexpected user_id: " . $item['user_id']
        );
    }

    // filter only anonymous
    $commentsByArticleId = $this->actingAs($this->user)
        ->get('/api/comments?' . http_build_query([
                'is_anonymous' => 1,
            ]))
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);

    foreach ($commentsByArticleId['data'] as $index => $item) {
        $this->assertEquals(
            true,
            $item['is_anonymous'],
            "Item at index {$index} has unexpected is_anonymous: " . $item['is_anonymous']
        );
    }

    // search by text
    $commentsByArticleId = $this->actingAs($this->user)
        ->get('/api/comments?' . http_build_query([
            'search' => 'Exactly',
        ]))
        ->assertOk()
        ->assertJsonStructure(['data', 'links', 'meta']);

    foreach ($commentsByArticleId['data'] as $index => $item) {
        $this->assertStringContainsString(
            'Exactly',
            $item['text'],
            "Item at index {$index} does not contain 'Exactly' in text."
        );
    }
});

test('guest tryna store comment', function () {
    Notification::fake();

    $this->post('/api/comments', [
            'text' => fake()->text(),
            'article_id' => $this->article->id,
            'is_anonymous' => false,
        ])
        ->assertUnauthorized();

    Notification::assertNothingSent();
});

test('user tryna store comment and event triggered', function () {
    Notification::fake();

    $article = Article::factory()->create();

    // user creating first comment
    $this->actingAs($this->user)
        ->post('/api/comments', [
            'text' => fake()->text(),
            'article_id' => $article->id,
            'is_anonymous' => false,
        ])
        ->assertCreated();
    Notification::assertNothingSent();

    // another user creates comment
    $this->actingAs($this->otherUser)
        ->post('/api/comments', [
            'text' => fake()->text(),
            'article_id' => $article->id,
            'is_anonymous' => false,
        ])
        ->assertCreated();
    Notification::assertSentTo([$this->user], ArticleCommented::class);
});

test('guest tryna show comment', function () {
    $this->get('/api/comments/'.$this->comment->id)
        ->assertUnauthorized();
});

test('user tryna show comment', function () {
    $this->actingAs($this->user)
        ->get('/api/comments/'.$this->comment->id)
        ->assertOk()
        ->assertJsonStructure(['data']);
});

test('guest tryna update comment', function () {
    $this->put('/api/comments/'.$this->comment->id, [
            'text' => 'Comment text'
        ])
        ->assertUnauthorized();
});

test('user tryna update not own comment', function () {
    $comment = Comment::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->otherUser)
        ->put('/api/comments/'.$comment->id, [
            'text' => 'Comment text'
        ])
        ->assertStatus(403);
});

test('user tryna update own comment and comment is updating correctly', function () {
    $comment = Comment::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)
        ->put('/api/comments/'.$comment->id, [
            'text' => 'Comment text'
        ])
        ->assertOk();

    $this->assertEquals(
        'Comment text',
        $response['data']['text'],
        "Comment text not updated"
    );
});

test('guest tryna delete comment', function () {
    $this->delete('/api/comments/'.$this->comment->id)
        ->assertUnauthorized();
});

test('user tryna delete not owned comment', function () {
    $comment = Comment::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->otherUser)
        ->delete('/api/comments/'.$comment->id)
        ->assertStatus(403);
});

test('user tryna delete own comment', function () {
    $comment = Comment::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->delete('/api/comments/'.$comment->id)
        ->assertNoContent();
});
