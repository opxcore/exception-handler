<?php
/** @var string $styles */
/** @var string $script */
/** @var string $title */
/** @var string $message */
/** @var array $previous */
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
        <?php foreach ($previous as $previously) {
            /** @var Throwable $previously */ ?>
            <div class="handler__previous">
                <p class="handler__previous-title"><?php echo get_class($previously) ?></p>
                <p class="handler__previous-message"><?php echo $previously->getMessage() ?></p>
            </div>
        <?php } ?>
    </div>
    <div class="handler__block">
        <h2 class="handler__block-title">Stack trace</h2>
        <div class="handler__trace">
            <?php foreach ($frames as $index => $frame) { ?>
                <div class="handler__trace-item">
                    <p class="handler__trace-item-title" data-index="<?php echo $index; ?>">
                        <span class="handler__trace-item-title-index"><?php echo $frame['index']; ?></span>
                        <?php if (isset($frame['file'])) { ?>
                            <span class="handler__trace-item-title-file"><?php echo $frame['file']; ?>
                                <span class="handler__trace-item-title-line"><?php echo $frame['line']; ?></span>
                            </span>
                        <?php } else { ?>
                            <span class="handler__trace-item-title-file"><?php echo $frame['function']; ?></span>
                        <?php } ?>
                    </p>
                    <?php if (isset($frame['code'])) { ?>
                        <div class="handler__trace-code <?php echo $index === 0 ? 'handler__trace-code-active' : ''; ?>"
                             data-index="<?php echo $index; ?>">
                            <?php foreach ($frame['code'] as $line) { ?>
                                <p class="handler__trace-code-line <?php echo $line['error'] ? 'handler__trace-code-line-error' : '' ?>">
                                    <span class="handler__trace-code-line-number"><?php echo $line['number']; ?></span>
                                    <span class="handler__trace-code-line-text"><?php echo $line['line']; ?></span>
                                </p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script><?php echo $script; ?></script>
</body>
</html>
