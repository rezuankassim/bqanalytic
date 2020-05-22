<?php

namespace RezuanKassim\BQAnalytic;

use Google\Auth\Cache\MemoryCacheItemPool;
use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

class BQAnalyticClientFactory
{
    public static function create($config)
    {
        $clientConfig = array_merge([
            'projectId' => $config['project'],
            'keyFilePath' => $config['credential'],
            'authCache' => self::configureCache($config['auth_cache_store']),
        ], Arr::get($config, 'client_options', []));

        return new BigQueryClient($clientConfig);
    }

    protected static function configureCache($cacheStore)
    {
        $store = Cache::store($cacheStore);

        $cache = new MemoryCacheItemPool($store);

        return $cache;
    }
}
