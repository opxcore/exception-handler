<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\ExceptionHandler\Handler;

class HtmlHandler extends Handler
{
    public function render(): void
    {
        $styles = file_get_contents(dirname(__DIR__) . '/Resources/styles/styles.css');
        $title = $this->getType();
        $message = $this->getMessage();
        $frames = $this->getFrames();

        require dirname(__DIR__) . '/Resources/views/debug.html.php';
    }

}