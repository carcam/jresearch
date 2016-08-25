<?php 

use Joomla\String\StringHelper;

/**
* @package		JResearch
* @subpackage 	Modules
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');


$path_relative = StringHelper::str_ireplace(JPATH_BASE, '', $dirname );
$path_relative = JPath::clean( $path_relative, $DS);
$modpath = JURI::root(true) . $path_relative . $DS;
$document = JFactory::getDocument();
$url = JURI::root(true).'index.php?option=com_jresearch&task=retrieveKeywordsAndFrequency&format=json&controller=publications';

$img_width = 1500;
$img_height = 3000;

$palette = SixtyNine\WordCloud\Helper\Palette::getNamedPalette($params->get('palette', 'grey'));
$font = 'Arial.ttf';
$font = __DIR__ . $DS.$font;

$ft = SixtyNine\WordCloud\FrequencyTable\FrequencyTableFactory::getDefaultFrequencyTable($corpus);

$builder = new SixtyNine\WordCloud\Builder\WordCloudBuilder(
    $ft,
    SixtyNine\WordCloud\Builder\Context\BuilderContextFactory::getDefaultBuilderContext($ft, $palette, $font, $img_width, $img_height),
    array(
        'font' => $font,
        'size' => array($img_width, $img_height)
    )
);

$imgRenderer = new SixtyNine\WordCloud\ImageBuilder\RawImageRenderer(
    $builder->build($maxWords, 1),
    new SixtyNine\WordCloud\Renderer\WordCloudRenderer()
);

?>
<div class="word-cloud">
    <img id="<?php echo $params->get('divid', 'word-cloud'); ?>" src="data:image/png;base64,<?php echo base64_encode($imgRenderer->getImage()); ?>"/>
</div>
</body>