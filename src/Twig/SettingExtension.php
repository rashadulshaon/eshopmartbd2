<?php

namespace App\Twig;

use App\Repository\SettingRepository;
use App\Repository\SiteLogoRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingExtension extends AbstractExtension
{
    public function __construct(
        private SettingRepository $settingRepo,
        private SiteLogoRepository $siteLogoRepo
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('siteTitle', [$this, 'siteTitle']),
            new TwigFunction('siteLogo', [$this, 'siteLogo']),
            new TwigFunction('sitePhone', [$this, 'sitePhone']),
            new TwigFunction('orderPhones', [$this, 'orderPhones'])
        ];
    }

    public function siteTitle()
    {
        return $this->settingRepo->findOneBy(['key' => 'Site Name'])->getValue();
    }

    public function siteLogo()
    {
        return $this->siteLogoRepo->findOneBy([]);
    }

    public function sitePhone()
    {
        return $this->settingRepo->findOneBy(['key' => 'Site Phone Number'])->getValue();
    }

    public function orderPhones()
    {
        $phones = [
            $this->settingRepo->findOneBy(['key' => 'Order Phone 1'])->getValue(),
            $this->settingRepo->findOneBy(['key' => 'Order Phone 2'])->getValue(),
            $this->settingRepo->findOneBy(['key' => 'Order Phone 3'])->getValue(),
        ];

        return $phones;
    }
}
