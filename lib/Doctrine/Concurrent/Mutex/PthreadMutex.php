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
 * Mutex implementation using "pthreads" PECL extension.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent\Mutex
 */
class PthreadMutex implements Mutex
{
    /**
     * @var integer
     */
    private $internal;

    /**
     * {@inheritdoc}
     *
     * @param integer|null $key Optional Mutex reference
     *
     * @throws \Doctrine\Concurrent\Exception In case it is unable to create mutex.
     */
    public function __construct($key)
    {
        try {
            $this->internal = $key ?: Mutex::create(false);
        } catch (\RuntimeException $exception) {
            throw new ConcurrentException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
    }

    /**
     * Destructor.
     *
     */
    public function __destruct()
    {
        try {
            Mutex::unlock($this->internal, true);
        } catch (\RuntimeException $exception) {
            // Do nothing, since we may have:
            // - Not a valid mutex
            // - Mutex not owned by thread
            // - Internal error
        }
    }

    /**
     * {@inheritdoc}
     */
    public function lock()
    {
        return Mutex::lock($this->internal);
    }

    /**
     * {@inheritdoc}
     */
    public function tryLock(Duration $duration = null)
    {
        $time = $duration->getAdjustedTime(microtime(true) / 1000);

        while ($time >= (microtime(true) / 1000)) {
            if (Mutex::trylock($this->internal)) {
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
        return Mutex::unlock($this->internal);
    }
}