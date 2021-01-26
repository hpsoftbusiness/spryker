<?php

namespace Pyz\Zed\ProductDataImport\Communication\Form;

use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\ProductDataImportTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Spryker\Zed\FileManagerGui\Communication\Form\Validator\Constraints\File;

/**
 * @method \Pyz\Zed\ProductDataImport\ProductDataImportConfig getConfig()
 * @method \Pyz\Zed\ProductDataImport\Communication\ProductDataImportCommunicationFactory getFactory()
 */
class ProductDataImportForm extends AbstractType
{
    public const FILED_FILE_UPLOAD = 'fileUpload';
    public const OPTION_ALLOWED_MIME_TYPES = ['text/plain'];
    protected const ERROR_MIME_TYPE_MESSAGE = 'File type is not allowed for uploading, it can be only csv file';

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
    protected function addUploadedFileField(FormBuilderInterface $builder): ProductDataImportForm
    {
        $builder->add(
            static::FILED_FILE_UPLOAD,
            FileType::class,
            [
                'constraints' => [
                    new File(
                        [
                            'maxSize' => $this->getConfig()->getDefaultFileMaxSize(),
                            'mimeTypes' => static::OPTION_ALLOWED_MIME_TYPES,
                            'mimeTypesMessage' => static::ERROR_MIME_TYPE_MESSAGE,
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
