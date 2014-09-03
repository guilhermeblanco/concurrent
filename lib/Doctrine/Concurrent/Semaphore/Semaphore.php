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

namespace Doctrine\Concurrent\Semaphore;

use Doctrine\Concurrent\Lock;

/**
 * A semaphore restricts access to a limited resource to a limited number of
 * instances. Semaphores differ from mutexes in that they can allow more than
 * one instance to access a resource at one time while a mutex only allows one
 * instance at a time.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent\Semaphore
 */
interface Semaphore extends Lock
{
    /**
     * Constructor.
     *
     * @param string $key
     * @param int    $maxAcquire
     */
    public function __construct($key, $maxAcquire = 1);
}