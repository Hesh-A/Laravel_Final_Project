<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

use App\Models\Comment;

uses(RefreshDatabase::class);

it('stores a comment for an authenticated user' , function(){

    $comment =  Comment::factory()->create();

    expect($comment)->toBeInstanceOf(Comment::class);

});