<!DOCTYPE html>
<html lang="en">

<head>

    @yield('css')
    <style>
        html,
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .hola {
            color: #FFFFFF,
                letter-spacing: .2rem;
            font-family: Arial,
                font-weight: bold
        }

        .header_container {
            display: flex;
            align-content: center;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: .5rem;
            font-family: Arial, Helvetica, sans-serif;
            letter-spacing: 1rem;
            padding: 1rem
        }

        .mail_container {
            background: #FFFFFF;
            box-shadow: 60px 20px 60px rgba(100, 100, 100, .2);
            width: 100%;
            display: flex;
            flex-direction: column;
            align-content: center;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="mail_container">
        <div class="header_container">
            <h1 class="hola">WAFI</h1>
            <img src="{{ $data->appIcon }}" width="200px" height="200px" />
        </div>
        <div>
            @yield('content')
        </div>
    </div>
</body>

</html>
