<?php

declare(strict_types=1);

use Hosttech\Defaults\Rector\HosttechRectorConfig;
use Hosttech\Defaults\Rector\HosttechRectorType;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    HosttechRectorConfig::apply(
        config: $rectorConfig,
        type: HosttechRectorType::Package,
        levelSet: LevelSetList::UP_TO_PHP_82,
    );
};
