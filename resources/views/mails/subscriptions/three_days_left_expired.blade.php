<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>De {{$subscription->subscriptionPlanPeriod->subscriptionPlan->name}} verloopt binnenkort!</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            background-color: rgb(238, 238, 238);
        }

        .container {
            flex-direction: column;
        }

        .container-top {
            background-color: rgb(64, 112, 255);
            height: 50vh;
        }

        .logo {
            position: relative;
            display: inline-block;
            align-items: center;
            top: 30px;
            left: 50px;
        }

        .image-logo {
            max-height: 30px;
        }

        .container-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: -250px;
        }

        .card {
            box-shadow: 0px 1px 3px 0px rgb(54 74 99 / 5%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: rgb(255, 255, 255);
            width: 30%;
            height: auto;
            bottom: 20%;
            padding: 30px;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, 0.125);
        }

        .card-title {
            margin-bottom: 40px;
        }

        .card-image {
            width: 150px;
            height: 150px;
        }

        .card-body-top {
            margin-inline: 30px;
            align-items: center;
            text-align: center;
            margin-top: 2.75rem;
            margin-bottom: 1rem;
            font-size: 14px;
            line-height: 28px;
            color: #000000;
            width: 100%;
        }

        .card-body-bottom {
            width: 100%;
            margin-inline: 30px;
            align-items: center;
            text-align: center;
            margin-top: 1rem;
            font-weight: 400;
            font-size: 14px;
            line-height: 28px;
            color: #000000;
        }

        .card-link-button {
            text-decoration: none;
        }

        .card-button {
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
            border-color: #4070ff;
            text-align: center;
            vertical-align: middle;
            border: 1px solid transparent;
            user-select: none;
        }

        .footer {
            width: 100%;
            position: relative;
            bottom: 0;
            left: 0;
            padding-bottom: 20px;
        }

        .content-footer {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            color: rgb(204, 204, 204);
        }

        .footer-logo {
            width: 100px;
            height: 100px;
        }

        @media only screen and (max-width: 992px) {
            .container {
                height: 100vh;
            }

            .card {
                width: 80%;
                height: auto;
                padding: 30px;
            }

            .logo {
                top: 20px;
                left: 10px;
            }

            .card-image {
                width: 100px;
                height: 100px;
            }

            .content-footer {
                font-size: 12px;
                line-height: 18px;
                text-align: center;
            }

            .footer-logo {
                width: 50px;
                height: 50px;
            }
        }

        @media only screen and (max-width: 330px) {
            .container-content {
                margin-top: -150px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="container-top">
        <a class="logo" href="#">
            <img class="image-logo" srcset="{{asset('assets/mails/subscription/logo.svg')}}" alt="logo"/>
        </a>
    </div>
    <div class="container-content">
        <div class="card">
            <h3 class="card-title"> De {{$subscription->subscriptionPlanPeriod->subscriptionPlan->name}} verloopt
                binnenkort! </h3>
            <img class="card-image" srcset="{{asset('assets/mails/subscription/banner.svg')}}" alt="EmailIcon"/>
            <p class="card-body-top">
						<span>
							<b> Beste {{$user->fullname}}, </b>
						</span>
                <br/>
                <span> U maakt nu bijna 2 weken gebruik van Flexavi. We zijn erg benieuwd hoe u het ervaren heeft. Over 3 dagen verloopt de {{$subscription->subscriptionPlanPeriod->subscriptionPlan->name}} en zal het systeem bevriezen. Wilt u geen werk verliezen en langer gebruik maken van Flexavi? </span>
                <br>
                <span> Zorg dat de abonnementsgegevens succesvol ingevuld zijn, zodat u direct weer verder kan met Flexavi! <br> Dit kunt u hier doen: </span>
            </p>
            <a class="card-link-button" href="#">
                <button class="card-button" type="submit"> Abonnement Aanmaken</button>
            </a>
            <p class="card-body-bottom">
                <span> Met vriendelijke groet, Het team van Flexavi</span>
                <br/>
            </p>
        </div>
    </div>
    <div class="footer">
        <div class="content-footer">
            <img class="footer-logo" srcset="{{asset('assets/mails/subscription/logo-bottom.svg')}}" alt="logo bottom"/>
            <div>
                <span> © 2022 FlexAvi, All rights reserved. <br/> Burg van Vrijberghestr 90, Tholen, Zeeland, 06-21509781 </span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
