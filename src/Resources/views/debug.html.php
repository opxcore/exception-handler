<?php
/** @var string $styles */
/** @var string $title */
/** @var string $message */
/** @var array $frames */
?>
<!DOCTYPE html>
<html lang="en">
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
        <h2 class="handler__block-title">Stack trace</h2>
        <div class="handler__trace">
            <?php foreach ($frames as $index => $frame) { ?>
                <div class="handler__trace-item">
                    <p class="handler__trace-item-title">
                        <span class="handler__trace-item-title-index"><?php echo $frame['index']; ?></span>
                        <span class="handler__trace-item-title-file"><?php echo $frame['file']; ?>
                            <span class="handler__trace-item-title-line"><?php echo $frame['line']; ?></span>
                        </span>
                    </p>
                    <div class="handler__trace-item"></div>

                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script></script>
</body>
</html>
