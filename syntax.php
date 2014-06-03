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
        $this->Lexer->addSpecialPattern('<wiyh[^>]*>',$mode,'plugin_whoisinyourhackspace');
    }

    public function handle($match, $state, $pos, &$handler){
      $return = array(
                      'ample' => true
                      );

      $this->_checkOption($match, "/-ample/i", $return['ample'], true);
      return $return;
    }

    public function render($mode, &$renderer, $data) {
        global $conf;

        if($mode != 'xhtml') return false;

        $api_path = $this->getConf('api_path');

        $file = file_get_contents($api_path);

        $api = json_decode($file);

        if ($api->state->open) {
            $renderer->doc .= sprintf('<p class="ample available">%s ist besetzt :).</p>',$api->space);
        } else {
            $renderer->doc .= sprintf('<p class="ample not-available">%s ist nicht besetzt :(.</p>',$api->space);
        }

        $renderer->doc .= sprintf('<p><a href="http://spacestatus.bastinat0r.de/#%s">MOAH!</a></p>',strtolower($api->space));

        return true;
    }
}

// vim:ts=4:sw=4:et:
