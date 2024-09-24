@component('mail::message')
{{ __('You have been invited to join the :group group!', ['group' => $invitation->group->name]) }}

{{ __('You may accept this invitation by clicking the button below:') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this group, you may discard this email.') }}
@endcomponent
