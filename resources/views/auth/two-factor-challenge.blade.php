<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA растау</title>
</head>
<body>
<h2>2FA кодын енгізіңіз</h2>
<form method="POST" action="{{ url('/two-factor-challenge') }}">
    <label for="code">Аутентификация коды:</label>
    <input type="text" name="code" required autofocus>
    <button type="submit">Растау</button>
</form>
</body>
</html>
