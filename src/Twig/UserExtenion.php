<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtenion extends AbstractExtension
{
    private const ROLES = [
        'ROLE_ADMIN' => 'Administrator',
        'ROLE_MODERATOR' => 'Moderator',
    ];

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_profile_image', [$this, 'getUserProfileImage'], ['is_safe' => ['html']]),
            new TwigFunction('user_profile_link', [$this, 'getUserProfileLink'], ['is_safe' => ['html']]),
            new TwigFunction('user_profile_role', [$this, 'getUserProfileRole']),
        ];
    }
    
    public function getUserProfileImage(?User $user, string $text = null, string $class = null): string
    {
        if ($user) {
            $route = $this->urlGenerator->generate('user.profile', ['slug' => $user->getSlug()]);
            $classAttr = $class ? ' class="' . $class . '"' : '';

            return '<div class="messageAuthor">
		            <div class="userAvatar">
					    <a href="https://forum.betriebsrat.de/core/user/27796-systemcheck/" aria-hidden="true" tabindex="-1">
                            <img src="https://forum.betriebsrat.de/core/images/avatars/gravatars/b27b15fe144fcc3d57e2a77b232daf4a-128.jpg" width="128" height="128" alt="" class="userAvatarImage"></a>					
					</div>
						
			    </div>';
        }

        return 'Compte supprimé';
    }

    public function getUserProfileLink(?User $user, string $text = null, string $class = null): string
    {
        if ($user) {
            $route = $this->urlGenerator->generate('user.profile', ['slug' => $user->getSlug()]);
            $classAttr = $class ? ' class="' . $class . '"' : '';

            return sprintf('<a href="%s"' . $classAttr . '>%s</a>', $route, $text ?? $user->getPseudo());
        }

        return 'Compte supprimé';
    }

    public function getUserProfileRole(User $user): ?string
    {
        foreach (self::ROLES as $k => $role) {
            if (in_array($k, $user->getRoles(), true)) {
                return $role;
            }
        }

        return null;
    }
}
