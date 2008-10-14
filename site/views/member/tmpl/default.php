<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo $this->member; ?></div>
<table cellspacing="5">
  <tr><th colspan="4" class="contentheading"><?php echo JText::_('JRESEARCH_PERSONAL_INFORMATION').': '; ?></th></tr>	
  <tr><td colspan="4"></td></tr>  
  <tr>  
    <td width="20%" class="field"><?php echo JText::_('JRESEARCH_POSITION').': ' ?></td>
    <td><?php echo empty($this->member->position)?JText::_('JRESEARCH_NOT_SPECIFIED'):$this->member->position; ?></td>
    <?php if(empty($this->member->url_photo)): ?>
    <td colspan="2" rowspan="3"></td>
    <?php else: ?>		
    <td colspan="2" rowspan="3"><img src="<?php echo $this->member->url_photo; ?>" border="0" alt="<?php echo $this->member; ?>" /></td>
    <?php endif; ?>		
  </tr>
  <tr>
  	<td width="20%" class="field"><?php echo JText::_('JRESEARCH_EMAIL').': ' ?></td>
  	<td><a href="mailto:<?php echo $this->member->email; ?>"><?php echo $this->member->email; ?></a></td>
  	<td colspan="2"></td>
  </tr> 
  <tr>
  	<td width="20%" class="field"><?php echo JText::_('JRESEARCH_RESEARCH_AREA').': ' ?></td>
  	<td><?php echo $this->area->name; ?></td>
	<td colspan="2"></td>
  </tr>
  <tr>
  	<?php if(empty($this->member->phone_or_fax)): ?>
  	<td colspan="2"></td>
  	<?php else: ?>	
  	<td width="20%" class="field"><?php echo JText::_('JRESEARCH_PHONE_OR_FAX').': ';  ?></td>
  	<td><?php echo $this->member->phone_or_fax; ?></td>
	<?php endif; ?>  		
  	<td colspan="2" class="field"></td>
  </tr>
  <?php $itemId = JRequest::getVar('Itemid', null); ?>
  <?php if(!empty($this->publications)){ ?>
    <tr><td colspan="4"></td></tr>
  	<tr><td align="left" class="contentheading" colspan="4" ><?php echo JText::_('JRESEARCH_PUBLICATIONS').': '; ?></td></tr>
  	<tr><td colspan="4">
	  	<ul>
		  	<?php foreach($this->publications as $pub): ?>
	  			<li><a href="index.php?option=com_jresearch&view=publication&task=show&id=<?php echo $pub->id ?><? echo $itemId?"&Itemid=$itemId":'' ?>"><?php echo $pub->title; ?></a></li>
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
  	<tr><td colspan="4"></td></tr>
  	<tr><td align="left" class="contentheading" colspan="4"><?php echo JText::_('JRESEARCH_PROJECTS').': '; ?></td></tr>
  	<tr><td colspan="4">
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
  	<tr><td align="left" class="contentheading" colspan="4"><?php echo JText::_('JRESEARCH_THESES').': '; ?></td></tr>
  	<tr><td colspan="4">
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
	<?php if(!empty($this->member->url_personal_page)):  ?>  	
  	<tr>
		<td colspan="4" align="left"><?php echo JHTML::_('link', $this->member->url_personal_page, JText::_('JRESEARCH_PERSONAL_PAGE')); ?></td>
	</tr>
	<?php endif; ?>  
  <?php if(!empty($this->member->description)): ?>
  <tr><td colspan="4" class="field"><?php echo JText::_('JRESEARCH_DESCRIPTION').': '; ?></td></tr>
  <tr><td colspan="4"><?php echo str_replace('<hr id="system-readmore" />', '', $this->member->description); ?></td></tr>			  
  <?php endif; ?>
  <tr><td>&nbsp;</td></tr>
  <tr><td colspan="4"><a href="javascript:history.go(-1)"><?php echo JText::_('Back'); ?></a></td></tr>
</table>
