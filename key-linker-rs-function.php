<?php

function otocon_replace_context($context) {
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
$the_title = the_title('' , '' , false);
$tiray = mb_split("[ \t　,ぁ-ん]+", $the_title);
$the_content = get_the_content();
$thecon = preg_replace("/[、。.・' ’\" ”！!？?()（）「」『』]+/u", "", $the_content);
$conray = mb_split("[ぁ-ん]+", $thecon);
$intersect = @array_intersect_key($tiray, $conray);
$strlen = strlen($intersect[0]);
  if ($strlen > 1) {
    $tarkey = $intersect[0];
      if(strpos($context, $tarkey) !== false ){
      $e_keyword = urlencode($tarkey);
      $laskeystart = strrpos($context, $tarkey);
      global $userafid, $tfsort, $tfcategory;
      if(!empty($userafid)){
        $querysmmit = urlencode("クエリ送信");
        $asbase1 = 'http://hb.afl.rakuten.co.jp/hgc/' . $userafid . '/?pc=';
        $asbase2 = 'http://search.rakuten.co.jp/search/mall/' . $e_keyword . $tfcategory . '/?' . $tfsort . 'grp=product&pc_search='. $querysmmit . '&scid=af_link_urltxt&m=' . 'http://m.rakuten.co.jp/';
        $asbase2 = urlencode($asbase2);
        $asbase2 = strtolower($asbase2);
        $raku_af_link = $asbase1.$asbase2;
        preg_match_all('/<a\s[^><]*?\btitle=["\']?([^"\'><]*)/', $context, $match_title);
        foreach ($match_title[1] as $title_key => $title_value){}
        preg_match_all('/<img\s[^><]*?\balt=["\']?([^"\'><]*)/', $context, $match_alt);
        foreach ($match_alt[1] as $alt_key => $alt_value){}
        if(empty($title_value) || $tarkey != $title_value){
          if(empty($alt_value) || $tarkey != $alt_value){
            $anchor = '<a href="' . $raku_af_link . '" target="_blank">' . $tarkey . '</a>';
            $context = substr_replace( $context, $anchor, $laskeystart, $strlen );
          }
        }
      }
    }
  }
return $context;
}
add_filter('the_content', 'otocon_replace_context');
add_filter('the_excerpt', 'otocon_replace_context');
