<?php

/*
 * This file is part of ProgPilot, a static analyzer for security
 *
 * @copyright 2017 Eric Therond. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */


namespace progpilot\Analysis;

use progpilot\Objects\MyDefinition;

class AssertionAnalysis {

	public static function temporary_simple($context, $data, $myblock, $resolve_temporary, $tempdefa)
	{
		$assertions = $myblock->get_assertions();

		$safe = false;
		$assertions_defs = [];
		foreach($assertions as $assertion)
		{
			$mydef = $assertion->get_def();
			$type_assertion = $assertion->get_type();
			$assertions_defs[] = $mydef;

			$defs_assert = ResolveDefs::temporary_simple($context, $data, $mydef);

			$equality = false;
			if($mydef == $defs_assert[0])
			{
				if($mydef->get_name() == $tempdefa->get_name())
				{
					$equality = true;
					// ???? !!!!
					$tempdefa->set_tainted(false);
				}
			}
			else if($defs_assert[0] == $resolve_temporary)
			{
				$equality = true;
			}

			if($equality && $type_assertion != "string")
			{
				$safe = true;
			}
		}
		
        if($resolve_temporary->get_cast() == MyDefinition::CAST_SAFE)
            $safe = true;
            
		return $safe;
	}
}
