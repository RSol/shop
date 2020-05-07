<?php


namespace core;


class Helper
{
    /**
     * @param $array
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public static function getValue($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return $default;
    }

    /**
     * @param string $sortGetParam
     * @param string $directionGetParam
     * @param int $defaultDirection
     * @return array
     */
    public static function getSortParam($sortGetParam = 's', $directionGetParam = 'd', $defaultDirection = SORT_ASC)
    {
        $request = App::getInstance()->getRequest();
        if (!$sortParam = $request->getParams($sortGetParam)) {
            return [null, null];
        }
        $direction = (int) $request->getParams($directionGetParam);
        if (!$direction || !in_array($direction, [SORT_ASC, SORT_DESC], true)) {
            return [$sortParam, $defaultDirection];
        }
        return [$sortParam, $direction];
    }

    /**
     * @param string $param
     * @param string $sortGetParam
     * @param string $directionGetParam
     * @param int $defaultDirection
     * @return string
     */
    public static function getSortUrl($param, $sortGetParam = 's', $directionGetParam = 'd', $defaultDirection = SORT_ASC)
    {
        parse_str($_SERVER['QUERY_STRING'], $queries);
        $queries[$sortGetParam] = $param;
        $queries[$directionGetParam] = $defaultDirection;

        [$sortParam, $direction] = self::getSortParam($sortGetParam, $directionGetParam, $defaultDirection);
        if ($sortParam !== $param) {
            return http_build_query($queries);
        }

        $queries[$directionGetParam] = $direction === SORT_ASC
            ? SORT_DESC
            : SORT_ASC;

        return http_build_query($queries);
    }

    /**
     * @param $label
     * @param $urlPrefix
     * @param $param
     * @param string $sortGetParam
     * @param string $directionGetParam
     * @param int $defaultDirection
     * @return string
     */
    public static function getSortLink($label, $param, $sortGetParam = 's', $directionGetParam = 'd', $defaultDirection = SORT_ASC)
    {
        $url = self::getSortUrl($param, $sortGetParam, $directionGetParam, $defaultDirection);
        return "<a href='?{$url}'>{$label}</a>";
    }

    /**
     * @param string $pageGetParam
     * @return int
     */
    public static function getCurrentPage($pageGetParam = 'p')
    {
        return (int) App::getInstance()->getRequest()->getParams($pageGetParam, 1);
    }

    /**
     * @param $data
     * @param int $perPage
     * @param string $pageGetParam
     * @return array
     */
    public static function getPageData($data, $perPage = 3, $pageGetParam = 'p')
    {
        $pageNo = (self::getCurrentPage($pageGetParam) - 1);
        return array_slice($data, $perPage * $pageNo, $perPage);
    }

    /**
     * @param $data
     * @param int $perPage
     * @return int
     */
    public static function getPageCount($data, $perPage = 3)
    {
        $chunks = array_chunk($data, $perPage);
        return count($chunks);
    }

    public static function getPageUrl($pageNo, $pageGetParam = 'p')
    {
        parse_str($_SERVER['QUERY_STRING'], $queries);
        $queries[$pageGetParam] = $pageNo;
        return http_build_query($queries);
    }
}
