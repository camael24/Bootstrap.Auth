<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2012, Ivan Enderlin. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace {

    /**
     * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
     * @copyright  Copyright © 2007-2012 Ivan Enderlin.
     */

    /**
     * \Hoa\Core
     */
    require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Core.link.php';

    /**
     * Call the real Hoa.php ;-).
     */


    from('Hoa')
        -> import('Console.Cursor');

    echo 'hello world';

// Save cursor position.
    Hoa\Console\Cursor::save();
    sleep(1);

// Go to the begining of the line.
    Hoa\Console\Cursor::move('LEFT');
    sleep(1);

// Replace “h” by “H”.
    echo 'H';
    sleep(1);

// Go to “w”.
    Hoa\Console\Cursor::move('→', 5);
    sleep(1);

// Replace “w” by “W”.
    echo 'W';
    sleep(1);

// Back to the saved position.
    Hoa\Console\Cursor::restore();
    sleep(1);

    echo '!';
}
