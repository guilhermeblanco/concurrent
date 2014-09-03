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

namespace Doctrine\Concurrent;

/**
 * A lock is a tool for controlling access to a shared resource by multiple 
 * threads. Commonly, a lock provides exclusive access to a shared resource: 
 * only one thread at a time can acquire the lock and all access to the shared 
 * resource requires that the lock be acquired first. However, some locks may 
 * allow concurrent access to a shared resource, such as {@link ReadWriteLock}.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 *
 * @package Doctrine\Concurrent
 */
interface Lock
{
    /**
     * Acquires the lock.
     * If the lock is not available at the time lock is requested, then the 
     * current execution enters in blocking state, awaiting until the lock 
     * gets acquired.
     * 
     * @return void
     */
    public function lock();
    
    /**
     * Acquires the lock if it is free within the given waiting time.
     * 
     * If the lock is available this method returns TRUE immediately.
     * If the lock is not available then the execution is blocked and freezes 
     * until one of three things happens:
     * - The lock is acquired by this process
     * - Process gets interrupted
     * - The specified waiting time elapses
     * 
     * If the lock is acquired then the value TRUE is returned.
     * 
     * If the specified waiting time elapses then the value FALSE is returned.
     * If the time is equal to zero, the method will attempt immediately and
     * return FALSE if it fails.
     * 
     * @param \Doctrine\Concurrent\Duration $duration
     * 
     * @return boolean
     */
    public function tryLock(Duration $duration = null);
    
    /**
     * Releases the lock.
     * 
     * @return void
     */
    public function unlock();
}