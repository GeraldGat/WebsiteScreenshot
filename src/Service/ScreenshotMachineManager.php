<?php

namespace App\Service;

use GuzzleHttp\Client;
use App\Entity\Website;

class ScreenshotMachineManager
{
    private $screenshotMachineKey;

    public function setScreenshotMachineKey(string $screenshotMachineKey) {
        $this->screenshotMachineKey = $screenshotMachineKey;
    }

    public function getScreenshotMachineKey() {
        return $this->screenshotMachineKey;
    }

    public function getScreenshot($websites) {
        if($this->screenshotMachineKey != null) {
            if(!is_array($websites)) {
                $websites = [$websites];
            }
    
            $guzzleClient = new Client();
            foreach($websites as $website) {
                $result = $guzzleClient->request('GET', 'http://api.screenshotmachine.com/', [
                    'query' => $this->getUrl($website),
                ]);

                if($result->getStatusCode() == '200') {
                    $website->setImageContent($result->getBody());
                }
            }
        } else {
            throw new Exception("No screenshot machine key given");
        }
    }

    private function getUrl(Website $website) {
        $parameters = array();
        $parameters['key'] = $this->screenshotMachineKey;
        $parameters['url'] = $website->getUrl();
        $parameters['dimension'] = $website->getDimension();
        $parameters['device'] = $website->getDevice();
        $parameters['delay'] = $website->getDelay();
        $parameters['cacheLimit'] = 0;
        $parameters['format'] = 'jpg';

        return $parameters;
    }
}