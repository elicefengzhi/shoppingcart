<?php
namespace VisioCrudModeler\JQueryValidator;

use Zend\InputFilter\InputFilterAwareInterface;

/**
 * Validator interface
 *
 * @author Piotr Duda <piotr.duda@dentsuaegis.com, dudapiotrek@gmail.com>
 * @link https://github.com/HyPhers/hyphers-visio-crud-zf2
 * @copyright Copyright (c) 2014 HyPHPers Isobar Poland (Piotr Duda , Przemysław Wlodkowski, Bartlomiej Wereszczynski , Jacek Pawelec , Robert Bodych)
 * @license New BSD License
 *
 */
class ValidatorManager implements InputFilterAwareInterface
{

    /**
     *
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * Template for validate plugin
     *
     * @var string
     */
    protected $template = "
            (function(jQuery) {
                $('#%s').validate({
                        rules: {
                            %s
                        }
                    }
                );
            })(jQuery);";

    /**
     * Ommitet classed for generating jquery validate rules
     *
     * @var array
     */
    protected $classToOmmit = array(
        'Zend\Validator\InArray'
    );

    /**
     * Return prepared Jquery Validate Script
     *
     * @param
     *            formName
     * @return string
     */
    public function getScript($formName)
    {
        $rules = '';
        $validatorsRules = '';

        foreach ($this->getInputFilter()->getInputs() as $inputFilter) {

            $name = $inputFilter->getName();

            if ($inputFilter->isRequired()) {
                $validatorsRules = ValidatorFactory::factory('Required', 'true')->getRule();
            }

            $validators = $inputFilter->getValidatorChain()->getValidators();
            if (! empty($validators)) {
                foreach ($validators as $validator) {
                    $class = get_class($validator['instance']);
                    if (in_array($class, $this->classToOmmit)) {
                        continue;
                    }
                    $validatorsRules .= ValidatorFactory::factory($class, $validator['instance'])->getRule();
                }
            }
            $rules .= " $name: {
                        $validatorsRules
                    },";

            $validatorsRules = '';
        }

        return sprintf($this->template, $formName, $rules);
    }

    /**
     * Set InputFilterInterface filter
     *
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     */
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * Get InputFilterInterface filter
     *
     * @return InputFilter
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }
}
