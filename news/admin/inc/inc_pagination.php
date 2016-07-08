<?php
$pagination = '
<ul class="pagination pagination-sm">';
if($limit > $count){$pagination .= '<li><a href="javascript:;">1</a></li>';}
if($count > $limit) :
 $x = 7; # Paging Page List
 $lastP = ceil($count/$limit);

 if($pgGo > 1){

 $pgPrev = $pgGo-1;

 $pagination .= '<li><a href="index.php'. $pgVar .'">«</a></li><li><a href="index.php'. $pgVar .'&amp;pgGo='. $pgPrev .'">&lt;</a></li>';

 }

 # Print page 1
 if($pgGo==1) $pagination .= '<li class="active"><a href="javascript:;">1</a></li>';
 else $pagination .= '<li><a href="index.php'. $pgVar .'">1</a></li>';
 # Print "..." or only 2
 if($pgGo-$x > 2) {
 $pagination .= '<li class="disabled"><a href="javascript:;">...</a></li>';
 $i = $pgGo-$x;
 } else {
 $i = 2;
 }
 # Print Pages
 for($i; $i<=$pgGo+$x; $i++) {
 if($i==$pgGo) $pagination .= '<li class="active"><a href="javascript:;">'. $i .'</a></li>';
 else $pagination .= '<li><a href="index.php'. $pgVar .'&amp;pgGo='. $i .'">'. $i .'</a></li>';
 if($i==$lastP) break;
 }
 # Print "..." or last page
 if($pgGo+$x < $lastP-1) {
 $pagination .= '<li class="disabled"><a href="javascript:;">...</a></li>';
 $pagination .= '<li><a href="index.php'. $pgVar .'&amp;pgGo='. $lastP .'">'. $lastP .'</a></li>';
 } elseif($pgGo+$x == $lastP-1) {
 $pagination .= '<li><a href="index.php'. $pgVar .'&amp;pgGo='. $lastP .'">'. $lastP .'</a></li>';
 }

 if($pgGo < $lastP){

 $pgNext = $pgGo+1;

 $pagination .= '<li><a href="index.php'. $pgVar .'&amp;pgGo='. $pgNext .'">&gt;</a></li><li><a href="index.php'. $pgVar .'&amp;pgGo='. $total_page .'">»</a></li>';

 }

endif;
$pagination .= '</ul>';
echo($pagination);
?>