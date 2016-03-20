<?php

namespace Atw\DdnsUserAdminBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\Paginator;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Atw\DdnsUserAdminBundle\Controller\Support\CreateFormHelperTrait;
use Atw\DdnsUserAdminBundle\Controller\Support\FlashBagTrait;
use Atw\DdnsUserAdminBundle\Controller\Support\SortConditionValidationTrait;
use Atw\DdnsUserAdminBundle\Dto\DnsUserCriteriaDto;
use Atw\DdnsUserAdminBundle\Dto\DnsUserExportDto;
use Atw\DdnsUserAdminBundle\Dto\DnsUserImportDto;
use Atw\DdnsUserAdminBundle\Dto\SortConditionDto;
use Atw\DdnsUserAdminBundle\Entity\DnsUser;
use Atw\DdnsUserAdminBundle\Exception\FormValidationException;
use Atw\DdnsUserAdminBundle\Exception\HtpasswdException;
use Atw\DdnsUserAdminBundle\Exception\SortConditionValidationException;
use Atw\DdnsUserAdminBundle\Form\DnsUserCriteriaType;
use Atw\DdnsUserAdminBundle\Form\DnsUserExportType;
use Atw\DdnsUserAdminBundle\Form\DnsUserImportType;
use Atw\DdnsUserAdminBundle\Form\DnsUserType;
use Atw\DdnsUserAdminBundle\Repository\DnsUserRepository;

/**
 * DnsUser コントローラー
 *
 * @Route("/dnsuser")
 */
class DnsUserController extends Controller
{
    use CreateFormHelperTrait;
    use FlashBagTrait;
    use SortConditionValidationTrait;

    /**
     * DDNSユーザ一覧
     *
     * @Route("/{page}", name="dnsuser", requirements={"page" = "^[1-9][0-9]*$"}, defaults={"page" = "1"})
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @param string  $page
     * @return array
     */
    public function indexAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $sortConditionDto = new SortConditionDto($request->query->get('sort', null), $request->query->get('direction', null));
        $dto = new DnsUserCriteriaDto();
        $criteriaForm = $this->createForm(new DnsUserCriteriaType(), $dto);
        $searchRequest = $request->query->get($criteriaForm->getName());
        $criteriaForm->submit($searchRequest);

        try {
            $this->tryFormValidate($criteriaForm);
            $this->trySortConditionValidate($sortConditionDto);

            $entities = $this->get('knp_paginator')->paginate(
                $em->getRepository(DnsUser::class)->findListByCriteria($searchRequest['criteria'], $sortConditionDto),
                $page,
                $searchRequest['displaycount'] ?: $this->getParameter('LIST_DISPLAY_LIMIT')
            );
            $entities->setPageRange($this->getParameter('LIST_DISPLAY_PAGE_RANGE'));
            $entities->setUsedRoute('dnsuser');
            $entities->setSortableTemplate('AtwDdnsUserAdminBundle:Pagination:sortable_link.html.twig');
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
            return $this->redirect($this->generateUrl('dnsuser'));
        } catch (SortConditionValidationException $e) {
            $this->flashError($e->getMessage());
            return $this->redirect($this->generateUrl('dnsuser'));
        }

        // 行ごとの削除フォーム
        $deleteForms = [];
        foreach ($entities as $entity) {
            $deleteForms[] = $this->createDeleteForm($entity->getId(), 'dnsuser_delete')->createView();
        }

        return [
            'criteriaForm' => $criteriaForm->createView(),
            'entities' => $entities,
            'deleteForms' => $deleteForms,
            'sortableTableAlias' => DnsUserRepository::ALIAS,
        ];
    }

    /**
     * DDNSユーザ追加画面
     *
     * @Route("/new", name="dnsuser_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new DnsUser();
        $form = $this->createCreateForm($entity, new DnsUserType(), 'dnsuser_new_confirm');
        $requestData = $request->query->get($form->getName());
        if (count($requestData)) {
            $form->submit($requestData);
        }

        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * DDNSユーザ追加入力確認画面
     *
     * @Route("/new", name="dnsuser_new_confirm")
     * @Method("POST")
     * @Template()
     */
    public function newConfirmAction(Request $request)
    {
        $entity = new DnsUser();
        $form = $this->createCreateForm($entity, new DnsUserType(), 'dnsuser_create');
        $form->handleRequest($request);

        try {
            $this->tryFormValidate($form);
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
            return $this->render(
                "AtwDdnsUserAdminBundle:DnsUser:new.html.twig",
                ['entity' => $entity, 'form' => $form->createView()]
            );
        }
        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * DDNSユーザ追加処理
     *
     * @Route("/", name="dnsuser_create")
     * @Method("POST")
     * @Template("AtwDdnsUserAdminBundle:DnsUser:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new DnsUser();
        $form = $this->createCreateForm($entity, new DnsUserType(), 'dnsuser_create');
        $form->handleRequest($request);

        try {
            $this->tryFormValidate($form);
            return $this->redirect($this->tryUpdateInsertAndGetUrl($entity));
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
        } catch (\Exception $e) {
            $this->flashError($e->getMessage());
        }
        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * DDNSユーザ編集
     *
     * @Route("/{id}/edit", name="dnsuser_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $entity = $em->getRepository(DnsUser::class)->tryGetEntityById($id);
        } catch (EntityNotFoundException $e) {
            $this->flashError($e->getMessage());
            return $this->redirect($this->generateUrl('dnsuser'));
        }
        $passwordBackup = $entity->getPassword();

        $editForm = $this->createEditForm($entity, new DnsUserType(), 'dnsuser_new_confirm');
        $requestData = $request->query->get($editForm->getName());
        if (count($requestData)) {
            // 確認画面から遷移した場合
            $editForm->submit($requestData);
            $entity->recoveryPasswordIfNull($passwordBackup);
        }

        return ['entity' => $entity, 'form' => $editForm->createView()];
    }

    /**
     * DDNSユーザ編集確認画面
     *
     * @Route("/{id}/edit", name="dnsuser_edit_confirm")
     * @Method("PUT")
     * @Template()
     */
    public function editConfirmAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $entity = $em->getRepository(DnsUser::class)->tryGetEntityById($id);
        } catch (EntityNotFoundException $e) {
            $this->flashError($e->getMessage());
            return $this->redirect($this->generateUrl('dnsuser'));
        }
        $passwordBackup = $entity->getPassword();

        $form = $this->createEditForm($entity, new DnsUserType(), 'dnsuser_update');
        $form->handleRequest($request);
        $entity->recoveryPasswordIfNull($passwordBackup);

        try {
            $this->tryFormValidate($form);
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
            return $this->render(
                "AtwDdnsUserAdminBundle:DnsUser:edit.html.twig",
                ['entity' => $entity, 'form' => $form->createView()]
            );
        }
        return ['entity' => $entity, 'form' => $form->createView()];
    }

    /**
     * DDNSユーザ編集更新処理
     *
     * @Route("/{id}", name="dnsuser_update")
     * @Method("PUT")
     * @Template("AtwDdnsUserAdminBundle:DnsUser:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $entity = $em->getRepository(DnsUser::class)->tryGetEntityById($id);
        } catch (EntityNotFoundException $e) {
            $this->flashError($e->getMessage());
            return $this->redirect($this->generateUrl('dnsuser'));
        }
        $passwordBackup = $entity->getPassword();

        $editForm = $this->createEditForm($entity, new DnsUserType(), 'dnsuser_update');
        $editForm->handleRequest($request);
        $entity->recoveryPasswordIfNull($passwordBackup);

        try {
            $this->tryFormValidate($editForm);
            return $this->redirect($this->tryUpdateInsertAndGetUrl($entity));
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
        } catch (HtpasswdException $e) {
            $this->flashError($e->getMessage());
        } catch (\Exception $e) {
            $this->flashError($e->getMessage());
        }
        return ['entity' => $entity, 'form' => $editForm->createView()];
    }

    /**
     * DDNSユーザ削除処理
     *
     * @Route("/{id}", name="dnsuser_delete")
     * @Method("DELETE")
     * @Template("AtwDdnsUserAdminBundle:DnsUser:edit.html.twig")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $entity = $em->getRepository(DnsUser::class)->tryGetEntityById($id);
        } catch (EntityNotFoundException $e) {
            $this->flashError($e->getMessage());
            return $this->redirect($this->generateUrl('dnsuser'));
        }

        $form = $this->createDeleteForm($id, 'dnsuser_delete');
        $form->handleRequest($request);

        try {
            $manager = $this->get('dnsuser_manager_interface');
            $manager->tryDelete($entity);

            $htpasswdManager = $this->get('htpasswd_manager_interface');
            $htpasswdManager->tryGenerateHtpasswd();

            $this->flashNotice("データを削除しました。");
        } catch (HtpasswdException $e) {
            $this->flashError($e->getMessage());
        } catch (\Exception $e) {
            $this->flashError($e->getMessage());
        }
        return $this->redirect($this->generateUrl('dnsuser'));
    }

    /**
     * エクスポート画面表示
     *
     * @Route("/export", name="dnsuser_export")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function exportAction(Request $request)
    {
        $dto = new DnsUserExportDto();
        $form = $this->createExportForm($dto, new DnsUserExportType(), 'dnsuser_export');
        $requestData = $request->query->get($form->getName());
        if (count($requestData)) {
            $form->submit($requestData);
        }

        return ['form' => $form->createView()];
    }

    /**
     * エクスポートデータダウンロード
     *
     * @Route("/download", name="dnsuser_download")
     * @Method("GET")
     * @param Request $request
     * @todo 今後エクスポート種別が増えた場合、処理を分割化すること
     */
    public function downloadAction(Request $request)
    {
        $dto = new DnsUserExportDto();
        $form = $this->createExportForm($dto, new DnsUserExportType(), 'dnsuser_export');
        $form->handleRequest($request);

        try {
            $this->tryFormValidate($form);

            $now = (new \DateTime())->format('YmdHis');
            $exportType = $dto->getExportType();
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository(DnsUser::class)->findAll();

            $response = $this->render('AtwDdnsUserAdminBundle:DnsUser:export.csv.twig', ['entities' => $entities]);
            $contents = mb_convert_encoding($response->getContent(), 'SJIS', mb_internal_encoding());
            $response->setContent($contents);
            $response->headers->set('Content-Disposition', "attachment; filename=htpasswd_{$now}.csv");
            $response->headers->set('Content-type', 'application/octet-stream');

            return $response;
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
        } catch (\Exception $e) {
            $this->flashError($e->getMessage());
        }
        return $this->render(
            "AtwDdnsUserAdminBundle:DnsUser:export.html.twig",
            ['form' => $form->createView()]
        );
    }

    /**
     * インポート画面表示
     *
     * @Route("/import", name="dnsuser_import")
     * @Method("GET")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function importAction(Request $request)
    {
        $dto = new DnsUserImportDto();
        $form = $this->createImportForm($dto, new DnsUserImportType(), 'dnsuser_import');
        $requestData = $request->query->get($form->getName());
        if (count($requestData)) {
            $form->submit($requestData);
        }

        return ['form' => $form->createView()];
    }

    /**
     * インポートデータアップロード
     *
     * @Route("/upload", name="dnsuser_upload")
     * @Method("POST")
     * @param Request $request
     */
    public function uploadAction(Request $request)
    {
        $dto = new DnsUserImportDto();
        $form = $this->createImportForm($dto, new DnsUserImportType(), 'dnsuser_import');
        $form->handleRequest($request);

        try {
            $this->tryFormValidate($form);

            $manager = $this->get('dnsuser_manager_interface');
            $resultCount = $manager->tryImportAndGetResultCount($dto);

            $htpasswdManager = $this->get('htpasswd_manager_interface');
            $htpasswdManager->tryGenerateHtpasswd();

            $this->flashNotice("{$resultCount}件データをインポートしました。");
            return $this->redirect($this->generateUrl('dnsuser'));
        } catch (HtpasswdException $e) {
            $this->flashError($e->getMessage());
        } catch (FormValidationException $e) {
            $this->flashError($e->getErrorMessages());
        } catch (\Exception $e) {
            $this->flashError($e->getMessage());
        }
        return $this->render("AtwDdnsUserAdminBundle:DnsUser:import.html.twig", ['form' => $form->createView()]);
    }

    /**
     * DDNSユーザの更新なければ追加を試み、成功したらURLを返却
     * @param DnsUser $entity
     * @return string
     * @throws \Exception
     */
    private function tryUpdateInsertAndGetUrl(DnsUser $entity)
    {
        try {
            $manager = $this->get('dnsuser_manager_interface');
            $manager->tryUpdateInsert($entity);

            $htpasswdManager = $this->get('htpasswd_manager_interface');
            $htpasswdManager->tryGenerateHtpasswd();

            $this->flashNotice("データを更新しました。");
            return $this->generateUrl('dnsuser');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
