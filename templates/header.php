<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($tituloPagina) ? htmlspecialchars($tituloPagina, ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') : htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="templates/assets/css/style.css">
</head>

<body>