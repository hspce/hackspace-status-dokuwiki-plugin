<?php
/**
 * DokuWiki Plugin whoisinyourhackspace (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Tim Schumacher <tim.daniel.schumacher@gmail.com>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_whoisinyourhackspace extends DokuWiki_Syntax_Plugin {
  /**
   * Check if a given option has been given, and remove it from the initial string
   * @param string $match The string match by the plugin
   * @param string $pattern The pattern which activate the option
   * @param $varAffected The variable which will memorise the option
   * @param $valIfFound the value affected to the previous variable if the option is found
   */
  private function _checkOption(&$match, $pattern, &$varAffected, $valIfFound){
    if ( preg_match($pattern, $match, $found) ){
      $varAffected = $valIfFound;
      $match = str_replace($found[0], '', $match);
    }
  } // _checkOption

    public function getType() {
        return 'substition';
    }

    public function getPType() {
        return 'block';
    }

    public function getSort() {
        return 0;
    }


    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\[wiyh\]',$mode,'plugin_whoisinyourhackspace');
    }

    public function render($mode, &$renderer, $data) {
        global $conf;

        if($mode != 'xhtml') return false;

        $api_path = $this->getConf('api_path');

        $file = file_get_contents($api_path);

        $api = json_decode($file);

        $content = '';
        $content .= '<div class="hackerspace-room-state">';
        $content .= "<h3>" . $this->getLang('wiyh_heading') . "</h3>";

        if ($api->state->open) {
            $content .= "<img class=\"icon\" src=\"{$api->state->icon->open}\" alt=\"{$api->space} ist besetzt.\" title=\"{$api->space} ist besetzt.\" />";
            $content .= "<p class=\"text\">{$api->space} " . $this->getLang('wiyh_open') . "</p>";
        } else {
            $content .= "<img class=\"icon\" src=\"{$api->state->icon->closed}\" alt=\"{$api->space} ist geschlossen.\" title=\"{$api->space} ist geschlossen.\" />";
            $content .= "<p class=\"text\">{$api->space} " . $this->getLang('wiyh_closed') . "</p>";
        }

        $content .= '<hr />';
        $content .= sprintf('<p><a href="http://spaceapi-stats.n39.eu/#%s">'.$this->getLang('wiyh_stats').'</a></p>',str_replace(' ','',strtolower($api->space)));
        $content .= '</div>';

        $renderer->doc .= $content;
        return true;
    }
}

// vim:ts=4:sw=4:et:
