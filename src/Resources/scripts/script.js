/*
 * This file is part of the OpxCore.
 *
 * Copyright (c) Lozovoy Vyacheslav <opxcore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

window.onload = function () {
    const items = document.querySelectorAll('.handler__trace-item-title');
    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener('click', toggleCodeVisibility);
    }
}

function toggleCodeVisibility(event) {
    const id = event.target.dataset.index;
    const items = document.querySelectorAll('.handler__trace-code-active');
    for (let i = 0; i < items.length; i++) {
        items[i].classList.remove('handler__trace-code-active');
    }
    document.querySelector('.handler__trace-code[data-index="' + id + '"]').classList.add('handler__trace-code-active');
}