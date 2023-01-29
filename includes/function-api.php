<?php

class API
{
    public static function callAPIViaCurl($api_url = '', $method = 'GET', $formdata = [], $headers = [])
    {
        $curl = curl_init();

        $curl_props = [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CUSTOMREQUEST => $method
        ];

        if (!empty($formdata)) {
            $curl_props[CURLOPT_POSTFIELDS] = json_encode($formdata);
        }

        if (!empty($headers)) {
            $curl_props[CURLOPT_POSTFIELDS] = $headers;
        }


        curl_setopt_array($curl, $curl_props);

        $response = curl_exec($curl);

        $error = curl_error($curl);

        curl_close($curl);

        if ($error) return "API not working";
        return json_decode($response);
    }

    public static function mailgunAPI($name = '', $email = '', $message = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, 'api:' . MAILGUN_API_KEY);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt(
            $curl,
            CURLOPT_URL,
            MAILGUN_API_URL
        );
        curl_setopt(
            $curl,
            CURLOPT_POSTFIELDS,
            array(
                'from' => $name . ' <' . $email . '>',
                'to' => 'zhonghow22t@forwardschool.edu.my',
                'subject' => 'New Request',
                'text' => $message
            )
        );
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if ($error)
            return 'API not working';
        return $response;
    }



    public static function catFact()
    {
        return json_decode(file_get_contents('https://catfact.ninja/fact'));
    }
}
