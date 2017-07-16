<script>
var pattern = new RegExp("[^value=\"](<?php echo htmlspecialchars($_SESSION['search'],ENT_COMPAT | ENT_XHTML,'cp1251')?>)", "gi")
var content = document.getElementById('mc2');
content.innerHTML = content.innerHTML.replace(pattern, "<span style=\"color:#2B29DF; background:#FDFF3F\">$1</span>");

</script>