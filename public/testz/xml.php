<?php

function dom_to_simple_array($domnode, &$array) {
	$array_ptr = &$array;
	$domnode = $domnode->firstChild;
	while (!is_null($domnode)) {
		if (trim($domnode->nodeValue) != "") {
			switch ($domnode->nodeType) {
				case XML_TEXT_NODE:
					$array_ptr['cdata'] = $domnode->nodeValue;
					break;
				case XML_ELEMENT_NODE:
					$array_ptr = &$array[$domnode->nodeName][];
					if ($domnode->hasAttributes() ) {
						foreach ($domnode->attributes as $d_attribute){
							$array_ptr[$d_attribute->name] = $d_attribute->value;
						}
					}
					break;
				case XML_CDATA_SECTION_NODE: //CDATA 8-)
					$array_ptr['cdata'] = $domnode->data;
					break;
			}
			if ( $domnode->hasChildNodes() ) {
				dom_to_simple_array($domnode, $array_ptr);
			}
		}
		$domnode = $domnode->nextSibling;
	}
} 

$dom = new DOMDocument();
$doc = $dom->load('http://www.360voice.com/api/blog-getentries.asp?tag=Zweiblumen&num=5');

$feed = array();
dom_to_simple_array($dom, $feed);



?>