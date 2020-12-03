<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\CategoryDataImport\Communication\Console;

use Generated\Shared\Transfer\OrderInvoiceSendRequestTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CategoryDataFormatterConsole extends Console
{
    protected const COMMAND_NAME = 'category:format';
    protected const COMMAND_DESCRIPTION = 'Format';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = APPLICATION_ROOT_DIR . '/data/import/category/';

        if (file_exists($path)) {
            $data = file_get_contents($path . 'category.csv');

            $outputData = [];
            $outputData[] = explode(',' , "category_key,parent_category_key,name.de_DE,name.en_US,meta_title.de_DE,meta_title.en_US,meta_description.de_DE,meta_description.en_US,meta_keywords.de_DE,meta_keywords.en_US,is_active,is_in_menu,is_clickable,is_searchable,is_root,is_main,node_order,template_name,category_image_name.en_US,category_image_name.de_DE");
            $outputData[] = explode(',' ,"demoshop,,Demoshop,Demoshop,Demoshop,Demoshop,Deutsche Version des Demoshop,English version of Demoshop,Deutsche Version des Demoshop,English version of Demoshop,1,1,1,0,1,1,,Catalog (default),,");

//            $categoriesHierarchy = [];
            foreach (explode("\n", $data) as $key => $line) {
                if ($key === 0) {
                    continue;
                }

                //category_key,parent_category_key,name.de_DE,name.en_US,meta_title.de_DE,meta_title.en_US,meta_description.de_DE,meta_description.en_US,meta_keywords.de_DE,meta_keywords.en_US,is_active,is_in_menu,is_clickable,is_searchable,is_root,is_main,node_order,template_name,category_image_name.en_US,category_image_name.de_DE
                //clothing_and_accessories,demoshop,Kleidung,Clothing & Accessories,Kleidung,Clothing & Accessories,Kleidung,Clothing & Accessories,Kleidung,Clothing & Accessories,1,1,1,1,0,1,90,Catalog (default),,
                $categoriesSubs = str_getcsv(str_replace('"', '\'', $line), ",", "'");

                if (count($categoriesSubs) !== 2) {
                    continue;
                }

                // Beauty & Healthcare
                // Beauty
                // Body Care
                // Bathing Products
//                foreach ($categoriesSubs as $categoriesSub) {


//                    if ($categoryName === "") {
//                        continue;
//                    }
//
                    $categoryKey = $this->getCategoryKey($categoriesSubs[0]);

//                    if (isset($categoriesHierarchy[$categoryKey])) {
//                        continue;
//                    }
//
//                    $categoriesHierarchy[$categoryKey] = true;
//
                    $outputData[] = [
                        $categoryKey,
                       'demoshop',
                        $categoriesSubs[0],
                        $categoriesSubs[1],
                        $categoriesSubs[0],
                        $categoriesSubs[1],
                        $categoriesSubs[0],
                        $categoriesSubs[1],
                        $categoriesSubs[0],
                        $categoriesSubs[1],
                        1,
                        1,
                        1,
                        1,
                        0,
                        1,
                        90,
                        'Catalog (default)',
                        '',
                        '',
                    ];
                }

            $fp = fopen($path . 'category_updated.csv', 'w');

            foreach ($outputData as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            return 1;
        }
    }

    /**
     * @param string $categoryName
     *
     * @return string
     */
    protected function getCategoryKey(string $categoryName): string
    {
        $categoryKey = str_replace('"', '', strtolower($categoryName));
        $categoryKey = str_replace(' ', '_', $categoryKey);
        $categoryKey = str_replace(',', '', $categoryKey);
        $categoryKey = str_replace('\'', '', $categoryKey);
        $categoryKey = str_replace('-', '_', $categoryKey);

        if (strpos($categoryKey, '&') !== false) {
            $categoryKey = str_replace('&', 'and', $categoryKey);
        }

        return $categoryKey;
    }
}
