<?php

namespace Xigen\Library\OnBuy;

class Constants {

    const REQUEST_TOKEN = 'auth/request-token';
    const CONTENT_TYPE = 'application/x-www-form-urlencoded';

    /**
     * @var string
     */
    public $domain = 'https://api.onbuy.com/';

    /**
     * @var string
     */
    public $version = 'v2/';

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $consumerKey;

}
