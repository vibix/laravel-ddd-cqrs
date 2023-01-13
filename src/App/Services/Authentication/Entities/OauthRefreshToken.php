<?php

declare(strict_types=1);

namespace App\Services\Authentication\Entities;

use DateTime;

//use Doctrine\ORM\Mapping as ORM;
// TODO: annotations to yaml

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_personal_access_clients", indexes={@ORM\Index(name="client_id_index", columns={"client_id"})})
 */
class OauthRefreshToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(name="client_id", type="string")
     */
    protected string $clientId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected DateTime $updatedAt;
}
