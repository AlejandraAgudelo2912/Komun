<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Welcome to Komun') }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333;">
<div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h1 style="color: #4F46E5; font-size: 28px; margin-bottom: 20px;">{{ __('Welcome to') }} <span style="color: #111827;">Komun</span>!</h1>

    <p style="font-size: 16px;">{{ __('Hello') }} <strong>{{ $user->name }}</strong>,</p>

    <p style="font-size: 16px;">{{ __('Thank you for registering on') }} <strong>Komun</strong>. {{ __('We are very happy to have you in our community.') }}</p>

    <p style="font-size: 16px;">{{ __('With Komun you can:') }}</p>
    <ul style="font-size: 16px; padding-left: 20px;">
        <li>🤝 {{ __('Create and manage help requests') }}</li>
        <li>🧭 {{ __('Connect with people who need support') }}</li>
        <li>🌍 {{ __('Be part of a supportive community') }}</li>
    </ul>

    <p style="font-size: 16px;">{{ __('Where to start?') }}</p>
    <ol style="font-size: 16px; padding-left: 20px;">
        <li>📝 {{ __('Complete your profile') }}</li>
        <li>🔍 {{ __('Explore existing requests') }}</li>
        <li>➕ {{ __('Create your first request') }}</li>
    </ol>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/') }}" style="display: inline-block; background-color: #4F46E5; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 16px;">
            {{ __('Go to Komun') }}
        </a>
    </div>

    <p style="font-size: 16px;">{{ __('If you have any questions, feel free to write to us. We are here to help.') }}</p>

    <p style="font-size: 16px;">{{ __('Best regards') }},<br><strong>{{ __('The Komun Team') }}</strong></p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #777;">
        <p>{{ __('This is an automated email. Please do not reply to this message.') }}</p>
    </div>
</div>
</body>
</html>
