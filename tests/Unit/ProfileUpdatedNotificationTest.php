<?php

use App\Models\User;
use App\Notifications\ProfileUpdatedNotification;
use Illuminate\Notifications\Messages\MailMessage;

it('uses the mail channel', function () {
    $user = User::factory()->create();
    $notification = new ProfileUpdatedNotification($user, 'old@example.com');

    expect($notification->via($user))->toBe(['mail']);
});

it('builds the expected mail message', function () {
    $user = User::factory()->create();
    $notification = new ProfileUpdatedNotification($user, 'old@example.com');

    $mail = $notification->toMail($user);

    expect($mail)->toBeInstanceOf(MailMessage::class);
    expect($mail->introLines)->toContain('Your profile has been updated successfully.');
    expect($mail->actionText)->toBe('View Profile');
    expect($mail->actionUrl)->toBe(url('/profile/edit'));
    expect($mail->outroLines)->toContain('Thank you for using our application!');
});

it('returns an array representation', function () {
    $user = User::factory()->create();
    $notification = new ProfileUpdatedNotification($user, 'old@example.com');

    expect($notification->toArray($user))->toBeArray();
});
