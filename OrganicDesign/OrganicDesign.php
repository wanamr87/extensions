<?php
/**
 * OrganicDesign extension - an extension to encapsulate all the functionality specific to the OD wiki
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @author Aran Dunkley [http://www.organicdesign.co.nz/nad User:Nad]
 * @copyright © 2012 Aran Dunkley
 * @licence GNU General Public Licence 2.0 or later
 */
if( !defined( 'MEDIAWIKI' ) ) die( "Not an entry point." );
define( 'OD_VERSION', "0.0.0, 2012-09-27" );

$wgExtensionFunctions[] = 'wfSetupOrganicDesign';
$wgExtensionCredits['other'][] = array(
	'name'        => "OrganicDesign",
	'author'      => "[http://www.organicdesign.co.nz/nad Aran Dunkley]",
	'description' => "An extension to encapsulate all the functionality specific to the Organic Design wiki",
	'url'         => "http://www.organicdesign.co.nz",
	'version'     => OD_VERSION
);

// Register the CSS file for the OrganicDesign skin
$wgResourceModules['skins.organicdesign'] = array(
        'styles' => array( 'organicdesign.css' => array( 'media' => 'screen' ) ),
        'remoteBasePath' => "$wgStylePath/organicdesign",
        'localBasePath' => "$IP/skins/organicdesign",
);

}



class OrganicDesign {

	function __construct() {
        global $wgUser, $wgTitle;

        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        $ssl = isset( $_SERVER['HTTPS'] );
        $www = preg_match( "|^www|", $host ) ? '' : 'www.';
        if( in_array( 'sysop', $wgUser->getEffectiveGroups() ) ) {
                if( $www || !$ssl ) {
                        header( "Location: https://$www$host$uri" );
                        exit;
                }
        } else {
                if( $ssl && ( !array_key_exists( 'title', $_REQUEST ) || $_REQUEST['title'] != 'Special:UserLogin' ) ) {
                        header( "Location: http://$www$host$uri" );
                        exit;
                }
                if( $www && preg_match( '|organicdesign.+[^t]$|', $host ) ) {
                        header( "Location: http://$www$host$uri" );
                        exit;
                }
        }

	}

}

function wfSetupOrganicDesign() {
	global $wgOrganicDesign;
	$wgOrganicDesign = new OrganicDesign();
}

