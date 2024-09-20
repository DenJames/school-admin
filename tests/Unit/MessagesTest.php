<?php

use App\Models\School;
use App\Models\Team;
use App\Models\User;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;

test('that it can send a message', function () {
    // Arrange
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    $team = Team::factory()->create([
        'user_id' => $sender->id,
        'name' => "{$sender->name}'s Team",
        'school_id' => School::factory(),
    ]);

    $team->users()->attach([$sender->id, $recipient->id]);

    $sender->switchTeam($team);
    $recipient->switchTeam($team);

    // Refresh users to load the updated relationships
    $sender->refresh();
    $recipient->refresh();

    loginAsUser($sender);

    // Act
    post(route('messages.store'), [
        'recipient' => ['id' => $recipient->id],
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Message sent.');

    // Assert
    assert(count($sender->messages) === 1);
    assert(count($recipient->receivedMessages) === 1);
});

test('that a message cannot be sent to a user that are not a part of the senders team', function () {
    // Arrange
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    $team = Team::factory()->create([
        'user_id' => $sender->id,
        'name' => "{$sender->name}'s Team",
        'school_id' => School::factory(),
    ]);

    $team2 = Team::factory()->create([
        'user_id' => $recipient->id,
        'name' => "{$recipient->name}'s Team",
        'school_id' => School::factory(),
    ]);

    $team->users()->attach([$sender->id]);
    $team2->users()->attach([$recipient->id]);

    loginAsUser($sender);

    // Act
    post(route('messages.store'), [
        'recipient' => ['id' => $recipient->id],
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ])
        ->assertRedirect()
        ->assertSessionHasErrors('message');

    // Assert
    assert(count($sender->messages) === 0);
    assert(count($recipient->receivedMessages) === 0);
});

test('that the sender can delete their message', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $message = $sender->messages()->create([
        'receiver_id' => $recipient->id,
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ]);

    loginAsUser($sender);

    delete(route('messages.destroy', $message))
        ->assertRedirect()
        ->assertSessionHas('success', 'Message deleted.');

    assert(count($sender->messages) === 0);
    assert(count($recipient->receivedMessages) === 0);
});


test('that only the author can delete the message', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $message = $sender->messages()->create([
        'receiver_id' => $recipient->id,
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ]);

    loginAsUser($recipient);

    delete(route('messages.destroy', $message))
        ->assertStatus(403);

    assert(count($sender->messages) === 1);
    assert(count($recipient->receivedMessages) === 1);
});


test('that a reply can be made to a message', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $message = $sender->messages()->create([
        'receiver_id' => $recipient->id,
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ]);

    loginAsUser($recipient);

    post(route('message.reply', $message), [
        'content' => 'I am good, thank you.',
    ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Reply sent.');

    assert(count($message->replies) === 1);
});

test('that it is not possible to reply to a message you are not a part of', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $otherUser = User::factory()->create();
    $message = $sender->messages()->create([
        'receiver_id' => $recipient->id,
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ]);

    loginAsUser($otherUser);

    post(route('message.reply', $message), [
        'content' => 'I am good, thank you.',
    ])
        ->assertStatus(403);

    assert(count($message->replies) === 0);
});

test('that a reply can be deleted from a message', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $message = $sender->messages()->create([
        'receiver_id' => $recipient->id,
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ]);
    $reply = $message->replies()->create([
        'user_id' => $recipient->id,
        'content' => 'I am good, thank you.',
    ]);

    loginAsUser($recipient);

    delete(route('message.reply.destroy', $reply))
        ->assertRedirect()
        ->assertSessionHas('success', 'Reply deleted.');

    assert(count($message->replies) === 0);
});

test('that a reply can only be deleted by its author', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();
    $message = $sender->messages()->create([
        'receiver_id' => $recipient->id,
        'subject' => 'Hello',
        'content' => 'Hello, how are you?',
    ]);
    $reply = $message->replies()->create([
        'user_id' => $recipient->id,
        'content' => 'I am good, thank you.',
    ]);

    loginAsUser($sender);

    delete(route('message.reply.destroy', $reply))
        ->assertStatus(403);

    assert(count($message->replies) === 1);
});
