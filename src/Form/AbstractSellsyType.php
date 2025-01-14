<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Sellsy\Form;

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Splash\Models\Objects\Invoice\PaymentMethods;
use Splash\Security\Oauth2\Form\PrivateAppConfigurationForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Webmozart\Assert\Assert;

abstract class AbstractSellsyType extends PrivateAppConfigurationForm
{
    /**
     * Select for Default Payment Method to FormBuilder
     */
    protected function addDefaultPaymentMethodsField(FormBuilderInterface $builder, array $options): self
    {
        //==============================================================================
        // Load & Validate Account Payment Methods
        $choices = $this->getMethodsChoices($options);
        //==============================================================================
        // Safety Check
        if (empty($choices)) {
            return $this;
        }

        $builder
            //==============================================================================
            // Default Payment Method
            ->add('PaymentMethodDefault', ChoiceType::class, array(
                'label' => "admin.payments.methods.default.title",
                'help' => "admin.payments.methods.default.help",
                'required' => true,
                'translation_domain' => "SellsyBundle",
                'choices' => $choices
            ))
        ;

        return $this;
    }

    /**
     * Add Payment Methods Translations to FormBuilder
     */
    protected function addPaymentMethodsField(FormBuilderInterface $builder, array $options): self
    {
        //==============================================================================
        // Load & Validate Account Payment Methods
        $choices = $this->getMethodsChoices($options);
        //==============================================================================
        // Safety Check
        if (empty($choices)) {
            return $this;
        }

        $builder
            ->add('PaymentMethodsAssociations', KeyValueType::class, array(
                'label' => "admin.payments.methods.associations.title",
                'help' => "admin.payments.methods.associations.help",
                'required' => false,
                'key_type' => ChoiceType::class,
                'key_options' => array(
                    'label' => "Splash Method",
                    'choices' => array_flip(PaymentMethods::getChoices()),
                    "row_attr" => array("class" => "col-md-6"),
                ),
                'value_type' => ChoiceType::class,
                'value_options' => array(
                    'label' => "Sellsy Method",
                    'choices' => $choices,
                    "row_attr" => array("class" => "col-md-6"),
                ),
                'translation_domain' => "SellsyBundle",
            ))
        ;

        return $this;
    }

    /**
     * Select for Default Payment Method to FormBuilder
     */
    private function getMethodsChoices(array $options): array
    {
        //==============================================================================
        // Load & Validate Account Payment Methods
        $methods = $options['data']["PaymentMethods"] ?? array();
        Assert::isArray($methods);
        Assert::allIsArray($methods);
        //==============================================================================
        // Build Payment Methods Choices
        $choices = array();
        foreach ($methods as $id => $method) {
            if ($label = $method['label'] ?? null) {
                $choices[$label] = $id;
            }
        }

        return $choices;
    }
}
