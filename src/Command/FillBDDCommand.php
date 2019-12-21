<?php

namespace App\Command;

use App\Entity\Diplome;
use App\Entity\Domaine;
use App\Entity\Formation;
use App\Entity\Organization;
use App\Entity\User;
use App\Form\User1Type;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FillBDDCommand extends ContainerAwareCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-user';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User();
        $user->setName("Admin");
        $user->setFirstName("Admin");
        $user->setEmail("admin@admin.admin");
        $user->setRoles(["role_admin"]);
        $user->setPlainPassword("admin");
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em->persist($user);

        $user = new User();
        $user->setName("Orga 1");
        $user->setFirstName("Truc");
        $user->setEmail("orga1@orga.orga");
        $user->setRoles(["role_organizateur"]);
        $user->setPlainPassword("orga1");
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em->persist($user);

        $orga = new Organization();
        $orga->setName("CENTRE DE FORMATION PROFESSIONNELLE DES MÉTIERS DU SPORT");
        $orga->setPhoneNumber("07 56 86 40 60");
        $orga->setNote("Marché de petite et grande structure");
        $orga->setWebSite("pasdutout.com");
        $orga->setField("BPJEPS - Santé");
        $orga->setUser($user);
        $em->persist($orga);

        $user = new User();
        $user->setName("Orga 2");
        $user->setFirstName("Truc");
        $user->setEmail("orga2@orga.orga");
        $user->setRoles(["role_organizateur"]);
        $user->setPlainPassword("orga2");
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em->persist($user);

        $orga2 = new Organization();
        $orga2->setName("A L'EAU MNS");
        $orga2->setPhoneNumber("04 72 81 93 69");
        $orga2->setNote("Rappeler début de semaine pro");
        $orga2->setWebSite("pasdutout.com");
        $orga2->setField("BNSSA");
        $orga2->setUser($user);
        $em->persist($orga2);

        $user = new User();
        $user->setName("Orga 3");
        $user->setFirstName("Truc");
        $user->setEmail("orga3@orga.orga");
        $user->setRoles(["role_organizateur"]);
        $user->setPlainPassword("orga3");
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em->persist($user);

        $orga3 = new Organization();
        $orga3->setName("GLOBAL TRAINING FORMATION");
        $orga3->setPhoneNumber("01 44 26 39 89");
        $orga3->setNote("Travail avec les entreprises également");
        $orga3->setWebSite("https://globaltraining-formation.fr/");
        $orga3->setField("");
        $orga3->setUser($user);
        $em->persist($orga3);

        $user = new User();
        $user->setName("Orga 4");
        $user->setFirstName("Truc");
        $user->setEmail("orga4@orga.orga");
        $user->setRoles(["role_organizateur"]);
        $user->setPlainPassword("orga4");
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em->persist($user);


        $orga4 = new Organization();
        $orga4->setName("UCPA");
        $orga4->setPhoneNumber("");
        $orga4->setNote("");
        $orga4->setWebSite("http://formation.ucpa.com");
        $orga4->setField("");
        $orga4->setUser($user);
        $em->persist($orga4);

        $user = new User();
        $user->setName("Orga 5");
        $user->setFirstName("Truc");
        $user->setEmail("orga5@orga.orga");
        $user->setRoles(["role_organizateur"]);
        $user->setPlainPassword("orga5");
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em->persist($user);

        $orga5 = new Organization();
        $orga5->setName("Trans-Faire");
        $orga5->setPhoneNumber("");
        $orga5->setNote("");
        $orga5->setWebSite("https://www.trans-faire.fr/");
        $orga5->setField("");
        $orga5->setUser($user);
        $em->persist($orga5);

        $domaine1 = new Domaine();
        $domaine1->setName("Coaching");
        $domaine2 = new Domaine();
        $domaine2->setName("Natation");
        $domaine3 = new Domaine();
        $domaine3->setName("Secourisme");
        $domaine4 = new Domaine();
        $domaine4->setName("Educateur");
        $domaine5 = new Domaine();
        $domaine5->setName("Sport Individuel");
        $domaine6 = new Domaine();
        $domaine6->setName("Sport Collectif");

        $em->persist($domaine1);
        $em->persist($domaine2);
        $em->persist($domaine3);
        $em->persist($domaine4);
        $em->persist($domaine5);
        $em->persist($domaine6);
        $diplome = new Diplome();
        $diplome->setName("Préparation BPJEPS AF");
        $diplome->setDomaine($domaine1);
        $em->persist($diplome);


        $formation = new Formation();
        $formation->setOrganization($orga);
        $formation->setName("Formation Trop bien");
        $formation->setAddress("22 Boulevard Gambetta");
        $formation->setCity("Issy-les-Moulineaux");
        $formation->setPostalCode("92130");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setModeFinancement("CB Lydia");
        $formation->setPhoneNumber("0756864660");
        $formation->setDiplome($diplome);
        $formation->setMail("");
        $date =new \DateTime("2020/01/09");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(1490);
        $formation->setConfirmer(true);
        $em->persist($formation);


        $diplome2 = new Diplome();
        $diplome2->setName("BPJEPS AAN");
        $diplome2->setDomaine($domaine2);
        $em->persist($diplome2);


        $formation = new Formation();
        $formation->setOrganization($orga);
        $formation->setName("Formation Trop bien");
        $formation->setAddress("22 Boulevard Gambetta");
        $formation->setModeFinancement("CB Lydia");
        $formation->setCity("Issy-les-Moulineaux");
        $formation->setPostalCode("92130");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setPhoneNumber("0756864660");
        $formation->setConfirmer(true);
        $formation->setMail("");
        $formation->setDiplome($diplome2);
        $date =new \DateTime("2020/01/09");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(8000);
        $formation->setConfirmer(true);
        $em->persist($formation);


        $diplome3 = new Diplome();
        $diplome3->setName("BPJEPS AF");
        $diplome3->setDomaine($domaine1);
        $em->persist($diplome3);

        $formation = new Formation();
        $formation->setOrganization($orga);
        $formation->setName("Formation Trop bien");
        $formation->setAddress("22 Boulevard Gambetta");
        $formation->setCity("Issy-les-Moulineaux");
        $formation->setPostalCode("92130");
        $formation->setModeFinancement("CB Lydia");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setMail("");
        $formation->setPhoneNumber("0756864660");
        $formation->setDiplome($diplome3);
        $date =new \DateTime("2020/01/09");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(6018);
        $formation->setConfirmer(true);
        $em->persist($formation);

        $diplome4 = new Diplome();
        $diplome4->setName("BPJEPS APT");
        $diplome4->setDomaine($domaine4);
        $em->persist($diplome4);

        $formation = new Formation();
        $formation->setName("Formation Trop bien");
        $formation->setOrganization($orga);
        $formation->setAddress("22 Boulevard Gambetta");
        $formation->setCity("Issy-les-Moulineaux");
        $formation->setMail("");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setModeFinancement("CB Lydia");
        $formation->setPostalCode("92130");
        $formation->setPhoneNumber("0756864660");
        $formation->setDiplome($diplome4);
        $date =new \DateTime("2020/01/09");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(8000);
        $formation->setConfirmer(true);
        $em->persist($formation);


        $diplome5 = new Diplome();
        $diplome5->setName("BPJEPS ASC");
        $diplome5->setDomaine($domaine6);
        $em->persist($diplome5);

        $formation = new Formation();
        $formation->setName("Formation Trop bien");
        $formation->setOrganization($orga);
        $formation->setAddress("22 Boulevard Gambetta");
        $formation->setCity("Issy-les-Moulineaux");
        $formation->setPostalCode("92130");
        $formation->setModeFinancement("CB Lydia");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setPhoneNumber("0756864660");
        $formation->setDiplome($diplome5);
        $formation->setMail("");
        $formation->setConfirmer(true);
        $date =new \DateTime("2020/01/09");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(8);
        $em->persist($formation);


        $diplome6 = new Diplome();
        $diplome6->setName("BNSSA + PSE1 et PSE2");
        $diplome6->setDomaine($domaine2);
        $em->persist($diplome6);

        $formation = new Formation();
        $formation->setOrganization($orga);
        $formation->setName("Formation Trop bien");
        $formation->setAddress("35-43 Rue Jean Mermoz");
        $formation->setModeFinancement("CB Lydia");
        $formation->setCity("Lagny-sur-Marne");
        $formation->setPostalCode("77400");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setMail("");
        $formation->setPhoneNumber("0134272850");
        $formation->setDiplome($diplome6);
        $formation->setConfirmer(true);
        $date =new \DateTime("2020/01/12");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(8);
        $em->persist($formation);



        $formation = new Formation();
        $formation->setOrganization($orga);
        $formation->setName("Formation Trop bien");
        $formation->setAddress("35-43 Rue Jean Mermoz");
        $formation->setModeFinancement("CB Lydia");
        $formation->setCity("Lagny-sur-Marne");
        $formation->setPerspective("Yolo c'est le feu");
        $formation->setPostalCode("77400");
        $formation->setPhoneNumber("0134272850");
        $formation->setConfirmer(true);
        $formation->setDiplome($diplome6);
        $formation->setMail("");
        $date =new \DateTime("2020/01/09");
        $formation->setDate($date);
        $date =new \DateTime("2021/01/09");
        $formation->setDateFin($date);
        $formation->setPrice(0);
        $em->persist($formation);


        $em->flush();
    }
}