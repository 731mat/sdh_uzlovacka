<?php
declare(strict_types = 1);

namespace AdminModule\Components\Theme;

use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Http\Response;

class Theme
{

    private const COOKIE = 'future';

    private const DARK = 'dark';

    private const LIGHT = 'bright';

    private IRequest $httpRequest;
    private IResponse $httpResponse;


    public function __construct(IRequest $httpRequest, IResponse $httpResponse)
    {
        $this->httpRequest = $httpRequest;
        $this->httpResponse = $httpResponse;
    }


    public function setDarkMode(): void
    {
        $this->setCookie(self::DARK);
    }


    public function setLightMode(): void
    {
        $this->setCookie(self::LIGHT);
    }


    public function isDarkMode(): ?bool
    {
        $cookie = $this->httpRequest->getCookie(self::COOKIE);
        return $cookie === self::DARK ? true : ($cookie === self::LIGHT ? false : null);
    }

    private function setCookie(string $mode): void
    {
        $response = $this->httpResponse;
        $response->setCookie(self::COOKIE, $mode, '+10 years', null, ".bnet-internet.info", true, null, 'None');
    }

}