function addslashes(A){A=A.replace(/\'/g,"\\'");A=A.replace(/\"/g,'\\"');A=A.replace(/\\/g,"\\\\");A=A.replace(/\0/g,"\\0");return A}function stripslashes(A){A=A.replace(/\\'/g,"'");A=A.replace(/\\"/g,'"');A=A.replace(/\\\\/g,"\\");A=A.replace(/\\0/g,"\0");return A};