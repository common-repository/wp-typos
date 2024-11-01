<?php 
/*
Plugin Name: WP Typos
Plugin URI: http://tigor.org.ua/wp-typos/
Description: Add typos in post ending to obtain traffic from search engines
Version: 0.1
Author: TIgor
Author URI: http://en.tigor.org.ua
License: GPL2
*/


/*  Copyright 2011 Tesliuk Igor  (email : tigoria@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 function mbStringToArray($string, $encoding = 'UTF-8')
  {
      $strlen = mb_strlen($string);
      while ($strlen) {
          $array[] = mb_substr($string, 0, 1, $encoding);
          $string = mb_substr($string, 1, $strlen, $encoding);
          $strlen = mb_strlen($string, $encoding);
      }
      return ($array);
  }

function wp_typos($content){
global $wpdb;
$id = $wpdb->post->id;
$options = get_option('wp_typos');
$typost = $options['post'][$id];

// if  {
$thepost = get_post($id);
$ty_title = wp_typos_do($thepost->post_title);
$typost['text'] = $ty_title;
// } 
//else {
//  
//
// }

if (is_single()) {
$content .= 'Неправильная раскладка: <b>'.$typost['text'].'</b>';
}
$options['post'][$id] = $typost;
update_option('wp_typos',$options);
return $content;
}

function wp_typos_do($data){

$text = wp_typos_raskladka($data);

return $text;
}

function wp_typos_raskladka($arg){
$text = '';
$arg = mb_convert_case($arg, MB_CASE_LOWER, "UTF-8");
$data = mbStringToArray($arg);
$options = get_option('wp_typos');
$layout = $options['layout']['raskladka'];

for ($i=0;$i<(mb_strlen($arg)); $i++){
if ($layout[$data[$i]]=='') 
{
$text = $text.$data[$i];

}
else 
{
$text=$text.$layout[$data[$i]];

}
} 

return $text;
}

function options_wp_typos(){
echo 'Hello world';
}


function register_typos_settings() {
register_setting('wp_typos_group','wp_typos');
}


function wp_typos_menu() {
add_options_page('WP Typos', 'WP Typos', 'manage_options', 'wp_typos', 'options_wp_typos');
add_action( 'admin_init', 'register_typos_settings' );
}

function wp_typos_init() {
add_filter('the_content', 'wp_typos');
require_once('layout.php');
}

add_action("plugins_loaded", "wp_typos_init");
add_action('admin_menu',"wp_typos_menu");
?>