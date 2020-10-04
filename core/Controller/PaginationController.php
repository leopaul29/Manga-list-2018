<?php 

namespace Core\Controller;

class PaginationController {

	public static function paginate($total = 0, $offset = 0, $limit = 10, $cssClass = '') {
	    /* is paginate needed ? */
	    $active = $total > $limit;
		/*
		 * init paging variables
		 */
		$limitsteps = 5; // limit step to display
		$steps = $limitsteps > 0;
		$currentstep = round($offset / $limit) + 1; // page to display
		$firststep = 1; 
		$laststep = round(ceil($total / $limit));

		/* init var beginstep and endstep
		 * if no param send to this component
		 */
		$beginstep = 0; $endstep = 0;

		/*
		 * if more than 2 steps
		 * calculate step number to display
		 */
		if($steps && $laststep > $firststep) {
			// first step to display
			$beginstep = $currentstep - round($limitsteps / 2) + ($limitsteps % 2);
			// last step to display
			$endstep = $currentstep + round($limitsteps / 2) - 1;

			if($beginstep < $firststep) {
                $beginstep = $firststep;
                $endstep = $limitsteps;
            }
            if ($endstep > $laststep) {
                $beginstep = $laststep - $limitsteps + 1;
                if ($beginstep < $firststep) {
                    $beginstep = $firststep;
                }
                $endstep = $laststep;
            }
		}

		/*
		 * return pagination params
		 */
		return $pagination = [
		    'active' => $active,
			'cssClass' => $cssClass,
			'currentstep' => $currentstep, 
			'firststep' => $firststep, 
			'steps' => $steps, 
			'laststep' => $laststep, 
			'beginstep' => $beginstep, 
			'endstep' => $endstep, 
			'next' => $currentstep + 1, 
			'previous' => $currentstep - 1
			];
	}
}