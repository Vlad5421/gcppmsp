<?php

namespace App\Controller\Admin;

use App\CollectionsGetter\FilialCollectionsGetter;
use App\Entity\Filial;
use App\Entity\FilialService;
use App\Entity\Service;
use App\Form\FilialFormType;
use App\Form\FilialServiceFormType;
use App\Repository\FilialRepository;
use App\Repository\FilialServiceRepository;
use App\Services\CustomSerializer;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilialAdminController extends AbstractController
{
    #[Route('/manage-panel/filial/all', name: 'app_admin_filial_all')]
    public function list(Request $request, FilialRepository $filRepo, PaginatorInterface $paginator, CustomSerializer $serialiser): Response
    {
        $filials = $filRepo->findAllSort('collection', $request->query->get('q'),);
//        dd($filials);
        $fs = $serialiser->serializeIt($filials);

        $pagination = $paginator->paginate(
            $fs, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->get('pageCount') ? $request->query->get('pageCount') : 200 /*limit per page*/
        );


        return $this->render('admin/list_entitys.html.twig', [
            'page' => 'Список филиалов',
            'entity' => '_filial',
            'collection' => $pagination,
            'exlude_columns' => ['image', 'collection']
        ]);
    }

    #[Route('/manage-panel/filial/create', name: 'app_admin_filial_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FilialFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var Filial $filial */
            $filial = $form->getData();
//            $filial->setServiceLogo('/img/priem-psy.jpg');

            $em->persist($filial);
            $em->flush();
            $this->addFlash('flash_message', 'Филиал создан');

            return $this->redirectToRoute('app_admin_filial_all');

        }

        return $this->render('admin/filial_admin/create.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }

    #[Route('/manage-panel/filial/edit/{id}', name: 'app_admin_filial_edit')]
    public function edit(Filial $filial, Request $request, EntityManagerInterface $em, FileUploader $filialFileUploader, FilialServiceRepository $fsr, CustomSerializer $serializer): Response
    {
        $form = $this->createForm(FilialFormType::class, $filial);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filial = $this->handleFormRequest($filialFileUploader, $form);
            $em->persist($filial);
            $em->flush();
            $this->addFlash('flash_message', 'Филиал изменён');

            return $this->redirectToRoute('app_admin_filial_all');

        }
        $uss = (new FilialCollectionsGetter($fsr))->getServices($filial);
        $sdsd = $serializer->serializeIt($uss);

        $resp_array = [
            'filial' => $filial,
            'form' => $form->createView(),
            'page' => 'Редактировать филиал',
        ];
        if (count($sdsd) > 0) $resp_array['services'] = $sdsd;

        return $this->render('admin/filial_admin/create.html.twig', $resp_array);
    }

    public function handleFormRequest(FileUploader $filialFileUploader, $form): Filial
    {
        /** @var Filial $filial */
        $filial = $form->getData();

        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();

        if ($image) {
            $fileName = $filialFileUploader->uploadFile($image, $filial->getImage());
            $filial->setImage($fileName);
        } else {
            $filial->setImage("logo-dom.jpg");
        }
        return $filial;

    }


    #[Route('/manage-panel/filial-service/create', name: 'app_admin_filialservice_create')]
    public function createComplect(Request $request, EntityManagerInterface $em, FilialServiceRepository $fsRepo): Response
    {
        $form = $this->createForm(FilialServiceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var FilialService $complect */
            $complect = $form->getData();

            $check = $fsRepo->findOneBy(['filial' => $complect->getFilial(), "service" => $complect->getService()]);

            if ($check) {
                $this->addFlash('flash_message', "!ВНИМАНИЕ. Этому филиалу уже назначена эта услуга");
            } else {
                $em->persist($complect);
                $em->flush();
                $this->addFlash('flash_message', 'Услуга добавлена к филиалу');
            }

            return $this->redirectToRoute('app_admin_filialservice_create');

        }

        return $this->render('admin/filial_admin/create_comlect.html.twig', [
            'form' => $form->createView(),
            'page' => 'Создать услугу'
        ]);
    }

    #[Route('/manage-panel/filial/{filial_id}/delite-servce/{id}', name: 'app_admin_filialservice_delite')]
    public function deliteComplect(Service $service, Request $request, EntityManagerInterface $em, FilialServiceRepository $fs_repo, FilialRepository $filialRepository): Response
    {
//        dump($service);
        $filial = $filialRepository->find($request->attributes->get("filial_id"));
        $complects = $fs_repo->findBy(["filial" => $filial, "service" => $service]);
        $count = count($complects);
        if ($count > 0){
            foreach ($complects as $complect){
                $em->remove((object)$complect);
            }
            $em->flush();
        }
        $this->addFlash('flash_message', "$count уeслуг/а откреплено от филиала");


        return $this->redirectToRoute('app_admin_filial_edit', [
            "id" => $request->attributes->get("filial_id"),

        ]);
    }
}