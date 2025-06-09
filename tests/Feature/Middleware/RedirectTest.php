<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

it('redirige a admin.dashboard si el usuario tiene rol admin', function () {
    // arrange
    $middleware = new RedirectIfAuthenticated;

    $user = new class
    {
        public function hasRole($role)
        {
            return $role === 'admin';
        }
    };

    Auth::shouldReceive('guard->check')->andReturn(true);
    Auth::shouldReceive('guard->user')->andReturn($user);

    $request = Request::create('/login', 'GET');

    // act
    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    // assert
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toBe(route('admin.dashboard'));
});

it('redirige a god.dashboard si el usuario tiene rol god', function () {
    $middleware = new RedirectIfAuthenticated;

    $user = new class
    {
        public function hasRole($role)
        {
            return $role === 'god';
        }
    };

    Auth::shouldReceive('guard->check')->andReturn(true);
    Auth::shouldReceive('guard->user')->andReturn($user);

    $request = Request::create('/login', 'GET');

    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toBe(route('god.dashboard'));
});

it('redirige a verificator.dashboard si el usuario tiene rol verificator', function () {
    $middleware = new RedirectIfAuthenticated;

    $user = new class
    {
        public function hasRole($role)
        {
            return $role === 'verificator';
        }
    };

    Auth::shouldReceive('guard->check')->andReturn(true);
    Auth::shouldReceive('guard->user')->andReturn($user);

    $request = Request::create('/login', 'GET');

    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toBe(route('verificator.dashboard'));
});

it('redirige a assistant.dashboard si el usuario tiene rol assistant', function () {
    $middleware = new RedirectIfAuthenticated;

    $user = new class
    {
        public function hasRole($role)
        {
            return $role === 'assistant';
        }
    };

    Auth::shouldReceive('guard->check')->andReturn(true);
    Auth::shouldReceive('guard->user')->andReturn($user);

    $request = Request::create('/login', 'GET');

    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toBe(route('assistant.dashboard'));
});

it('redirige a needhelp.dashboard si el usuario tiene rol needHelp', function () {
    $middleware = new RedirectIfAuthenticated;

    $user = new class
    {
        public function hasRole($role)
        {
            return $role === 'needHelp';
        }
    };

    Auth::shouldReceive('guard->check')->andReturn(true);
    Auth::shouldReceive('guard->user')->andReturn($user);

    $request = Request::create('/login', 'GET');

    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toBe(route('needhelp.dashboard'));
});

it('redirige a home si el usuario no tiene rol especificado', function () {
    $middleware = new RedirectIfAuthenticated;

    $user = new class
    {
        public function hasRole($role)
        {
            return false;
        }
    };

    Auth::shouldReceive('guard->check')->andReturn(true);
    Auth::shouldReceive('guard->user')->andReturn($user);

    $request = Request::create('/login', 'GET');

    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
    expect($response->getTargetUrl())->toBe(url('/'));
});

it('pasa la peticion al siguiente middleware si no hay usuario autenticado', function () {
    $middleware = new RedirectIfAuthenticated;

    Auth::shouldReceive('guard->check')->andReturn(false);

    $request = Request::create('/login', 'GET');

    $response = $middleware->handle($request, fn ($req) => new Response('next called'));

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getContent())->toBe('next called');
});
