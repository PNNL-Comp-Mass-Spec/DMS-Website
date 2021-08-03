<?php

/**
 * CodeIgniter Date Helpers, copied from CodeIgniter 3
 */

if ( ! function_exists('days_in_month'))
{
	/**
	 * Number of days in a month
	 *
	 * Takes a month/year as input and returns the number of days
	 * for the given month/year. Takes leap years into consideration.
	 *
	 * @param	int	a numeric month
	 * @param	int	a numeric year
	 * @return	int
	 */
	function days_in_month($month = 0, $year = '')
	{
		if ($month < 1 OR $month > 12)
		{
			return 0;
		}
		elseif ( ! is_numeric($year) OR strlen($year) !== 4)
		{
			$year = date('Y');
		}

		if (defined('CAL_GREGORIAN'))
		{
			return cal_days_in_month(CAL_GREGORIAN, $month, $year);
		}

		if ($year >= 1970)
		{
			return (int) date('t', mktime(12, 0, 0, $month, 1, $year));
		}

		if ($month == 2)
		{
			if ($year % 400 === 0 OR ($year % 4 === 0 && $year % 100 !== 0))
			{
				return 29;
			}
		}

		$days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		return $days_in_month[$month - 1];
	}
}
