<?php
/**
 * Helper functions and services specific to our ACF blocks.
 */

namespace CT;

Class ACFHelpers
{
    /**
     * Returns the ACF $fields array populated with the files from the given directory.
     *
     * Used to dynamically populate an ACF select menu.
     * See https://www.advancedcustomfields.com/resources/dynamically-populate-a-select-fields-choices/
     *
     * @param  array  $fields      ACF select menu settings
     * @param  string $dir_path   '/path/to/directory'
     * @param  string $regex      Filter the files by this regex
     * @return array  $fields
     */
    public static function get_select_menu_files_from_directory( $fields, $dir_path, $regex, $url_path )
    {
        $choices = [];

        // Get the image files in the given directory.
        $file_paths = recursive_file_search( $dir_path, $regex );

        if ( empty( $file_paths ) ) {
            return $fields;
        }

        $choices['none'] = 'Select One';

        foreach ( $file_paths as $file_path ) {
            $filename = basename( $file_path );
            $url = "{$url_path}/{$filename}";

            $choices[$url] = filename_to_label( $filename );
        }

        $fields['choices'] = $choices;

        return $fields;
    }
}
