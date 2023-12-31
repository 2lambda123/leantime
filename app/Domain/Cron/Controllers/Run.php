<?php

namespace Leantime\Domain\Cron\Controllers {

    use Leantime\Core\Controller;
    use Leantime\Domain\Cron\Services\Cron;
    use PDO;
    use PHPMailer\PHPMailer\Exception;

    /**
     *
     */
    class Run extends Controller
    {
        private Cron $cronSvc;

        /**
         * init - initialize private variables
         *
         * @access public
         */
        public function init(Cron $cronSvc)
        {
            $this->cronSvc = $cronSvc;
        }

        /**
         * @return void
         * @throws Exception
         * @throws Exception
         */
        public function run(): void
        {
            $this->cronSvc->runCron();
        }
    }
}
