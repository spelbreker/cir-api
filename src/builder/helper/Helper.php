<?php


namespace patrickDevelopment\cir\builder\helper;

use DOMDocument;

class helper
{
    //https://stackoverflow.com/questions/255312/how-to-get-a-variable-name-as-a-string-in-php
    public static function print_var_name($var)
    {
        foreach ($GLOBALS as $var_name => $value) {
            if ($value === $var) {
                return $var_name;
            }
        }

        return false;
    }


    // https://stackoverflow.com/questions/14553547/what-is-the-best-php-dom-2-array-function
    static function xml_to_array($root)
    {
        $result = array();

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }
            }
            $groups = array();
            foreach ($children as $child) {
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = self::xml_to_array($child);
                } else {
                    if (!isset($groups[$child->nodeName])) {
                        $result[$child->nodeName] = array($result[$child->nodeName]);
                        $groups[$child->nodeName] = 1;
                    }
                    $result[$child->nodeName][] = self::xml_to_array($child);
                }
            }
        }

        return $result;
    }


}
