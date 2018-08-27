<?php

namespace WeProvide\ConfigBundle\Dumper;

abstract class AbstractDumper
{
    /**
     * @param array  $configValues
     * @param string $filePath
     * @return string
     */
    public function dump(array $configValues, $filePath)
    {
        file_put_contents($filePath, $this->dumpContent(array('parameters' => $configValues)));
    }

    /**
     * @param array $configValues
     * @return string
     */
    abstract protected function dumpContent(array $configValues);
}
