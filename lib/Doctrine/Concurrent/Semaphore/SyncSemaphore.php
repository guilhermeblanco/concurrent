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

use Doctrine\Concurrent\Exception as ConcurrentException;
use Doctrine\Concurrent\Duration;

/**
 * Semaphore implementation using "sync" PECL extension, a cross-platform
 * compatible synchronization library for PHP.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent\Semaphore
 */
class SyncSemaphore implements Semaphore
{
    /**
     * @var \SyncSemaphore
     */
    private $internal;

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\Concurrent\Exception In case it is unable to create semaphore.
     */
    public function __construct($key, $maxAcquire = 1)
    {
        try {
            $this->internal = new \SyncSemaphore($key, $maxAcquire, false);
        } catch (\Exception $exception) {
            throw new ConcurrentException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getPrevious()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function lock()
    {
        return $this->internal->lock(-1);
    }

    /**
     * {@inheritdoc}
     */
    public function tryLock(Duration $duration = null)
    {
        return $this->internal->lock($duration->getAdjustedTime(0));
    }

    /**
     * {@inheritdoc}
     */
    public function unlock()
    {
        return $this->internal->unlock();
    }
}