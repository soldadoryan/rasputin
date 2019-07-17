<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        table {
            width: 600px;
            background-color: #f1f1f1;
            margin: 0 auto;
        }

        .title-mail {
            background-color:#004391;
        }

        .body-mail p {
            margin: 25px;
        }

        .footer-mail {
            background-color: #2c2c2c;

        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td class="title-mail" align="center">
                <img src="{{ $message->embed('images/logo-branca.png') }}" style="margin: 20px 0; height: 50px">
            </td>
        </tr>
        <tr class="body-mail">
            <td>
                <p>Prezado(a),</p>
                 @yield('content')
            </td>
        </tr>
        <tr class="footer-mail">
            <td>
                @yield('footer-mail')
            </td>
        </tr>
    </table>

</body>
</html>