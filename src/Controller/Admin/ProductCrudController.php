<?php
declare(strict_types=1);
namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),

            // Champ image VichUploader
            FormField::addPanel('Image du produit'),
            TextField::new('imageFile', 'Image du produit')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(), // seulement sur création / édition

            IntegerField::new('stock'),
         //   AssociationField::new('Merchant'),
         //   AssociationField::new('categorie'),
                AssociationField::new('merchant', 'Marchand')
                    ->setRequired(true)
                    ->autocomplete(),
            AssociationField::new('category', 'Category')
                    ->setRequired(true)
                    ->autocomplete(),
        ];
    }
}
