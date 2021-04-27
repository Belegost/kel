<?php
/**
 * Created by PhpStorm.
 * User: Grand
 * Date: 10.06.2018
 * Time: 13:03
 */

namespace App\Lib;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class JSONHelper {
    use ContainerAwareTrait;

    public function parseFile(string $file, $default = null) {
        /** @var \App\Kernel $kernel */
        $kernel = $this->container->get('kernel');

        $path = sprintf('%s/%s.json',
            $kernel->getProjectDir(),
            preg_replace("/\.json$/", '', $file)
        );

        $content = file_get_contents($path);
        if ($result = json_decode($content, true)) {
            return $result;
        }

        return $default;
    }
}