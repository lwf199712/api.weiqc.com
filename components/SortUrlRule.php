<?php declare(strict_types=1);


namespace app\components;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

class SortUrlRule extends BaseObject implements UrlRuleInterface
{

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request    $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     *                            If false, it means this rule cannot be used to parse this path info.
     * @throws InvalidConfigException
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^[\0-9a-z]{13,13}$%', $pathInfo, $matches)) {
            $params['token'] = current($matches);
            return  ['v1/user-action/url-convert/convert',$params];
        }
        return false; // 本规则不会起作用

    }

    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string     $route   the route. It should not have slashes at the beginning or the end.
     * @param array      $params  the parameters
     * @return string|bool the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        return false; // this rule does not apply
    }
}