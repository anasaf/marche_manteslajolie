<?php
declare(strict_types=1);
namespace App\Controller\Admin;

use App\Entity\Merchant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

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
            TextField::new('telephone'),
            EmailField::new('email'),
            AssociationField::new('address', 'Adresse')
                ->setRequired(true)
                ->setFormTypeOptions([
                    'choice_label' => 'address', // ou 'city', ou un getter comme 'getFullAddress'
                ])
                ->autocomplete(),
            CollectionField::new('products', 'Produits')
                ->useEntryCrudForm(ProductCrudController::class)
        ];
    }
}
