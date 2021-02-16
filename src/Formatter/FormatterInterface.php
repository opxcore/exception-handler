<?php
/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpxCore\ExceptionHandler\Formatter;

use Throwable;

interface FormatterInterface
{
    /**
     * Format throwable.
     *
     * @param Throwable $e
     *
     * @return  mixed
     */
    public function format(Throwable $e);
}