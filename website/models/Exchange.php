<?php

    class Exchange {

            public function getCurrent() {
                $apiUrl = 'http://webtask.future-processing.com:8068/currencies';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $headers = [ 'Accept: application/json' ];

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $data = curl_exec($ch);

                $httpCode = curl_getinfo($ch)['http_code'];

                curl_close($ch);

                if ($httpCode === 200) {
                    return $data;
                }

                return false;
            }

    }
