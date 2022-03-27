<?php

declare(strict_types=1);

namespace AxelFeL\CustomJoft\Firework\entity\animation;

use AxelFeL\CustomJoft\Firework\entity\FireworksRocket;
use pocketmine\entity\animation\Animation;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\types\ActorEvent;

class FireworkParticleAnimation implements Animation
{
    private FireworksRocket $firework;

    public function __construct(FireworksRocket $firework)
    {
        $this->firework = $firework;
    }

    public function encode(): array
    {
        return [
            ActorEventPacket::create($this->firework->getId(), ActorEvent::FIREWORK_PARTICLES, 0)
        ];
    }
}