<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Renvoyer la vue du profil en passant l'utilisateur en paramètre
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, $id): Response
    {
        // $form = $this->createForm(UserType::class, $user);
        $form = $this->createFormBuilder($user)
            ->add('name')
            ->add('email')
            ->add('image', FileType::class, [
                'required' => false,
                'data_class' => null,
                // mapped false permet d'extraire un input du reste du formulaire, ça évite qu'un input
                // soit lié à l'objet envoyé dans le formulaire
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                    ]),
                ]
            ])

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            // je verifie qu'une nouvelle image a ete envoyé avec le formulaire 
            if ($image != null) {
                // je verifie l'existance d'une encienne image au produit 
                // si c'est le cas je supprime l'ancienne image 
                if (file_exists($this->getParameter('user_image_directory') . $user->getImage())) {
                    unlink($this->getParameter('user_image_directory') . $user->getImage());
                }

                // puis je telechager la nouvelle image et change le nom de l'image en base de donnees

                $imgName = uniqid() . '.' . $image->guessExtension();
                $user->setImage($imgName);
                $image->move($this->getParameter('user_image_directory'), $imgName);
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
