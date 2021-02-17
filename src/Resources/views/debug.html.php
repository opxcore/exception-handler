<?php
/** @var string $styles */
/** @var string $title */
/** @var string $message */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title></title>
    <style><?php echo $styles ?></style>
</head>
<body>

<div class="handler">
    <div class="handler__block">
        <h1 class="handler__title"><?php echo $title ?></h1>
        <h2 class="handler__message"><?php echo $message ?></h2>
    </div>
    <div class="handler__block">
        <h1 class="handler__title">Stack trace:</h1>
    </div>
</div>

<script></script>
</body>
</html>
