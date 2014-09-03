<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Concurrent\Mutex;

use Doctrine\Concurrent\Lock;

/**
 * A mutex is a mutual exclusion object that restricts access to a shared
 * resource (e.g. a file) to a single instance. Countable mutexes acquire the
 * mutex a single time and internally track the number of times the mutex is
 * locked. The mutex is unlocked as soon as it goes out of scope or is
 * unlocked the same number of times that it was locked.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent\Mutex
 */
interface Mutex extends Lock
{
    /**
     * Constructor.
     *
     * @param string $key
     */
    public function __construct($key);
}