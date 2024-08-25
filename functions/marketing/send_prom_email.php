<?php
add_action('wp_ajax_send_prom_email', 'send_prom_email');
add_action('wp_ajax_nopriv_send_prom_email', 'send_prom_email');
function send_prom_email()
{
    $email = $_POST['email'];
    $to = $email;
    $subject = '10% הנחה למוצרים מחנויות שמכירים!';
    $body = '<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotional Email | Shopper</title>
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap");


        body {
            margin: 0;
        }

        table {
            border-spacing: 0;
        }

        td {
            padding: 0;
        }

        img {
            border: 0;
        }

        .main {
            max-width: 700px;
        }

        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
        }

        p {
            margin: 0;
        }

        .main-container {
            max-width: 800px;
            margin: auto;
        }

        .header {
            width: 100%;
            background-color: #3735AC;
            color: white;
            padding: 20px 60px 0px 60px;
            direction: rtl;
        }

        .header>.header-column {
            max-width: 350px;
            display: inline-block;
            vertical-align: top;
        }

        .header-right {
            text-align: center;
        }

        .header-left {
            text-align: left;
        }

        .header-left-h-s {
            font-family: "Dancing Script", cursive;
            font-weight: bold;
            font-size: 20px;

        }

        .header-left-h-l {
            font-size: 64px;
            font-weight: bold;
        }

        .header-right-h-s {
            font-size: 17px;
        }

        .header-right-h-l {
            font-size: 50px;
            font-weight: bold;
            direction: rtl;
        }

        .header-bottom {
            margin: auto;
            margin-top: 30px;
        }

        .header-bottom-content>span {
            width: fit-content;
            margin: auto;
            background-color: #5271FF;
            font-weight: bold;
            font-size: 1.4rem;
            padding: 4px 50px 4px 50px;

        }

        .body {
            padding: 20px 60px;
            font-size: 19px;
            color: #3735AC;
        }

        .body-cont {
            background-color: #E8EFF9;
            border-radius: 20px;
            direction: rtl;
            width: 100%;
            padding: 0 80px;
        }

        .body-1 {
            text-align: center;
            padding: 20px 0 20px 0px;
            font-weight: bold;
            font-size: 25px;
        }

        .steps {
            margin-bottom: 1rem;
        }

        .bullet {
            color: white;
            font-weight: bold;
            font-size: 24px;
            background-color: #3735AC;
            padding: 12px 22px;
            border-radius: 100%;
            display: inline-block;
        }

        .bulet-set {
            position: relative;
            top: -14px;
        }

        .text {
            align-self: center;
            margin-right: 10px;
            display: inline-block;
            max-width: 300px;
        }

        .body-3 {
            background-image: url("https://ilshopper5stg.wpengine.com/wp-content/themes/bacola/assets/images/coupon-image.png");
            background-position: center;
            background-repeat: no-repeat;
            background-size: 360px auto;
            background-origin: content-box;
        }

        .body-3 .coupon-text {
            text-align: center;
            font-size: 20px;
            width: fit-content;
            margin: auto;
        }

        .coupon-text .white {
            color: white;
            font-weight: bold;
        }

        .coupon-text .special {
            background-color: white;
            padding: 8px 40px;
            width: fit-content;
            margin: auto;
            letter-spacing: 4px;
            margin: 15px auto;
        }

        .coupon-text .small {
            font-size: 15px;
        }

        .body-4 {
            text-align: center;
            padding: 20px 0px;
        }

        .footer {
            width: 100%;
            background-color: #E8EFF9;
            color: #000000;
            padding: 30px 70px 30px 70px;
        }

        .footer>.footer-column {
            display: inline-block;
            vertical-align: top;
        }

        .footer-right {
            text-align: center;

        }

        .footer-right>p {
            direction: rtl;
            text-align: right;
            width: 370px;
            font-size: 17px;
        }

        .footer-left {
            text-align: left;
            font-size: 12px;
        }

        .link-1 {
            color: #3735AC;
            text-decoration: none;
        }

        .link-2 {
            color: #3735AC;
            text-decoration: underline;
        }

        .link-3 {
            color: #000000;
            text-decoration: underline;
        }

        @media (max-width:660px) {
            .main {
                width: 100%;
                max-width: none;
            }

            .header {
                padding: 4vw 4vw 1vw 4vw;
            }

            .header-left-h-s {
                font-size: 3vw;
            }

            .header-left-h-l {
                font-size: 10vw;
            }

            .header>.header-column {
                max-width: fit-content;
            }

            .header-right-h-s {
                font-size: 3vw;
            }

            .header-right-h-l {
                font-size: 8vw;
            }

            .header-bottom-content>span {
                font-size: 4vw;
            }

            .body {
                padding: 4vw 6vw;
                font-size: 3.4vw;
            }

            .body-cont {
                padding: 0 4vw 0 3vw;
            }

            .body-1 {
                padding: 20px 0px;
                font-size: 5vw;
            }

            .text {
                max-width: 50vw;
            }

            .body-3 {
                background-size: 360px auto;
            }

            .body-3 .coupon-text {
                font-size: 4vw;
                margin-right: 0 !important;
            }

            .coupon-text .special {
                padding: 1vw 3vw;
            }

            .coupon-text .small {
                font-size: 2.3vw;
            }

            .footer {
                padding: 5vw 6vw 5vw 6vw;
            }

            .f-heading-l {
                font-size: 3vw !important;
                margin-bottom: 1vw !important;
            }

            .footer-left {
                font-size: 2.4vw;
            }

            .footer-right>p {
                width: 41vw;
                font-size: 2.8vw;
            }
        }

        @media (max-width: 590px) {
            .body-3 {
                background-size: 306px auto;
            }
        }

        @media (max-width: 470px) {
            .body-3 {
                background-size: 272px auto;
            }
        }

        @media (max-width: 350px) {
            .body-3 {
                background-size: 204px auto;
            }
        }
    </style>
</head>

<body>

    <center class="wrapper">

        <table class="main" width="100%">

            <!-- HEADER -->
            <tr style="background-color: #3735AC;">
                <td class="header">
                    <table class="header-column" style="float: left;">
                        <tr>
                            <td class="header-left">
                                <p class="header-left-h-s">the</p>
                                <p class="header-left-h-l">SALE</p>
                            </td>
                        </tr>
                    </table>
                    <table class="header-column">
                        <tr>
                            <td class="header-right">
                                <p class="header-right-h-s">מזמינים מחנויות שמכירים</p>
                                <p class="header-right-h-l">10% הנחה</p>
                                <p class="header-right-h-s">הזמינו עכשיו</p>
                            </td>
                        </tr>
                    </table>
                    <table class="header-bottom">
                        <tr>
                            <td class="header-bottom-content">
                                <span>
                                    Shopper
                                </span>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>

            <!-- BODY -->
            <tr>
                <td class="body">
                    <table class="body-cont">
                        <tr>
                            <td>
                                <table style="margin: auto;">
                                    <tr>
                                        <td class="body-1">
                                            <p>למימוש הקופון</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="body-2">
                                            <table class="steps">
                                                <tr>
                                                    <td class="bullet">1</td>
                                                    <td class="text">היכנסו לאתר <b><a class="link-1"
                                                                href="www.shopper.shop"> Shopper.shop</a></b></td>
                                                </tr>
                                            </table>
                                            <table class="steps">
                                                <tr>
                                                    <td class="bullet">2</td>
                                                    <td class="text">הזמינו מוצרים עם קוד הקופון</td>
                                                </tr>
                                            </table>
                                            <table class="steps">
                                                <tr>
                                                    <td class="bullet">3</td>
                                                    <td class="text">בעת איסוף המוצרים הציגו למוכר את האתר: <a
                                                            class="link-2" href="www.shopper.shop">לחצו כאן</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="height: 200px;">
                                        <td class="body-3">
                                            <table style="margin: auto;">
                                                <tr>
                                                    <td>
                                                        <!-- <img src="./coupon-image.png" alt="coupon-image"> -->
                                                        <table class="coupon-text">
                                                            <tr>
                                                                <td>
                                                                    <p class="white">COUPON CODE</p>
                                                                    <p class="special">SHOPPER10</p>
                                                                    <p class="white small">10% הנחה על כל המוצרים
                                                                        מהחנויות
                                                                        באתר</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="body-4">בתוקף עד 10.12</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- FOOTER -->
            <tr style="background-color: #E8EFF9;">
                <td class="footer">
                    <table class="footer-column">
                        <tr>
                            <td class="footer-left">
                                <p style="font-size: 17px; font-weight: bold; margin-bottom: 6px;" class="f-heading-l">
                                    CONTACT US</p>
                                <p style="color: #585757;" class="f-email-l">info@tngshopper.com</p>
                                <p style="font-weight: bold;" class="f-link-l"><i>www.shopper.shop</i></p>
                            </td>
                        </tr>
                    </table>
                    <table class="footer-column" style="float: right;">
                        <tr>
                            <td class="footer-right">
                                <p>אימייל זה נשלח בעת הרשמתך לאתר <b>Shopper.</b> במידה ואינכם מעוניינים לקבל אימייל
                                    נוסף מאתנו
                                    <a class="link-3" href="#">לחצו כאן</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>


        </table>

    </center>

</body>

</html>';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $qry = wp_mail($to, $subject, $body, $headers);
    if ($qry) {
        echo 'Promotional email sent';
    } else {
        echo 'Promotional email not sent';
    }

    wp_die();
}
