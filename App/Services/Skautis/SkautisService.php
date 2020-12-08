<?php


namespace App\Services\Skautis;

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

        $this->skautis = Skautis::getInstance($appid, $test);
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
        $this->skautis->setLoginData($data);
        return true;
    }

    /**
     * @return string
     */
    public function getLogoutUrl(): string
    {
        return $this->skautis->getLogoutUrl();
    }

}