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

namespace BitExpert\SyliusForceCustomerLoginPlugin\Doctrine\ORM;

use BitExpert\SyliusForceCustomerLoginPlugin\Model\WhitelistEntryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

class WhitelistEntryRepository extends EntityRepository implements WhitelistEntryRepositoryInterface
{
    /**
     * @return array<WhitelistEntryInterface>
     */
    public function findByChannel(ChannelInterface $channel): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where(':channel MEMBER OF p.channels')
            ->setParameters(new ArrayCollection([
                new Parameter('channel', $channel),
            ]));

        $result = $qb->getQuery()->getResult();

        return is_array($result) ? $result : [];
    }
}
