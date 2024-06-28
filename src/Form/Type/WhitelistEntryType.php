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

use BitExpert\SyliusForceCustomerLoginPlugin\Model\StrategyInterface;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class WhitelistEntryType extends AbstractResourceType
{
    /**
     * @param RewindableGenerator $strategies
     */
    public function __construct(
        private readonly iterable $strategies,
        string $dataClass,
        array $validationGroups = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $strategies = [];
        foreach ($this->strategies as $strategy) {
            /** @var StrategyInterface $strategy */
            $strategies[] = $strategy;
        }

        $builder
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'bitexpert_sylius_forcelogin.ui.channels',
            ])
            ->add('label', TextType::class, [
                'label' => 'bitexpert_sylius_forcelogin.ui.label',
                'empty_data' => '',
            ])
            ->add('urlRule', TextType::class, [
                'label' => 'bitexpert_sylius_forcelogin.ui.urlRule',
                'empty_data' => '',
            ])
            ->add('strategy', ChoiceType::class, [
                'choices' => $strategies,
                'choice_value' => 'name',
                'choice_label' => function (?StrategyInterface $strategy): string {
                    return is_object($strategy) ? $strategy->getName() : '';
                },
                'label' => 'bitexpert_sylius_forcelogin.ui.strategy',
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitexpert_sylius_forcelogin_whitelist_entry';
    }
}
