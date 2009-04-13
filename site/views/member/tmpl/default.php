<?php
/**
 * @package JResearch
 * @subpackage Staff
 * Default view for showing a single member
 */


// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="componentheading"><?php echo $this->member; ?></h1>
<table class="frontendsingleitem">
<tbody>
  <tr><th style="width:100%;" colspan="4"><h2 class="contentheading"><?php echo JText::_('JRESEARCH_PERSONAL_INFORMATION').': '; ?></h2></th></tr>	
  <tr>  
    <th scope="row"><?php echo JText::_('JRESEARCH_POSITION').': ' ?></th>
    <td><?php echo empty($this->member->position)?JText::_('JRESEARCH_NOT_SPECIFIED'):$this->member->position; ?></td>
    <?php if(empty($this->member->url_photo)): ?>
    <td style="width:50%;" colspan="2" rowspan="3"></td>
    <?php else: 
    	$url = JResearch::getUrlByRelative($this->member->url_photo);
    ?>		
    <td style="width:50%;" colspan="2" rowspan="3"><img src="<?php echo $url; ?>" border="0" alt="<?php echo $this->member; ?>" /></td>
    <?php endif; ?>		
  </tr>
  <?php
  if($this->member->former_member == 0 && $this->params->get('member_show_email', 'no') == 'yes')
  {
  ?>
	  <tr>
	  	<th scope="row"><?php echo JText::_('JRESEARCH_EMAIL').': ' ?></th>
	  	<td><?php echo JHTML::_('email.cloak',$this->member->email);?></td>
	  	<td style="width:50%;" colspan="2"></td>
	  </tr>
  <?php 
  }
  ?>
  <tr>
  	<th scope="row"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></th>
  	<td><?php echo $this->area->name; ?></td>
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
  <tr><td colspan="4"><?php echo str_replace('<hr id="system-readmore" />', '', $this->member->description); ?></td></tr>			  
  <?php endif; ?>
  <?php $itemId = JRequest::getVar('Itemid', null); ?>
  <?php if(!empty($this->publications)){ ?>
  	<tr><th style="width:100%;" scope="row" colspan="4" ><h2 class="contentheading"><?php echo JText::_('JRESEARCH_PUBLICATIONS').': '; ?></h2></th></tr>
  	<tr><td style="width:100%;" colspan="4">
	  	<ul>
		  	<?php foreach($this->publications as $pub): ?>
		  		<?php if(!$this->applyStyle): ?>
	  				<li><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id ?><? echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo $pub->title; ?></a></li>
	  			<?php else: ?>
	  				<li>
	  					<?php  
	  						$styleObj =& JResearchCitationStyleFactory::getInstance($this->style, $pub->pubtype);
	  						echo $styleObj->getReferenceHTMLText($pub, true); 
	  					?>
	  					<a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id; ?><?php $Itemid = JRequest::getVar('Itemid'); echo !empty($Itemid)?'&Itemid='.$Itemid:''; ?>"><?php echo JText::_('JRESEARCH_MORE'); ?></a>&nbsp;
	  				</li>
	  			<?php endif; ?>	
	  		<?php endforeach; ?>
	  	</ul>
	  	<div>
	  		<?php if($this->npublications > count($this->publications)): ?>
	  				<a href="index.php?option=com_jresearch&publications_view_all=1&theses_view_all=<?php echo $this->theses_view_all; ?>&projects_view_all=<?php echo $this->projects_view_all; ?>&task=show&view=member&id=<?php echo $this->member->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_ALL'); ?></a>
	  		<?php else: ?>
	  				<?php if($this->publications_view_all): ?>		
	  					<a href="index.php?option=com_jresearch&publications_view_all=0&theses_view_all=<?php echo $this->theses_view_all; ?>&projects_view_all=<?php echo $this->projects_view_all; ?>&task=show&view=member&id=<?php echo $this->member->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_LESS'); ?></a>
	  				<?php endif; ?>
	  		<? endif; ?>
	  	</div>
	  	</td>
  	</tr>
  <?php } ?>
  
  <?php if(!empty($this->projects)){ ?>
  	<tr><th style="width:100%;" scope="row" colspan="4"><h2 class="contentheading"><?php echo JText::_('JRESEARCH_PROJECTS').': '; ?></h2></th></tr>
  	<tr><td style="width:100%;" colspan="4">
	  	<ul>
		  	<?php foreach($this->projects as $proj): ?>
	  			<li><a href="index.php?option=com_jresearch&view=project&task=show&id=<?php echo $proj->id ?><? echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo $proj->title; ?></a></li>
	  		<?php endforeach; ?>
	  	</ul>
	  	<div>
	  		<?php if($this->nprojects > count($this->projects)): ?>
	  				<a href="index.php?option=com_jresearch&projects_view_all=1&theses_view_all=<?php echo $this->theses_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&task=show&view=member&id=<?php echo $this->member->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_ALL'); ?></a>
	  		<?php else: ?>
	  				<?php if($this->projects_view_all): ?>		
	  					<a href="index.php?option=com_jresearch&projects_view_all=0&theses_view_all=<?php echo $this->theses_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&task=show&view=member&id=<?php echo $this->member->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_LESS'); ?></a>
	  				<?php endif; ?>
	  		<? endif; ?>
	  	</div>
  	</td></tr>
  <?php } ?>

  <?php if(!empty($this->theses)){ ?>
  	<tr><th style="width:100%;" scope="col" colspan="4"><h2 class="contentheading"><?php echo JText::_('JRESEARCH_THESES').': '; ?></h2></th></tr>
  	<tr><td style="width:100%;" colspan="4">
	  	<ul>
		  	<?php foreach($this->theses as $thesis): ?>
	  			<li><a href="index.php?option=com_jresearch&view=thesis&task=show&id=<?php echo $thesis->id ?><? echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo $thesis->title; ?></a></li>
	  		<?php endforeach; ?>
	  	</ul>
	  	<div>

	  		<?php if($this->ntheses > count($this->theses)): ?>
	  				<a href="index.php?option=com_jresearch&theses_view_all=1&projects_view_all=<?php echo $this->projects_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&task=show&view=member&id=<?php echo $this->member->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_ALL'); ?></a>
	  		<?php else: ?>
	  				<?php if($this->theses_view_all): ?>		
	  					<a href="index.php?option=com_jresearch&projects_view_all=<?php echo $this->projects_view_all; ?>&publications_view_all=<?php echo $this->publications_view_all; ?>&theses_view_all=0&task=show&view=member&id=<?php echo $this->member->id; ?><?php echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo JText::_('JRESEARCH_FRONTEND_PUBLICATIONS_VIEW_LESS'); ?></a>
	  				<?php endif; ?>
	  		<?php endif; ?>
	  	</div>
  	</td></tr>
  <?php } ?>  
</tbody>
</table>
<?php if(!empty($this->member->url_personal_page)):  ?>  	
	<div><?php echo JHTML::_('link', $this->member->url_personal_page, JText::_('JRESEARCH_PERSONAL_PAGE')); ?></div>
<?php endif; ?>  
<div>&nbsp;</div>
<div><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></div>