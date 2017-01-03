<?php

namespace App\Services;

use App\Transformers\Spreadsheet\SpreadsheetTransformer;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\WriterInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\File;

class Spreadsheet
{
    /**
     * The reader factory instance.
     *
     * @var \Box\Spout\Writer\WriterFactory
     */
    protected $writerFactory;

    /**
     * Create an XLSX (Excel) file.
     *
     * @param Collection             $collection
     * @param SpreadsheetTransformer $transformer
     * @param array                  $headerRows
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function xlsxFile($collection, $transformer, $headerRows = [])
    {
        $writer = $this->getWriterFactory()->create(Type::XLSX);

        return $this->file($collection, $transformer, $writer, $headerRows);
    }

    /**
     * Create an CSV file.
     *
     * @param Collection             $collection
     * @param SpreadsheetTransformer $transformer
     * @param array                  $headerRows
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function csvFile($collection, $transformer, $headerRows = [])
    {
        $writer = $this->getWriterFactory()->create(Type::CSV);

        return $this->file($collection, $transformer, $writer, $headerRows);
    }

    /**
     * Generate a spreadsheet.
     *
     * @param string                 $path
     * @param Collection             $collection
     * @param SpreadsheetTransformer $transformer
     * @param WriterInterface        $writer
     * @param array                  $headerRows
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function generate($path, $collection, $transformer, WriterInterface $writer, $headerRows)
    {
        $writer->openToFile($path);

        foreach ($headerRows as $row) {
            $writer->addRow($row);
        }

        //$writer->addRow($transformer->header());

        $collection->chunk(50)->each(function ($objects) use ($writer, $transformer) {
            foreach ($objects as $object) {
                $writer->addRow($transformer->transform($object));
            }
        });

        $writer->close();
    }

    /**
     * Generate a file.
     *
     * @param Collection             $collection
     * @param SpreadsheetTransformer $transformer
     * @param WriterInterface        $writer
     * @param array                  $extraHeaderRows
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function file($collection, $transformer, WriterInterface $writer, $extraHeaderRows)
    {
        $path = $this->tmpFilePath();

        $this->generate($path, $collection, $transformer, $writer, $extraHeaderRows);

        return new File($path);
    }

    /**
     * Generate a temporary file path.
     *
     * @return string
     */
    protected function tmpFilePath()
    {
        return storage_path('app/tmp/'.md5(uniqid()));
    }

    /**
     * Get the reader factory instance.
     *
     * @return \Box\Spout\Writer\WriterFactory
     */
    public function getWriterFactory()
    {
        return $this->writerFactory ?: app(WriterFactory::class);
    }

    /**
     * Set the reader factory instance.
     *
     * @param \Box\Spout\Writer\WriterFactory $writerFactory
     *
     * @return $this
     */
    public function setWriterFactory(WriterFactory $writerFactory)
    {
        $this->writerFactory = $writerFactory;

        return $this;
    }
}
