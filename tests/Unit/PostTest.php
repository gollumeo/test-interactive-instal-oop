<?php

use app\Models\Post;


test('Basic test', function () {
    expect(true)->toBeTrue();
});


test('Create-post', function () {
    $now = date('Y-m-d H:i:s');

    $post = Post::create([
        'author' => 'test author',
        'title' => 'test title',
        'body' => 'test content',
        'last_updated' => $now,
    ]);

    expect($post)->toBeInstanceOf(Post::class)
        ->and($post->author)->toBe('test author')
        ->and($post->title)->toBe('test title')
        ->and($post->body)->toBe('test content')
        ->and($post->last_updated)->toBe($now);
});
