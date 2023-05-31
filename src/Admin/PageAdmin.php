<?php

namespace App\Admin;

use App\Repository\PageRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\String\Slugger\SluggerInterface;

final class PageAdmin extends AbstractAdmin
{
    private $slugger;
    private $pageRepo;

    public function __construct(SluggerInterface $slugger, PageRepository $pageRepo)
    {
        $this->slugger = $slugger;
        $this->pageRepo = $pageRepo;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues['_sort_order'] = 'DESC';
        $sortValues['_sort_by'] = 'id';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title')
            ->add('description', CKEditorType::class);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->add('title');
        $list->add('slug');
        $list->add(ListMapper::NAME_ACTIONS, null, [
            'actions' => [
                'edit' => [],
                'delete' => [],
            ]
        ]);
    }

    protected function prePersist(object $page): void
    {
        $slug = $this->slugger->slug($page->getTitle());
        $result = $this->pageRepo->createQueryBuilder('c')
            ->where('c.slug LIKE :querySlug')
            ->setParameter('querySlug', $slug . '%')
            ->getQuery()
            ->getResult();

        if ($result) {
            $slug = $slug . (int) substr(end($result)->getSlug(), -1) + 1;
        }
        $page->setSlug($slug);
    }
}
