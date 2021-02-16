<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\ExceptionHandler\Output;

use Throwable;

interface OutputInterface
{
    /**
     * Output formatted throwable.
     *
     * @param mixed $content
     *
     * @return  mixed
     */
    public function output($content);
}