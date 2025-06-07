<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Application Status') }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; color: #333;">
<div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
    <h2 style="color: #4F46E5;">{{ __('Hello') }} {{ $applicant->name }},</h2>

    <p style="font-size: 16px;">
        {{ __('Your application for the request') }} <strong>"{{ $requestModel->title }}"</strong> {{ __('has been') }}
        <strong style="color: {{ $status === 'accepted' ? '#10B981' : '#EF4444' }};">
            {{ $status === 'accepted' ? __('accepted') : __('rejected') }}
        </strong>.
    </p>

    @if($status === 'accepted')
        <p style="font-size: 16px;">
            🎉 {{ __('Congratulations! They will contact you soon to coordinate the help.') }}
        </p>
    @else
        <p style="font-size: 16px;">
            {{ __('We sincerely appreciate your willingness. ❤️ We encourage you to continue participating in other requests!') }}
        </p>
    @endif

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ url('/') }}" style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-size: 16px;">
            {{ __('View other requests') }}
        </a>
    </div>

    <p style="font-size: 16px; margin-top: 30px;">{{ __('Best regards') }},<br><strong>{{ __('The Local Support Network Team') }}</strong></p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280;">
        <p>{{ __('This is an automated email. Please do not reply directly to this message.') }}</p>
    </div>
</div>
</body>
</html>
