<?php

namespace App\Services;

use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Writer\WriterInterface;
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param object                                $transformer
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function xlsxFile($query, $transformer)
    {
        $writer = $this->getWriterFactory()->create(Type::XLSX);

        return $this->file($query, $transformer, $writer);
    }

    /**
     * Generate a spreadsheet.
     *
     * @param string                                $path
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param object                                $transformer
     * @param \Box\Spout\Writer\WriterInterface     $writer
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function generate($path, $query, $transformer, WriterInterface $writer)
    {
        $writer->openToFile($path);
        $writer->addRow($transformer->header());

        $query->chunk(200, function ($models) use ($writer, $transformer) {
            foreach ($models as $model) {
                $writer->addRow($transformer->transform($model));
            }
        });

        $writer->close();
    }

    /**
     * Generate a file.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param object                                $transformer
     * @param \Box\Spout\Writer\WriterInterface     $writer
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function file($query, $transformer, WriterInterface $writer)
    {
        $path = $this->tmpFilePath();

        $this->generate($path, $query, $transformer, $writer);

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
