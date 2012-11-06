<?php
/**
 * This class provides easy XML encoding and decoding
 * @package Framsie
 * @version 1.0
 * @copyright 2012 Travis Brown <tmbrown6@gmail.com>
 * @license GPLv3 <http://www.gnu.org/licenses/gpl-3.0.html>
 * @author Travis Brown <tmbrown6@gmail.com>
 */
class FramsieXml extends DOMDocument {

	///////////////////////////////////////////////////////////////////////////
	/// Constants ////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////

	/**
	 * This constant contains the value for UTF-8 encodings
	 * @var string
	 */
	const XML_ENCODING_UTF8       = 'UTF-8';

	/**
	 * This constant contains the value for XML version 1.0
	 * @var string
	 */
	const XML_VERSION_1_0         = '1.0';

	///////////////////////////////////////////////////////////////////////////
	/// Public Methods ///////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////

	/**
	 * This method encodes an array or an object into an XML data structure
	 * @package Framsie
	 * @subpackage FramsieXml
	 * @access public
	 * @static
	 * @param array|boolean|integer|object|string $mEntity
	 * @param DOMElement $oDomElement
	 * @param DOMDocument $oDomDocument
	 * @return string
	 */
	public function fromMixed($mEntity, $oDomElement = null) {
		// Check for a DOMElement
		if (empty($oDomElement)) {
			// Set the DOMElement to the current DOMDocument
			$oDomElement = $this;
		}
		// Check for an array or an object
		if (is_array($mEntity) || is_object($mEntity)) {
			// Loop through the elements of the entity
			foreach ($mEntity as $mIndex => $mElement) {
				// Check for a numerically indexed array
				if (is_int($mIndex)) {
					// Check to see if this is the first index
					if ($mIndex === 0) {
						// Set the node to the DOMElement
						$oNode = $oDomElement;
					} else {
						// Create the node
						$oNode = $this->createElement($oDomElement->tagName);
						// Set the node into the parent
						$oDomElement->parentNode->appendChild($oNode);
					}
				} else {
					// Create the node
					$oNode = $this->createElement($mIndex);
					// Set the node into the document
					$oDomElement->appendChild($oNode);
				}
				// Execute this method once more
				$this->fromMixed($mElement, $oNode);
			}
		} else {
			// Check to see if the entity is a boolean
			if (is_bool($mEntity)) {
				// Reset the entity
				$mEntity = (string) (($mEntity === true) ? 'true' : 'false');
			}
			// Append the entity to the element
			$oDomElement->appendChild($this->createTextNode($mEntity));
		}
		// Return the XML
		return $this->saveXML();
	}
}
