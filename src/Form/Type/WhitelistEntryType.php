<?php

/*
 * This file is part of the Sylius Force Customer Login package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace BitExpert\SyliusForceCustomerLoginPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\GridBundle\Form\Type\Filter\SelectFilterType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class WhitelistEntryType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'bitexpert_sylius_forcelogin.ui.channels',
            ])
            ->add('label', TextType::class, [
                'label' => 'bitexpert_sylius_forcelogin.ui.label',
            ])
            ->add('urlRule', TextType::class, [
                'label' => 'bitexpert_sylius_forcelogin.ui.urlRule',
            ])
            ->add('strategy', SelectFilterType::class, [
                'choices' => [
                    'static' => 'Static',
                    'regex' => 'Regex'
                ],
                'label' => 'bitexpert_sylius_forcelogin.ui.strategy',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitexpert_sylius_forcelogin_whitelist_entry';
    }
}
