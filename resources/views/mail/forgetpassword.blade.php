<!-- filepath: /C:/laragon/www/intershipwebproject/resources/views/mail/forgetpassword.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Forget Password</title>
</head>
<body>
<h1>Forget Password</h1>
    <p>Hello, {{ $user->name }}</p>
 
    <p>Your password reset link is:</p>
    <a href="{{ url('reset-password', ['token' => $token]) }}?email={{ urlencode($user->email) }}">Reset Password</a>

    <a href="{{ url('login') }}">Go to Login Page</a>
</body>
</html>