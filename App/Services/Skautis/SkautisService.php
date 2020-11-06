<?php


namespace App\Services\Skautis;

use Exception;
use Skautis\HelperTrait;
use Skautis\Skautis;

require dirname(__DIR__, 2) . '/../bootstrap.php';

/**
 * Class SkautisService
 * @package App\Services
 */
class SkautisService
{
    /**
     * @var HelperTrait|Skautis|null
     */
    public $skautis;

    /**
     * SkautisService constructor.
     */
    public function __construct()
    {
        $appid = $_ENV['SKAUTIS_APPID'];
        $test = $_ENV['SKAUTIS_TEST'];

        try {
            $this->skautis = Skautis::getInstance($appid, $test);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @param string $backlink
     * @return string
     */
    public function getLoginUrl(string $backlink): string
    {
        return $this->skautis->getLoginUrl($backlink);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function confirmLogin(array $data): ?bool
    {
        try {
            $this->skautis->setLoginData($data);
            return true;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }


}