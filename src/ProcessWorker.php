<?php

namespace Phppm;

use Symfony\Component\Console\Output\OutputInterface;

class ProcessWorker
{
    /**
     * @var int
     */
    protected $interval;

    /**
     * @var ProcessInterface
     */
    public $process;

    /**
     * @var int
     */
    protected $pid;

    /**
     * @var bool
     */
    protected $isRunning = false;

    /**
     * @var bool
     */
    protected $stop = false;

    /**
     * ProcessWorker constructor.
     *
     * @param string $process
     * @param OutputInterface $output
     * @param int $interval
     */
    public function __construct(string $process, OutputInterface $output, int $interval = 100)
    {
        $this->process = new $process($output, $this);
        $this->interval = $interval;
    }

    public function runProcess() : void
    {
        if ($this->isRunning) {
            return;
        }

        $this->pid = getmypid();
        $this->isRunning = true;

        while (true) {
            $this->process->exec();
            usleep($this->interval * 1000);

            if ($this->stop) {
                break;
            }
        }
    }

    /**
     * @return int
     */
    public function getPid() : int
    {
        return $this->pid;
    }

    /**
     * @return $this
     */
    public function stop()
    {
        $this->stop = true;

        return $this;
    }
}
