<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Document</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }
    </style>
</head>

<body>
<div
    style="
        height: 100%;
        flex-direction: column;
        background-color: rgb(238, 238, 238);
      "
>
    <div style="position: absolute; top: 30px; left: 50px">
        <a
            style="position: relative; display: inline-block; align-items: center"
            href="#"
        ><img
                style="max-height: 30px"
                src="{{asset('assets/mails/svg/logoWhite.svg')}}"
                alt="logo"
            /></a>
    </div>
    <div
        style="
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          background-color: rgb(64, 112, 255);
          height: 50vh;
        "
    >
        <div
            style="
            box-shadow: 0 1px 3px 0 rgb(54 74 99 / 5%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: rgb(255, 255, 255);
            height: auto;
            width: 30%;
            bottom: 30%;
            position: absolute;
            padding: 30px;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, 0.125);
          "
        >
            @yield('content')
        </div>
        <div
            style="
            position: absolute;
            bottom: 10px;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            color: rgb(204, 204, 204);
          "
        >
            <div>
            <span
            >© 2022 FlexAvi, All rights reserved.<br/>Burg van Vrijberghestr
              90, Tholen, Zeeland, 06-21509781</span
            >
            </div>
            <div>
                <div
                    style="
                position: relative;
                padding: 1.5rem;
                margin: auto;
                text-align: center;
              "
                >
                    <a style="text-decoration: none; margin: 15px" href="#"
                    >
                        <button
                            class="btn"
                            style="
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    background-color: rgb(64, 112, 255);
                    justify-content: center;

                    position: relative;
                    letter-spacing: 0.02em;
                    display: inline-flex;
                    align-items: center;

                    border: 1px solid transparent;
                    padding: 0.4375rem 1.125rem;
                    font-size: 0.8125rem;
                    line-height: 1.25rem;
                    user-select: none;

                    text-align: center;
                    vertical-align: middle;
                  "
                        >
                            <img
                                src="{{asset('assets/mails/svg/facebook-rounded-white.svg')}}"
                                style="max-width: 64px"
                                alt="facebook"
                            /></button
                        >
                    </a>
                    <a style="text-decoration: none; margin: 15px" href="#"
                    >
                        <button
                            style="
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    background-color: rgb(64, 112, 255);
                    justify-content: center;

                    position: relative;
                    letter-spacing: 0.02em;
                    display: inline-flex;
                    align-items: center;

                    border: 1px solid transparent;
                    padding: 0.4375rem 1.125rem;
                    font-size: 0.8125rem;
                    line-height: 1.25rem;
                    user-select: none;

                    text-align: center;
                    vertical-align: middle;
                  "
                        >
                            <img
                                src="{{asset('assets/mails/svg/linkedin-rounded.svg')}}"
                                style="max-width: 64px"
                                alt="linkedin"
                            /></button
                        >
                    </a>
                    <a style="text-decoration: none; margin: 15px" href="#"
                    >
                        <button
                            style="
                    width: 45px;
                    height: 45px;
                    border-radius: 50%;
                    background-color: rgb(64, 112, 255);
                    justify-content: center;

                    position: relative;
                    letter-spacing: 0.02em;
                    display: inline-flex;
                    align-items: center;

                    border: 1px solid transparent;
                    padding: 0.4375rem 1.125rem;
                    font-size: 0.8125rem;
                    line-height: 1.25rem;
                    user-select: none;

                    text-align: center;
                    vertical-align: middle;
                  "
                        >
                            <img
                                src="{{asset('assets/mails/svg/call.svg')}}"
                                style="max-width: 64px"
                                alt="call"
                            /></button
                        >
                    </a>
                </div>
            </div>
            <div
                style="display: flex; flex-direction: column"
            >
            <span
            >You received this email because you signed up for Flexavi</span
            ><a href="#" style="color: rgb(64, 112, 255)"><u>Unsubcribe</u></a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
