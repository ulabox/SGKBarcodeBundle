<?php

namespace SGK\BarcodeBundle\Generator;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

use SGK\BarcodeBundle\Type\Type;
use SGK\BarcodeBundle\DineshBarcode\DNS2D;
use SGK\BarcodeBundle\DineshBarcode\DNS1D;

/**
 * Class Generator
 * Encapsulation of project https://github.com/dineshrabara/barcode for Symfony2 usage
 *
 * @package SGK\BarcodeBundle\Generator
 */
class Generator
{
    /**
     * @var DNS2D
     */
    protected $dns2d;

    /**
     * @var DNS1D
     */
    protected $dns1d;

    /**
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * @var array
     */
    protected $formatFunctionMap = array(
        'svg'  => 'getBarcodeSVG',
        'html' => 'getBarcodeHTML',
        'png'  => 'getBarcodePNG',
    );

    /**
     * construct
     */
    public function __construct()
    {
        $this->dns2d = new DNS2D();
        $this->dns1d = new DNS1D();
        $this->resolver = new OptionsResolver();
        $this->configureOptions($this->resolver);
    }

    /**
     * @param array $options
     *        string $code   code to print
     *        string $type   type of barcode
     *        string $format output format
     *        int    $width  Minimum width of a single bar in user units.
     *        int    $height Height of barcode in user units.
     *        string $color  Foreground color (in SVG format) for bar elements (background is transparent).
     *
     * @return mixed
     */
    public function generate($options = array())
    {
        $options = $this->resolver->resolve($options);

        if (Type::getDimension($options['type']) == '2D') {
            return call_user_func_array(
                array(
                    $this->dns2d,
                    $this->formatFunctionMap[$options['format']],
                ),
                array($options['code'], $options['type'], $options['width'], $options['height'], $options['color'])
            );
        } else {
            return call_user_func_array(
                array(
                    $this->dns1d,
                    $this->formatFunctionMap[$options['format']],
                ),
                array($options['code'], $options['type'], $options['width'], $options['height'], $options['color'])
            );
        }
    }

    /**
     * Configure generate options
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array(
                'code', 'type', 'format',
            ))
            ->setDefined(array(
                'width', 'height', 'color',
            ))
            ->setDefaults(array(
                'width' => function (Options $options) {
                    return Type::getDimension($options['type']) == '2D' ? 5 : 2;
                },
                'height' => function (Options $options) {
                    return Type::getDimension($options['type']) == '2D' ? 5 : 30;
                },
                'color' => function (Options $options) {
                    return $options['format'] == 'png' ? array(0, 0, 0) : 'black';
                },
            ));

            $allowedTypes = array(
                'code'   => array('string'),
                'type'   => array('string'),
                'format' => array('string'),
                'width'  => array('integer'),
                'height' => array('integer'),
                'color'  => array('string', 'array'),
            );

            foreach ($allowedTypes as $typeName => $typeValue) {
                $resolver->setAllowedTypes($typeName, $typeValue);
            }

            $allowedValues = array(
                'type'   => array_merge(
                    Type::$oneDimensionalBarcodeType,
                    Type::$twoDimensionalBarcodeType
                ),
                'format' => array('html', 'png', 'svg'),
            );

            foreach ($allowedValues as $valueName => $value) {
                $resolver->setAllowedValues($valueName, $value);
            }

    }
}
