<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductDataImport\Communication\Form;

use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Pyz\Zed\ProductDataImport\Communication\Form\Constrain\CsvFileConstrain;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints\File;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 * @method \Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\ProductDataImport\Business\ProductDataImportFacadeInterface getFacade()
 */
class ProductDataImportForm extends AbstractType
{
    public const FILED_FILE_UPLOAD = 'fileUpload';
    public const FILED_STORE = 'store';
    public const OPTION_ALLOWED_MIME_TYPES = ['text/plain', 'csv'];
    protected const CSV_FILE_DELIMITER = ',';
    protected const ERROR_MIME_TYPE_MESSAGE = 'File type is not allowed for uploading, it can be only csv file';
    protected const ERROR_CSV_DELIMITER_INCORRECT_MESSAGE = 'Csv file has incorrect delimiter. It should be " {{delimiter}} "';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addUploadedFileField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUploadedFileField(FormBuilderInterface $builder)
    {
        $stores = Store::getInstance()->getAllowedStores();

        $builder
            ->add(
                static::FILED_STORE,
                ChoiceType::class,
                [
                    'choices' => array_combine($stores, $stores),
                ]
            )
            ->add(
                static::FILED_FILE_UPLOAD,
                FileType::class,
                [
                    'attr' => [
                        'accept' => '.csv',
                    ],
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => $this->getConfig()->getDefaultFileMaxSize(),
                                'mimeTypes' => static::OPTION_ALLOWED_MIME_TYPES,
                                'mimeTypesMessage' => static::ERROR_MIME_TYPE_MESSAGE,
                            ]
                        ),
                        new CsvFileConstrain(
                            [
                                'delimiter' => self::CSV_FILE_DELIMITER,
                                'incorrectSeparatorMessage' => self::ERROR_CSV_DELIMITER_INCORRECT_MESSAGE,
                            ]
                        ),
                    ],
                ]
            );

        $builder->get(static::FILED_FILE_UPLOAD)
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($data) {
                        return $data;
                    },
                    function (?UploadedFile $uploadedFile = null) {
                        return $this->mapUploadedFileToTransfer($uploadedFile);
                    }
                )
            );

        return $this;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile|null $uploadedFile
     *
     * @return \Generated\Shared\Transfer\FileUploadTransfer|null
     */
    protected function mapUploadedFileToTransfer(?UploadedFile $uploadedFile = null): ?FileUploadTransfer
    {
        if ($uploadedFile === null) {
            return $uploadedFile;
        }

        $fileUploadTransfer = new FileUploadTransfer();
        $fileUploadTransfer->setClientOriginalName($uploadedFile->getClientOriginalName());
        $fileUploadTransfer->setRealPath($uploadedFile->getRealPath());
        $fileUploadTransfer->setMimeTypeName($uploadedFile->getMimeType());
        $fileUploadTransfer->setClientOriginalExtension($uploadedFile->getClientOriginalExtension());
        $fileUploadTransfer->setSize($uploadedFile->getSize());

        return $fileUploadTransfer;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProductDataImportTransfer::class,
                'allow_extra_fields' => true,
            ]
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'product_data_import_file_upload';
    }
}
