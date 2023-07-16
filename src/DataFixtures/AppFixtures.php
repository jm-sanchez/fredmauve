<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Image;
use App\Entity\Work;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture 
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setEmail("admin@admin.fr")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->encoder->hashPassword($admin, 'azerazer'));
        $manager->persist($admin);

        for ($i = 1; $i <= 5; $i++) { 
            $contact = new Contact();
            $timezone = new DateTimeZone('Europe/Paris');
            $contact->setEmail("user".$i."@user.fr")
                    ->setName("Joe".$i)
                    ->setSubject("Hello Fred")
                    ->setMessage("lorem ipsum dolor sit amet, consectetur adip.")
                    ->setCreatedAt(new \DateTime("now", $timezone))
                    ->setAdministrator($admin);
            $manager->persist($contact);
        }

        // for ($i=1; $i <= 4; $i++) {
        //     $work = new Work();
        //     $image = new Image();
        //     $category = new Category();
        //     $category->setName("art")
        //             ->setSlug("art");
        //     $image->setName("public/assets/uploads/images/img_exemple.png");
        //     $work->setTitle("titre".$i)
        //         ->setFormat("50 x 50 cm")
        //         ->setCategory($category)
        //         ->setTechnique("Illustration sur papier")
        //         ->setQuantity($i)
        //         ->addImage($image);
        //     $manager->persist($work);
        // }
        $manager->flush();
    }
}
