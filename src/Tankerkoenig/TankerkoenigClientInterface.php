<?php

declare(strict_types=1);

namespace App\Tankerkoenig;

use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailRequest;
use App\Tankerkoenig\Application\UseCase\GasStationDetail\GetGasStationDetailResponse;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListRequest;
use App\Tankerkoenig\Application\UseCase\GasStationList\GetGasStationListResponse;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesRequest;
use App\Tankerkoenig\Application\UseCase\GasStationPrice\GetGasStationPricesResponse;
use App\Tankerkoenig\Domain\Exception\TankerkoenigException;

interface TankerkoenigClientInterface
{
    /** @throws TankerkoenigException */
    public function getDetail(GetGasStationDetailRequest $req): GetGasStationDetailResponse;

    /** @throws TankerkoenigException */
    public function getList(GetGasStationListRequest $req): GetGasStationListResponse;

    /** @throws TankerkoenigException */
    public function getPrices(GetGasStationPricesRequest $req): GetGasStationPricesResponse;
}
