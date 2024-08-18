<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ORM\ORM;

class Country extends ORM
{
    protected $table = 'countries';

    protected $casts = [
        'id'             => 'int',
        'name_ru'        => 'string',
        'name_en'        => 'string',
        'iso3166alpha2'  => 'string',
        'iso3166alpha3'  => 'string',
        'iso3166numeric' => 'int',
        'continent'      => 'int',
    ];

    public function getNameRu(): string
    {
        return $this->name_ru;
    }

    public function getNameEn(): string
    {
        return $this->name_en;
    }


    const int CONTINENT_UNKNOWN = 0;
    const int CONTINENT_AF = 1;
    const int CONTINENT_AS = 2;
    const int CONTINENT_EU = 3;
    const int CONTINENT_NA = 4;
    const int CONTINENT_OC = 5;
    const int CONTINENT_SA = 6;
    const int CONTINENT_AN = 7;

    private const array CONTINENT_LIST = [
        self::CONTINENT_UNKNOWN => 'Not select',
        self::CONTINENT_AF      => 'Africa',
        self::CONTINENT_AS      => 'Asia',
        self::CONTINENT_EU      => 'Europe',
        self::CONTINENT_NA      => 'North America',
        self::CONTINENT_OC      => 'Oceania',
        self::CONTINENT_SA      => 'South America',
        self::CONTINENT_AN      => 'Antarctica',
    ];

    protected static array $continentShort = array(
        self::CONTINENT_AF => 'AF',
        self::CONTINENT_AS => 'AS',
        self::CONTINENT_EU => 'EU',
        self::CONTINENT_NA => 'NA',
        self::CONTINENT_OC => 'OC',
        self::CONTINENT_SA => 'SA',
        self::CONTINENT_AN => 'AN',
    );

    public static function getContinentList(): array
    {
        return self::CONTINENT_LIST;
    }

    public static function getContinentShortList(): array
    {
        return self::$continentShort;
    }

    public function getContinent(): int
    {
        return $this->Continent;
    }

    public function getContinentName(): string
    {
        return self::CONTINENT_LIST[$this->getContinent()];
    }

    public function getContinentShortName(): string
    {
        return self::$continentShort[$this->getContinent()];
    }

    public function setContinent(int $value): void
    {
        $this->Continent = $value;
    }

    public function getISO3166alpha2(): string
    {
        return $this->ISO3166alpha2;
    }

    public function setISO3166alpha2(string $value): void
    {
        $this->ISO3166alpha2 = $value;
    }

    public function getISO3166alpha3(): string
    {
        return $this->ISO3166alpha3;
    }

    public function setISO3166alpha3(string $value): void
    {
        $this->ISO3166alpha3 = $value;
    }

    public function getISO3166numeric(): string
    {
        return $this->ISO3166numeric;
    }

    public function setISO3166numeric(string $value): void
    {
        $this->ISO3166numeric = $value;
    }

    public function getCodeWithName(): string
    {
        $r = $this->getISO3166alpha2();
        $r .= ' ' . $this->getName();

        return $r;
    }

    public function getCodeWithTitleName(): string
    {
        $title = $this->getName();

        return "<span title={$title}>{$this->getISO3166alpha2()}</span>";
    }

    public function getCountryDisplay(): string
    {
        $title = $this->getName();

        $r = "<span title='{$title}'>";
        $r .= $this->getISO3166alpha3();
        $r .= "</span>";
        return $r;
    }
}