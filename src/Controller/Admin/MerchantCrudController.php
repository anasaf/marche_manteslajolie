<?php
namespace App\Controller\Admin;

use App\Entity\merchant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class MerchantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Merchant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description'),
            TextField::new('adresse'),
            TextField::new('telephone'),
            TextField::new('email'),
            AssociationField::new('category'),

            // logo upload
         //   VichImageField::new('logoFile')->onlyOnForms(),
            ImageField::new('logoName')->setBasePath('/uploads/merchants')->onlyOnIndex(),

            ArrayField::new('positionPlan3D')->hideOnIndex(),
        ];
    }
}
