<?php

/**
 * Wrapper function for jimport functionality. It allows to import classes and files
 * from J!Research code space
 * @param string $entity The file to import
 * @param string $space Where to look for
 */
function jresearchimport($entity, $space = 'system'){
    if($space == 'system')
        jimport($entity);
    elseif($space == 'jresearch'){

    }elseif($space == 'jresearch.site'){

    }elseif($space == 'jresearch.admin'){

    }else{

    }

}

?>
