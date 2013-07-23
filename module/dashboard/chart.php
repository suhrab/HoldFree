<?php

//if (!defined('CHECK')) {
//    exit;
//}

define('DATE_TODAY', date('Y-m-d'));
define('CHART_DATA_FILE', DIR_UPLOAD . '/chart/' . DATE_TODAY . '.txt');
$chart_data_file    = DIR_UPLOAD . '/chart/' . DATE_TODAY . '.txt';
$chart_data         = array();

if (file_exists(CHART_DATA_FILE)) {
    $chart_data = file_get_contents(CHART_DATA_FILE);
}
else {
    require_once DIR_CLASS . 'google-api-php-client/Google_Client.php';
    require_once DIR_CLASS . 'google-api-php-client/contrib/Google_AnalyticsService.php';

    $client = new Google_Client();
    $client->setApplicationName('Hello Analytics API Sample');

    $client->setClientId('921866991525-b1e4fk7u7ho0cn502ola13t17m332qoh.apps.googleusercontent.com');
    $client->setClientSecret('K99K8to5ylY1oYncaLkNTB86');
    $client->setRedirectUri('http://holdfree.com/index.php?module=dashboard&dashboard=1');
    $client->setDeveloperKey('AIzaSyBv0_WUSJBB_edBmt0RCX2AB4hp6st3KXY');
    $client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));

    $client->setUseObjects(true);

    function runMainDemo(&$analytics) {
        try {

            // Step 2. Get the user's first profile ID.
            $profileId = getFirstProfileId($analytics);

            if (isset($profileId)) {

                // Step 3. Query the Core Reporting API.
                $results = getResults($analytics, $profileId);

                $file_descriptor = fopen(CHART_DATA_FILE, 'a+');
                fwrite($file_descriptor, json_encode($results));
                fclose($file_descriptor);

                die(json_encode(array()));
            }

        } catch (apiServiceException $e) {
            // Error from the API.
            print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();

        } catch (Exception $e) {
            print 'There wan a general error : ' . $e->getMessage();
        }
    }

    function getFirstprofileId(&$analytics) {
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {

            $items = $accounts->getItems();

            $firstAccountId = $items[1]->getId();

            $webproperties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($webproperties->getItems()) > 0) {
                $items = $webproperties->getItems();
                $firstWebpropertyId = $items[0]->getId();

                $profiles = $analytics->management_profiles->listManagementProfiles($firstAccountId, $firstWebpropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();
                    return $items[0]->getId();

                } else {
                    throw new Exception('No profiles found for this user.');
                }
            } else {
                throw new Exception('No webproperties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    function getResults(&$analytics, $profileId) {
        $date_today = date('Y-m-d', 1345303414);
        $data = array();

        for ($i = 1; $i < 15; $i++)
        {
            $date = date('Y-m-d', strtotime('-'. $i .' days', strtotime($date_today)));
            $result = $analytics->data_ga->get('ga:' . $profileId, $date, $date, 'ga:visitors,ga:percentNewVisits');
            $rows = $result->getRows();

            $data['visitors'][] = array(
                'date' => strtotime($date) * 1000,
                'data' => $rows[0][0]
            );

            $data['percentNewVisits'][] = array(
                'date' => strtotime($date) * 1000,
                'data' => $rows[0][1]
            );
        }

        return $data;
    }

    if (isset($_GET['code'])) {
        $client->authenticate();
        $_SESSION['token'] = $client->getAccessToken();
        $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
    }

    if (isset($_SESSION['token'])) {
        $client->setAccessToken($_SESSION['token']);
    }

    if (!$client->getAccessToken()) {
        $authUrl = $client->createAuthUrl();
        print "<a class='login' href='$authUrl'>Connect Me!</a>";

    } else {
        $analytics = new Google_AnalyticsService($client);
        runMainDemo($analytics);
    }
}

die(json_encode($chart_data));

