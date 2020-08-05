<?php

/*
 * Kult Engine
 * PHP framework
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package Kult Engine
 * @author Théo Sorriaux (philiphil)
 * @copyright Copyright (c) 2016-2018, Théo Sorriaux
 * @license MIT
 * @link https://github.com/Philiphil/Kult-Engine
 */

namespace kult_engine;

class drive
{
    public static function getdrivescope()
    {
        return ['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/userinfo.profile'];
    }

    public static function verifier_login($login)
    {
        $scope = self::getdrivescope();
        $client = new \Google_Client();
        $client->setScopes($scope);
        $client->setAuthConfig(constant('controllerpath').'secret_key.json');
        $client->setAccessType('offline');
        $accessToken = $client->fetchAccessTokenWithAuthCode($login);
        $client->setAccessToken($accessToken);

        return $accessToken;
    }

    public static function APIAuth()
    {
        $client = new \Google_Client();
        $client->setScopes(self::getdrivescope());
        $client->setAuthConfig(constant('controllerpath').'secret_key.json');
        $client->setAccessType('offline');

        return $client->createAuthUrl();
    }

    public static function getClient($token)
    {
        $client = new \Google_Client();
        $client->setScopes(self::getdrivescope());
        $client->setAuthConfig(constant('controllerpath').'secret_key.json');
        $client->setAccessType('offline');
        $client->setAccessToken($token);

        return $client;
    }

    public static function getUploadFolderId($token, $nom = 'test share')
    {
        //$nom=string
        $client = self::getClient($token);

        $driveService = new \Google_Service_Drive($client);

        $optParams = [
            'pageSize' => 999,
            'fields'   => 'nextPageToken, files(id, name)',
        ];
        $results = $driveService->files->listFiles($optParams);

        foreach ($results as $key) {
            if ($key->name == $nom) {
                return $key->id;
            }
        }

        return -1;
    }

    public static function move_files_to_uploadfolder($token, $args)
    {
        //args = [googleDriveFileId]
        $client = self::getClient($token);
        $driveService = new \Google_Service_Drive($client);

        $folderId = self::getUploadFolderId($token);

        $emptyFileMetadata = new \Google_Service_Drive_DriveFile();
        foreach ($args as $key) {
            $file = $driveService->files->update(
                $key,
                $emptyFileMetadata,
                ['addParents' => $folderId]
            );
        }
    }

    public static function upload_file($token, $file)
    {
        //$file= fullpath/file.php
        $client = self::getClient($token);
        $driveService = new \Google_Service_Drive($client);

        $fileMetadata = new \Google_Service_Drive_DriveFile(
            ['name'       => substr($file, strrpos($file, '/') + 1),
                'parents' => [self::getUploadFolderId($token)],
            ]
        );

        $content = file_get_contents($file);
        $driveService->files->create(
            $fileMetadata,
            [
                'data'       => $content,
                'mimeType'   => kmime_content_type($file),
                'uploadType' => 'media',
            ]
        );
    }

    public static function get_identity($token = null)
    {
        return json_decode(
            file_get_contents(
                'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.
                $token['access_token']
            ),
            true
        );
    }
}
/*
SERVICE
    require_once '../config.php';
    kult_engine\invoker::require_basics(["webService"]);

    kult_engine\webService::service('getAuthToken', function ($args) {

    	$token = kult_engine\drive::verifier_login($args);
    	$o=null;

    	if(is_array($token)){
    		kult_engine\session::connexion();
    		kult_engine\session::set("AuthToken", $token);

    	}


    	return ["key" => $token,
                "identity" => kult_engine\drive::get_identity($token)];
    }, 'POST');



    kult_engine\webService::service('googleConnexion', function ($args) {
    	return ["url_code" =>kult_engine\drive::APIAuth($args["id_token"])];
    }, 'POST');


    kult_engine\webService::service('move_files_to_uploadfolder', function ($args) {

        $retour = kult_engine\drive::move_files_to_uploadfolder(kult_engine\session::get("AuthToken"), $args);

        return ["retour" => $retour];
    }, 'POST');*/

    /*
    AJAX

<script>

(function() {

var bfr = localStorage.getItem("token_time")
var naming = setInterval(cb,5000);

 if(
    bfr == null ||
    bfr < Math.floor(+new Date/1000)
){
         var n = new ReqAjax("googleConnexion");
         n.send(UrlAjax.demo, function(cb){
             window.open(cb.url_code)
         })
}else{
    console.debug(bfr)
    document.getElementById("username").innerHTML = localStorage.getItem("name")
    clearInterval(naming)

}

function cb(){
    var bfr = localStorage.getItem("name")
    if(bfr != null){
        clearInterval(naming)
        document.getElementById("username").innerHTML = localStorage.getItem("name")
    }
}


})();

</script>*/
