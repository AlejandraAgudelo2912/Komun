<?php


it('displays the assistant form', function () {
    //loguearse
    $this->actingAs(\App\Models\User::factory()->create());

    // act
    $response = $this->get(route('assistant.form'));

    // assert
    $response->assertStatus(200);
    $response->assertViewIs('assistant-form');

});
