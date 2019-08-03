<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use App\Service\GoogleDriveUploadManager;
use App\Service\GoogleDriveClientManager;
use App\Service\ScreenshotMachineManager;
use App\Entity\Website;

class WebsiteScreenshotController extends AbstractController
{
    private $configPath;

    public function __construct() {
        $this->configPath = __DIR__.'/../Resources/config/websitescreenshot.yaml';
    }

    /**
     * @Route("/", name="website_screenshot_dashboard")
     */
    public function dashboard() 
    {
        return $this->render('dashboardPage.html.twig', $this->getWebsiteScreenshotConfig());
    }

    /**
     * @Route("/config", name="website_screenshot_configure")
     */
    public function config(Request $request) 
    {
        if($request->getMethod() == 'POST') {
            $config = array();

            $idClient = $request->request->get('idClient');
            $secret = $request->request->get('secret');
            $redirectUri = $request->request->get('redirectUri');
            $parents = $request->request->get('parents');
            $scopes = $request->request->get('scopes');
            $screenshotUserKey = $request->request->get('screenshotUserKey');
            $websites = $request->request->get('websites');

            if(!empty($idClient) && !empty($secret) && !empty($redirectUri) && !empty($screenshotUserKey)) {
                $config['idClient'] = $idClient;
                $config['secret'] = $secret;
                $config['redirectUri'] = $redirectUri;
                $config['parents'] = $parents;
                $config['scopes'] = count($scopes) == 0 ? array('https://www.googleapis.com/auth/drive.file') : $scopes;
                $config['screenshotUserKey'] = $screenshotUserKey;

                foreach($websites as $key => $website) {
                    if(isset($website['addDateInName']) && $website['addDateInName'] == 'on') {
                        $websites[$key]['addDateInName'] = true;
                    } else {
                        $websites[$key]['addDateInName'] = false;
                    }
                }

                $config['websites'] = $websites;

                $yamlConfig = Yaml::dump($config, 3);
    
                file_put_contents($this->configPath, $yamlConfig);

                $this->addFlash(
                    'success',
                    'Config successfully change.'
                );

                return $this->redirectToRoute('website_screenshot_dashboard');
            } else {
                $this->addFlash(
                    'error',
                    'Please complete all required field.'
                );
            }
        }

        return $this->render('configPage.html.twig', $this->getWebsiteScreenshotConfig());
    }

    /**
     * @Route("/screen", name="website_screenshot_make_screen")
     */
    public function makeScreen(
        ScreenshotMachineManager $screenshotManager,
        GoogleDriveClientManager $clientManager, 
        GoogleDriveUploadManager $uploadManager
    ) {
        $data = $this->getWebsiteScreenshotConfig();

        $isUserRetrieved = $clientManager->requestClient($data['idClient'], $data['secret'], $data['redirectUri'], $data['scopes']);
        $screenshotManager->setScreenshotMachineKey($data['screenshotUserKey']);
        
        if($isUserRetrieved) {

            $uploadManager->setClient($clientManager->getClient());

            foreach($data['websites'] as $websiteData) {
                $website = new Website();
                $website->setUrl($websiteData['url']);
                $name = $websiteData['addDateInName'] ? $websiteData['name'].date('d_m_Y') : $websiteData['name'];
                $website->setName($name);
                $screenshotManager->getScreenshot($website);

                $uploadManager->prepareFile($website->getName(), 'image/jpeg', $data['parents']);
                $uploadManager->upload($website->getImageContent());
            }

            $this->addFlash(
                'success',
                'The screenshot was successfully taken and send to your Google Drive.'
            );
            return $this->redirectToRoute('website_screenshot_dashboard');
        } else {
            return $this->redirect($clientManager->getAuthUrl());
        }
    }

    private function getWebsiteScreenshotConfig() {
        $value = Yaml::parseFile($this->configPath);

        return $value;
    }
}