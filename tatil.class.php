<?php
/**
 * Created by PhpStorm.
 * User: cagri
 * Date: 13.06.2018
 * Time: 18:27
 */

class Tatil
{
    protected $url_repo_list, $username, $token;

    const CACHE_TIMEOUT      = 5; //seconds
    const CACHE_EXT          = '.cache';
    const DEFAULT_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko)
                                Chrome/67.0.3396.79 Safari/537.36';

    /**
     * Tatil constructor.
     * @param String $username
     * @param String $token
     */
    function __construct(String $username, String $token)
    {
        $this->url_repo_list = 'https://api.github.com/users/' . $username . '/repos';
        //Why am i converted them to ENV?
        putenv('username=' . $username);
        putenv('token=' . $token);
    }

    /**
     * it returns repo list as an array and this is only one public method
     * @return array or false
     */
    public function getRepoList(): Array
    {
        $file_name      = getenv('username') . self::CACHE_EXT;
        $file_path      = $_SERVER['DOCUMENT_ROOT'] . '/cache';
        $file_full_path = $file_path . '/' . $file_name;

        if (!file_exists($file_full_path) || filemtime($file_full_path) < time() - self::CACHE_TIMEOUT) {
            //echo 'remote';
            $this->getListFromRemote();
        }

        $file_content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/cache/' . getenv('username') . self::CACHE_EXT);
        return $this->toJson($file_content);
    }

    /**
     * It takes json, returns an array
     * @param String $json
     * @return array
     */
    private function toJson(String $json): Array
    {
        //if file hasn't data
        if (strlen($json) < 1) return false;

        //they wanted repos from me. For now i returned just names of them.
        $repo_list = [];
        $arr       = json_decode($json, TRUE);
        if (json_last_error()) {
            return false;
        }

        foreach ($arr as $item) {
            array_push($repo_list, $item['full_name']);
        }

        return $repo_list;
    }

    /**
     * Gets repo list from github and cache it in a file that has extension `cache` in `cache` directory
     */
    private function getListFromRemote(): bool
    {
        try {
            ob_start();
            $handle = curl_init($this->url_repo_list);
            curl_setopt($handle, CURLOPT_HTTPHEADER, array(
                'username' => getenv('username') . ':' . getenv('token')
            ));
            curl_setopt($handle, CURLOPT_USERAGENT, self::DEFAULT_USER_AGENT);
            curl_exec($handle);

            //set file cache
            $file_flag = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/cache/' . getenv('username') . self::CACHE_EXT, ob_get_contents());
            curl_close($handle);
            ob_clean();
            if (!$file_flag) throw new Exception();
            return true;
        } catch (Exception $ex) {
            return false;
        }

    }

}