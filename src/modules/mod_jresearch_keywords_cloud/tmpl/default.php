<?php // no direct access
/**
* @package		JResearch
* @subpackage 	Modules
* @license		GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

$path_relative = JString::str_ireplace(JPATH_BASE, '', $dirname );
$path_relative = JPath::clean( $path_relative, '/');
$modpath = JURI::root(true) . $path_relative . '/';
$document = JFactory::getDocument();

$document->addScript('http://d3js.org/d3.v3.min.js');
$document->addScript($modpath.'d3.layout.cloud.js');

if ($params->get('cssfile') != '')
{
	$cssfile = $params->get('cssfile');
	$cssfile = JURI::root(true).'/'.$cssfile;
	$document->addStyleSheet( $cssfile );
}

?>
<script type="text/javascript">
    var fill = d3.scale.category20();

    d3.json("index.php?option=com_jresearch&task=retrieveKeywordsAndFrequency&format=json&controller=publications", function(data) {	
  
    d3.layout.cloud().size([<?php echo $params->get('width', 100); ?>, <?php echo $params->get('height', 100); ?>])
      .words(data)
      .padding(5)
      .rotate(function() { return ~~(Math.random() * 2) * 90; })
      .font("Impact")
      .fontSize(function(d) { return Math.max(6, Math.min(d.size, 11)); })
      .on("end", draw)
      .start();

    function draw(words) {
        d3.select("#<?php echo $params->get('divid', 'cloud') ?>").append("svg")
            .attr("width", <?php echo $params->get('width', 100); ?>)
            .attr("height", <?php echo $params->get('height', 100); ?>)
        .append("g")
        .attr("transform", "translate(50,11)")
        .selectAll("text")
        .data(data)
        .enter().append("text")
        .style("font-size", function(d) { return d.relevance + "px"; })
        .style("font-family", "Impact")
        .style("fill", function(d, i) { return fill(i); })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.label; });
    }
});

</script>
<div id="<?php echo $params->get('divid', 'cloud'); ?>"></div>
