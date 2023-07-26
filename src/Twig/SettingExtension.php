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
            new TwigFunction('siteEmail', [$this, 'siteEmail']),
            new TwigFunction('shopAddress', [$this, 'shopAddress']),
            new TwigFunction('orderPhones', [$this, 'orderPhones']),
            new TwigFunction('facebookUrl', [$this, 'facebookUrl']),
            new TwigFunction('instagramUrl', [$this, 'instagramUrl']),
            new TwigFunction('youtubeUrl', [$this, 'youtubeUrl'])
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

    public function siteEmail()
    {
        return $this->settingRepo->findOneBy(['key' => 'Site Email Address'])->getValue();
    }

    public function shopAddress()
    {
        return $this->settingRepo->findOneBy(['key' => 'Shop Address'])->getValue();
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

    public function facebookUrl()
    {
        return $this->settingRepo->findOneBy(['key' => 'Facebook URL'])->getValue();
    }

    public function instagramUrl()
    {
        return $this->settingRepo->findOneBy(['key' => 'Instagram URL'])->getValue();
    }

    public function youtubeUrl()
    {
        return $this->settingRepo->findOneBy(['key' => 'Youtube URL'])->getValue();
    }
}
