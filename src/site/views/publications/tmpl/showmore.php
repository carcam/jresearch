<?php if($this->showmore): ?>
    <span><?php echo JHTML::_('jresearchfrontend.link', JText::_('JRESEARCH_MORE'), 'publication', 'show', $pub->id); ?></span>
<?php endif; ?>
<?php   $attachments = array();
        if($this->showDigital == 'yes_and_use_tag') {
            $digitalVersion = $pub->getAttachment($this->digitalVersionTag);
            if ($digitalVersion != null) {
                $attachments[] = $digitalVersion;
            }
        } else if ($this->showDigital == 'yes_and_use_url') {
            if (!empty($pub->url)) {
                $attachments[] = array('url' => $pub->url, 
                    'tag' => '['.JText::_('JRESEARCH_DIGITAL_VERSION').']');
            }
        }

        if($this->showFulltext){
            $fullText = $pub->getAttachment($this->fullTextTag);
            if ($fullText != null) {
                $attachments[] = $fullText;
            }
        } 

        if($this->showBibtex) {
            $entry = array();
            $entry['url'] = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=bibtex&amp;id='.$pub->id;
            $entry['tag'] = '[Bibtex]';
            $attachments[] = $entry;
        }

        if ($this->showRIS) {
            $entry = array();
            $entry['url'] = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=ris&amp;id='.$pub->id;
            $entry['tag'] = '[RIS]';
            $attachments[] = $entry;
        }

        if ($this->showMODS) {
            $entry = array();
            $entry['url'] = 'index.php?option=com_jresearch&amp;controller=publications&amp;task=export&amp;format=mods&amp;id='.$pub->id;
            $entry['tag'] = '[MODS]';
            $attachments[] = $entry;                    
        }

        echo JHTML::_('jresearchfrontend.attachments', $attachments, 'horizontal');                
    ?>                        
    <?php 		
           $canDo = JResearchAccessHelper::getActions('publication', $pub->id);
           if($canDo->get('core.publications.edit') || ($canDoPublications->get('core.publications.edit.own') 
                   && $pub->created_by == $user->get('id'))):	 
    ?>	 	
    <span>	
        <?php echo JHTML::_('jresearchfrontend.icon','edit', 'publications', $pub->id, $user->get('id'), array('pubtype' => $pub->pubtype)); ?> 
    </span>
    <?php endif; ?>
    <?php if($canDoPublications->get('core.publications.delete')): ?>
        <?php echo JHTML::_('jresearchfrontend.icon','remove', 'publications', $pub->id); ?>
    <?php endif; ?>