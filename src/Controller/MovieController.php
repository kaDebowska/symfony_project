<?php
/**
 * Movie controller.
 */

namespace App\Controller;

use App\Entity\Movie;
use App\Form\Type\MovieType;
use App\Service\MovieServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class MovieController.
 */
#[Route('/movie')]
class MovieController extends AbstractController
{
    /**
     * Movie service.
     */
    private MovieServiceInterface $movieService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param MovieServiceInterface $movieService Movie service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(MovieServiceInterface $movieService, TranslatorInterface $translator)
    {
        $this->movieService = $movieService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'movie_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->movieService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('movie/index.html.twig', [
           'pagination' => $pagination,
        ]);
    }

    /**
     * Show action.
     *
     * @param Movie $movie Movie entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'movie_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Movie $movie): Response
    {
        return $this->render(
            'movie/show.html.twig',
            ['movie' => $movie]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'movie_create', methods: 'GET|POST', )]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie, ['action' => $this->generateUrl('movie_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->movieService->save($movie);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Movie    $movie    Task entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'movie_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie, [
            'method' => 'PUT',
            'action' => $this->generateUrl('movie_edit', ['id' => $movie->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->movieService->save($movie);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/edit.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Movie    $movie    Movie entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'movie_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(FormType::class, $movie, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('movie_delete', ['id' => $movie->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->movieService->delete($movie);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/delete.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }
}
