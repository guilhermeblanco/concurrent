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
 * Mutex implementation using APC entries.
 *
 * IMPORTANT: This is not recommended to be used in a high volume application.
 * Also, beware that you are able to remove a "lock" entry created by another
 * request, since there is no easy way to guarantee the entry created is owned
 * by the process trying to remove it.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent\Mutex
 */
class ApcMutex implements Mutex
{
    /**
     * @var string
     */
    private $internal;

    /**
     * {@inheritdoc}
     */
    public function __construct($key)
    {
        $this->internal = $key;
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
        return apc_add($this->internal, 1);
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
        return apc_delete($this->internal);
    }
}