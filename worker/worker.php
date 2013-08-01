<?php
$worker = new GearmanWorker();
$worker->addServer('91.213.233.143');
$worker->addFunction('convert_v1', 'convertJob');

set_error_handler(function($errno , $errstr ,$errfile, $errline, $errcontext) {
    throw new ErrorException($errstr, $errno, E_ALL | E_STRICT, $errfile, $errline);
}, $error_types = E_ALL | E_STRICT);

define('DB_USER', 'root');
define('DB_PASS', '232323');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '8889');
define('DB_NAME', 'holdfree');

while ($worker->work()) {}
//$worker->work();

function convert_with_progress($command, $progressCallback, &$errors = null){
    $process = popen($command, 'r');
    $line = '';
    $lines = [];
    $totalDurationSeconds = 0.0;

    try{
        // стартовая информация ffmpeg
        while(!feof($process)){
            $line = trim(fgets($process));
            $lines[] = $line;

            if($totalDurationSeconds == 0.0 && preg_match('/^Duration: ([0-9]+:[0-9]+:[0-9]+.[0-9]+), start:/', $line, $matches) == 1){
                list($durationHours, $durationMinutes, $durationSeconds) = explode(':', $matches[1]);
                list($durationSeconds, $durationMilliseconds) = explode('.', $durationSeconds);
                $totalDurationSeconds = $durationHours * 60*60 + $durationMinutes * 60 + $durationSeconds + $durationMilliseconds / 100;
            }

            if($line == 'Press [q] to stop, [?] for help') break;
        }
        if($totalDurationSeconds == 0)
            throw new Exception('totalDurationSeconds is zero');

        $line = '';
        $completePercentLastSendTime = 0;
        while (false !== ($char = fgetc($process)))
        {
            if ($char == "\r")
            {
                $line = trim($line);
                $lines[] = $line;
                // progress information
                if(preg_match('/^frame=\s+[0-9]+\s+fps=\s*[0-9.]+ q=.+time=([0-9]+:[0-9]+:[0-9]+\.[0-9]+) bitrate=/', $line, $matches) == 1){
                    list($completeHours, $completeMinutes, $completeSeconds) = explode(':', $matches[1]);
                    list($completeSeconds, $completeMilliseconds) = explode('.', $completeSeconds);
                    $totalCompleteSeconds = $completeHours * 60*60 + $completeMinutes * 60 + $completeSeconds + $completeMilliseconds / 100;

                    // колво процентов готовности
                    $completePercent = (int)round($totalCompleteSeconds * 100 / $totalDurationSeconds);

                    if((time() - $completePercentLastSendTime) >= 10){
                        $progressCallback($completePercent);
                        $completePercentLastSendTime = time();
                    }
                }
                $line = "";
            }
            else if($char == "\n"){
                // progress info end
                $lines[] = $line;
                break;
            }
            else {
                $line .= $char;
            }
        }

        $conversionWasSuccessful = false;
        while(!feof($process)){
            $line = trim(fgets($process));
            $lines[] = $line;
            if(preg_match('/^\[libx264 @ \w+\] kb\/s:[0-9.]+$/', $line ) == 1){
                $conversionWasSuccessful = true;
                break;
            }
        }

        if($conversionWasSuccessful){
            $completePercent = 100;
            $progressCallback($completePercent);
        }
        else{
            throw new Exception('successful match not found');
        }

        pclose($process);
        return true;
    } catch(\Exception $e) {
        $linesCount = count($lines);
        $last5_lines = [];
        for($i = $linesCount - 1; $i != 0 && count($last5_lines) <= 5; $i--)
            $last5_lines[] = $lines[$i];
        $errors =  [
            'error' => $e,
            'ffmpeg_last5_lines' => $last5_lines
        ];

        pclose($process);
        return false;
    }
}

function sentFileToStorage($file_path, $storage_url)
{
    $post = array('filename' => '@' . $file_path);
    $storage_url = $storage_url . '/api/uploadFile';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$storage_url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec ($ch);
    curl_close ($ch);

    $result = (array) json_decode($result);

    return $result['fileURL'];
}

function convert($command, &$errors = null){
    $process = popen($command, 'r');
    $line = '';
    $lines = [];
    try{
        $conversionWasSuccessful = false;
        while(!feof($process)){
            $line = trim(fgets($process));
            $lines[] = $line;
            if(preg_match('/^\[libx264 @ \w+\] kb\/s:[0-9.]+$/', $line ) == 1){
                $conversionWasSuccessful = true;
                break;
            }
        }
        if(!$conversionWasSuccessful)
            throw new Exception('successful match not found');

        pclose($process);
        return true;
    } catch(\Exception $e) {
        $linesCount = count($lines);
        $last5_lines = [];
        for($i = $linesCount - 1; $i != 0 && count($last5_lines) <= 5; $i--)
            $last5_lines[] = $lines[$i];
        $errors =  [
            'error' => $e,
            'ffmpeg_last5_lines' => $last5_lines
        ];
        pclose($process);
        return false;
    }
}

function convertJob(GearmanJob $job)
{
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT
        . ';charset=utf8', DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $payload = $job->workload();
        $payload = unserialize($payload);

        if (!isset($payload['source_file_url'])) {
            throw new Exception('Not defined source file!');
        }

        if (!isset($payload['row_id'])) {
            throw new Exception('Not defined row id!');
        }

        if (!isset($payload['storage_server'])) {
            throw new Exception('Not defined storage server!');
        }

        if (!isset($payload['video_sizes']) || !is_array($payload['video_sizes'])) {
            throw new Exception('Not defined video sizes!');
        }

        $tmp_file = tempnam('tmp', 'tmp');
        $new_file = tempnam('tmp', 'new');

        if (!copy($payload['source_file_url'], $tmp_file)) {
            throw new Exception('Copy source file failed!');
        }

        $commands = [];
        $files_urls = [];

        if (isset($payload['video_sizes']['480p'])) {
            $output_file = $new_file . '_480p.mp4';
            $commands[] = ['output_file' => $output_file, 'cmd' => 'ffmpeg -i ' . $tmp_file . ' -s hd480 -f mp4 ' . $output_file . ' 2>&1', 'dimension' => '480p'];
        }
        if (isset($payload['video_sizes']['720p'])) {
            $output_file = $new_file . '_720p.mp4';
            $commands[] = ['output_file' => $output_file, 'cmd' => 'ffmpeg -i ' . $tmp_file . ' -s hd720 -f mp4 ' . $output_file . ' 2>&1', 'dimension' => '720p'];
        }
        if (isset($payload['video_sizes']['1080p'])) {
            $output_file = $new_file . '_1080p.mp4';
            $commands[] = ['output_file' => $output_file, 'cmd' => 'ffmpeg -i ' . $tmp_file . ' -s hd1080 -f mp4 ' . $output_file . ' 2>&1', 'dimension' => '1080p'];
        }

        if (!count($payload['video_sizes'])) {
            $output_file = $new_file . '.mp4';
            $commands[] = ['output_file' => $output_file, 'cmd' => 'ffmpeg -i ' . $tmp_file . ' ' . $output_file . '.mp4 2>&1', 'dimension' => '480p'];
        }

        $first_command = array_shift($commands);

        $r = convert_with_progress($first_command['cmd'], function($completePercent) use ($pdo, $payload) {
            $sth = $pdo->prepare('UPDATE hf_file SET complete_status = :complete_status WHERE id = :row_id');

            if ($completePercent == 100) {
                $completePercent = mt_rand(95, 99);
            }

            $sth->execute(array('complete_status' => $completePercent, 'row_id' => $payload['row_id']));
        }, $convert_errors);

        if (!$r) {
            throw new Exception(implode(', ', $convert_errors));
        }

        $file_url = sentFileToStorage($first_command['output_file'], 'http://holdfreestorage.com');
        $files_urls[$first_command['dimension']] = $file_url;
        $sth = $pdo->prepare('UPDATE hf_file SET files = :files_urls, complete_status = 100 WHERE id = :row_id');
        $sth->execute(['files_urls' => json_encode($files_urls), 'row_id' => $payload['row_id']]);

        foreach ($commands as $command) {
            $r = convert($command['cmd'], $convert_errors);

            $file_url = sentFileToStorage($command['output_file'], 'http://holdfreestorage.com');
            $files_urls[$command['dimension']] = $file_url;
            $sth = $pdo->prepare('UPDATE hf_file SET files = :files_urls WHERE id = :row_id');
            $sth->execute(['files_urls' => json_encode($files_urls), 'row_id' => $payload['row_id']]);

            if (!$r) {
                $convert_errors = implode(', ', $convert_errors);
                throw new Exception($convert_errors);
            }
        }

    }
    catch (Exception $e) {
        if (empty($pdo) || empty($payload['row_id'])) {
            die;
        }

        $sth = $pdo->prepare('UPDATE hf_file SET status_message = :status_message WHERE id = :row_id');
        $sth->execute(array('status_message' => $e->getMessage() . ' / ' . $e->getLine(), 'row_id' => $payload['row_id']));
    };
}