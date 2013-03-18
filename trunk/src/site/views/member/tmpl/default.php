<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for showing a single member
 */


// no direct access
defined('_JEXEC') or die('Restricted access'); 
JHTML::_('behavior.modal');
?>
<h2 class="componentheading"><?php echo JResearchPublicationsHelper::formatAuthor($this->member->__toString(), $this->params->get('staff_format', 'last_first')); ?></h2>
<table class="frontendsingleitem">
<tbody>
  <tr><th style="width:100%;" colspan="4"><h3 class="contentheading"><?php echo JText::_('JRESEARCH_PERSONAL_INFORMATION'); ?></h3></th></tr>	
  <tr>  
    <th scope="row"><?php echo JText::_('JRESEARCH_POSITION').': ' ?></th>
    <td><?php echo empty($this->member->position)?JText::_('JRESEARCH_NOT_SPECIFIED'):$this->member->getPositionObj(); ?></td>
    <?php if(empty($this->member->url_photo)): ?>
    <td style="width:50%;" colspan="2" rowspan="3"></td>
    <?php else: 
    	$url = JResearchUtilities::getUrlByRelative($this->member->url_photo);
    	$thumb = ($this->params->get('thumbnail_enable', 1) == 1)? JResearchUtilities::getThumbUrlByRelative($this->member->url_photo) : $url;
    ?>		
    <td style="width:50%;" colspan="2" rowspan="3">
    	<a href="<?php echo $url?>" class="modal" rel="{handler: 'image'}">
    		<img src="<?php echo $thumb; ?>" border="0" alt="<?php echo $this->member; ?>" />
    	</a>
    </td>
    <?php endif; ?>		
  </tr>
  <?php
  if(!$this->member->former_member && $this->params->get('member_show_email', 'no') == 'yes'):
  ?>
	  <tr>
	  	<th scope="row"><?php echo JText::_('JRESEARCH_EMAIL').': ' ?></th>
	  	<td><?php echo JHTML::_('email.cloak',$this->member->email);?></td>
	  	<td style="width:50%;" colspan="2"></td>
	  </tr>
  <?php 
  endif;
  ?>
  <tr>
  	<th scope="row"><?php echo JText::_('JRESEARCH_RESEARCH_AREAS').': ' ?></th>
  	<td><?php echo JHTML::_('jresearchfrontend.researchareaslinks', $this->member->getResearchAreas('names')); ?></td>
	<td style="width:50%;" colspan="2"></td>
  </tr>
  <tr>
  	<?php if(empty($this->member->phone_or_fax)): ?>
  	<td style="width:50%;" colspan="2"></td>
  	<?php else: ?>	
  	<th scope="row"><?php echo JText::_('JRESEARCH_PHONE_OR_FAX').': ';  ?></th>
  	<td><?php echo $this->member->phone_or_fax; ?></td>
	<?php endif; ?>  		
  	<td colspan="2"></td>
  </tr>
  <tr>
  	<?php if(empty($this->member->location)): ?>
	<td style="width:50%;" colspan="2"></td>
	<?php else: ?>	
	<th scope="row"><?php echo JText::_('JRESEARCH_LOCATION').': ';  ?></th>
	<td><?php echo $this->member->location; ?></td>
	<?php endif; ?>  		
	<td colspan="2"></td>
  </tr>
  <?php if(!empty($this->member->description)): ?>
  <tr><th scope="col" colspan="4"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></th></tr>
  <tr><td colspan="4"><?php echo $this->description; ?></td></tr>			  
  <?php endif; ?>
  <?php if(!empty($this->teams)):?>
  <tr>
  	<th scope="col" colspan="4"><h3 class="contentheading"><?php echo JText::_('JRESEARCH_TEAMS'); ?></h3></th>
  </tr>
  <tr>
  	<td colspan="4">
  		<ul>
  			<?php foreach($this->teams as $team):?>
  				<li><?php echo JHTML::_('jresearch.link', $team, 'teams', 'show', $team->id); ?></li>
  			<?php endforeach; ?>
  		</ul>
  	</td>
  </tr>
  <?php endif; ?>
  <?php $itemId = JRequest::getVar('Itemid', null); ?>
  <?php if(!empty($this->publications)){ ?>
  	<tr><th style="width:100%;" scope="col" colspan="4" ><h3 class="contentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS'); ?></h3></th></tr>
  	<tr><td style="width:100%;" colspan="4">
	  	<ul>
		  	<?php foreach($this->publications as $pub): ?>
		  		<?php if(!$this->applyStyle): ?>
	  				<li><a href="index.php?option=com_jresearch&amp;view=publication&amp;task=show&amp;id=<?php echo $pub->id ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo $pub->title; ?></a></li>
	  			<?php else: ?>
	  				<li>
	  					<?php  
	  						$styleObj =& JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
	  						echo $styleObj->getReferenceHTMLText($pub, true); 
	  					?>
	  					<a href="index.php?option=com_jresearch&amp;view=publication&amp;task=show&amp;id=<?php echo $pub->id; ?><?php $Itemid = JRequest::getVar('Itemid'); echo !empty($Itemid)?'&amp;Itemid='.$Itemid:''; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	  				</li>
	  			<?php endif; ?>	
	  		<?php endforeach; ?>
	  	</ul>
	  	<div>
	  		<?php if($this->npublications > count($this->publications)){ ?>
	  				<a href="index.php?option=com_jresearch&amp;publications_view_all=1&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;task=show&amp;view=member&amp;id=<?php echo $this->member->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_ALL'); ?></a>
			<?php } elseif($this->publications_view_all){ ?>		
  					<a href="index.php?option=com_jresearch&amp;publications_view_all=0&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;task=show&amp;view=member&amp;id=<?php echo $this->member->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_LESS'); ?></a>
	  		<?php } ?>
	  	</div>
	  	</td>
  	</tr>
  <?php } ?>
  
  <?php if(!empty($this->projects)){ ?>
  	<tr><th style="width:100%;" scope="row" colspan="4"><h3 class="contentheading"><?php echo JText::_('JRESEARCH_PROJECTS'); ?></h3></th></tr>
  	<tr><td style="width:100%;" colspan="4">
	  	<ul>
		  	<?php foreach($this->projects as $proj): ?>
	  			<li><a href="index.php?option=com_jresearch&amp;view=project&amp;task=show&amp;id=<?php echo $proj->id ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo $proj->title; ?></a></li>
	  		<?php endforeach; ?>
	  	</ul>
	  	<div>
	  		<?php if($this->nprojects > count($this->projects)){ ?>
					<a href="index.php?option=com_jresearch&amp;projects_view_all=1&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;task=show&amp;view=member&amp;id=<?php echo $this->member->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_ALL'); ?></a>
			<?php } elseif($this->projects_view_all){ ?>		
					<a href="index.php?option=com_jresearch&amp;projects_view_all=0&amp;theses_view_all=<?php echo $this->theses_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;task=show&amp;view=member&amp;id=<?php echo $this->member->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_LESS'); ?></a>
	  		<?php } ?>
	  	</div>
  	</td></tr>
  <?php } ?>

  <?php if(!empty($this->theses)){ ?>
  	<tr><th style="width:100%;" scope="col" colspan="4"><h3 class="contentheading"><?php echo JText::_('JRESEARCH_THESES'); ?></h3></th></tr>
  	<tr><td style="width:100%;" colspan="4">
	  	<ul>
		  	<?php foreach($this->theses as $thesis): ?>
	  			<li><a href="index.php?option=com_jresearch&amp;view=thesis&amp;task=show&amp;id=<?php echo $thesis->id ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo $thesis->title; ?></a></li>
	  		<?php endforeach; ?>
	  	</ul>
	  	<div>

			<?php if($this->ntheses > count($this->theses)){ ?>
					<a href="index.php?option=com_jresearch&amp;theses_view_all=1&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;task=show&amp;view=member&amp;id=<?php echo $this->member->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_ALL'); ?></a>
			<?php } elseif($this->theses_view_all){ ?>		
					<a href="index.php?option=com_jresearch&amp;projects_view_all=<?php echo $this->projects_view_all; ?>&amp;publications_view_all=<?php echo $this->publications_view_all; ?>&amp;theses_view_all=0&amp;task=show&amp;view=member&amp;id=<?php echo $this->member->id; ?><?php echo $itemId?"&amp;Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_LESS'); ?></a>
			<?php } ?>
	  	</div>
  	</td></tr>
  <?php } ?>  
</tbody>
</table>
<?php if(!empty($this->member->url_personal_page)):  ?>  	
	<div><h3><?php echo JText::_('JRESEARCH_PERSONAL_PAGE');  ?></h3><?php echo JHTML::_('link', str_replace('&', '&amp;', $this->member->url_personal_page), $this->member->url_personal_page); ?></div>
<?php endif; ?>  
<?php 
$cv = $this->member->getCV();
if($cv !== false):
	  echo '<span><strong>'.JText::_('JRESEARCH_MEMBER_CV').':</strong> '.JHTML::_('jresearchhtml.attachment', $cv).'</span>';
endif; ?>

<div>&nbsp;</div>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>