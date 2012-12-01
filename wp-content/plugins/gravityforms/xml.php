<?php
class RGXML{
    private $options = array();

    public function __construct($options=array()){
        $this->options = $options;
    }

    public function serialize($parent_node_name, $data, $path=""){
        if(empty($path)){
            $path = $parent_node_name;
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        }

        //if this element is marked as hidden, ignore it
        if($this->options[$path]["is_hidden"])
            return "";

        //if the content is not an array, simply render the node
        if(!is_array($data))
            return strlen($data) == 0 && !$this->options[$path]["allow_empty"] ? "" : "<$parent_node_name>" . $this->xml_value($parent_node_name, $data) . "</$parent_node_name>";

        $is_associative = $this->is_assoc($data);
        $is_empty = true;

        //opening parent node
        $version = $path == $parent_node_name ? "version=\"" . GFCommon::$version . "\"" : "";
        $xml = "<$parent_node_name $version";

        if($is_associative){
            //adding properties marked as attributes for associative arrays
            foreach($data as $key => $obj){
                $child_path = "$path/$key";
                if($this->is_attribute($child_path)){
                    $value = $this->xml_attribute($obj);
                    if(strlen($value) > 0 || $this->options[$child_path]["allow_empty"]){
                        $xml .= " $key=\"$value\"";
                        $is_empty = false;
                    }
                }
            }
        }
        //closing element start tag
        $xml .= ">";

        //for a regular array, the child element will be the singular vesion of the parent element(i.e. <forms><form>...</form><form>...</form></forms>)
        $child_node_name = $this->to_singular($parent_node_name);

        //adding other properties as elements
        foreach($data as $key => $obj){
            $node_name = $is_associative ? $key : $child_node_name;
            $child_path = "$path/$node_name";
            if(!$this->is_attribute($child_path)){

                $child_xml = $this->serialize($node_name, $obj, $child_path);
                if(strlen($child_xml) > 0){
                    $xml .= $child_xml;
                    $is_empty = false;
                }
            }
        }

        //closing parent node
        $xml .= "</$parent_node_name>";

        return $is_empty ? "" : $xml;
    }

    public function unserialize($xml_string){
        $xml_parser = xml_parser_create();
        $values = array();
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
        xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);

        xml_parse_into_struct($xml_parser, $xml_string, $values);

        $object = $this->unserialize_node($values, 0);
        xml_parser_free($xml_parser);

        return $object;
    }

    private function unserialize_node($values, $index){
        $current = $values[$index];

        //initializing current object
        $obj = array();

        //each attribute becomes a property of the object
        if(is_array($current["attributes"])){
            foreach($current["attributes"] as $key => $attribute)
                $obj[$key] = $attribute;
        }

        //for nodes without children(i.e. <title>contact us</title> or <rule fieldId="10" operator="is" />), simply return its content
        if($current["type"] == "complete"){
            $val = isset($current["value"]) ? $current["value"] : "";
            return !empty($obj) ? $obj : $val;
        }

        //get the current node's immediate children
        $children = $this->get_children($values, $index);

        if(is_array($children)){
            //if all children have the same tag, add them as regular array items (not associative)
            $is_identical_tags = $this->has_identical_tags($children);
            $unserialize_as_array = $is_identical_tags && $this->options[$children[0]["tag"]]["unserialize_as_array"];

            //serialize every child and add it to the object (as a regular array item, or as an associative array entry)
            foreach($children as $child){
                $child_obj = $this->unserialize_node($values, $child["index"]);
                if($unserialize_as_array)
                    $obj[] = $child_obj;
                else
                    $obj[$child["tag"]] = $child_obj;
            }
        }
        return $obj;
    }

    private function get_children($values, $parent_index){
        $level = $values[$parent_index]["level"] + 1;
        $nodes = array();
        for($i= $parent_index + 1, $count = sizeof($values); $i<$count; $i++){
            $current = $values[$i];

            //If we have reached the close tag for the parent node, we are done. Return the current nodes.
            if($current["level"] == $level -1 && $current["type"] == "close")
                return $nodes;
            else if($current["level"] == $level && ($current["type"] == "open" || $current["type"] == "complete"))
                $nodes[] = array("tag" => $current["tag"], "index" => $i); //this is a child, add it to the list of nodes

        }
        return $nodes;
    }

    private function has_identical_tags($nodes){
        $tag = $nodes[0]["tag"];
        foreach($nodes as $node){
            if($node["tag"] != $tag)
                return false;
        }
        return true;
    }

    private function is_attribute($path){
        return $this->options[$path]["is_attribute"];
    }

    private function xml_value($node_name, $value){
        if(strlen($value) == 0)
            return "";

        if($this->xml_is_cdata($node_name))
            return $this->xml_cdata($value);
        else
            return $this->xml_content($value);
    }

    private function xml_attribute($value){
        if ( seems_utf8( $value ) )
            $value = utf8_encode( $value );

        return esc_attr($value);
    }

    private function xml_cdata($value){
        if ( seems_utf8( $value ) )
            $value = utf8_encode( $value );

        return "<![CDATA[$value" . ( ( substr( $value, -1 ) == ']' ) ? ' ' : '') . "]]>";
    }

    private function xml_content($value){
        return $value;
    }

    private function xml_is_cdata($node_name){
        return true;
    }

    private function is_assoc($array){
        return is_array($array) && array_diff_key($array,array_keys(array_keys($array)));
    }

    private function to_singular($str){

        $last3 = strtolower(substr($str, strlen($str) - 3));
        $fourth = strtolower(substr($str, strlen($str) - 4, 1));

        if( $last3 == "ies" && in_array($fourth, array("a","e","i","o","u") ) ){
            return substr($str, 0, strlen($str)-3) . "y";
        }
        else{
            return substr($str, 0, strlen($str)-1);
        }
    }
}



/*
function startElement($parser, $tagName, $attrs) {
    global $updated_timestamp, $all_links, $map;
    global $names, $urls, $targets, $descriptions, $feeds;

    if ($tagName == 'OUTLINE') {
        foreach (array_keys($map) as $key) {
            if (isset($attrs[$key])) {
                $$map[$key] = $attrs[$key];
            }
        }

        //echo("got data: link_url = [$link_url], link_name = [$link_name], link_target = [$link_target], link_description = [$link_description]<br />\n");

        // save the data away.
        $names[] = $link_name;
        $urls[] = $link_url;
        $targets[] = $link_target;
        $feeds[] = $link_rss;
        $descriptions[] = $link_description;
    } // end if outline
}

function endElement($parser, $tagName) {
    // nothing to do.
}

// Create an XML parser
$xml_parser = xml_parser_create();

// Set the functions to handle opening and closing tags
xml_set_element_handler($xml_parser, "startElement", "endElement");

if (!xml_parse($xml_parser, $opml, true)) {
    echo(sprintf(__('XML error: %1$s at line %2$s'),
    xml_error_string(xml_get_error_code($xml_parser)),
    xml_get_current_line_number($xml_parser)));
}

// Free up memory used by the XML parser
xml_parser_free($xml_parser);
*/

?>
