<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v6/services/keyword_plan_service.proto

namespace Google\Ads\GoogleAds\V6\Services;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The max cpc bid forecast curve.
 *
 * Generated from protobuf message <code>google.ads.googleads.v6.services.KeywordPlanMaxCpcBidForecastCurve</code>
 */
class KeywordPlanMaxCpcBidForecastCurve extends \Google\Protobuf\Internal\Message
{
    /**
     * The forecasts for the Keyword Plan campaign at different max CPC bids.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v6.services.KeywordPlanMaxCpcBidForecast max_cpc_bid_forecasts = 1;</code>
     */
    private $max_cpc_bid_forecasts;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Ads\GoogleAds\V6\Services\KeywordPlanMaxCpcBidForecast[]|\Google\Protobuf\Internal\RepeatedField $max_cpc_bid_forecasts
     *           The forecasts for the Keyword Plan campaign at different max CPC bids.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Ads\GoogleAds\V6\Services\KeywordPlanService::initOnce();
        parent::__construct($data);
    }

    /**
     * The forecasts for the Keyword Plan campaign at different max CPC bids.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v6.services.KeywordPlanMaxCpcBidForecast max_cpc_bid_forecasts = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getMaxCpcBidForecasts()
    {
        return $this->max_cpc_bid_forecasts;
    }

    /**
     * The forecasts for the Keyword Plan campaign at different max CPC bids.
     *
     * Generated from protobuf field <code>repeated .google.ads.googleads.v6.services.KeywordPlanMaxCpcBidForecast max_cpc_bid_forecasts = 1;</code>
     * @param \Google\Ads\GoogleAds\V6\Services\KeywordPlanMaxCpcBidForecast[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setMaxCpcBidForecasts($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Ads\GoogleAds\V6\Services\KeywordPlanMaxCpcBidForecast::class);
        $this->max_cpc_bid_forecasts = $arr;

        return $this;
    }

}

