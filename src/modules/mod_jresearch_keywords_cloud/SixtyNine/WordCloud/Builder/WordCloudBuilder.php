<?php

namespace SixtyNine\WordCloud\Builder;

use SixtyNine\WordCloud\FrequencyTable\FrequencyTable,
    SixtyNine\WordCloud\WordCloud,
    SixtyNine\WordCloud\Builder\Context\BuilderContext;

class WordCloudBuilder
{
    const WORDS_HORIZONTAL = 0;
    const WORDS_MAINLY_HORIZONTAL = 1;
    const WORDS_MIXED = 6;
    const WORDS_MAINLY_VERTICAL = 9;
    const WORDS_VERTICAL = 10;

    protected $frequencyTable;

    protected $cloud;

    protected $context;

    protected $config;

    public function __construct(FrequencyTable $table, BuilderContext $context, $config)
    {
        $this->checkConfigParameters($config);
        $this->config = $config;
        $this->context = $context;
        $this->frequencyTable = $table;
        $this->cloud = new WordCloud($config['size'][0], $config['size'][1]);
        $this->cloud->setFont($config['font']);
    }

    protected function checkConfigParameters($config)
    {
        foreach (array('size', 'font') as $param) {
            if (!array_key_exists($param, $config)) {
                throw new \InvalidArgumentException("Missing config parameter '$param'");
            }
        }
        if (!is_array($config['size']) || !count($config['size']) == 2) {
            throw new \InvalidArgumentException("Invalid config parameter 'size'. It must be a 2 dimensional array of int.");
        }
    }

    /**
     * @param int $limit The maximal number of words to show
     * @param int $orientation The orientation (see self::WORDS_* constants)
     * @param float $paddingSize
     * @param int $paddingAngle
     * @return \SixtyNine\WordCloud\WordCloud
     */
    public function build(
        $limit = null,
        $orientation = self::WORDS_MIXED,
        $paddingSize = 1.05,
        $paddingAngle = 0
    )
    {
        $table = $this->frequencyTable->getTable($limit);

        $counter = 0;

        $this->cloud->resetMask();

        // Add the words in the cloud and compute the size and orientation
        foreach($table as $text => $item)
        {
            $word = $this->cloud->addWord($text, $item->title);

            // Calculate the font size
            $word->size = $this->context->getFontSizeCalculator()->calculateFontSize($text, $item->count);

            // Randomize the text orientation
            $word->angle = 0;
            if (rand(1, 10) <= $orientation) $word->angle = 90;

            // Calculate the bounding box of the text
            $word->textBox = imagettfbbox(
                $word->size * $paddingSize,
                $word->angle - $paddingAngle,
                $this->cloud->getFont(),
                $text
            );

            // Calculate the color
            $word->color = $this->context->getColorChooser()->getNextColor();

            // Search a place for the word
            $coord = $this->context->getWordUsher()->getPlace($text, $word->angle, $word->textBox);
            $word->x = $coord[0];
            $word->y = $coord[1];

            $counter++;
        }

        $this->cloud->setMask($this->context->getWordUsher()->getMask());

        return $this->cloud;
    }

}
