<?php

declare(strict_types=1);

namespace App\Tankerkoenig\Infrastructure\Http\Mapper;

final class MapperRegistry
{
    private array $instances = [];

    public function getOpeningTimeMapper(): GasStationDetail\OpeningTimeMapper
    {
        return $this->instances['opening_time'] ??= new GasStationDetail\OpeningTimeMapper();
    }

    public function getStationDetailMapper(): GasStationDetail\StationDetailMapper
    {
        return $this->instances['station_detail'] ??= new GasStationDetail\StationDetailMapper(
            $this->getOpeningTimeMapper()
        );
    }

    public function getStationListMapper(): GasStationList\StationListMapper
    {
        return $this->instances['station_list'] ??= new GasStationList\StationListMapper();
    }

    public function getPriceMapper(): GasStationPrice\PriceMapper
    {
        return $this->instances['price'] ??= new GasStationPrice\PriceMapper();
    }
}
