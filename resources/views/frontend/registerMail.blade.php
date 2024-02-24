<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data['title'] }}</title>
</head>

<body>
    <p>Hi, {{$data['name']}}, Welcome to Referral System!</p>
    <p><b>Useremail:-</b> {{$data['email']}}</p>
    <p><b>Password:-</b> {{$data['password']}}</p>
    <p>You can add user to your network by share your <a href="{{$data['url']}}">Referral Link</a></p>
    <p>Thank You !</p>
</body>
</html>
