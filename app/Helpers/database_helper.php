<?php

/**
 * Helpers for database interaction
 */

if ( ! function_exists('update_search_path'))
{
    /**
     * Updates the database search path for Postgres connections. Does nothing for SQL Server connections
     * @param BaseConnection $db
     * @return void
     */
    function update_search_path($db)
    {
        if (property_exists($db, 'schema') && empty($db->DBPrefix) && (! empty($db->swapPre))) {
            // Set the search path appropriately; CodeIgniter always overrides the default search path if a schema is specified
            // Also, the schema is not always properly updated each time if shared connections are allowed
            $db->simpleQuery('SET search_path TO ' . $db->swapPre);
        }
    }
}
