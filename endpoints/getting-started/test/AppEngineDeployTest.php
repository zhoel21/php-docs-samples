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

use Google\Cloud\TestUtils\AppEngineDeploymentTrait;
use Google\Cloud\TestUtils\FileUtil;

class DeployTest extends \PHPUnit_Framework_TestCase
{
    use AppEngineDeploymentTrait, DeployTestTrait;

    public static function beforeDeploy()
    {
        $serviceName = getenv('GOOGLE_ENDPOINTS_SERVICE_NAME');
        $configId = getenv('GOOGLE_ENDPOINTS_CONFIG_ID');

        // copy the source files to a temp directory
        $tmpDir = FileUtil::cloneDirectoryIntoTmp(__DIR__ . '/..');
        self::$gcloudWrapper->setDir($tmpDir);
        chdir($tmpDir);

        // update app.yaml
        $appYaml = str_replace(
            ['ENDPOINTS SERVICE NAME', 'ENDPOINTS CONFIG ID'],
            [$serviceName, $configId],
            file_get_contents('app.yaml')
        );
        file_put_contents($tmpDir . '/app.yaml', $appYaml);
    }
}
