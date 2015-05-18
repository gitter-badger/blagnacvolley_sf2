<?php

namespace Tools\ReCaptchaBundle\Services;

use Symfony\Component\DependencyInjection\Container;

class ReCaptcha
{
    protected $container;
    protected $secret;
    protected $request;

    public function __construct(Container $container, $secret, $request)
    {
        $this->container = $container;
        $this->secret = $secret;
        $this->request = $request;
    }

    /**
     * @param $response
     * @return mixed
     */
    private function doRequest($response)
    {
        $fields = array(
            'secret' => urlencode($this->secret),
            'response' => urlencode($response),
        );

        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $ch = curl_init();

        // URL, nombre de variables POST, champs
        curl_setopt($ch, CURLOPT_URL, $this->request);
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }

    /**
     * @param $response
     * @return array|bool
     */
    public function checkReCaptcha($response)
    {
        $data = self::doRequest($response);

        $dataDecode = json_decode($data, true);
        if ($dataDecode != null) {
            if ($dataDecode['success'] == true)
                return array();

            return (array_key_exists('error-codes', $dataDecode) ? $dataDecode['error-codes'] : array());
        }
        return false;
    }
}
