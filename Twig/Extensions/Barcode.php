<?php

namespace SGK\BarcodeBundle\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use SGK\BarcodeBundle\Generator\Generator;

/**
 * Class Project_Twig_Extension
 *
 * @package SGK\BarcodeBundle\Twig\Extensions
 */
class Barcode extends Twig_Extension
{
    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'barcode',
                function ($options = array()) {
                    echo $this->generator->generate($options);
                }
            ),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'barcode';
    }
}
