<?php


it('shows the correct messages when clearing cache', function () {
    // act
    Artisan::call('komun:clear-cache');

    // assert
    expect(Artisan::output())
        ->toContain('Limpiando cachés...')
        ->toContain('✅ Cachés limpiadas correctamente.');
});

it('clears all caches and shows success message', function () {
    // act
    Artisan::call('komun:clear-cache');

    // assert
    expect(Artisan::output())
        ->toContain('Limpiando cachés...')
        ->toContain('✅ Cachés limpiadas correctamente.');
});



