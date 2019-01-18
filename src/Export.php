<?php
/**
* Export
*
* Export files using customisation of PHPOffice/PhpSpreadsheet
* Location: https://github.com/PHPOffice/PhpSpreadsheet
*
* @package      PHP Library
* @subpackage   phplibrary
* @category     Files
* @author       Zlatan Stajić <contact@zlatanstajic.com>
*/
namespace phplibrary;

use PhpOffice\PhpSpreadsheet\Helper\Sample as Sample;
use PhpOffice\PhpSpreadsheet\IOFactory as IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType as DataType;

/**
* Export files using customisation of PHPOffice/PhpSpreadsheet
*/
class Export {

    // -------------------------------------------------------------------------

    /**
    * Instance of Spreadsheet object
    *
    * @var Spreadsheet
    */
    private static $spreadsheet;

    // -------------------------------------------------------------------------

    /**
    * Properties for file export
    *
    * @var array
    */
    private static $properties = array(
        'file_name'           => 'file_export',
        'type'                => 'xlsx',
        'head'                => array(),
        'data'                => array(),
        'data_types'          => array(),
        'document_properties' => array(
            'creator'     => 'Maarten Balliauw',
            'title'       => 'Office 2007 XLSX Test Document',
            'description' => 'Test document for Office 2007 XLSX, generated using PHP classes.',
            'keywords'    => 'office 2007 openxml php',
            'category'    => 'Test result file',
        ),
    );

    // -------------------------------------------------------------------------

    /**
    * Available cells
    *
    * @var array
    */
    private static $cells = array(
        '',
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'J',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
        'Z',
    );

    // -------------------------------------------------------------------------

    /**
    * Available types
    *
    * @var array
    */
    private static $allowed_types = array(
        'xlsx',
        'xls',
        'csv',
        'osp',
    );

    // -------------------------------------------------------------------------

    /**
    * Export file
    *
    * @param array $params
    *
    * @return void
    */
    public static function export_file($params)
    {
        self::set_properties($params);
        self::create_spreadsheet_object();
        self::deploy();
    }

    // -------------------------------------------------------------------------

    /**
    * Setting properties for file export
    *
    * @param array $params
    *
    * @var void
    */
    private static function set_properties($params)
    {
        isset($params['head'])
            ? self::$properties['head'] = $params['head']
            : NULL;

        isset($params['data'])
            ? self::$properties['data'] = $params['data']
            : NULL;

        isset($params['type'])
            ? self::$properties['type'] = $params['type']
            : NULL;

        isset($params['data_types'])
            ? self::$properties['data_types'] = $params['data_types']
            : NULL;

        isset($params['file_name'])
            ? self::$properties['file_name'] = $params['file_name']
            : NULL;

        isset($params['document_properties'])
            ? self::set_document_properties($params['document_properties'])
            : NULL;
    }

    // -------------------------------------------------------------------------

    /**
    * Create Spreadsheet object
    *
    * @return void
    */
    private static function create_spreadsheet_object()
    {
        // Create new Spreadsheet object
        self::$spreadsheet = new Spreadsheet();

        // Set document properties
        self::$spreadsheet->getProperties()->setCreator(self::$properties['document_properties']['creator']);
        self::$spreadsheet->getProperties()->setLastModifiedBy(self::$properties['document_properties']['creator']);
        self::$spreadsheet->getProperties()->setTitle(self::$properties['document_properties']['title']);
        self::$spreadsheet->getProperties()->setSubject(self::$properties['document_properties']['title']);
        self::$spreadsheet->getProperties()->setDescription(self::$properties['document_properties']['description']);
        self::$spreadsheet->getProperties()->setKeywords(self::$properties['document_properties']['keywords']);
        self::$spreadsheet->getProperties()->setCategory(self::$properties['document_properties']['category']);
    }

    // -------------------------------------------------------------------------

    /**
    * Setting export document_properties
    *
    * @param array $params
    *
    * @return void
    */
    private static function set_document_properties($params)
    {
        $keys = array_keys(self::$properties['document_properties']);

        foreach ($keys as $key)
        {
            if (isset($params[$key]))
            {
                self::$properties['document_properties'][$key] = $params[$key];
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
    * Deploy export after switching type
    *
    * @return void
    */
    private static function deploy()
    {
        switch (self::$properties['type'])
        {
            case 'osp':
            {
                self::line_arrangement(self::$properties['data']);
                self::to_osp(self::$spreadsheet, self::$properties['file_name']);

                break;
            }
            case 'csv':
            {
                $csv = self::line_arrangement(self::$properties['data']);
                self::to_csv($csv, self::$properties['file_name']);

                break;
            }
            case 'xls':
            {
                self::cell_arrangement(
                    self::$spreadsheet,
                    self::$properties['head'],
                    self::$properties['data'],
                    self::$properties['data_types']
                );

                self::to_xls(
                    self::$spreadsheet,
                    self::$properties['file_name']
                );

                break;
            }
            case 'xlsx':
            {
                self::cell_arrangement(
                    self::$spreadsheet,
                    self::$properties['head'],
                    self::$properties['data'],
                    self::$properties['data_types']
                );

                self::to_xlsx(
                    self::$spreadsheet,
                    self::$properties['file_name']
                );

                break;
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
    * Export files to Text (osp)
    *
    * @param Spreadsheet $spreadsheet
    * @param string $file_name
    *
    * @return void
    */
    private static function to_osp($spreadsheet, $file_name)
    {
        if ( ! headers_sent())
        {
            // Redirect output to a client’s web browser (CSV)
            header('Content-Type: text/txt');
            header('Content-Disposition: attachment;filename="' . $file_name . '.osp"');
            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            ob_end_flush();

            $writer = IOFactory::createWriter($spreadsheet, 'Csv');
            $writer->save('php://output');

            exit;
        }
    }

    // -------------------------------------------------------------------------

    /**
    * Export files to CSV (csv)
    *
    * @param string $csv
    * @param string $file_name
    *
    * @return void
    */
    private static function to_csv($csv, $file_name)
    {
        if ( ! headers_sent())
        {
            // Redirect output to a client’s web browser (CSV)
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $file_name . '.csv"');
            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            ob_end_flush();

            print $csv;

            exit;
        }
    }

    // -------------------------------------------------------------------------

    /**
    * Export files to Excel 5 (xls)
    *
    * @param Spreadsheet $spreadsheet
    * @param string $file_name
    *
    * @return void
    */
    private static function to_xls($spreadsheet, $file_name)
    {
        if ( ! headers_sent())
        {
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');
            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');

            exit;
        }
    }

    // -------------------------------------------------------------------------

    /**
    * Export files to Excel 2007 (xlsx)
    *
    * @param Spreadsheet $spreadsheet
    * @param string $file_name
    *
    * @return void
    */
    private static function to_xlsx($spreadsheet, $file_name)
    {
        if ( ! headers_sent())
        {
            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
            header('Cache-Control: max-age=0');

            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');

            exit;
        }
    }

    // -------------------------------------------------------------------------

    /**
    * Arrange data in line for export
    *
    * @param array $data
    *
    * @return mixed
    */
    private static function line_arrangement($data)
    {
        if ( ! empty($data))
        {
            ob_start();

            foreach ($data as $item)
            {
                $item_indexed      = array_values($item);
                $item_indexed_size = count($item_indexed);
                $value             = '';

                for ($i=0; $i<$item_indexed_size; $i++)
                {
                    $value .= $item_indexed[$i] . ';';
                }

                if ( ! headers_sent())
                {
                    echo $value . "\r\n";
                }
            }

            if (headers_sent())
            {
                ob_end_flush();
            }
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
    * Arrange data in cells for export
    *
    * @param Spreadsheet $spreadsheet
    * @param array $head
    * @param array $data
    * @param array $data_types
    *
    * @return mixed
    */
    private static function cell_arrangement($spreadsheet, $head, $data, $data_types=array())
    {
        if ( ! empty($data))
        {
            // Print head
            $iteration = 1;

            foreach ($head as $item)
            {
                $spreadsheet->getActiveSheet()->getColumnDimension(self::$cells[$iteration])->setAutoSize(TRUE);
                $spreadsheet->getActiveSheet()->setCellValueExplicit(
                    self::$cells[$iteration] . '1',
                    $item,
                    DataType::TYPE_STRING
                );

                $iteration++;
            }

            // Number of cells
            $number_of_cells = count($head);

            // Print data
            $iteration = 2;

            foreach ($data as $item)
            {
                $item_indexed = array_values($item);

                for ($i=1; $i<=$number_of_cells; $i++)
                {
                    $data_type = isset($data_types[$i-1]['index'])
                        ? $data_types[$i-1]['type']
                        : '';

                    switch ($data_type)
                    {
                        case 'TEXT':
                        {
                            $spreadsheet->getActiveSheet()->setCellValueExplicit(
                                self::$cells[$i] . $iteration,
                                $item_indexed[$i-1],
                                DataType::TYPE_STRING
                            );

                            break;
                        }
                        default:
                        {
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue(
                                self::$cells[$i] . $iteration,
                                $item_indexed[$i-1]
                            );
                        }
                    }

                }

                $iteration++;
            }
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
    * Allowed types for export
    *
    * @return array
    */
    public static function allowed_types()
    {
        return self::$allowed_types;
    }

    // -------------------------------------------------------------------------
}
