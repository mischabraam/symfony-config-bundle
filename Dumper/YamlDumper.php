<?php

namespace WeProvide\ConfigBundle\Dumper;

use WeProvide\ConfigBundle\Entity\ConfigValue;
use WeProvide\ConfigBundle\Entity\ConfigVariable;
use WeProvide\ConfigBundle\Writer\Writer;
use Symfony\Component\Yaml\Inline;

class YamlDumper extends AbstractDumper
{
    /**
     * @var Writer
     */
    private $writer;

    /**
     * YamlDumper constructor.
     */
    public function __construct()
    {
        $this->writer = new Writer();
    }

    /**
     * @param array $content
     * @return string
     */
    protected function dumpContent(array $content)
    {
        $this->writer->reset();

        $this->dumpContentRecursively($content);

        return $this->writer->getContent();
    }

    /**
     * @param array $content
     */
    private function dumpContentRecursively(array $content)
    {
        foreach ($content as $key => $value) {
            if ($value instanceof ConfigValue) {
                $this->dumpConfigValue($value);
                continue;
            }

            $this->writer->write(Inline::dump($key).':');
            $this->writer->write("\n")->indent();
            $this->dumpContentRecursively($value);
            $this->writer->outdent();
        }
    }

    /**
     * @param ConfigValue $configValue
     */
    private function dumpConfigValue(ConfigValue $configValue)
    {
        $this->writer->write(Inline::dump($configValue->getConfigVariable()->getName()).':');

        // TODO: implement TYPE_LIST
        switch ($configValue->getConfigVariable()->getType()) {
            case ConfigVariable::TYPE_BOOLEAN:
                $this->writer->write(' '.Inline::dump($configValue->getValue() === 'true'));
                break;

            case ConfigVariable::TYPE_INTEGER:
                $this->writer->write(' '.Inline::dump((int)$configValue->getValue()));
                break;

            default:
                $this->writer->write(' '.Inline::dump($configValue->getAggregatedValue()));
        }

        $this->writer->write("\n");
    }
}
