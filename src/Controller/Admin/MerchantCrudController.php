<?php
namespace App\Controller\Admin;

use App\Entity\Merchant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
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
            TextEditorField::new('address'),
            TextField::new('city'),
            TextField::new('telephone'),
            TextField::new('postalecode'),
            TextField::new('country'),
            BooleanField::new('isactive'),
        ];
    }
}
