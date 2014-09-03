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

use Doctrine\Concurrent\Exception as ConcurrentException;
use Doctrine\Concurrent\Duration;

/**
 * Mutex implementation using "sysvsem" extension, the default built-in PHP
 * extension to control Semaphores.
 *
 * Unfortunately, we need to use System V Semaphores to emulate mutexes.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent\Mutex
 */
class SystemVMutex implements Mutex
{
    /**
     * @var resource
     */
    private $internal;

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\Concurrent\Exception In case it is unable to create mutex.
     */
    public function __construct($key)
    {
        // Limit to a maximum of 257 mutexes
        $identifier = ftok($key, 't') % 257 + 0xBADBEEF;

        $this->internal = sem_get($identifier, 1);

        if ( ! $this->internal) {
            throw new ConcurrentException(
                sprintf('Unable to create lock with identifier "%s".', $identifier)
            );
        }
    }

    /**
     * Destructor.
     *
     */
    public function __destruct()
    {
        $this->unlock();
    }

    /**
     * {@inheritdoc}
     */
    public function lock()
    {
        return sem_acquire($this->internal);
    }

    /**
     * {@inheritdoc}
     */
    public function tryLock(Duration $duration = null)
    {
        $time = $duration->getAdjustedTime(microtime(true) / 1000);

        while ($time >= (microtime(true) / 1000)) {
            if ($this->lock()) {
                return true;
            }

            usleep(1000); // wait 1 ms
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function unlock()
    {
        return sem_release($this->internal);
    }
}