<?php

it('shows the correct messages when clearing cache', function () {
    // act
    Artisan::call('komun:clear-cache');

    // assert
    expect(Artisan::output())
        ->toContain('Cleaning caches...')
        ->toContain('Caches cleared!');
});

it('clears all caches and shows success message', function () {
    // act
    Artisan::call('komun:clear-cache');

    // assert
    expect(Artisan::output())
        ->toContain('Cleaning caches...')
        ->toContain('Caches cleared!');
});
