@extends('mails.template')

@section('content')
    <h3>Invitation</h3>
    <img
        width="150px"
        height="150px"
        src="{{asset('assets/mails/svg/registration-completed.svg')}}"
        alt="EmailIcon"
    />
    <p
        style="
              margin-inline: 30px;
              align-items: center;
              text-align: center;
              margin-top: 2.75rem;
              margin-bottom: 1rem;
            "
    >
        You're invited to join Flexavi as a 'Employee'
        Click button bellow to accept the invitation,
        so you will get a generated default password to login
    </p>
    <a href="#"
    >
        <a
            href="{{config('app.frontend_url') . '/registration?code='.$invitation->registration_code}}"
            style="
                position: relative;
                letter-spacing: 0.02em;
                display: inline-flex;
                align-items: center;
                cursor: pointer;
                padding: 0.6875rem 1.5rem;
                font-size: 0.9375rem;
                line-height: 1.25rem;
                border-radius: 32px;
                color: #fff;
                background-color: #4070ff;
                text-align: center;
                vertical-align: middle;
                border: 1px solid transparent;
                user-select: none;
              "
        >
            Confirm Account
        </a>
    </a
    >
@endsection
