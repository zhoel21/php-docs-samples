<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\Cloud\Samples\AppEngine\Endpoints;

use Google\Cloud\TestUtils\ContainerEngineDeploymentTrait;
use Google\Cloud\TestUtils\FileUtil;

class DeployTest extends \PHPUnit_Framework_TestCase
{
    use ContainerEngineDeploymentTrait, DeployTestTrait;

    public static function beforeDeploy()
    {
        // specify the k8s service name
        self::$kubeService = 'esp-echo';

        // get the endpoints service name and config id
        $serviceName = getenv('GOOGLE_ENDPOINTS_SERVICE_NAME');
        $configId = getenv('GOOGLE_ENDPOINTS_CONFIG_ID');

        // copy the source files to a temp directory
        $tmpDir = FileUtil::cloneDirectoryIntoTmp(__DIR__ . '/..');
        self::$gcloudWrapper->setDir($tmpDir);
        chdir($tmpDir);

        // update container-engine.yaml
        $containerEngineYaml = str_replace(
            ['SERVICE_NAME', 'SERVICE_VERSION'],
            [$serviceName, $configId],
            file_get_contents('container-engine.yaml')
        );
        file_put_contents(
            $tmpDir . '/container-engine.yaml',
            $containerEngineYaml
        );
    }

    public static function afterDeploy()
    {
        // delete the endpoints service
        if (getenv('GOOGLE_KEEP_DEPLOYMENT') !== 'true') {
            $serviceName = getenv('GOOGLE_ENDPOINTS_SERVICE_NAME');
            $cmd = sprintf(
                'gcloud -q service-management delete %s --project %s',
                $serviceName,
                $this->project
            );
            exec($cmd);
        }
    }
}
