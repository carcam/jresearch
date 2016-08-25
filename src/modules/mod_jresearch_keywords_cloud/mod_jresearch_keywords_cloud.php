<?php
/**
* @version		$Id: mod_quickcoops.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(__DIR__ .'/SixtyNine/WordCloud/Box.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Word.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/WordCloud.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/WordCloudBuilder.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Renderer/WordCloudRenderer.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Helper/Palette.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/Filters/FrequencyTableFilterInterface.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/Filters/RemoveTrailingPunctuation.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/Filters/RemoveUnwantedCharacters.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/FrequencyTableWord.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/FrequencyTable.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/FrequencyTableFactory.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/BuilderContext.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Mask.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/WordUsher.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/DefaultWordUsher.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/ColorChooser.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/RotatorColorChooser.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/FontSizeCalculatorInterface.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/DefaultFontSizeCalculator.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/Builder/Context/BuilderContextFactory.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/ImageBuilder/AbstractImageRenderer.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/ImageBuilder/RawImageRenderer.php');
require_once(__DIR__ .'/SixtyNine/WordCloud/FrequencyTable/Filters/RemoveShortWords.php');

/**use SixtyNine\WordCloud\Builder\WordCloudBuilder;
use SixtyNine\WordCloud\Renderer\WordCloudRenderer;
use SixtyNine\WordCloud\Helper\Palette;
use SixtyNine\WordCloud\FrequencyTable\FrequencyTableFactory;
use SixtyNine\WordCloud\Builder\Context\BuilderContextFactory;
use SixtyNine\WordCloud\ImageBuilder\RawImageRenderer; **/

if(!JComponentHelper::isEnabled('com_jresearch', true))
{
	JFactory::getApplication()->enqueueMessage('J!Research is not enabled or installed', "error");
}
$DS = DIRECTORY_SEPARATOR;
require_once(JPATH_ADMINISTRATOR.$DS.'components'.$DS.'com_jresearch'.$DS.'helpers'.$DS.'keywords.php');

$dirname = dirname(__FILE__);

$types = array();
if ($params->get('include_publications') == '1')
    $types[] = 'publications';

if ($params->get('include_projects') == '1')
    $types[] = 'projects';

$keywords = JResearchKeywordsHelper::getKeywordsByRelevance($types);
// Now expand the keywords based on their frequency
$corpus = array();
$maxWords = count($keywords);
foreach($keywords as $wordEntry) {
	for ($i = 0; $i < intval($wordEntry['relevance']); ++$i)
		$corpus[] = $wordEntry['keyword'];
}

$layout = (string) $params->get('layout', 'default');

require(JModuleHelper::getLayoutPath('mod_jresearch_keywords_cloud', $layout));