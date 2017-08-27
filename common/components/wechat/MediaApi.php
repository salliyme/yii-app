<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 16:00
 */

namespace common\components\wechat;


class MediaApi extends BaseApi
{
    /**
     * @param $mediaPath
     * @param $type
     * @return mixed
     */
    public function addTempMedia($mediaPath, $type)
    {
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload';
        $params = [
            'access_token' => $this->getAccessToken(),
            'type' => $type
        ];

        if (class_exists('\CURLFile')) {
            $params['media'] = new \CURLFile(realpath($mediaPath));
        } else {
            $params['media'] ='@' . realpath($mediaPath);
        }

        $result = static::curlPostFile($url, $params);
        return $result;
    }

    /**
     * @param $media_id
     * @return mixed
     */
    public function getTempMedia($media_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get';
        $params = [
            'access_token' => $this->getAccessToken(),
            'media_id' => $media_id
        ];
        $url .= '?'. http_build_query($params);
        $result = self::curlGet($url);

        return $result;
    }

    /**
     * @param $filepath
     * @return mixed
     */
    public function uploadImge($filepath)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg';
        $params = [
            'access_token' => $this->getAccessToken()
        ];

        if (class_exists('\CURLFile')) {
            $params['buffer'] = new \CURLFile(realpath($filepath));
        } else {
            $params['buffer'] ='@' . realpath($filepath);
        }

        $result = self::curlPostFile($url, $params);
        return $result;
    }
}