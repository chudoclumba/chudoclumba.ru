<div id="flash_sound"></div>
<script type="text/javascript">
$(document).ready(function(){

// FLASH FILE EMBED

$('#flash_sound').flash(
     {
         src: 'about/mp3_player2.swf',
         width: 180,
         menu:true,
         height: 30,
         background: '#000000',
         id: 'mymovie',
         wmode: 'transparent',
         flashvars: { filename: '123.mp3' }
      },
      {
          expressInstall: true,
          version: '8'
      }
 );

 }); </script>